<?php

// ============================================================
// app/Http/Controllers/Complex/DeviceManagementController.php
// Module Gestion — Création, édition, suppression, contrôle
// Middleware requis : auth + role:complex,admin + level:advanced
// ============================================================

namespace App\Http\Controllers\Complex;

use App\Http\Controllers\Controller;
use App\Models\{Device, DeviceCategory, Zone, User};
use App\Services\ExperienceService;
use Illuminate\Http\{RedirectResponse, Request};
use Illuminate\Support\Facades\{Auth, Cache};
use Illuminate\View\View;

class DeviceManagementController extends Controller
{
    public function __construct(private ExperienceService $xpService) {}

    // ─────────────────────────────────────────────────────────
    // INDEX — liste des appareils gérables
    // ─────────────────────────────────────────────────────────
    public function index(Request $request): View
    {
        $query = Device::query()
            ->with(['category', 'zone', 'creator'])
            ->withCount('readings');

        // Filtres
        if ($request->filled('category')) $query->byCategory($request->category);
        if ($request->filled('zone'))     $query->byZone($request->zone);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('search'))   $query->search($request->search);

        $devices    = $query->latest()->paginate(15)->withQueryString();
        $categories = DeviceCategory::allActiveCached();
        $zones      = Zone::allActiveCached();

        // Stats globales (mises en cache 5 min)
        $stats = Cache::remember('devices.stats', 300, function () {
            return [
                'total'       => Device::count(),
                'online'      => Device::where('status', 'online')->count(),
                'offline'     => Device::where('status', 'offline')->count(),
                'maintenance' => Device::where('status', 'maintenance')->count(),
                'error'       => Device::where('status', 'error')->count(),
            ];
        });

        return view('simple.devices.index', compact('devices', 'categories', 'zones', 'stats'));
    }


    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'serial_number'     => ['required', 'string', 'max:100', 'unique:devices,serial_number'],
            'category_id'       => ['required', 'integer', 'exists:device_categories,id'],
            'zone_id'           => ['nullable', 'integer', 'exists:zones,id'],
            'model'             => ['nullable', 'string', 'max:255'],
            'manufacturer'      => ['nullable', 'string', 'max:255'],
            'ip_address'        => ['nullable', 'ip'],
            'firmware_version'  => ['nullable', 'string', 'max:50'],
            'installation_date' => ['nullable', 'date'],
            'warranty_until'    => ['nullable', 'date', 'after_or_equal:installation_date'],
        ]);

        $device = Device::create([
            ...$validated,
            'status'     => 'offline',
            'created_by' => Auth::id(),
        ]);

        Cache::forget('devices.stats');
        Zone::clearCache();

        $this->xpService->award(
            Auth::user(),
            'device_added',
            User::XP_DEVICE_ADDED,
            "Ajout de l'appareil : {$device->name}",
            $device
        );

        return redirect()->route('complex.devices.index')
            ->with('success', "L'appareil « {$device->name} » a été créé avec succès.");
    }

public function create(): View
{
    $categories = \App\Models\DeviceCategory::orderBy('name')->get();
    $zones = \App\Models\Zone::orderBy('name')->get();

    return view('complex.devices.create', compact('categories', 'zones'));
}

// ─────────────────────────────────────────────────────────
// SHOW — détail d’un appareil
// ─────────────────────────────────────────────────────────
public function show(Device $device): View
{
    $device->load(['category', 'zone', 'creator', 'readings']);

    return view('complex.devices.show', compact('device'));
}

// ─────────────────────────────────────────────────────────
// EDIT — formulaire de modification
// ─────────────────────────────────────────────────────────
public function edit(Device $device): View
{
    $categories = DeviceCategory::orderBy('name')->get();
    $zones = Zone::orderBy('name')->get();

    return view('complex.devices.edit', compact('device', 'categories', 'zones'));
}

// ─────────────────────────────────────────────────────────
// UPDATE — mise à jour appareil
// ─────────────────────────────────────────────────────────
public function update(Request $request, Device $device): RedirectResponse
{
    $validated = $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        'serial_number'     => ['required', 'string', 'max:100', 'unique:devices,serial_number,' . $device->id],
        'category_id'       => ['required', 'integer', 'exists:device_categories,id'],
        'zone_id'           => ['nullable', 'integer', 'exists:zones,id'],
        'status'            => ['required', 'in:online,offline,maintenance,error'],
        'model'             => ['nullable', 'string', 'max:255'],
        'manufacturer'      => ['nullable', 'string', 'max:255'],
        'ip_address'        => ['nullable', 'ip'],
        'firmware_version'  => ['nullable', 'string', 'max:50'],
        'installation_date' => ['nullable', 'date'],
        'warranty_until'    => ['nullable', 'date', 'after_or_equal:installation_date'],
    ]);

    $device->update($validated);

    Cache::forget('devices.stats');

    return redirect()->route('complex.devices.index')
        ->with('success', 'Appareil mis à jour avec succès.');
}

// ─────────────────────────────────────────────────────────
// DESTROY — suppression appareil
// ─────────────────────────────────────────────────────────
public function destroy(Device $device): RedirectResponse
{
    $device->delete();

    Cache::forget('devices.stats');

    return redirect()->route('complex.devices.index')
        ->with('success', 'Appareil supprimé avec succès.');
}

}
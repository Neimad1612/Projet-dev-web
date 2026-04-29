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

    // ─────────────────────────────────────────────────────────
    // CREATE — formulaire de création
    // ─────────────────────────────────────────────────────────
    public function create(): View
    {
        $categories = DeviceCategory::allActiveCached();
        $zones      = Zone::allActiveCached();

        return view('complex.devices.create', compact('categories', 'zones'));
    }

    // ─────────────────────────────────────────────────────────
    // STORE — enregistrement d'un nouvel appareil
    // ─────────────────────────────────────────────────────────
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
        ], [
            'serial_number.unique'              => 'Ce numéro de série est déjà utilisé.',
            'category_id.exists'                => 'Catégorie invalide.',
            'zone_id.exists'                    => 'Zone invalide.',
            'warranty_until.after_or_equal'     => 'La garantie doit être postérieure à la date d\'installation.',
        ]);

        $device = Device::create([
            ...$validated,
            'status'     => 'offline',   // tout nouvel appareil commence hors ligne
            'is_active'  => true,
            'created_by' => Auth::id(),
        ]);

        // Invalider les caches stats et liste
        Cache::forget('devices.stats');

        // Invalider le cache des zones pour mettre à jour les compteurs
        Zone::clearCache();

        // Attribuer les XP pour ajout d'un appareil
        $this->xpService->award(
            Auth::user(),
            'device_added',
            User::XP_DEVICE_ADDED,
            "Ajout de l'appareil : {$device->name}",
            $device
        );

        return redirect()
            ->route('complex.devices.index')
            ->with('success', "L'appareil « {$device->name} » a été créé avec succès.");
    }

    // ─────────────────────────────────────────────────────────
    // SHOW — détail d'un appareil
    // ─────────────────────────────────────────────────────────
    public function show(Device $device): View
    {
        $device->load(['category', 'zone', 'creator', 'controls' => function ($q) {
            $q->with('user')->latest()->limit(10);
        }]);

        $readingsRaw = Cache::remember(
            "device.{$device->id}.readings.24h",
            300,
            fn() => $device->readings()
                ->where('recorded_at', '>=', now()->subDay())
                ->orderBy('recorded_at')
                ->get()
                ->toArray()
        );

        $readingsByMetric = collect($readingsRaw)->groupBy('metric');

        return view('complex.devices.show', compact('device', 'readingsByMetric'));
    }

    // ─────────────────────────────────────────────────────────
    // EDIT — formulaire d'édition
    // ─────────────────────────────────────────────────────────
    public function edit(Device $device): View
    {
        $categories = DeviceCategory::allActiveCached();
        $zones      = Zone::allActiveCached();

        return view('complex.devices.edit', compact('device', 'categories', 'zones'));
    }

    // ─────────────────────────────────────────────────────────
    // UPDATE — mise à jour
    // ─────────────────────────────────────────────────────────
    public function update(Request $request, Device $device): RedirectResponse
{
    $validated = $request->validate([
        'name'              => ['required', 'string', 'max:255'],
        // On exclut l'ID actuel de la vérification d'unicité
        'serial_number'     => ['required', 'string', 'max:100', "unique:devices,serial_number,{$device->id}"],
        'category_id'       => ['required', 'integer', 'exists:device_categories,id'],
        'zone_id'           => ['nullable', 'integer', 'exists:zones,id'],
        'is_active'         => ['required', 'boolean'],
    ]);

    $device->update($validated);
    $device->invalidateDataCache(); // Nettoyage du cache spécifique à l'objet

    return redirect()
        ->route('complex.devices.index')
        ->with('success', "Appareil mis à jour.");
}

    // ─────────────────────────────────────────────────────────
    // DESTROY — suppression douce (SoftDelete)
    // ─────────────────────────────────────────────────────────
    public function destroy(Device $device): RedirectResponse
    {
        $name = $device->name;
        $device->delete();

        Cache::forget('devices.stats');
        Cache::forget("device.{$device->id}.current_data");

        return redirect()
            ->route('complex.devices.index')
            ->with('success', "Appareil « {$name} » supprimé.");
    }

    // ─────────────────────────────────────────────────────────
    // CONTROL — envoyer une commande IoT
    // ─────────────────────────────────────────────────────────
    public function control(Request $request, Device $device): RedirectResponse
    {
        $validated = $request->validate([
            'action'     => ['required', 'string', 'in:power_on,power_off,set_temperature,restart,set_mode'],
            'parameters' => ['nullable', 'array'],
            'parameters.temperature' => ['nullable', 'numeric', 'min:-30', 'max:300'],
            'parameters.mode'        => ['nullable', 'string', 'max:50'],
        ]);

        $control = $device->controls()->create([
            'user_id'    => Auth::id(),
            'action'     => $validated['action'],
            'parameters' => $validated['parameters'] ?? [],
            'status'     => 'pending',
        ]);

        // TODO: dispatch(new SendDeviceCommand($control));

        $device->invalidateDataCache();

        $this->xpService->award(
            Auth::user(),
            'device_controlled',
            User::XP_DEVICE_CONTROL,
            "Commande « {$validated['action']} » sur {$device->name}",
            $device
        );

        return back()->with('success', "Commande « {$validated['action']} » envoyée à {$device->name}.");
    }

    // ─────────────────────────────────────────────────────────
    // ASSIGN ZONE — changer la zone d'un appareil
    // ─────────────────────────────────────────────────────────
    public function assignZone(Request $request, Device $device): RedirectResponse
    {
        $request->validate([
            'zone_id' => ['nullable', 'integer', 'exists:zones,id'],
        ]);

        $device->update(['zone_id' => $request->zone_id]);
        $device->invalidateDataCache();

        $zoneName = $request->zone_id
            ? Zone::find($request->zone_id)?->name ?? 'inconnue'
            : 'aucune zone';

        return back()->with('success', "Zone mise à jour : {$zoneName}.");
    }
}
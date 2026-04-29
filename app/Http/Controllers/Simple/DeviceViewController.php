<?php

// ============================================================
// app/Http/Controllers/Simple/DeviceViewController.php — CORRIGÉ
// $zones utilise maintenant Zone::allActiveCached() comme $categories,
// garantissant le même format de sortie (Collection d'objets).
// La vue n'a plus besoin de is_object() ni de blindage.
// ============================================================

namespace App\Http\Controllers\Simple;

use App\Http\Controllers\Controller;
use App\Models\{Device, DeviceCategory, Zone};
use App\Services\ExperienceService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class DeviceViewController extends Controller
{
    public function __construct(private ExperienceService $xpService) {}

    public function index(Request $request)
    {
        $query = Device::query()
            ->with(['category', 'zone']);

        if ($request->filled('category')) $query->byCategory($request->category);
        if ($request->filled('zone'))     $query->byZone($request->zone);
        if ($request->filled('status'))   $query->where('status', $request->status);
        if ($request->filled('search'))   $query->search($request->search);

        $devices    = $query->paginate(12)->withQueryString();

        // Les deux variables utilisent désormais la même méthode de cache
        // → toujours des Collections d'objets Eloquent, jamais de tableaux bruts
        $categories = DeviceCategory::allActiveCached();
        $zones      = Zone::allActiveCached();          // ← CORRECTION PRINCIPALE

        return view('simple.devices.index', compact('devices', 'categories', 'zones'));
    }

    public function show(Device $device)
    {
        // Le Middleware TrackDeviceView attribue les XP — pas besoin de le faire ici

        $readings = Cache::remember(
            "device.{$device->id}.readings.24h",
            300,
            fn() => $device->readings()
                ->where('recorded_at', '>=', now()->subDay())
                ->orderBy('recorded_at')
                ->get()
                ->toArray()             // même logique : toArray() avant le cache
        );

        // On reconstruit le groupBy depuis le tableau
        $readingsByMetric = collect($readings)->groupBy('metric');

        return view('simple.devices.show', compact('device', 'readingsByMetric'));
    }

    public function requestDelete(Device $device)
    {
        if (!in_array(auth()->user()->role, ['complex', 'admin'])) {
            abort(403);
        }

        if ($device->delete_requested) {
            return back()->with('error', 'Déjà demandé');
        }

        $device->update([
            'delete_requested' => true,
            'delete_requested_at' => now(),
        ]);

        return back()->with('success', 'Demande envoyée');
    }
}
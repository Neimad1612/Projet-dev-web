<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceModerationController extends Controller
{
    /**
     * Liste des demandes de suppression
     */
    public function deletionRequests()
    {
        $devices = Device::where('delete_requested', true)
            ->whereNull('deleted_at')
            ->latest()
            ->get();

        return view('admin.devices.deletion-requests', compact('devices'));
    }

    /**
     * Validation de suppression (admin)
     */
    public function approveDelete(Device $device)
    {
        // Safety: ensure request flag is set (optional but cleaner)
        if (!$device->delete_requested) {
            return back()->with('error', 'Aucune demande de suppression trouvée.');
        }

        // Direct deletion
        $device->delete();

        return redirect()
            ->back()
            ->with('success', 'Objet supprimé avec succès.');
    }

    /**
     * Refus de suppression
     */
    public function rejectDelete(Device $device)
    {
        $device->update([
            'delete_requested' => false,
            'delete_requested_at' => null,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Demande de suppression refusée.');
    }
}
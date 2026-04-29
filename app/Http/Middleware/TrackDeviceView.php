<?php
namespace App\Http\Middleware;

use App\Models\User;
use App\Services\ExperienceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class TrackDeviceView
{
    public function __construct(private ExperienceService $xpService) {}

    public function handle(Request $request, Closure $next): mixed {
        $response = $next($request);
        if (Auth::check() && $request->route('device')) {
            $user = Auth::user();
            $device = $request->route('device');
            $cacheKey = "xp.device_view.{$user->id}.{$device->id}." . now()->format('Y-m-d-H');
            
            if (!Cache::has($cacheKey)) {
                Cache::put($cacheKey, true, 3600);
                $this->xpService->award($user, 'device_view', User::XP_DEVICE_VIEW, "Consultation appareil: {$device->name}", $device);
            }
        }
        return $response;
    }
}
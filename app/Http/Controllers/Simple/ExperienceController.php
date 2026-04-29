<?php

namespace App\Http\Controllers\Simple;

use App\Http\Controllers\Controller;
use App\Models\ExperienceLog;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index()
    {
        // On récupère l'historique de l'utilisateur connecté, du plus récent au plus ancien
        $logs = ExperienceLog::where('user_id', Auth::id())
                             ->latest('created_at')
                             ->paginate(15);

        return view('simple.xp.index', compact('logs'));
    }
}
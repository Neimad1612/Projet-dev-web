<?php

namespace App\Http\Controllers;

use App\Models\FreeTourStep;

class FreeTourController extends Controller
{
    public function index()
    {
        $steps = FreeTourStep::activeCached();

        return view('public.tour.index', compact('steps'));
    }

    public function show(int $step)
    {
        $steps = FreeTourStep::activeCached();

        $currentStep = $steps->firstWhere('order', $step);

        if (!$currentStep) {
            abort(404, 'Étape de visite introuvable.');
        }

        $prevStep = $steps->firstWhere('order', $step - 1);
        $nextStep = $steps->firstWhere('order', $step + 1);

        return view('public.tour.show', compact('currentStep', 'prevStep', 'nextStep', 'steps'));
    }
}
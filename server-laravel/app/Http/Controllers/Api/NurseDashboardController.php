<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Task;

class NurseDashboardController extends Controller
{
    /**
     * Display the Home Screen
     * 
     */
    public function home()
    {
        $patients = Patient::latest()->get();
        $inboxItems = Task::latest()->get();

        return view('screens.home', [
            'patients' => $patients,
            'inboxItems' => $inboxItems,
        ]);
    }

    /**
     * Display the Patients Screen
     * 
     */
    public function patients()
    {
        $patients = Patient::latest()->get();

        return view('screens.patients', [
            'patients' => $patients,
        ]);
    }

    /**
     * Display the Inbox Screen
     * 
     */
    public function inbox()
    {
        $inboxItems = Task::latest()->get();

        return view('screens.inbox', [
            'inboxItems' => $inboxItems,
        ]);
    }
}

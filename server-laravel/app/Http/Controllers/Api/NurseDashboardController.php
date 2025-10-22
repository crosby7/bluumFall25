<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Task;
use App\Models\TaskSubscription;

class NurseDashboardController extends Controller
{

    /**
     * BaseContext: provide the patients and task subscriptions to all views
     * 
     */
     public function baseContext() {
        $patients = Patient::latest()->get();
        $tasks = TaskSubscription::with('task')->get()->map(function ($subscription) {
            $task = $subscription->task;
            $task->due_time = $subscription->start_at->setTimezone($subscription->timezone)->format('g:i a');
            $task->patient_id = $subscription->patient_id;
            $task->status = $subscription->completions()->latest()->first() ? 'complete' : 'pending';
            return $task;
        });

        return [
            'patients' => $patients,
            'tasks' => $tasks,
        ];
     }

    /**
     * Display the Home Screen
     * 
     */
    public function home()
    {
        return view('screens.home', $this->baseContext());
    }

    /**
     * Display the Patients Screen
     * 
     */
    public function patients()
    {
        return view('screens.patients', $this->baseContext());
    }

    /**
     * Display the Inbox Screen
     * 
     */
    public function inbox()
    {
        return view('screens.inbox', $this->baseContext());
    }
}

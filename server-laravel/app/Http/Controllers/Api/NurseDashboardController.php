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
    public function baseContext()
    {
        $patients = Patient::latest()->get();
        $tasks = TaskSubscription::with('task')->get()->map(function ($subscription) {
            // Return a new object instead of modifying the task model
            return (object) [
                'id' => $subscription->task->id,
                'name' => $subscription->task->name,
                'description' => $subscription->task->description,
                'scheduled_time' => $subscription->scheduled_time ? \Carbon\Carbon::parse($subscription->scheduled_time)->format('g:i a') : '',
                'patient_id' => $subscription->patient_id,
                'status' => $subscription->completions()->latest()->first() ? 'complete' : 'pending',
            ];
        });

        return [
            'patients' => $patients,
            'tasks' => $tasks,
        ];
    }

    /**
     * Display the Search Results
     * 
     */
    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        // If no input, return default suggestions
        if ($query === '') {
            return response()->json([
                ['type' => 'action', 'label' => 'Create New Patient', 'action' => 'createPatient'],
                ['type' => 'action', 'label' => 'Create New Task', 'action' => 'createTask'],
            ]);
        }

        $results = [];

        // Search patients (room or name)
        $patients = Patient::where('username', 'ILIKE', "%{$query}%")->limit(3)
            ->get();

        foreach ($patients as $patient) {
            $results[] = [
                'type' => 'patient',
                'id' => $patient->id,
                'label' => "{$patient->username}",
            ];
        }

        // Search pages by name (home, inbox, patients)
        $pages = collect([
            ['label' => 'Go Home', 'route' => route('home')],
            ['label' => 'Go to Patients', 'route' => route('patients')],
            ['label' => 'Go to Inbox', 'route' => route('inbox')],
        ])->filter(function ($page) use ($query) {
            return stripos($page['label'], $query) !== false;
        });

        foreach ($pages as $page) {
            $results[] = array_merge(['type' => 'page'], $page);
        }

        return response()->json($results);
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

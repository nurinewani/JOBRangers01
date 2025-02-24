<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    // Display schedules
    public function index()
    {
        $user = Auth::user();

        // Check if the user is an ADMIN or RECRUITER
        if ($user->role == 2 || $user->role == 1) {
            // Admins and Recruiters can see all schedules
            $schedules = Schedule::with('user')->get();
        } else {
            // Users can only see their own schedules
            $schedules = Schedule::where('user_id', $user->id)->get();
        }

        return view('schedule.index', compact('schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('schedule.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i',
            'repeat' => 'nullable|string|in:none,daily,weekly,yearly',
            'repeat_days' => 'nullable|array',
            'repeat_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $schedule = new Schedule;
        $schedule->user_id = auth()->id();
        $schedule->title = $request->title;
        $schedule->description = $request->description;
        $schedule->start = $request->start;
        $schedule->end = $request->end;
        $schedule->repeat = $request->repeat;
        
        // Store repeat days as array
        if ($request->repeat == 'weekly' && $request->repeat_days) {
            $schedule->repeat_days = $request->repeat_days;
        }
        
        $schedule->schedule_date = $request->schedule_date;
        $schedule->save();

        return redirect()->route('schedule.index')->with('success', 'Schedule created successfully.');
    }
    
    
    private function handleRepeat($schedule, $request)
    {
        // Get the repetition type
        $repeatType = $request->repeat;
    
        // Handle different repetition types
        switch ($repeatType) {
            case 'daily':
                $this->createDailySchedules($schedule);
                break;
            case 'weekly':
                $this->createWeeklySchedules($schedule, $request->repeat_days);
                break;
            case 'yearly':
                $this->createYearlySchedules($schedule);
                break;
        }
    }
    
    private function createDailySchedules($schedule)
    {
        // Repeat the schedule every day for the next 30 days (for example)
        for ($i = 1; $i <= 30; $i++) {
            $newSchedule = $schedule->replicate();
            $newSchedule->start = $schedule->start->addDay();
            $newSchedule->end = $schedule->end->addDay();
            $newSchedule->save();
        }
    }
    
    private function createWeeklySchedules($schedule, $repeatDays)
    {
        // Repeat the schedule weekly on the selected days for the next 12 weeks (for example)
        for ($i = 1; $i <= 12; $i++) {
            foreach ($repeatDays as $day) {
                $newSchedule = $schedule->replicate();
                $newSchedule->start = $schedule->start->next($day);
                $newSchedule->end = $schedule->end->next($day);
                $newSchedule->save();
            }
        }
    }
    
    private function createYearlySchedules($schedule)
    {
        // Repeat the schedule every year for the next 5 years (for example)
        for ($i = 1; $i <= 5; $i++) {
            $newSchedule = $schedule->replicate();
            $newSchedule->start = $schedule->start->addYear();
            $newSchedule->end = $schedule->end->addYear();
            $newSchedule->save();
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('schedule.show', compact('schedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        return view('schedule.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date_format:H:i',
            'end' => 'required|date_format:H:i|after:start',
            'repeat' => 'nullable|string|in:none,daily,weekly,yearly',
            'repeat_days' => 'nullable|array',
            'repeat_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'schedule_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $schedule = Schedule::findOrFail($id);
        $schedule->update([
            'title' => $request->title,
            'start' => $request->start,
            'end' => $request->end,
            'description' => $request->description,
            'repeat' => $request->repeat,
            'schedule_date' => $request->schedule_date,
            'repeat_days' => $request->repeat == 'weekly' && $request->repeat_days ? $request->repeat_days : null,
        ]);

        return redirect()->route('schedule.index')->with('success', 'Schedule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedule.index')->with('success', 'Schedule deleted successfully.');
    }
}

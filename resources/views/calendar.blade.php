@extends('layouts.user')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>

    <style>
        /* Calendar container styling */
        #calendar {
            margin-top: 20px;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 15px;
            background-color: #fff;
        }

        /* Time grid styling */
        .fc-timegrid-slot {
            height: 50px !important;
        }

        .fc-timegrid-axis-cushion {
            font-size: 14px !important;
        }

        /* Event styling */
        .job-event {
            background-color: #FF9800 !important; /* Orange for jobs */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        .job-event-allday {
            background-color: rgba(255, 152, 0, 0.3) !important; /* Semi-transparent orange */
            border: none !important;
        }

        .schedule-event-admin {
            background-color: #4CAF50 !important; /* Green for admin schedules */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        .schedule-event-recruiter {
            background-color: #2196F3 !important; /* Blue for recruiter schedules */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }
        
        .schedule-event-user {
            background-color: #9C27B0 !important; /* Purple for user schedules */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        .schedule-event {
            background-color: #2196F3 !important; /* Blue for schedules */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        /* Event hover effect */
        .fc-event:hover {
            opacity: 0.9;
            cursor: pointer;
        }

        /* Legend styling */
        .calendar-legend {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .legend-item {
            display: inline-block;
            margin-right: 20px;
        }

        .legend-color {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 5px;
            vertical-align: middle;
            border-radius: 3px;
        }

        /* Modal styling */
        .modal-backdrop {
            z-index: 1040 !important;
        }
        .modal {
            z-index: 1050 !important;
        }

        /* Job status indicators */
        .job-status-accepted {
            border-left: 4px solid #4CAF50 !important; /* Green border for active */
        }

        .job-status-pending {
            border-left: 4px solid #FFC107 !important; /* Yellow border for pending */
        }

        .job-status-completed {
            border-left: 4px solid #9E9E9E !important; /* Grey border for completed */
        }

        /* Add this style for job events */
        .user-job-event {
            background-color: #FF9800 !important; /* Orange color for jobs */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }
    </style>
    <div class="container">
    <h1>My Calendar</h1>
    
    <!-- Simplified Legend Section -->
    <div class="calendar-legend">
        <div class="legend-item">
            <span class="legend-color" style="background-color: #2196F3;"></span>
            <span>My Schedule</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #FF9800;"></span>
            <span>Jobs</span>
        </div>
        
        <!-- Job Status Indicators -->
        <div class="legend-item">
            <span class="legend-color" style="background-color: #4CAF50;"></span>
            <span>Active Job</span>
        </div>
        <div class="legend-item">
            <span class="legend-color" style="background-color: #9E9E9E;"></span>
            <span>Completed Job</span>
        </div>

    </div>

    <div id="calendar"></div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    views: {
                        dayGridMonth: {
                            displayEventEnd: true,
                        },
                        timeGridWeek: {
                            slotDuration: '01:00:00',
                            slotMinTime: '00:00:00',
                            slotMaxTime: '24:00:00'
                        },
                        timeGridDay: {
                            slotDuration: '01:00:00',
                            slotMinTime: '00:00:00',
                            slotMaxTime: '24:00:00'
                        }
                    },
                    allDaySlot: true,
                    events: function(info, successCallback, failureCallback) {
                        Promise.all([
                            fetch('/calendar/schedules').then(response => response.json()),
                            fetch('/calendar/job-applications').then(response => response.json()),
                            fetch('/calendar/user-jobs').then(response => response.json())
                        ])
                        .then(([schedules, jobApplications, userJobs]) => {
                            const events = [
                                ...schedules.map(schedule => ({
                                    id: schedule.id,
                                    title: `${schedule.title}`,
                                    start: schedule.start,
                                    end: schedule.end,
                                    className: 'schedule-event',
                                    extendedProps: {
                                        type: 'schedule',
                                        user: schedule.user_name,
                                        description: schedule.description
                                    }
                                })),
                                ...jobApplications.map(app => ({
                                    id: app.job.id,
                                    title: `[Job] ${app.job.title}`,
                                    start: app.job.start_date,
                                    end: app.job.end_date,
                                    className: 'job-event',
                                    extendedProps: {
                                        type: 'job',
                                        recruiter: app.job.recruiter_name,
                                        location: app.job.location,
                                        salary: app.job.salary,
                                        duration: app.job.duration,
                                        status: app.status,
                                        description: app.job.description
                                    }
                                })),
                                ...userJobs
                            ];
                            successCallback(events);
                        })
                        .catch(error => {
                            console.error('Error fetching events:', error);
                            failureCallback(error);
                        });
                    },
                    eventDidMount: function(info) {
                        // Add status-specific styling for jobs
                        if (info.event.extendedProps.type === 'job') {
                            const status = info.event.extendedProps.status.toLowerCase();
                            if (status === 'active') {
                                info.el.classList.add('job-status-active');
                            } else if (status === 'pending') {
                                info.el.classList.add('job-status-pending');
                            } else if (status === 'completed') {
                                info.el.classList.add('job-status-completed');
                            }
                        }
                        
                        // Hide background events in time grid views
                        if (info.event.display === 'background' && 
                            (calendar.view.type === 'timeGridWeek' || calendar.view.type === 'timeGridDay')) {
                            info.el.style.display = 'none';
                        }
                        // Hide time-specific events in month view
                        if (!info.event.display && 
                            info.event.extendedProps.type === 'job' && 
                            calendar.view.type === 'dayGridMonth') {
                            info.el.style.display = 'none';
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.display !== 'background') {
                            let event = info.event;
                            let props = event.extendedProps;
                            
                            let message = '';
                            if (props.type === 'job') {
                                message = `Job: ${event.title.replace('[Job] ', '')}\n` +
                                        `Recruiter: ${props.recruiter}\n` +
                                        `Location: ${props.location}\n` +
                                        `Salary: ${props.salary}\n` +
                                        `Duration: ${props.duration}\n` +
                                        `Status: ${props.status}\n` +
                                        `Time: ${event.start.toLocaleTimeString()} - ${event.end.toLocaleTimeString()}\n` +
                                        `Description: ${props.description}`;
                            } else if (props.type === 'user-job') {
                                message = `My Job: ${event.title.replace('[My Job] ', '')}\n` +
                                        `Location: ${props.location}\n` +
                                        `Salary: ${props.salary}\n` +
                                        `Duration: ${props.duration}\n` +
                                        `Status: ${props.status}\n` +
                                        `Time: ${event.start.toLocaleTimeString()} - ${event.end.toLocaleTimeString()}\n` +
                                        `Description: ${props.description}`;
                            } else {
                                message = `Schedule: ${event.title.replace('[Schedule] ', '')}\n` +
                                        `User: ${props.user}\n` +
                                        `Start: ${event.start.toLocaleString()}\n` +
                                        `End: ${event.end.toLocaleString()}\n` +
                                        `Description: ${props.description}`;
                            }
                            alert(message);
                        }
                    }
                });

                calendar.render();

                // Handle form submission
                document.getElementById('eventForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    
                    fetch('/calendar/store', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            calendar.refetchEvents();
                            $('#eventModal').modal('hide');
                            this.reset();
                        }
                    });
                });
            });
        </script>
    </div>
@endsection

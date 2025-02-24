@extends('layouts.admin')
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

        /* Job status styling */
        .job-status-open {
            background-color: #17a2b8 !important; /* Blue for open jobs */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        .job-status-active {
            background-color: #28a745 !important; /* Green for active jobs */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        .job-status-scheduled {
            background-color: #ffc107 !important; /* Yellow for scheduled jobs */
            border: none !important;
            color: black !important;
            font-weight: bold !important;
        }

        .job-status-completed {
            background-color: #6c757d !important; /* Grey for completed jobs */
            border: none !important;
            color: white !important;
            font-weight: bold !important;
        }

        .job-status-closed {
            background-color: #800000 !important; /* Maroon for closed jobs */
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
    </style>

    <div class="container">
        <h1>Job Calendar Management</h1>
                
        <!-- Calendar Legend -->
        <div class="calendar-legend mt-3 bg-light">
            <div class="legend-item">
                <span class="legend-color job-status-open"></span>
                <span>Open Jobs</span>
            </div>
            <div class="legend-item">
                <span class="legend-color job-status-active"></span>
                <span>Active Jobs</span>
            </div>
            <div class="legend-item">
                <span class="legend-color job-status-scheduled"></span>
                <span>Scheduled Jobs</span>
            </div>
            <div class="legend-item">
                <span class="legend-color job-status-completed"></span>
                <span>Completed Jobs</span>
            </div>
            <div class="legend-item">
                <span class="legend-color job-status-closed"></span>
                <span>Closed Jobs</span>
            </div>
        </div>
        <div id="calendar"></div>

        <!-- Job Details Modal -->
        <div class="modal fade" id="jobModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jobModalLabel">Job Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="jobDetails"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

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
                        timeGridWeek: {
                            slotDuration: '00:30:00',
                            slotMinTime: '06:00:00',
                            slotMaxTime: '22:00:00',
                            nowIndicator: true
                        },
                        timeGridDay: {
                            slotDuration: '00:30:00',
                            slotMinTime: '06:00:00',
                            slotMaxTime: '22:00:00',
                            nowIndicator: true
                        }
                    },
                    allDaySlot: false,
                    events: function(fetchInfo, successCallback, failureCallback) {
                        fetch("{{ route('admin.calendar.events') }}")
                            .then(response => response.json())
                            .then(events => {
                                const formattedEvents = [];
                                
                                events.forEach(event => {
                                    // Get start and end dates
                                    const startDate = new Date(event.start_date);
                                    const endDate = new Date(event.end_date);
                                    
                                    // Create an event for each day in the date range
                                    for (let date = new Date(startDate); date <= endDate; date.setDate(date.getDate() + 1)) {
                                        const currentDate = date.toISOString().split('T')[0];
                                        
                                        formattedEvents.push({
                                            id: event.id,
                                            title: event.title,
                                            start: `${currentDate}T${event.start_time}`, // Combine date with start time
                                            end: `${currentDate}T${event.end_time}`,    // Combine date with end time
                                            className: `job-status-${event.status.toLowerCase()}`,
                                            extendedProps: {
                                                recruiter: event.recruiter_name,
                                                location: event.location,
                                                salary: event.salary,
                                                duration: event.duration,
                                                status: event.status,
                                                description: event.description,
                                                dateRange: `${event.start_date} to ${event.end_date}` // Store full date range
                                            }
                                        });
                                    }
                                });
                                
                                successCallback(formattedEvents);
                            })
                            .catch(error => {
                                console.error('Error fetching events:', error);
                                failureCallback(error);
                            });
                    },
                    eventClick: function(info) {
                        let event = info.event;
                        let props = event.extendedProps;
                        
                        document.getElementById('jobDetails').innerHTML = `
                            <p><strong>Title:</strong> ${event.title}</p>
                            <p><strong>Recruiter:</strong> ${props.recruiter}</p>
                            <p><strong>Location:</strong> ${props.location}</p>
                            <p><strong>Salary:</strong> ${props.salary}</p>
                            <p><strong>Duration:</strong> ${props.duration}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${getBadgeClass(props.status)}">${props.status}</span></p>
                            <p><strong>Daily Schedule:</strong> ${formatTime(event.start)} - ${formatTime(event.end)}</p>
                            <p><strong>Date Range:</strong> ${props.dateRange}</p>
                            <p><strong>Description:</strong> ${props.description || 'No description available'}</p>
                        `;
                        
                        // Show modal
                        new bootstrap.Modal(document.getElementById('jobModal')).show();
                    }
                });

                calendar.render();

                // Helper function to get badge class based on status
                function getBadgeClass(status) {
                    switch(status.toLowerCase()) {
                        case 'active':
                            return 'success';
                        case 'pending':
                            return 'warning';
                        case 'completed':
                            return 'secondary';
                        default:
                            return 'primary';
                    }
                }

                // Refresh calendar events every minute to catch status updates
                setInterval(() => {
                    calendar.refetchEvents();
                }, 60000); // 60000 milliseconds = 1 minute

                // Add modal close button functionality
                document.getElementById('closeModalBtn').addEventListener('click', function() {
                    const modal = document.getElementById('jobModal');
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });

                // Add ESC key functionality
                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        const modal = document.getElementById('jobModal');
                        const modalInstance = bootstrap.Modal.getInstance(modal);
                        if (modalInstance) {
                            modalInstance.hide();
                        }
                    }
                });

                // Add this helper function for time formatting
                function formatTime(date) {
                    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                }
            });
        </script>
    </div>
@endsection
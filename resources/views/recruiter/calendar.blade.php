@extends('layouts.recruiter')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Job Calendar</h3>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css' rel='stylesheet' />
<link href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.print.min.css' rel='stylesheet' media='print' />
<style>
    .fc-event {
        cursor: pointer;
    }
    #calendar {
        margin: 20px 0;
        background: white;
    }
</style>
@endpush

@push('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: {
            url: '{{ route('recruiter.calendar.events') }}',
            error: function() {
                alert('Error fetching events!');
            }
        },
        eventRender: function(event, element) {
            element.popover({
                title: event.title,
                content: `
                    <p><strong>Location:</strong> ${event.location}</p>
                    <p><strong>Salary:</strong> RM${event.salary}</p>
                    <p><strong>Duration:</strong> ${event.duration} hours</p>
                    <p><strong>Status:</strong> ${event.status}</p>
                `,
                trigger: 'hover',
                placement: 'top',
                container: 'body',
                html: true
            });
        },
        eventClick: function(event) {
            // You can add a modal or redirect to job details page
            window.location.href = `/recruiter/jobs/${event.id}`;
        },
        displayEventTime: true,
        displayEventEnd: true,
        firstDay: 1, // Monday
        businessHours: true,
        editable: false,
        eventLimit: true
    });
});
</script>
@endpush
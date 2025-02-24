@extends('layouts.user')

@section('content')
    <h1>Create Your Schedule</h1>
    <form action="{{ route('schedule.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Schedule Title</label>
            <input type="text" name="title" class="form-control" id="title" required>
        </div>
        
        <div class="form-group">
            <label for="start">Start Time</label>
            <input type="time" name="start" class="form-control" id="start" required>
        </div>
        
        <div class="form-group">
            <label for="end">End Time</label>
            <input type="time" name="end" class="form-control" id="end" required>
        </div>
        
        <div class="form-group">
            <label for="repeat">Repeat Event</label><br>
            <input type="radio" name="repeat" value="none" id="repeat-none" checked> <label for="repeat-none">None</label><br>
            <input type="radio" name="repeat" value="daily" id="repeat-daily"> <label for="repeat-daily">Daily</label><br>
            <input type="radio" name="repeat" value="weekly" id="repeat-weekly"> <label for="repeat-weekly">Weekly</label><br>
            <input type="radio" name="repeat" value="yearly" id="repeat-yearly"> <label for="repeat-yearly">Yearly</label>
        </div>

        <!-- Specific Date Field -->
        <div class="form-group" id="specificDate">
            <label for="schedule_date">Schedule Date</label>
            <input type="date" name="schedule_date" class="form-control" id="schedule_date">
        </div>

        <!-- Repeat Days Field -->
        <div class="form-group" id="repeatDays">
            <label>Select Days for Weekly Repeat</label>
            <div class="days-container">
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="monday" id="monday">
                    <label for="monday">Monday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="tuesday" id="tuesday">
                    <label for="tuesday">Tuesday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="wednesday" id="wednesday">
                    <label for="wednesday">Wednesday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="thursday" id="thursday">
                    <label for="thursday">Thursday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="friday" id="friday">
                    <label for="friday">Friday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="saturday" id="saturday">
                    <label for="saturday">Saturday</label>
                </div>
                <div class="day-option">
                    <input type="checkbox" name="repeat_days[]" value="sunday" id="sunday">
                    <label for="sunday">Sunday</label>
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Schedule</button>
    </form>
@endsection

@section('styles')
<style>
    #repeatDays, #specificDate {
        display: none; /* Default to hidden using CSS */
    }

    #repeatDays {
        margin-top: 10px;
    }

    .days-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* 3 columns layout */
        gap: 10px; /* Space between options */
        margin-top: 10px;
    }

    .day-option {
        display: flex;
        align-items: center;
    }

    .day-option label {
        margin-left: 5px;
    }
</style>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function () {
        // Show or hide fields based on repeat option
        $('input[name="repeat"]').change(function () {
            let repeatValue = $(this).val();

            if (repeatValue === 'none') {
                $('#specificDate').show();  // Show specific date field
                $('#schedule_date').val(''); // Clear date field if previously hidden
                $('#repeatDays').hide();   // Hide repeat days field
            } else if (repeatValue === 'weekly') {
                $('#specificDate').hide(); // Hide specific date field
                $('#repeatDays').show();   // Show repeat days field
            } else {
                $('#specificDate').hide(); // Hide specific date field
                $('#repeatDays').hide();   // Hide repeat days field
            }
        });

        // Initialize visibility
        $('input[name="repeat"]:checked').trigger('change');
    });
</script>
@endsection

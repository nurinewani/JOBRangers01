@extends('layouts.user')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-header">
            <h4>Create Schedule</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('schedule.store') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="title">Event Title</label>
                    <input type="text" 
                           name="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           id="title" 
                           placeholder="e.g., Work Shift, Monthly Meeting, Birthday"
                           value="{{ old('title') }}" 
                           required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="description">Description</label>
                    <textarea name="description" 
                              class="form-control @error('description') is-invalid @enderror" 
                              id="description" 
                              placeholder="Add details about your event"
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="start">Start Time</label>
                            <input type="time" 
                                   name="start" 
                                   class="form-control @error('start') is-invalid @enderror" 
                                   id="start" 
                                   value="{{ old('start') }}" 
                                   required>
                            @error('start')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="end">End Time</label>
                            <input type="time" 
                                   name="end" 
                                   class="form-control @error('end') is-invalid @enderror" 
                                   id="end" 
                                   value="{{ old('end') }}" 
                                   required>
                            @error('end')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <div class="form-group mb-3">
                    <label>Event Repeat Pattern</label>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check">
                            <input type="radio" name="repeat" value="none" id="repeat-none" class="form-check-input" 
                                {{ old('repeat', 'none') === 'none' ? 'checked' : '' }}>
                            <label class="form-check-label" for="repeat-none">One-time Event</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="repeat" value="daily" id="repeat-daily" class="form-check-input"
                                {{ old('repeat') === 'daily' ? 'checked' : '' }}>
                            <label class="form-check-label" for="repeat-daily">Daily (Every day)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="repeat" value="weekly" id="repeat-weekly" class="form-check-input"
                                {{ old('repeat') === 'weekly' ? 'checked' : '' }}>
                            <label class="form-check-label" for="repeat-weekly">Weekly (Select days)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="repeat" value="yearly" id="repeat-yearly" class="form-check-input"
                                {{ old('repeat') === 'yearly' ? 'checked' : '' }}>
                            <label class="form-check-label" for="repeat-yearly">Yearly</label>
                        </div>
                    </div>
                </div>

                <div id="specificDate" class="form-group mb-3">
                    <label for="schedule_date">Event Date</label>
                    <input type="date" 
                           name="schedule_date" 
                           class="form-control @error('schedule_date') is-invalid @enderror" 
                           id="schedule_date"
                           value="{{ old('schedule_date') }}">
                    <div class="form-text">Select the date for your one-time or yearly event</div>
                    @error('schedule_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div id="repeatDays" class="form-group mb-3">
                    <label>Select Days of the Week</label>
                    <div class="days-container">
                        @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                            <div class="form-check">
                                <input type="checkbox" 
                                       name="repeat_days[]" 
                                       value="{{ $day }}" 
                                       id="{{ $day }}" 
                                       class="form-check-input weekly-day-checkbox"
                                       {{ in_array($day, old('repeat_days', [])) ? 'checked' : '' }}>
                                <label class="form-check-label" for="{{ $day }}">{{ ucfirst($day) }}</label>
                            </div>
                        @endforeach
                    </div>
                    <div class="form-text">For weekly events, select which days of the week this event occurs (at least one day required)</div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Create Schedule</button>
                    <a href="{{ route('schedule.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .days-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 10px;
        margin-top: 10px;
        padding: 15px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }

    #repeatDays, #specificDate {
        display: none;
    }

    .form-check {
        margin-bottom: 0.5rem;
    }

    .gap-3 {
        gap: 1rem !important;
    }

    .form-text {
        margin-top: 0.5rem;
        color: #6c757d;
    }
</style>
@endsection

@section('scripts')
@parent
<script>
    $(document).ready(function () {
        // Initially hide both conditional fields
        $('#specificDate, #repeatDays').hide();
        
        // Handle repeat pattern changes
        $('input[name="repeat"]').change(function () {
            let repeatValue = $(this).val();
            
            // First hide both sections
            $('#specificDate, #repeatDays').hide();
            
            // Then show only the relevant section
            if (repeatValue === 'none' || repeatValue === 'yearly') {
                $('#specificDate').show();
                $('#schedule_date').prop('required', true);
                $('.weekly-day-checkbox').prop('required', false);
            } else if (repeatValue === 'weekly') {
                $('#repeatDays').show();
                $('#specificDate').hide();
                $('#schedule_date').prop('required', false);
                // Remove required from individual checkboxes
                $('.weekly-day-checkbox').prop('required', false);
            } else {
                // For daily, both sections remain hidden
                $('#specificDate').hide();
                $('#schedule_date').prop('required', false);
                $('.weekly-day-checkbox').prop('required', false);
            }
        });

        // Set initial state based on default selection
        $('input[name="repeat"]:checked').trigger('change');

        // Form validation
        $('form').submit(function(e) {
            let repeatValue = $('input[name="repeat"]:checked').val();
            
            if ((repeatValue === 'none' || repeatValue === 'yearly') && !$('#schedule_date').val()) {
                e.preventDefault();
                alert('Please select a date for your event.');
            } else if (repeatValue === 'weekly') {
                // Check if at least one day is selected
                if (!$('.weekly-day-checkbox:checked').length) {
                    e.preventDefault();
                    alert('Please select at least one day of the week for weekly events.');
                }
            }
        });
    });
</script>
@endsection

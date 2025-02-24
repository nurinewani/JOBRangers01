@extends('layouts.user')
@section('content')
<div class="row"> 

    <div class="col-lg-12 margin-tb"> 
        <div class="pull-left"> 
            <h2>User Schedules</h2> 
        </div> 
    </div>
    <div style="margin-bottom: 20px; margin-top: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('schedule.create') }}">
                Add New Schedule
            </a>
        </div>

    </div>
</div> 



    @if ($message = Session::get('success')) 
        <div class="alert alert-success"> 
            <p>{{ $message }}</p> 
        </div> 
    @endif 

    @if ($message = Session::get('error'))  
    <div class="alert alert-danger">         
        <p>{{ $message }}</p>    
    </div> 
    @endif

<div class="card">
    <div class="card-header">
        Schedules List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Schedule">
                <thead>
                    <tr>
                        <th width="200">Title</th>
                        <th>Description</th>
                        <th>Repeat Days</th>
                        <th>Schedule Date</th>
                        <th width="200">Start Time</th>
                        <th width="200">End Time</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $schedule)
                        <tr data-entry-id="{{ $schedule->id }}">
                            <td>{{ $schedule->title ?? '' }}</td>
                            <td>{{ $schedule->description ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $repeatDays = [];
                                    if ($schedule->repeat_days) {
                                        if (is_string($schedule->repeat_days)) {
                                            $repeatDays = json_decode($schedule->repeat_days, true) ?? [];
                                        } else {
                                            $repeatDays = $schedule->repeat_days;
                                        }
                                    }
                                @endphp
                                
                                @if(!empty($repeatDays))
                                    {{ implode(', ', array_map('ucfirst', $repeatDays)) }}
                                @else
                                    None
                                @endif
                            </td>
                            <td>{{ $schedule->schedule_date ?? 'N/A' }}</td>
                            <td>{{ $schedule->start ? \Carbon\Carbon::parse($schedule->start)->format('H:i') : 'N/A' }}</td>
                            <td>{{ $schedule->end ? \Carbon\Carbon::parse($schedule->end)->format('H:i') : 'N/A' }}</td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('schedule.show', $schedule->id) }}">
                                    View
                                </a>
                                <a class="btn btn-xs btn-info" href="{{ route('schedule.edit', $schedule->id) }}">
                                    Edit
                                </a>
                                <form action="{{ route('schedule.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
        $.extend(true, $.fn.dataTable.defaults, {
            order: [[ 1, 'asc' ]],
            pageLength: 10,
        });
        $('.datatable-Schedule:not(.ajaxTable)').DataTable({ buttons: dtButtons });
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
@endsection

@section('content') 
    <div class="row"> 
        <div class="col-lg-12 margin-tb"> 
            <div class="pull-left"> 
                <h2 style="margin-left: 20px;">Applied Jobs</h2> 
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

    <table class="table table-bordered" style="margin-top: 20px; border-radius: 10px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);"> 
        <tr style="text-align: center; border: 1px solid #000 !important;">
            <th style="border: 1px solid #000 !important;">No</th>
            <th style="border: 1px solid #000 !important;">Title</th>
            <th style="border: 1px solid #000 !important;">Location</th>
            <th style="border: 1px solid #000 !important;">Salary (RM)</th>
            <th style="border: 1px solid #000 !important;">Action</th> 
        </tr> 

        @foreach ($jobs as $job)
        <tr style="border: 1px solid #000 !important;"> 
            <td style="border: 1px solid #000 !important;">{{ $job->id }}</td>
            <td style="border: 1px solid #000 !important;">{{ $job->title }}</td>
            <td style="border: 1px solid #000 !important;">{{ $job->location }}</td>
            <td style="border: 1px solid #000 !important;">{{ $job->salary }}</td>
            <td style="border: 1px solid #000 !important;">
                <!-- Show job details -->
                <a class="btn btn-info" href="{{ route('user.jobs.show', $job->id) }}" style="margin:5px;">Show</a>

                <!-- Delete job application -->
                <form action="{{ route('user.jobapplication.destroy', $job->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="margin:5px;" onclick="return confirm('Are you sure you want to delete your application for this job?')">Delete</button>
                </form>
            </td>
        </tr> 
        @endforeach 
    </table>
@endsection

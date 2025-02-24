@section('content') 
    <div class="row"> 
        <div class="col-lg-12 margin-tb"> 
            <div class="pull-left"> 
                <h2>Applied Jobs</h2> 
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

    <table class="table table-bordered"> 
        <tr style="text-align: center;">
            <th>No</th>
            <th>Title</th>
            <th>Location</th>
            <th>Salary (RM)</th>
            <th width="250px">Action</th> 
        </tr> 
 
        @foreach ($jobs as $job)
 
        <tr> 
            <td>{{ $job->id }}</td>
            <td>{{ $job->title }}</td>
            <td>{{ $job->location }}</td>
            <td>{{ $job->salary }}</td>
            <td>
                <form action="{{ route('recruiter.jobs.destroy', $job->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('recruiter.jobs.show',$job->id) }}" style="margin:5px;">Show</a>
                    <a class="btn btn-primary" href="{{ route('recruiter.jobs.edit', $job->id) }}" style="margin:5px;">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="margin:5px;" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr> 
        @endforeach 
    </table>
@endsection


@section('content') 
    <div class="row"> 
        <div class="col-lg-12 margin-tb"> 
            <div class="pull-left"> 
                <h2>Discover Jobs!</h2> 
            </div> 
        </div> 
    </div> 
    
    @if ($message = Session::get('success')) 
        <div class="alert alert-success"> 
            <p>{{ $message }}</p> 
        </div> 
    @endif 

    <table class="table table-bordered"> 
        <tr> 
            <!--<th>No</th>--> 
            <th>Title</th>
            <th>Description</th>
            <th>Location</th>
            <th>Salary (RM)</th>
            <th>Duration(Hrs)</th>
            <th>Application Deadline</th>
            <th>Apply</th>
            <th width="250px">Action</th> 
        </tr> 
 
 
        @foreach ($jobs as $job)
 
        <tr> 
            <!--<td>{{ $job->id }}</td>--> 
            <td>{{ $job->title }}</td>
            <td>{{ $job->description }}</td>
            <td>{{ $job->location }}</td>
            <td>{{ $job->salary }}</td>
            <td>{{ $job->duration }}</td>
            <td>{{ $job->application_deadline }}</td>
            <td>{{ $job->apply }}</td>
            <td>
                <form action="{{ route('jobs.destroy', $job->id) }}" method="POST">
                    <a class="btn btn-info" href="{{ route('jobs.show',$job->id) }}">Show</a>
                    <a class="btn btn-primary" href="{{ route('jobs.edit', $job->id) }}">Edit</a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr> 
        @endforeach 
    </table>
@endsection


@foreach($applications as $application)
    <p>{{ $application->user->name }} - {{ $application->status }}</p>
    <form action="{{ route('applications.update', [$application->id, 'accepted']) }}" method="POST">
        @csrf
        <button type="submit">Accept</button>
    </form>
    <form action="{{ route('applications.update', [$application->id, 'rejected']) }}" method="POST">
        @csrf
        <button type="submit">Reject</button>
    </form>
@endforeach

@extends('layouts.user')
@section('content')
<div class="row">
  <div class="row mb-2">
    <br>
    <div class="col-sm-6">
      <h1 class="m-0">Hello, USER!</h1>
    </div>
    <br>
    <br>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="/home">User</a></li>
        <li class="breadcrumb-item active"><a href="/home">Dashboard</a></li>
      </ol>
    </div>
  </div>

  <!-- Statistics Boxes -->
  <div class="col-lg-3 col-6 h-">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(47, 83, 189); color: white; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ $totalJobs }}</h3>
        <p style="padding-left: 20px;">Total Jobs in The System</p>
      </div>
      <div class="icon">
        <i class="ion ion-bag" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(240, 210, 63); color: black; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ number_format($jobApplyRate, 2) }}%</h3>
        <p style="padding-left: 20px;">Job Apply Rate</p>
      </div>
      <div class="icon">
        <i class="ion ion-stats-bars" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(26, 163, 56); color: white; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ $totalApplications }}</h3>
        <p style="padding-left: 20px;">Total Applications</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(125, 188, 255); color: black; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ $totalVisitors }}</h3>
        <p style="padding-left: 20px;">Total Visitors</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>
</div>

<!-- Add this after the statistics boxes -->
<div class="row mt-2">
    <div class="col-12">
        <div class="card">
            <div class="card-header" style="background-color:rgb(90, 69, 0); color: white;">
                <h3 class="card-title">
                    <i class="fas fa-briefcase"></i> My Job Applications Status
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Applied Applications -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-info">
                            <span class="info-box-icon">
                                <i class="fas fa-file-alt"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><strong>Applied Jobs</strong></span>
                                <span class="info-box-number">{{ $appliedApplications }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $totalApplications > 0 ? ($appliedApplications / $totalApplications) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Applications (Approved/Accepted) -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-success">
                            <span class="info-box-icon">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><strong>Active Jobs</strong></span>
                                <span class="info-box-number">{{ $activeApplications }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $totalApplications > 0 ? ($activeApplications / $totalApplications) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rejected Applications -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-danger">
                            <span class="info-box-icon">
                                <i class="fas fa-times-circle"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><strong>Rejected Jobs</strong></span>
                                <span class="info-box-number">{{ $rejectedApplications }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: {{ $totalApplications > 0 ? ($rejectedApplications / $totalApplications) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Applications -->
                    <div class="col-md-3 col-sm-6 col-12">
                        <div class="info-box bg-secondary">
                            <span class="info-box-icon">
                                <i class="fas fa-folder"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text"><strong>Total Applications</strong></span>
                                <span class="info-box-number">{{ $totalApplications }}</span>
                                <div class="progress">
                                    <div class="progress-bar" style="width: 100%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Applications Table -->
                <div class="table-responsive mt-4">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUserApplications as $application)
                                <tr>
                                    <td>{{ $application->job->title }}</td>
                                    <td>{{ $application->created_at->format('d M Y') }}</td>
                                    <td>
                                        @switch($application->status)
                                            @case('applied')
                                                <span class="badge bg-warning">Applied</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-info">Approved</span>
                                                @break
                                            @case('accepted')
                                                <span class="badge bg-success">Accepted</span>
                                                @break
                                            @case('declined')
                                                <span class="badge bg-secondary">Declined</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('withdrawn')
                                                <span class="badge bg-dark">Withdrawn</span>
                                                @break
                                            @default
                                                <span class="badge bg-light text-dark">{{ ucfirst($application->status) }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('user.applications.show', $application->id) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

  <div class="row">
    <!-- Recent Job Applications -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:rgb(90, 69, 0); color: white;">
          <h3 class="card-title">
            <i class="bi bi-bell-fill"></i> Recent Job Applications
          </h3>
        </div>
        <div class="card-body">
          <ul class="list-group">
            @foreach($recentApplications as $application)
              <li class="list-group-item">
                {{ $application->user->name }} applied for 
                <strong>{{ $application->job->title }}</strong> on 
                {{ $application->created_at->format('d M Y') }}.
              </li>
            @endforeach
          </ul>
        </div>
      </div>
  </div>

    <!-- Today's Schedule -->
    <div class="col-lg-6">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color:rgb(90, 69, 0); color: white;">
          <h3 class="card-title">
            <i class="bi bi-calendar-day"></i> Today's Schedule ({{ now()->format('d M Y') }})
          </h3>
        </div>
        <div class="card-body">
          <div class="table-responsive">
          <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Time</th>
                  <th>Title</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($todaysSchedule as $schedule)
                  <tr>
                    <td style="width: 180px;">
                      <span class="text-primary">{{ $schedule->start_time }}</span> - 
                      <span class="text-danger">{{ $schedule->end_time }}</span>
                    </td>
                    <td><strong>{{ $schedule->title }}</strong></td>
                    <td>{{ Str::limit($schedule->description, 50) }}</td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="3" class="text-center text-muted">
                      <i class="bi bi-calendar-x"></i> No schedules for today
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @include('user.partials.informations')
</div> 
@endsection

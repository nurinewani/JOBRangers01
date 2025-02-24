@extends('layouts.admin')
@section('content')
<div class="row">
  <div class="row mb-2">
    <br>
    <div class="col-sm-6">
      <h1 class="m-0">Hello, ADMINISTRATOR!</h1>
    </div>
    <br>
    <br>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboardA') }}">Admin</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('admin.dashboardA') }}">Dashboard</a></li>
      </ol>
    </div>
  </div>

  <!-- Statistics Boxes -->
  <div class="col-lg-3 col-6 h-">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(47, 83, 189); color: white; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ $totalJobs }}</h3>
        <p style="padding-left: 20px;">Total Jobs</p>
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
        <p style="padding-left: 20px;">All Time Applications</p>
      </div>
      <div class="icon">
        <i class="ion ion-person-add" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box" style="height: 120px; padding:10px; background-color: rgb(61, 58, 54); color: white; border-radius: 70px;">
      <div class="inner">
        <h3 style="padding-left: 20px;">{{ $totalVisitors }}</h3>
        <p style="padding-left: 20px;">Total Visitors</p>
      </div>
      <div class="icon">
        <i class="ion ion-pie-graph" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box" style="height: 120px; padding:10px; background-color: #8B0000; color: white; border-radius: 70px;">
      <div class="inner">
          <h3 style="padding-left: 20px;">{{ $pendingApplications }}</h3>
          <p style="padding-left: 20px;">Active Applications</p>
      </div>
      <div class="icon">
          <i class="ion ion-clock" style="padding-right: 20px;"></i>
      </div>
    </div>
  </div>

    <div class="col-lg-3 col-6">
      <div class="small-box" style="height: 120px; padding:10px; background-color: #4B0082; color: white; border-radius: 70px;">
        <div class="inner">
          <h3 style="padding-left: 20px;">{{ $totalRecruiters }}</h3>
          <p style="padding-left: 20px;">Total Recruiters</p>
        </div>
        <div class="icon">
          <i class="ion ion-person" style="padding-right: 20px;"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-6">
      <div class="small-box" style="height: 120px; padding:10px; background-color: #006400; color: white; border-radius: 70px;">
        <div class="inner">
          <h3 style="padding-left: 20px;">{{ $activeJobs }}</h3>
          <p style="padding-left: 20px;">Active Jobs</p>
        </div>
        <div class="icon">
          <i class="ion ion-briefcase" style="padding-right: 20px;"></i>
        </div>
      </div>
    </div>

    <div class="col-lg-3 col-6">
      <div class="small-box" style="height: 120px; padding:10px; background-color: #FF8C00; color: white; border-radius: 70px;">
        <div class="inner">
          <h3 style="padding-left: 20px;">{{ $completedJobs }}</h3>
          <p style="padding-left: 20px;">Completed Jobs</p>
        </div>
        <div class="icon">
          <i class="ion ion-checkmark" style="padding-right: 20px;"></i>
        </div>
      </div>
    </div>
  </div>

  <!-- New Statistics Row -->
  <div class="row">
  <!-- Recent Job Applications -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
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

  <!-- Recent Job Listings -->
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
        <h3 class="card-title">
          <i class="bi bi-briefcase-fill"></i> Recent Job Listings
        </h3>
      </div>
      <div class="card-body">
        <ul class="list-group">
          @foreach($recentJobs as $job)
            <li class="list-group-item">
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <strong>{{ $job->title }}</strong>
                  <br>
                  <small class="text-muted">
                    Posted by: {{ $job->creator_with_role }}
                  </small>
                </div>
                <small class="text-muted">
                  {{ $job->created_at->diffForHumans() }}
                </small>
              </div>
            </li>
          @endforeach
        </ul>
      </div>
    </div>
  </div>

  <!-- System Status -->
  <div class="col-lg-12 mt-4">
    <div class="card">
      <div class="card-header bg-secondary text-white">
        <h3 class="card-title">
          <i class="bi bi-gear-fill"></i> System Status
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3 text-center">
            <h4>Server Load</h4>
            <div class="progress">
              <div class="progress-bar bg-success" role="progressbar" style="width: {{ $serverLoad }}%">
                {{ $serverLoad }}%
              </div>
            </div>
          </div>
          <div class="col-md-3 text-center">
            <h4>Storage Usage</h4>
            <div class="progress">
              <div class="progress-bar bg-info" role="progressbar" style="width: {{ $storageUsage }}%">
                {{ $storageUsage }}%
              </div>
            </div>
          </div>
          <div class="col-md-3 text-center">
            <h4>Last Backup</h4>
            <p>{{ $lastBackup }}</p>
          </div>
          <div class="col-md-3 text-center">
            <h4>System Version</h4>
            <p>v{{ $systemVersion }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // User Activity Chart
  const ctx = document.getElementById('userActivityChart').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: {!! json_encode($activityDates) !!},
      datasets: [{
        label: 'User Activity',
        data: {!! json_encode($activityCounts) !!},
        borderColor: 'rgb(75, 192, 192)',
        tension: 0.1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>
@endpush
@endsection



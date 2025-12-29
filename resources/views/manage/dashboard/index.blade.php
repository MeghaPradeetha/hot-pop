@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h2 class="h4 text-gray-800">Dashboard Overview</h2>
        <p class="text-muted">Welcome back, Admin!</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #4e73df !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #1cc88a !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Devices</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalDevices }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-mobile-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #36b9cc !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">New Users (7 Days)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newUsersLast7Days }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
         <div class="card border-0 shadow-sm h-100 py-2" style="border-left: 4px solid #f6c23e !important;">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">New Users (Today)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newUsersToday }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <div class="col-xl-8 col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-white">
                <h6 class="m-0 font-weight-bold text-primary">User Growth (Last 6 Months)</h6>
            </div>
            <div class="card-body">
                <div class="chart-area" style="height: 320px;">
                    <canvas id="userGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>

     <div class="col-xl-4 col-lg-5">
         <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                 <div class="d-grid gap-2">
                    <a href="{{ route('manage.users.index') }}" class="btn btn-outline-primary"><i class="fas fa-user-edit me-2"></i> Manage Users</a>
                 </div>
            </div>
        </div>
        <!-- Subscription Plans Mini Table -->
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-primary">Active Plans</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped mb-0 text-sm">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($plans as $plan)
                            <tr>
                                <td>{{ $plan->name }}</td>
                                <td>${{ number_format($plan->price, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="2" class="text-center">No plans found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="card-footer bg-white text-center">
                <a href="{{ route('manage.subs-plans.index') }}" class="small">View All Plans</a>
            </div>
        </div>
     </div>
</div>

<!-- Detailed Tables Row -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Recent Users</h6>
                <a href="{{ route('manage.users.index') }}" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                 <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentUsers as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3">No users found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reports -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-secondary">Recent Reports</h6>
                <a href="{{ route('manage.reports.index') }}" class="btn btn-sm btn-secondary">View All</a>
            </div>
             <div class="card-body p-0">
                 <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Reported By</th>
                                <th>Reason</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                         <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td>{{ $report->user->name ?? 'Unknown' }}</td>
                                <td>{{ Str::limit($report->reason, 30) }}</td>
                                <td>{{ $report->created_at->format('d M Y') }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-3">No reports found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');
        const userGrowthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'New Users',
                    data: @json($chartData),
                    borderColor: '#4e73df',
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    pointRadius: 3,
                    pointBackgroundColor: '#4e73df',
                    pointBorderColor: '#4e73df',
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: '#4e73df',
                    pointHoverBorderColor: '#4e73df',
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    },
                    y: {
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return value;
                            }
                        },
                        grid: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    },
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        titleMarginBottom: 10,
                        titleColor: '#6e707e',
                        titleFontSize: 14,
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        intersect: false,
                        mode: 'index',
                        caretPadding: 10,
                    }
                }
            }
        });
    });
</script>
@endpush

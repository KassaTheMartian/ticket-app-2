@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label>From Date</label>
                <input type="date" name="start_date" class="form-control" 
                       value="{{ \Carbon\Carbon::parse($startDate)->startOfDay()->format('Y-m-d') }}" 
                       max="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4">
                <label>To Date</label>
                <input type="date" name="end_date" class="form-control" 
                       value="{{ \Carbon\Carbon::parse($endDate)->endOfDay()->format('Y-m-d') }}" 
                       max="{{ now()->format('Y-m-d') }}">
            </div>
            <div class="col-md-4 d-flex align-items-end justify-content-between">
                <button type="submit" class="btn btn-primary mt-3 px-5">Statistics</button>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3 px-5">Reset</a>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalUsers }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalCustomers }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalTickets }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-ticket-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Unresolved Tickets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $totalNewTickets + $totalInProgressTickets }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-spinner fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Status</h6>
                </div>
                <div class="card-body">
                    <canvas id="ticketStatusChart"></canvas>
                </div>
            </div>
        </div>
        <!-- Biểu đồ Department -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tickets by Department</h6>
                </div>
                <div class="card-body">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Biểu đồ Ticket Type -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tickets by Type</h6>
                </div>
                <div class="card-body">
                    <canvas id="ticketTypeChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Trends</h6>
                </div>
                <div class="card-body">
                    <canvas id="ticketChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-xl-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top 10 Customers by Number of Tickets</h6>
                </div>
                <div class="card-body">
                    <canvas id="topCustomersChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ticket trends chart
    const dailyTickets = @json($dailyTickets);
    const labels = dailyTickets.map(item => item.date);
    const data = dailyTickets.map(item => item.total_tickets);

    new Chart(document.getElementById('ticketChart'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Number of Tickets',
                data: data,
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
    // Ticket status chart
    new Chart(document.getElementById('ticketStatusChart'), {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: ['New', 'In Progress', 'Resolved'],
            datasets: [{
                data: [
                    {{ $totalNewTickets }}, 
                    {{ $totalInProgressTickets }}, 
                    {{ $totalResolvedTickets }}
                ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                ]
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = ((value / total) * 100).toFixed(1);
                        return `${value}\n(${percentage}%)`;
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let value = context.parsed;
                            let percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    // Biểu đồ Department
    const departmentData = @json($ticketsByDepartment);
    new Chart(document.getElementById('departmentChart'), {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: departmentData.map(item => item.department_name),
            datasets: [{
                data: departmentData.map(item => item.total_tickets),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ]
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = ((value / total) * 100).toFixed(1);
                        return `${value}\n(${percentage}%)`;
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let value = context.parsed;
                            let percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Ticket Type Chart
    const ticketTypeData = @json($ticketsByType);
    new Chart(document.getElementById('ticketTypeChart'), {
        type: 'pie',
        plugins: [ChartDataLabels],
        data: {
            labels: ticketTypeData.map(item => item.type_name),
            datasets: [{
                data: ticketTypeData.map(item => item.total_tickets),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(153, 102, 255, 0.8)'
                ]
            }]
        },
        options: {
            plugins: {
                datalabels: {
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value, context) {
                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                        let percentage = ((value / total) * 100).toFixed(1);
                        return `${value}\n(${percentage}%)`;
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let total = context.dataset.data.reduce((a, b) => a + b, 0);
                            let value = context.parsed;
                            let percentage = ((value / total) * 100).toFixed(2);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    // Top Customers Chart
    const topCustomersData = @json($topCustomers);
    new Chart(document.getElementById('topCustomersChart'), {
        type: 'bar',
        plugins: [ChartDataLabels],
        data: {
            labels: topCustomersData.map(item => item.name),
            datasets: [{
                label: 'Number of Tickets',
                data: topCustomersData.map(item => item.total_tickets),
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y', // Biểu đồ ngang
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    color: 'black',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value;
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Tickets'
                    }
                }
            }
        }
    });
});
</script>
@endsection
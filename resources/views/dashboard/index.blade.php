@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-5 text-center">
        <h2 class="fw-bold display-5 text-primary">📚 Teacher Dashboard</h2>
        <p class="text-muted">Overview of your students and their performance</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body text-center py-4">
                    <h6 class="text-muted">Total Students</h6>
                    <h3 class="text-primary mb-0">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body text-center py-4">
                    <h6 class="text-muted">Total Grades</h6>
                    <h3 class="text-success mb-0">{{ $totalGrades }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body text-center py-4">
                    <h6 class="text-muted">Average Grade</h6>
                    <h3 class="text-info mb-0">{{ number_format($averageGrade, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow rounded-4 h-100">
                <div class="card-body text-center py-4">
                    <h6 class="text-muted">Passed / Failed</h6>
                    <h3 class="text-warning mb-0">{{ $passedStudents }} / {{ $failedStudents }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="card border-0 shadow rounded-4">
        <div class="card-header bg-white border-0 rounded-top-4 py-3">
            <h5 class="mb-0 fw-bold text-dark">📈 Average Grades per Student</h5>
        </div>
        <div class="card-body">
            <canvas id="gradesChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Chart Script -->
<script>
    const ctx = document.getElementById('gradesChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($studentNames) !!},
            datasets: [{
                label: 'Average Grade',
                data: {!! json_encode($averageGrades) !!},
                backgroundColor: 'rgba(0, 123, 255, 0.6)',
                borderColor: 'rgba(0, 123, 255, 1)',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        stepSize: 10
                    }
                }
            }
        }
    });
</script>

@endsection

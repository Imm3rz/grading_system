@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Dashboard Summary</h2>
    <div class="row mt-4">

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Total Students</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalStudents }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Total Grades</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $totalGrades }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-info mb-3">
                <div class="card-header">Average Grade</div>
                <div class="card-body">
                    <h5 class="card-title">{{ number_format($averageGrade, 2) }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-secondary mb-3">
                <div class="card-header">Passed Students</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $passedStudents }}</h5>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card text-white bg-danger mb-3">
                <div class="card-header">Failed Students</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $failedStudents }}</h5>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

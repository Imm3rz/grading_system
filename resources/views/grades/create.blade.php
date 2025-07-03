@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Grades for {{ $student->name }}</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>There was a problem:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.store', $student->id) }}" method="POST">
        {{ csrf_field() }}

        @php
            $subjects = ['Math', 'Science', 'English', 'Filipino', 'P.E.'];
        @endphp

        @foreach ($subjects as $subject)
            <div class="form-group mb-3">
                <label for="grades[{{ $subject }}]">{{ $subject }}:</label>
                <input type="number" name="grades[{{ $subject }}]" class="form-control" required min="0" max="100" step="0.01" placeholder="e.g. 89.5">
            </div>
        @endforeach

        <button type="submit" class="btn btn-primary">Save Grades</button>
        <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

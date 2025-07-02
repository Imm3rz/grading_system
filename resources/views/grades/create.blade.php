@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add Grade for {{ $student->name }}</h2>

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

        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" class="form-control" required placeholder="e.g. Math, Science">
        </div>

        <div class="form-group">
            <label for="grade">Grade:</label>
            <input type="number" name="grade" class="form-control" required min="0" max="100" step="0.01" placeholder="e.g. 89.5">
        </div>

        <button type="submit" class="btn btn-primary">Add Grade</button>
        <a href="{{ route('students.show', $student->id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

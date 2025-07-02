@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Grade</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('grades.update', $grade->id) }}" method="POST">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <div class="form-group">
            <label for="subject">Subject:</label>
            <input type="text" name="subject" class="form-control" value="{{ $grade->subject }}" required>
        </div>

        <div class="form-group">
            <label for="grade">Grade:</label>
            <input type="number" name="grade" class="form-control" value="{{ $grade->grade }}" required min="0" max="100" step="0.01">
        </div>

        <button type="submit" class="btn btn-primary">Update Grade</button>
        <a href="{{ route('students.show', $grade->student_id) }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Student</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
        </div>
    @endif

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">

        <div class="form-group mb-3">
            <label for="name">Student Name:</label>
            <input type="text" name="name" id="name" value="{{ $student->name }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="grade_level">Grade Level:</label>
            <input type="text" name="grade_level" id="grade_level" value="{{ $student->grade_level }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="parent_email">Parent Email:</label>
            <input type="email" name="parent_email" id="parent_email" value="{{ $student->parent_email }}" class="form-control">
        </div>

        <div class="form-group mb-4">
            <label for="parent_phone">Parent Phone:</label>
            <input type="text" name="parent_phone" id="parent_phone" value="{{ $student->parent_phone }}" class="form-control">
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Add New Student</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following errors:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('students.store') }}" method="POST">
    {{ csrf_field() }}

    <div class="form-group">
        <label for="name">Student Name:</label>
        <input type="text" name="name" class="form-control" required placeholder="Enter full name">
    </div>

    <div class="form-group">
        <label for="grade_level">Grade Level:</label>
        <input type="text" name="grade_level" class="form-control" required placeholder="e.g., Grade 10">
    </div>
    <!-- Parent Email -->
<div class="mb-4">
    <label for="parent_email" class="block font-bold">Parent Email</label>
    <input type="email" name="parent_email" class="border p-2 rounded w-full" placeholder="e.g. parent@email.com">
</div>

<!-- Optional: Parent Phone -->
<div class="mb-4">
    <label for="parent_phone" class="block font-bold">Parent Phone</label>
    <input type="text" name="parent_phone" class="border p-2 rounded w-full" placeholder="e.g. 09123456789">
</div>


    <button type="submit" class="btn btn-primary">Add Student</button>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancel</a>
</form>

</div>
@endsection

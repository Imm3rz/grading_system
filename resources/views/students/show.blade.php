@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Grades for {{ $student->name }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <p><strong>Student ID:</strong> {{ $student->id }}</p>

    @if($grades->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grades as $grade)
<tr>
    <td>{{ $grade->subject }}</td>
    <td>{{ $grade->grade }}</td>
    <td>
        <a href="{{ route('grades.edit', $grade->id) }}" class="btn btn-sm btn-warning">Edit</a>

        <form action="{{ route('grades.destroy', $grade->id) }}" method="POST" style="display:inline;">
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
        </form>
    </td>
</tr>
@endforeach

            </tbody>
        </table>

        <p><strong>Average:</strong> {{ number_format($average, 2) }}</p>
        <p><strong>Remarks:</strong> {{ $remark }}</p>
    @else
        <p>No grades available for this student.</p>
    @endif

    <a href="{{ route('grades.create', $student->id) }}" class="btn btn-success">Add Grade</a>
    <a href="{{ route('students.index') }}" class="btn btn-secondary">Back to Student List</a>
</div>
@endsection

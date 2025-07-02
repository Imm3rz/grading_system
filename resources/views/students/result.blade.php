@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $student->name }}'s Grades</h1>
    <p><strong>Grade Level:</strong> {{ $student->grade_level }}</p>
    <p><strong>Student ID:</strong> {{ $student->student_id }}</p>

    <table class="table-auto w-full mt-4 border">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Subject</th>
                <th class="px-4 py-2">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($grades as $grade)
                <tr>
                    <td class="border px-4 py-2">{{ $grade->subject }}</td>
                    <td class="border px-4 py-2">{{ $grade->grade }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="mt-4"><strong>Average:</strong> {{ round($average, 2) }}</p>
    <p><strong>Remark:</strong> {{ $remark }}</p>
</div>
@endsection

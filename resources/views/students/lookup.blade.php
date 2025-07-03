@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Check Grades</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('grades.lookup') }}" method="POST" class="space-y-4">
        {{ csrf_field() }}
        <label for="student_id">Enter Student ID:</label>
        <input type="text" name="student_id" class="border p-2 rounded w-64" placeholder="e.g. STD-0001">
        <button class="bg-blue-500 text-white px-4 py-2 rounded">Check Grades</button>
    </form>

  @if(session('recent_students'))
        <div class="mt-6 bg-gray-100 p-4 rounded">
            <h2 class="font-bold mb-2">Recently Viewed Students</h2>
            <ul>
                @foreach(session('recent_students') as $recent)
                    <li>
                        <a href="{{ route('grades.lookup') }}" onclick="event.preventDefault(); document.getElementById('sform-{{ $recent['id'] }}').submit();">
                            {{ $recent['name'] }} ({{ $recent['student_id'] }})
                            @if(isset($recent['source']) && $recent['source'] === 'public')
                                <span class="text-xs text-gray-500">(from public)</span>
                            @endif
                        </a>
                        <form id="sform-{{ $recent['id'] }}" action="{{ route('grades.lookup') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" name="student_id" value="{{ $recent['student_id'] }}">
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

</div>
@endsection

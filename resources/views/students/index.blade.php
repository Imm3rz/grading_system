@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student List</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('students.create') }}" class="btn btn-primary mb-3">Add Student</a>

    <!-- Search & Filter Form -->
    <form id="searchForm" method="GET" action="{{ route('students.index') }}" class="mb-6 flex flex-wrap items-center gap-4">
        <input
            type="text"
            name="search"
            id="searchInput"
            value="{{ request('search') }}"
            placeholder="Search by name or grade"
            class="px-4 py-2 border border-gray-300 rounded-md w-64"
        >

        <select name="grade" id="gradeFilter" class="px-4 py-2 border border-gray-300 rounded-md">
            <option value="">All Grades</option>
            <option value="Grade 7">Grade 7</option>
            <option value="Grade 8">Grade 8</option>
            <option value="Grade 9">Grade 9</option>
            <option value="Grade 10">Grade 10</option>
        </select>
    </form>

    <!-- Students Table Section -->
    <div id="studentsTable">
        

        @include('students.partials.table', ['students' => $students])
    </div>
</div>

<!-- jQuery and AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        function fetchStudents() {
            const search = $('#searchInput').val();
            const grade = $('#gradeFilter').val();

            $.ajax({
                url: "{{ route('students.index') }}",
                type: "GET",
                data: {
                    search: search,
                    grade: grade
                },
                success: function (data) {
                    $('#studentsTable').html(data);
                },
                error: function () {
                    alert('Something went wrong while fetching students.');
                }
            });
        }

        $('#searchInput, #gradeFilter').on('input change', function () {
            fetchStudents();
        });
    });
</script>
@endsection

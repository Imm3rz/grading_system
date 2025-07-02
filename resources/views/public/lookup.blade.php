<!DOCTYPE html>
<html>
<head>
    <title>Grade Checker</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4 text-center">Check Your Grades</h1>

        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">{{ session('error') }}</div>
        @endif

        <form action="{{ route('grades.public.lookup') }}" method="POST" class="space-y-4">
            {{ csrf_field() }}
            <div>
                <label for="student_id" class="block text-sm font-medium">Student ID</label>
                <input type="text" name="student_id" class="border p-2 rounded w-full mt-1" placeholder="e.g. STD-0001" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded w-full hover:bg-blue-600">
                Check Grades
            </button>
        </form>
    </div>
</body>
</html>

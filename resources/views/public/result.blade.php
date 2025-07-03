<!DOCTYPE html>
<html>
<head>
    <title>Grade Results</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded shadow w-full max-w-xl">
        <h1 class="text-2xl font-bold mb-2">{{ $student->name }}'s Grades</h1>
        <p class="mb-1"><strong>Grade Level:</strong> {{ $student->grade_level }}</p>
        <p class="mb-4"><strong>Student ID:</strong> {{ $student->student_id }}</p>

        <table class="table-auto w-full border mb-4">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Subject</th>
                    <th class="px-4 py-2 text-left">Grade</th>
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

        <p><strong>Average:</strong> {{ round($average, 2) }}</p>
        <p><strong>Remark:</strong> {{ $remark }}</p>

        <a href="{{ url('report-card') }}" class="mt-6 inline-block text-blue-500 hover:underline">Check another student</a>
    </div>
</body>
</html>

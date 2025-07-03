<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Student;
use App\Grade;
use DB;

class DashboardController extends Controller
{
    public function index()
{
    $teacherId = auth()->id(); // If you're assigning students per teacher

    $students = Student::where('user_id', $teacherId)->with('grades')->get();

    $totalStudents = $students->count();
    $totalGrades = 0;
    $gradeSum = 0;
    $passedStudents = 0;
    $failedStudents = 0;

    $studentNames = [];
    $averageGrades = [];

    foreach ($students as $student) {
        $grades = $student->grades;
        $count = $grades->count();

        if ($count > 0) {
            $avg = $grades->avg('grade');
            $gradeSum += $grades->sum('grade');
            $totalGrades += $count;

            $studentNames[] = $student->name;
            $averageGrades[] = round($avg, 2);

            if ($avg >= 75) {
                $passedStudents++;
            } else {
                $failedStudents++;
            }
        }
    }

    $averageGrade = $totalGrades > 0 ? $gradeSum / $totalGrades : 0;

    return view('dashboard.index', compact(
        'totalStudents',
        'totalGrades',
        'averageGrade',
        'passedStudents',
        'failedStudents',
        'studentNames',
        'averageGrades'
    ));
}

}

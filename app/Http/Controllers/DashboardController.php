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
        $totalStudents = Student::count();
        $totalGrades = Grade::count();
        $averageGrade = Grade::avg('grade');

        $passedStudents = 0;
        $failedStudents = 0;

        // Count passed/failed students by averaging their grades
        $students = Student::with('grades')->get();
        foreach ($students as $student) {
            $avg = $student->grades->avg('grade');
            if ($avg >= 75) {
                $passedStudents++;
            } else {
                $failedStudents++;
            }
        }

        return view('dashboard.index', compact(
            'totalStudents',
            'totalGrades',
            'averageGrade',
            'passedStudents',
            'failedStudents'
        ));
    }
}

<?php

namespace App\Http\Controllers;

use App\Grade;
use App\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    public function edit(Grade $grade)
    {
        return view('grades.edit', compact('grade'));
    }

    public function update(Request $request, Grade $grade)
    {
       

$validator = Validator::make($request->all(), [
    'subject' => 'required|string|max:255',
    'grade' => 'required|numeric|min:0|max:100',
]);

if ($validator->fails()) {
    return redirect()->back()
                     ->withErrors($validator)
                     ->withInput();
}


        $grade->update($request->only('subject', 'grade'));

        return redirect()->route('students.show', $grade->student_id)
                         ->with('success', 'Grade updated successfully.');
    }

    public function destroy(Grade $grade)
    {
        $studentId = $grade->student_id;
        $grade->delete();

        return redirect()->route('students.show', $studentId)
                         ->with('success', 'Grade deleted successfully.');
    }
}

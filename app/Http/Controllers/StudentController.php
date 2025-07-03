<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Student;
use App\Grade;
require_once base_path('vendor/autoload.php');

use Brevo\Client\Configuration;
use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Model\SendSmtpEmail;


class StudentController extends Controller
{

    public function user()
{
    return $this->belongsTo(User::class);
}


    // Show all students
    public function index(Request $request)
{
    $students = \App\Student::where('user_id', auth()->id());


    if ($request->has('search')) {
        $search = $request->input('search');
        $students->where(function($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
              ->orWhere('grade_level', 'like', '%' . $search . '%');
        });
    }

    if ($request->has('grade') && $request->grade !== '') {
        $students->where('grade_level', $request->grade);
    }

    $students = $students->orderBy('name')->paginate(10);

    // If it's an AJAX request, return only the table partial
    if ($request->ajax()) {
        return view('students.partials.table', compact('students'))->render();
    }

    // Normal page load
    return view('students.index', compact('students'));
}




    // Show form to add a new student
    public function create()
    {
        return view('students.create');
    }

    // Save new student to the database
    // Save new student to the database
   public function store(Request $request)
{
    $this->validate($request, [
        'name' => 'required|string|max:255',
        'grade_level' => 'required|string|max:255',
        'parent_email' => 'nullable|email',
        'parent_phone' => 'nullable|string',
    ]);

    $student = new Student();
    $latest = Student::orderBy('id', 'desc')->first();
    $nextNumber = $latest ? $latest->id + 1 : 1;
    $student->student_id = 'STD-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    $student->name = $request->name;
    $student->parent_email = $request->parent_email;
    $student->parent_phone = $request->parent_phone;
    $student->grade_level = $request->grade_level;

    $student->user_id = auth()->id(); // Attach to logged-in user
    $student->save();

    return redirect()->route('students.index')->with('success', 'Student added successfully.');
}




    // Show a single student with grades, average, and remarks
    public function show($id)
    {
        $student = Student::findOrFail($id);
        $grades = $student->grades;
        $average = $grades->avg('grade');
        $remark = $average >= 75 ? 'Passed' : 'Failed';


        return view('students.show', compact('student', 'grades', 'average', 'remark'));
    }

    // Show form to add grades for a student
    public function createGrade($id)
    {
        $student = Student::findOrFail($id);
        return view('grades.create', compact('student'));
    }

    // Store grades for a student
    public function storeGrade(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'grades' => 'required|array',
        'grades.*' => 'required|numeric|min:0|max:100',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    foreach ($request->grades as $subject => $grade) {
        Grade::create([
            'student_id' => $id,
            'subject' => $subject,
            'grade' => $grade,
        ]);
    }

    return redirect()->route('students.show', $id)->with('success', 'Grades added.');
}



// Show form to edit a student
public function edit($id)
{
    $student = Student::findOrFail($id);
    return view('students.edit', compact('student'));
}

// Update student
public function update(Request $request, $id)
{
    $validator = \Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'grade_level' => 'required|string|max:255',
        'parent_email' => 'nullable|email',
        'parent_phone' => 'nullable|string|max:20',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $student = Student::findOrFail($id);
    $student->name = $request->name;
    $student->grade_level = $request->grade_level;
    $student->parent_email = $request->parent_email;
    $student->parent_phone = $request->parent_phone;
    $student->save();

    return redirect()->route('students.index')->with('success', 'Student updated successfully.');
}


// Delete student
public function destroy($id)
{
    $student = Student::findOrFail($id);
    $student->delete();

    return redirect()->route('students.index')->with('success', 'Student deleted successfully.');
}



public function gradeLookupForm()
{
    return view('students.lookup');
}

public function gradeLookup(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'student_id' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $student = Student::where('student_id', $request->student_id)->first();

    if (!$student) {
        return back()->with('error', 'Student ID not found.');
    }

    $grades = $student->grades;
    $average = $grades->avg('grade');
    $remark = $average >= 75 ? 'Passed' : 'Failed';

    // ✅ Add to session (recently viewed)
    $recent = session()->get('recent_students', []);

    // Remove duplicates by ID
    $recent = array_filter($recent, function ($entry) use ($student) {
        return $entry['id'] !== $student->id;
    });

    // Add to the end
    $recent[] = [
        'id' => $student->id,
        'student_id' => $student->student_id,
        'name' => $student->name,
        'grade_level' => $student->grade_level,
        'source' => 'internal' // optional tag for your logic
    ];

    // Keep only last 5
    $recent = array_slice($recent, -5);

    session()->put('recent_students', $recent);

    return view('students.result', compact('student', 'grades', 'average', 'remark'));
}

// ✅ Public grade checker (no login required)
public function publicGradeLookupForm()
{
    return view('public.lookup');
}

public function publicGradeLookup(Request $request)
{
    $validator = \Validator::make($request->all(), [
        'student_id' => 'required|string',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $student = Student::where('student_id', $request->student_id)->first();

    if (!$student) {
        return back()->with('error', 'Student ID not found.');
    }

    $grades = $student->grades;
    $average = $grades->avg('grade');
    $remark = $average >= 75 ? 'Passed' : 'Failed';

    // ✅ Add to session (visible to teachers)
    $recent = session()->get('recent_students', []);

    // Remove duplicates by ID
    $recent = array_filter($recent, function ($entry) use ($student) {
        return $entry['id'] !== $student->id;
    });

    // Add to the end
    $recent[] = [
        'id' => $student->id,
        'student_id' => $student->student_id,
        'name' => $student->name,
        'grade_level' => $student->grade_level,
        'source' => 'public' // tag it as public view
    ];

    // Keep only last 5
    $recent = array_slice($recent, -5);

    session()->put('recent_students', $recent);

    return view('public.result', compact('student', 'grades', 'average', 'remark'));
}





public function __construct()
{
    $this->middleware('auth');
}

public function sendGradesToParent($id)
{
    $student = Student::with('grades')->findOrFail($id);

    if (!$student->parent_email) {
        return back()->with('error', 'No parent email found for this student.');
    }

    // Build the grades list
    $gradesList = '';
    foreach ($student->grades as $grade) {
        $gradesList .= "<li>" . htmlspecialchars($grade->subject) . ": " . htmlspecialchars($grade->grade) . "</li>";
    }

    // Email HTML body
    $html = "<p>Hello Parent,</p>
             <p>Here are the grades for <strong>" . htmlspecialchars($student->name) . "</strong>:</p>
             <ul>" . $gradesList . "</ul>
             <p>Thank you!</p>";

    // Prepare cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.resend.com/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer re_AoEF9Lt3_CDEfQMYkaTzqnvzFSQbYdxGQ',
        'Content-Type: application/json'
    ));

    $postData = array(
        "from" => "onboarding@resend.dev",
        "to" => $student->parent_email, // use actual parent email here
        "subject" => "Grade Report for " . $student->name,
        "html" => $html
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    // Trust SSL cert (fix for PHP 5.6)
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_CAINFO, "C:\\xampp 5.6\\php\\extras\\ssl\\cacert.pem"); // Make sure this path is correct

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($status !== 200) {
        return back()->with('error', 'Failed to send email. cURL error: ' . $error . ' | HTTP Code: ' . $status);
    }

    return back()->with('success', 'Email sent to parent successfully.');
}






}

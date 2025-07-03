<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Student;
use App\Grade;


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
        'subject' => 'required|string|max:255',
        'grade' => 'required|numeric|min:0|max:100',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    Grade::create([
        'student_id' => $id,
        'subject' => $request->subject,
        'grade' => $request->grade,
    ]);

    return redirect()->route('students.show', $id)->with('success', 'Grade added.');
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
]);

if ($validator->fails()) {
    return redirect()->back()
        ->withErrors($validator)
        ->withInput();
}


    $student = Student::findOrFail($id);
    $student->update($request->all());

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

    // Fetch grades
    $grades = $student->grades;
    $average = $grades->avg('grade');
    $remark = $average >= 75 ? 'Passed' : 'Failed';

    // Add to session (recently viewed)
    $recent = session()->get('recent_students', []);
    $recent[$student->id] = [
        'id' => $student->id,
        'student_id' => $student->student_id,
        'name' => $student->name,
        'grade_level' => $student->grade_level
    ];
    $recent = array_slice($recent, -5, true); // max 5
    session()->put('recent_students', $recent);

    return view('students.result', compact('student', 'grades', 'average', 'remark'));
}


// Public grade lookup form
public function publicGradeLookupForm()
{
    return view('public.lookup');
}

// Public grade lookup handler
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

    $gradesList = '';
    foreach ($student->grades as $grade) {
        $gradesList .= "<li>{$grade->subject}: {$grade->grade}</li>";
    }

    $postData = [
        "sender" => [
            "name" => "NEUST",
            "email" => "johnemerteamintelligence@gmail.com" // ✅ make sure this is verified in Brevo
        ],
        "to" => [
            [
                "email" => "johnemermartin@gmail.com",
                "name" => $student->name . "'s Parent"
            ]
        ],
        "subject" => "Grade Report for " . $student->name,
        "htmlContent" => "
            <p>Hello Parent,</p>
            <p>Here are the grades for <strong>{$student->name}</strong>:</p>
            <ul>{$gradesList}</ul>
            <p>Thank you!</p>
        "
    ];

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.brevo.com/v3/smtp/email",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "api-key: BanjWN8rkXvVE7tF", // Replace with your actual API key
            "Content-Type: application/json",
            "Accept: application/json"
        ],
        CURLOPT_POSTFIELDS => json_encode($postData)
    ]);

    $response = curl_exec($curl);
    $httpStatus = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    curl_close($curl);

    $responseData = json_decode($response, true);

    if ($error || $httpStatus !== 201) {
        if (!empty($responseData['message'])) {
    $message = $responseData['message'];
} elseif (!empty($error)) {
    $message = $error;
} else {
    $message = 'Unknown error';
}

        return back()->with('error', 'Failed to send email: ' . $message);
    }

    return back()->with('success', 'Email sent to parent successfully.');
}



}

<?php

namespace App\Http\Controllers\Student;

use App\Exports\StudentsExport;
use App\Filters\StudentFilters;
use App\Helpers\AppHelper;
use App\Models\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Models\Student;
use App\Models\Training\TrainingRecord;
use App\Services\Students\StudentService;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StudentsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index(Request $request, StudentFilters $filters)
    {
        $this->authorize('index', Student::class);

        // Global scope in Student model
        $query = Student::filter($filters)
            ->with(['latestAuth'])
            ->leftjoin('tr', 'users.id', '=', 'tr.student_id')
            ->select('users.*')
            ->distinct('users.id')
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM tr WHERE tr.student_id = users.id) as training_records_count'));

        AppHelper::addCaseloadConditionEloquent($query, auth()->user());

        $students = $query->paginate(session('students_per_page', config('model_filters.default_per_page')));

        return view('students.index', compact('students', 'filters'));
    }

    public function export(StudentFilters $filters)
    {
        $this->authorize('export', Student::class);

        return Excel::download(new StudentsExport($filters), 'students.xlsx');
    }

    public function show(Student $student)
    {
        $this->authorize('show', $student);

        $homeAddress = $student->homeAddress();
        $workAddress = $student->workAddress();

        $trsQuery = TrainingRecord::latest()
            ->with(['employer', 'portfolios'])
            ->where('student_id', $student->id);
        $trsQuery = $trsQuery->join('users', 'users.id', '=', 'tr.student_id');
        $trsQuery = $trsQuery->select('tr.*');

        AppHelper::addCaseloadConditionEloquent($trsQuery, auth()->user());

        $trs = $trsQuery->get();

        return view('students.show', compact('student', 'homeAddress', 'workAddress', 'trs'));
    }

    public function create()
    {
        $this->authorize('create', Student::class);

        $homeAddress = new Address();
        $workAddress = new Address();

        return view('students.create', compact('homeAddress', 'workAddress'));
    }

    public function store(StoreStudentRequest $request, StudentService $studentService)
    {
        $this->authorize('create', Student::class);

        $data = $request->validated();
        if ($request->gender === 'SELF' && $request->gender_self_describe) {
            $data['gender'] = $request->gender_self_describe;
        }

        $student = $studentService->create($data);

        return redirect()
            ->route('students.show', $student)
            ->with(['alert-success' => 'Student record is created successfully. Login details are emailed to the student.']);
    }

    public function edit(Student $student)
    {
        $this->authorize('edit', $student);

        $homeAddress = $student->homeAddress();
        $workAddress = $student->workAddress();

        return view('students.edit', compact('student', 'homeAddress', 'workAddress'));
    }

    public function update(StoreStudentRequest $request, Student $student, StudentService $studentService)
    {
        $this->authorize('update', $student);

        $data = $request->validated();
        if ($request->gender === 'SELF' && $request->gender_self_describe) {
            $data['gender'] = $request->gender_self_describe;
        }

        $student = $studentService->update($data, $student);

        return redirect()
            ->route('students.show', $student)
            ->with(['alert-success' => 'Student record is updated successfully.']);
    }

    public function destroy(Student $student, StudentService $studentService)
    {
        $this->authorize('destroy', Student::class);

        if (!AppHelper::requestFromOffice()) {
            return response()->json([
                'success' => false,
                'message' => 'Deletion of student record is currently disabled. Please contact Perspective Support for further info.'
            ]);
        }

        if ($student->training_records->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Student has got training records in the system, hence it cannot be deleted.'
            ]);
        }

        $studentService->delete($student);

        return response()->json([
            'success' => true,
            'message' => 'Student record has been deleted successfully.'
        ]);
    }
}

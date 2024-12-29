<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentAnswer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseStudentController extends Controller
{
    public function index(Course $course)
    {
        $students = $course->students()->orderByDesc('id')->get();
        $questions = $course->questions()->orderByDesc('id')->get();
        $totalQuestions = $questions->count();
        foreach ($students as $student) {
            $studentAnswers = StudentAnswer::whereHas('question', function ($query) use ($course) {
                $query->where('course_id', $course->id);
            })->where('user_id', $student->id)->get();
            $answersCount = $studentAnswers->count();
            $correctAnswerCount = $studentAnswers->where('answer', 'correct')->count();
            if ($answersCount == 0) {
                $student->status = 'Not Started';
            } elseif ($correctAnswerCount < $totalQuestions) {
                $student->status = 'Not passed';
            } else {
                $student->status = 'Passed';
            }
        }
        return view('admin.students.index', compact('course', 'questions', 'students'));
    }

    public function create(Course $course)
    {
        $students = $course->students()->orderByDesc('id')->get();
        return view('admin.students.add_student', compact('course', 'students'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        $user = User::where('email', $validated['email'])->first();
        $isEnrolled = $course->students()->where('user_id', $user->id)->exists();
        if ($isEnrolled) {
            $error = ValidationException::withMessages([
                'system_error' => ['Student already enrolled in this course'],
            ]);
        }
        DB::beginTransaction();
        try {
            $course->students()->attach($user->id);
            DB::commit();
            return redirect()->route('dashboard.course.course_students.index', $course)->with('success', 'Student added successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw $error ?? ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
    }
}

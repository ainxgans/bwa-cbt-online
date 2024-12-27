<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CourseQuestionController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answers' => 'required|array',
            'answers.*' => 'required|string|max:255',
            'correct_answer' => 'required|integer',
        ]);
        DB::beginTransaction();
        try {
            $question = $course->questions()->create([
                'question' => $validated['question'],
            ]);
            foreach ($validated['answers'] as $index => $answerText) {
                $isCorrect = ($request->correct_answer == $index);
                $question->answers()->create([
                    'answer' => $answerText,
                    'is_correct' => $isCorrect,
                ]);
            }
            DB::commit();
            return redirect()->route('dashboard.course.show', $course->id)->with('success', 'Question created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function create(Course $course)
    {
        $students = $course->students()->orderByDesc('id')->get();
        return view('admin.questions.create', compact('course', 'students'));
    }

    public function show($id)
    {
    }

    public function edit(CourseQuestion $courseQuestion)
    {
        $course = $courseQuestion->course;
        $students = $course->students()->orderByDesc('id')->get();
        return view('admin.questions.edit', compact('course', 'courseQuestion', 'students'));
    }

    public function update(Request $request, CourseQuestion $courseQuestion)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:255',
            'answers' => 'required|array',
            'answers.*' => 'required|string|max:255',
            'correct_answer' => 'required|integer',
        ]);
        DB::beginTransaction();
        try {
            $courseQuestion->update([
                'question' => $validated['question'],
            ]);
            $courseQuestion->answers()->delete();
            foreach ($validated['answers'] as $index => $answerText) {
                $isCorrect = ($request->correct_answer == $index);
                $courseQuestion->answers()->create([
                    'answer' => $answerText,
                    'is_correct' => $isCorrect,
                ]);
            }
            DB::commit();
            return redirect()->route('dashboard.course.show', $courseQuestion->course->id)->with('success', 'Question updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function destroy(CourseQuestion $courseQuestion)
    {
        try {
            DB::beginTransaction();
            $courseQuestion->delete();
            DB::commit();
            return redirect()->route('dashboard.course.show', $courseQuestion->course_id)->with('success', 'Course deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }
}

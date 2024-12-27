<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnswer;
use App\Models\CourseQuestion;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentAnswerController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request, Course $course, $question)
    {
        $questionDetails = $course->questions()->where('id', $question)->firstOrFail();
        $validated = $request->validate([
            'answer_id' => 'required|exists:answers,id',
        ]);
        DB::beginTransaction();
        try {
            $selectedAnswer = CourseAnswer::find($validated['answer_id']);
            if ($selectedAnswer->course_question_id != $question) {
                throw ValidationException::withMessages([
                    'answer_id' => ['Sysrtm Error', 'Jawaban tidak tersedia pada pertanyaan !'],
                ]);
            }
            $existingAnswer = StudentAnswer::where('user_id', auth()->id())
                ->where('course_question_id', $question)
                ->first();
            if ($existingAnswer) {
                throw ValidationException::withMessages([
                    'answer_id' => ['Sysrtm Error', 'Kamu telah menjawab pertanyaan ini sebelumnya !'],
                ]);
            }
            $answerValue = $selectedAnswer->is_correct ? 'correct' : 'wrong';
            StudentAnswer::create([
                'user_id' => auth()->id(),
                'course_question_id' => $question,
                'answer' => $answerValue,
            ]);
            DB::commit();
            $nextQuestion = CourseQuestion::where('course_id', $course->id)
                ->where('id', '>', $question)
                ->orderBy('id')
                ->first();
            if ($nextQuestion) {
                return redirect()->route('dashboard.learning.course', ['course' => $course->id, 'question' => $nextQuestion->id]);
            } else {
                return redirect()->route('dashboard.learning.finished.course', $course->id);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function create()
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function destroy($id)
    {
    }
}

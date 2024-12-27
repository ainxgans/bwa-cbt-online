<?php

namespace App\Http\Controllers;

use App\Models\CourseQuestion;
use App\Models\StudentAnswer;

class LearningController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $myCourses = $user->courses()->with('category')->orderByDesc('id')->get();

        foreach ($myCourses as $course) {
            $totalQuestionCourse = $course->lessons()->count();
            $answeredQuestionCourse = StudentAnswer::where('user_id', $user->id)
                ->whereHas('question', function ($query) use ($course) {
                    $query->where('course_id', $course->id);
                })->distinct()->count('course_question_id');
            if ($answeredQuestionCourse < $totalQuestionCourse) {
                $firstUnansweredQuestion = CourseQuestion::where('course_id', $course->id)
                    ->whereNotIn('id', function ($query) use ($user) {
                        $query->select('course_question_id')
                            ->from('student_answers')
                            ->where('user_id', $user->id);
                    })->orderByDesc('id')->first();
                $course->nextQuestionId = $firstUnansweredQuestion?->id;
            } else {
                $course->nextQuestionId = null;
            }
        }
        return view('student.courses.index', compact('myCourses'));
    }

    public function learning(Course $course, $question)
    {
        $user = auth()->user();
        $isEnrolled = $user->courses()->where('course_id', $course->id)->exists();
        if (!$isEnrolled) {
            return redirect()->route('student.courses.index')->with('error', 'You are not enrolled in this course');
        }
        $question = CourseQuestion::where('course_id', $course->id)->where('id', $question)->firstOrFail();
        return view('student.courses.learning', compact('course', 'question'));
    }
}

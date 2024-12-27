<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('id', 'desc')->get();
        return view('admin.courses.index', compact('courses'));
    }

    public function show($id)
    {
        $course = Course::find($id);
        $students = $course->students()->orderByDesc('id')->get();
        $questions = $course->questions()->orderByDesc('id')->get();
        return view('admin.courses.manage', compact('course', 'students', 'questions'));
    }

    public function edit(Course $course)
    {
        $categories = Category::all();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover' => 'sometimes|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|integer',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('cover')) {
                $file = $request->file('cover')->store('product_covers', 'public');
                $validated['cover'] = $file;
            }
            $validated['slug'] = Str::slug($validated['name']);
            $course->update($validated);
            DB::commit();
            return redirect()->route('dashboard.course.index')->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cover' => 'required|image|mimes:jpeg,png,jpg',
            'category_id' => 'required|integer',
        ]);
        DB::beginTransaction();
        try {
            if ($request->hasFile('cover')) {
                $file = $request->file('cover')->store('product_covers', 'public');
                $validated['cover'] = $file;
            }
            $validated['slug'] = Str::slug($validated['name']);
            $newCourse = Course::create($validated);
            DB::commit();
            return redirect()->route('dashboard.course.index')->with('success', 'Course created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.courses.create', compact('categories'));
    }

    public function destroy(Course $course)
    {
        try {
            DB::beginTransaction();
            $course->delete();
            DB::commit();
            return redirect()->route('dashboard.course.index')->with('success', 'Course deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'system_error' => ['System Error', $e->getMessage()],
            ]);
        }
    }
}

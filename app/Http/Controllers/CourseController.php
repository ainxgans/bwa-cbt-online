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

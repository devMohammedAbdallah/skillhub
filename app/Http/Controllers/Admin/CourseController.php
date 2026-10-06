<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display a listing of courses.
     */
    public function index(): View
    {
        $courses = Course::with(['category', 'instructor'])
            ->orderByDesc('id')
            ->get();

        return view('admin.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $instructors = Instructor::orderBy('name')->get();

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    /**
     * Store a newly created course in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'category_id' => ['required', 'exists:categories,id'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [], [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'category_id' => 'الفئة',
            'instructor_id' => 'المدرب',
            'price' => 'السعر',
            'image' => 'الصورة',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('courses', 'public');
        }

        Course::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'instructor_id' => $validated['instructor_id'],
            'price' => $validated['price'],
            'image' => $validated['image'] ?? null,
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'تم إنشاء الدورة بنجاح.');
    }

    /**
     * Show the form for editing the specified course.
     */
    public function edit(Course $course): View
    {
        $categories = Category::orderBy('name')->get();
        $instructors = Instructor::orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'instructors'));
    }

    /**
     * Update the specified course in storage.
     */
    public function update(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:10000'],
            'category_id' => ['required', 'exists:categories,id'],
            'instructor_id' => ['required', 'exists:instructors,id'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [], [
            'title' => 'العنوان',
            'description' => 'الوصف',
            'category_id' => 'الفئة',
            'instructor_id' => 'المدرب',
            'price' => 'السعر',
            'image' => 'الصورة',
        ]);

        $data = [
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
            'instructor_id' => $validated['instructor_id'],
            'price' => $validated['price'],
        ];

        if ($request->hasFile('image')) {
            if ($course->image && Storage::disk('public')->exists($course->image)) {
                Storage::disk('public')->delete($course->image);
            }
            $data['image'] = $request->file('image')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'تم تحديث الدورة بنجاح.');
    }

    /**
     * Remove the specified course from storage.
     */
    public function destroy(Course $course): RedirectResponse
    {
        DB::transaction(function () use ($course) {
            Enrollment::where('course_id', $course->id)->delete();
            $course->delete();
        });

        if ($course->image && Storage::disk('public')->exists($course->image)) {
            Storage::disk('public')->delete($course->image);
        }

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'تم حذف الدورة بنجاح.');
    }
}

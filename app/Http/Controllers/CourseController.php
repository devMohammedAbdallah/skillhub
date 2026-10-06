<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Public listing of all courses with optional category filter.
     */
    public function index(Request $request): View
    {
        $categories = Category::orderBy('name')->get();

        $query = Course::with(['category', 'instructor']);

        $categoryId = $request->input('category');
        if ($categoryId !== null && $categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        $courses = $query->orderByDesc('id')->get();

        return view('courses.index', compact('courses', 'categories', 'categoryId'));
    }

    /**
     * Show the full details of a single course.
     */
    public function show(Request $request, Course $course): View
    {
        $course->load(['category', 'instructor']);

        $enrolled = false;
        if ($request->user() && ! $request->user()->isAdmin()) {
            $enrolled = Enrollment::where('user_id', $request->user()->id)
                ->where('course_id', $course->id)
                ->exists();
        }

        return view('courses.show', compact('course', 'enrolled'));
    }

    /**
     * Enroll the authenticated student in the course.
     * Guests are redirected to login by the auth middleware.
     */
    public function enroll(Request $request, Course $course): RedirectResponse
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()
                ->route('courses.show', $course)
                ->with('info', 'الإدارة لا تقوم بالتسجيل في الدورات.');
        }

        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->exists();

        if ($alreadyEnrolled) {
            return redirect()
                ->route('courses.show', $course)
                ->with('warning', 'أنت مسجل بالفعل في هذه الدورة.');
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        return redirect()
            ->route('courses.show', $course)
            ->with('success', 'تم التسجيل في الدورة بنجاح.');
    }
}

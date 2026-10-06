<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstructorController extends Controller
{
    /**
     * Display a listing of instructors.
     */
    public function index(): View
    {
        $instructors = Instructor::orderByDesc('id')->get();

        return view('admin.instructors.index', compact('instructors'));
    }

    /**
     * Show the form for creating a new instructor.
     */
    public function create(): View
    {
        return view('admin.instructors.create');
    }

    /**
     * Store a newly created instructor in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [], [
            'name' => 'الاسم',
            'bio' => 'النبذة',
            'image' => 'الصورة',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('instructors', 'public');
        }

        Instructor::create([
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
            'image' => $validated['image'] ?? null,
        ]);

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'تم إنشاء المدرب بنجاح.');
    }

    /**
     * Show the form for editing the specified instructor.
     */
    public function edit(Instructor $instructor): View
    {
        return view('admin.instructors.edit', compact('instructor'));
    }

    /**
     * Update the specified instructor in storage.
     */
    public function update(Request $request, Instructor $instructor): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [], [
            'name' => 'الاسم',
            'bio' => 'النبذة',
            'image' => 'الصورة',
        ]);

        $data = [
            'name' => $validated['name'],
            'bio' => $validated['bio'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
                Storage::disk('public')->delete($instructor->image);
            }
            $data['image'] = $request->file('image')->store('instructors', 'public');
        }

        $instructor->update($data);

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'تم تحديث المدرب بنجاح.');
    }

    /**
     * Remove the specified instructor from storage.
     */
    public function destroy(Instructor $instructor): RedirectResponse
    {
        if ($instructor->image && Storage::disk('public')->exists($instructor->image)) {
            Storage::disk('public')->delete($instructor->image);
        }

        $instructor->delete();

        return redirect()
            ->route('admin.instructors.index')
            ->with('success', 'تم حذف المدرب بنجاح.');
    }
}

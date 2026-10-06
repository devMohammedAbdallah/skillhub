<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Admin dashboard with summary stats.
     */
    public function __invoke(): View
    {
        $stats = [
            'students' => User::where('role', 'student')->count(),
            'courses' => Course::count(),
            'categories' => Category::count(),
            'enrollments' => Enrollment::count(),
        ];

        return view('admin.dashboard', $stats);
    }
}

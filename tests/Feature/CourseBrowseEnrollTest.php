<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseBrowseEnrollTest extends TestCase
{
    use RefreshDatabase;

    private function student(): User
    {
        return User::factory()->create(['role' => 'student']);
    }

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function category(string $name = 'Programming'): Category
    {
        return Category::create(['name' => $name]);
    }

    private function instructor(string $name = 'Instructor One'): Instructor
    {
        return Instructor::create(['name' => $name]);
    }

    private function course(array $overrides = []): Course
    {
        return Course::create(array_merge([
            'title' => 'Laravel 101',
            'description' => 'Intro course',
            'category_id' => $this->category()->id,
            'instructor_id' => $this->instructor()->id,
            'price' => 10,
        ], $overrides));
    }

    public function test_guest_can_browse_courses(): void
    {
        $this->course(['title' => 'Public Course A']);

        $this->get('/courses')
            ->assertOk()
            ->assertSee('Public Course A')
            ->assertSee('الدورات');
    }

    public function test_guest_can_view_course_details(): void
    {
        $course = $this->course([
            'title' => 'Detailed Course',
            'description' => 'Full description here',
        ]);

        $this->get("/courses/{$course->id}")
            ->assertOk()
            ->assertSee('Detailed Course')
            ->assertSee('Full description here')
            ->assertSee('التسجيل بالدورة');
    }

    public function test_courses_index_filters_by_category(): void
    {
        $design = $this->category('Design');
        $dev = $this->category('Development');

        $this->course(['title' => 'Design Course', 'category_id' => $design->id]);
        $this->course(['title' => 'Dev Course', 'category_id' => $dev->id]);

        $this->get('/courses')
            ->assertOk()
            ->assertSee('Design Course')
            ->assertSee('Dev Course');

        $this->get("/courses?category={$design->id}")
            ->assertOk()
            ->assertSee('Design Course')
            ->assertDontSee('Dev Course');
    }

    public function test_guest_enroll_redirects_to_login_without_creating_enrollment(): void
    {
        $course = $this->course();

        $this->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('login'));

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_student_can_enroll_in_course(): void
    {
        $course = $this->course();
        $student = $this->student();

        $this->actingAs($student)
            ->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_enroll_shows_success_message_on_course_page(): void
    {
        $course = $this->course();
        $student = $this->student();

        $this->actingAs($student)
            ->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('courses.show', $course));

        $this->actingAs($student)
            ->get("/courses/{$course->id}")
            ->assertOk()
            ->assertSee('تم التسجيل في الدورة بنجاح', false)
            ->assertSee('أنت مسجل في هذه الدورة', false);
    }

    public function test_student_cannot_enroll_twice(): void
    {
        $course = $this->course();
        $student = $this->student();

        $this->actingAs($student)
            ->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('courses.show', $course));

        $this->actingAs($student)
            ->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseCount('enrollments', 1);

        $this->actingAs($student)
            ->get("/courses/{$course->id}")
            ->assertOk()
            ->assertSee('أنت مسجل بالفعل في هذه الدورة.', false);
    }

    public function test_course_page_shows_enroll_button_for_guests(): void
    {
        $course = $this->course();

        $this->get("/courses/{$course->id}")
            ->assertOk()
            ->assertSee('التسجيل بالدورة');
    }

    public function test_admin_course_page_does_not_show_enroll_button(): void
    {
        $course = $this->course();

        $this->actingAs($this->admin())
            ->get("/courses/{$course->id}")
            ->assertOk()
            ->assertDontSee('التسجيل بالدورة')
            ->assertSee('الإدارة لا تقوم بالتسجيل في الدورات.', false);
    }

    public function test_admin_cannot_enroll_even_if_posting_directly(): void
    {
        $course = $this->course();
        $admin = $this->admin();

        $this->actingAs($admin)
            ->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('courses.show', $course));

        $this->assertDatabaseCount('enrollments', 0);
    }

    public function test_student_home_shows_enrolled_courses(): void
    {
        $course = $this->course(['title' => 'My Enrolled Course']);
        $student = $this->student();

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $this->actingAs($student)
            ->get('/home')
            ->assertOk()
            ->assertSee('دوراتي')
            ->assertSee('My Enrolled Course');
    }

    public function test_student_home_shows_empty_state_when_no_enrollments(): void
    {
        $student = $this->student();

        $this->actingAs($student)
            ->get('/home')
            ->assertOk()
            ->assertSee('لم تسجل في أي دورة بعد')
            ->assertSee('تصفح الدورات');
    }

    public function test_navbar_shows_courses_link_for_guests_and_auth_users(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee(route('courses.index'), false)
            ->assertSee('الدورات');

        $this->actingAs($this->student())
            ->get('/home')
            ->assertOk()
            ->assertSee(route('courses.index'), false)
            ->assertSee('الدورات');
    }

    public function test_guest_gets_login_page_on_enroll_post(): void
    {
        $course = $this->course();

        $this->post("/courses/{$course->id}/enroll")
            ->assertRedirect(route('login'));
    }

    public function test_courses_listing_shows_empty_state_when_no_courses(): void
    {
        $this->get('/courses')
            ->assertOk()
            ->assertSee('لا توجد دورات متاحة حالياً');
    }

    public function test_unknown_course_returns_404(): void
    {
        $this->get('/courses/99999')->assertNotFound();
    }
}

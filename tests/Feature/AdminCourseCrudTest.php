<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCourseCrudTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->admin()->create();
    }

    private function student(): User
    {
        return User::factory()->create(['role' => 'student']);
    }

    private function category(?string $name = null): Category
    {
        return Category::create(['name' => $name ?? 'Programming']);
    }

    private function instructor(?string $name = null): Instructor
    {
        return Instructor::create(['name' => $name ?? 'Instructor One']);
    }

    /**
     * Minimal valid 1x1 PNG (base64).
     */
    private function pngFile(string $name = 'course.png'): UploadedFile
    {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');

        return new UploadedFile(
            $this->tempFile($png),
            $name,
            'image/png',
            null,
            true
        );
    }

    /**
     * Minimal valid 1x1 JPEG (base64).
     */
    private function jpgFile(string $name = 'course.jpg'): UploadedFile
    {
        $jpg = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigD//2Q==');

        return new UploadedFile(
            $this->tempFile($jpg),
            $name,
            'image/jpeg',
            null,
            true
        );
    }

    private function tempFile(string $binary): string
    {
        $path = tempnam(sys_get_temp_dir(), 'img');
        file_put_contents($path, $binary);

        return $path;
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/courses')->assertRedirect('/login');
    }

    public function test_student_gets_403_on_course_pages(): void
    {
        $student = $this->student();
        $course = Course::create([
            'title' => 'Protected',
            'description' => 'Desc',
            'category_id' => $this->category()->id,
            'instructor_id' => $this->instructor()->id,
            'price' => 10,
        ]);

        $this->actingAs($student)->get('/admin/courses')->assertForbidden();
        $this->actingAs($student)->get('/admin/courses/create')->assertForbidden();
        $this->actingAs($student)->post('/admin/courses', ['title' => 'X'])->assertForbidden();
        $this->actingAs($student)->get("/admin/courses/{$course->id}/edit")->assertForbidden();
        $this->actingAs($student)->put("/admin/courses/{$course->id}", ['title' => 'Y'])->assertForbidden();
        $this->actingAs($student)->delete("/admin/courses/{$course->id}")->assertForbidden();
    }

    public function test_admin_can_view_course_list_with_dropdown_entities(): void
    {
        $category = $this->category('Design');
        $instructor = $this->instructor('Sara');

        Course::create([
            'title' => 'UI Basics',
            'description' => 'Learn UI',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 49.99,
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/courses')
            ->assertOk()
            ->assertSee('UI Basics')
            ->assertSee('Design')
            ->assertSee('Sara')
            ->assertSee('49.99');
    }

    public function test_admin_can_view_create_form_with_category_and_instructor_dropdowns(): void
    {
        $this->category('Web Dev');
        $this->instructor('Khaled');

        $this->actingAs($this->admin())
            ->get('/admin/courses/create')
            ->assertOk()
            ->assertSee('Web Dev')
            ->assertSee('Khaled')
            ->assertSee('name="category_id"', false)
            ->assertSee('name="instructor_id"', false);
    }

    public function test_admin_can_create_course_without_image(): void
    {
        $category = $this->category();
        $instructor = $this->instructor();

        $response = $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'Laravel 101',
            'description' => 'Intro course',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 0,
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('courses', ['title' => 'Laravel 101', 'price' => 0]);
    }

    public function test_admin_can_create_course_with_image(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $instructor = $this->instructor();

        $response = $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'With Image',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 99.5,
            'image' => $this->jpgFile(),
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');

        $course = Course::where('title', 'With Image')->first();
        $this->assertNotNull($course);
        $this->assertNotNull($course->image);
        $this->assertStringStartsWith('courses/', $course->image);
        Storage::disk('public')->assertExists($course->image);
    }

    public function test_admin_cannot_create_course_with_missing_required_fields(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => '',
            'description' => '',
            'category_id' => '',
            'instructor_id' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors([
            'title',
            'description',
            'category_id',
            'instructor_id',
            'price',
        ]);
        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_cannot_create_course_with_negative_price(): void
    {
        $category = $this->category();
        $instructor = $this->instructor();

        $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'Bad Price',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => -1,
        ])->assertSessionHasErrors('price');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_cannot_create_course_with_invalid_category(): void
    {
        $instructor = $this->instructor();

        $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'Bad Category',
            'description' => 'Desc',
            'category_id' => 99999,
            'instructor_id' => $instructor->id,
            'price' => 10,
        ])->assertSessionHasErrors('category_id');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_cannot_upload_non_image_file(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $instructor = $this->instructor();

        $bad = new UploadedFile(
            $this->tempFile('%PDF-1.4 fake'),
            'doc.pdf',
            'application/pdf',
            null,
            true
        );

        $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'Bad File',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 10,
            'image' => $bad,
        ])->assertSessionHasErrors('image');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_cannot_upload_oversized_image(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $instructor = $this->instructor();

        $jpeg = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigD//2Q==');
        $jpeg = substr($jpeg, 0, -2).str_repeat("\0", 2200 * 1024)."\xFF\xD9";

        $path = tempnam(sys_get_temp_dir(), 'big');
        file_put_contents($path, $jpeg);
        $big = new UploadedFile($path, 'big.jpg', 'image/jpeg', null, true);

        $this->actingAs($this->admin())->post('/admin/courses', [
            'title' => 'Big Image',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 10,
            'image' => $big,
        ])->assertSessionHasErrors('image');

        $this->assertDatabaseCount('courses', 0);
    }

    public function test_admin_can_edit_course_without_replacing_image(): void
    {
        Storage::fake('public');
        $category = $this->category('Old Cat');
        $newCategory = $this->category('New Cat');
        $instructor = $this->instructor();
        $newInstructor = $this->instructor('New Instructor');

        $relative = 'courses/old.jpg';
        Storage::disk('public')->put($relative, file_get_contents($this->jpgFile()->getRealPath()));

        $course = Course::create([
            'title' => 'Old Title',
            'description' => 'Old desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 10,
            'image' => $relative,
        ]);

        $response = $this->actingAs($this->admin())->put("/admin/courses/{$course->id}", [
            'title' => 'New Title',
            'description' => 'New desc',
            'category_id' => $newCategory->id,
            'instructor_id' => $newInstructor->id,
            'price' => 25.5,
        ]);

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');
        $course->refresh();
        $this->assertSame('New Title', $course->title);
        $this->assertSame('New desc', $course->description);
        $this->assertSame($newCategory->id, $course->category_id);
        $this->assertSame($newInstructor->id, $course->instructor_id);
        $this->assertEquals(25.5, (float) $course->price);
        $this->assertSame($relative, $course->image);
        Storage::disk('public')->assertExists($relative);
    }

    public function test_admin_can_replace_image_on_edit(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $instructor = $this->instructor();

        $oldPath = 'courses/old.jpg';
        Storage::disk('public')->put($oldPath, file_get_contents($this->jpgFile()->getRealPath()));

        $course = Course::create([
            'title' => 'Replace Me',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 5,
            'image' => $oldPath,
        ]);

        $this->actingAs($this->admin())->put("/admin/courses/{$course->id}", [
            'title' => 'Replace Me',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 5,
            'image' => $this->pngFile('new.png'),
        ]);

        $course->refresh();
        $this->assertNotNull($course->image);
        $this->assertNotSame($oldPath, $course->image);
        Storage::disk('public')->assertExists($course->image);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_admin_can_delete_course_image_and_related_enrollments(): void
    {
        Storage::fake('public');
        $category = $this->category();
        $instructor = $this->instructor();
        $student = $this->student();
        $otherStudent = $this->student();

        $path = 'courses/del.jpg';
        Storage::disk('public')->put($path, file_get_contents($this->jpgFile()->getRealPath()));

        $course = Course::create([
            'title' => 'Delete Me',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 20,
            'image' => $path,
        ]);

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);
        Enrollment::create([
            'user_id' => $otherStudent->id,
            'course_id' => $course->id,
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($this->admin())->delete("/admin/courses/{$course->id}");

        $response->assertRedirect(route('admin.courses.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
        $this->assertDatabaseMissing('enrollments', ['course_id' => $course->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_index_shows_placeholder_when_image_missing(): void
    {
        $category = $this->category();
        $instructor = $this->instructor();

        Course::create([
            'title' => 'No Photo Course',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 0,
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/courses')
            ->assertOk()
            ->assertSee('bi-image');
    }

    public function test_index_does_not_break_if_image_file_missing_from_disk(): void
    {
        $category = $this->category();
        $instructor = $this->instructor();

        Course::create([
            'title' => 'Ghost Course',
            'description' => 'Desc',
            'category_id' => $category->id,
            'instructor_id' => $instructor->id,
            'price' => 0,
            'image' => 'courses/missing.jpg',
        ]);

        $this->actingAs($this->admin())
            ->get('/admin/courses')
            ->assertOk()
            ->assertSee('bi-image')
            ->assertSee('Ghost Course');
    }
}

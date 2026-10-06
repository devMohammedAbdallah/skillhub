<?php

namespace Tests\Feature;

use App\Models\Instructor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminInstructorCrudTest extends TestCase
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

    /**
     * Minimal valid 1x1 PNG (base64).
     */
    private function pngFile(string $name = 'avatar.png'): UploadedFile
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
    private function jpgFile(string $name = 'avatar.jpg'): UploadedFile
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
        $this->get('/admin/instructors')->assertRedirect('/login');
    }

    public function test_student_gets_403_on_instructor_pages(): void
    {
        $student = $this->student();
        $instructor = Instructor::create(['name' => 'Protected']);

        $this->actingAs($student)->get('/admin/instructors')->assertForbidden();
        $this->actingAs($student)->get('/admin/instructors/create')->assertForbidden();
        $this->actingAs($student)->post('/admin/instructors', ['name' => 'X'])->assertForbidden();
        $this->actingAs($student)->get("/admin/instructors/{$instructor->id}/edit")->assertForbidden();
        $this->actingAs($student)->put("/admin/instructors/{$instructor->id}", ['name' => 'Y'])->assertForbidden();
        $this->actingAs($student)->delete("/admin/instructors/{$instructor->id}")->assertForbidden();
    }

    public function test_admin_can_view_instructor_list(): void
    {
        Instructor::create(['name' => 'Existing Instructor', 'bio' => 'Bio text']);

        $this->actingAs($this->admin())
            ->get('/admin/instructors')
            ->assertOk()
            ->assertSee('Existing Instructor')
            ->assertSee('Bio text');
    }

    public function test_admin_can_create_instructor_without_image(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/instructors', [
            'name' => 'Ahmed',
            'bio' => 'Math teacher',
        ]);

        $response->assertRedirect(route('admin.instructors.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('instructors', ['name' => 'Ahmed', 'bio' => 'Math teacher']);
    }

    public function test_admin_can_create_instructor_with_image(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin())->post('/admin/instructors', [
            'name' => 'Sara',
            'bio' => 'English teacher',
            'image' => $this->jpgFile(),
        ]);

        $response->assertRedirect(route('admin.instructors.index'));
        $response->assertSessionHas('success');

        $instructor = Instructor::where('name', 'Sara')->first();
        $this->assertNotNull($instructor);
        $this->assertNotNull($instructor->image);
        $this->assertStringStartsWith('instructors/', $instructor->image);
        Storage::disk('public')->assertExists($instructor->image);
    }

    public function test_admin_cannot_create_instructor_without_name(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/instructors', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('instructors', 0);
    }

    public function test_admin_cannot_upload_non_image_file(): void
    {
        Storage::fake('public');

        $bad = new UploadedFile(
            $this->tempFile('%PDF-1.4 fake'),
            'doc.pdf',
            'application/pdf',
            null,
            true
        );

        $this->actingAs($this->admin())
            ->post('/admin/instructors', ['name' => 'X', 'image' => $bad])
            ->assertSessionHasErrors('image');

        $this->assertDatabaseCount('instructors', 0);
    }

    public function test_admin_cannot_upload_oversized_image(): void
    {
        Storage::fake('public');

        // Valid JPEG header + padding past 2MB so mimes still sees jpeg-ish content
        $jpeg = base64_decode('/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UHRofHh0aHBwgJC4nICIsIxwcKDcpLDAxNDQ0Hyc5PTgyPC4zNDL/2wBDAQkJCQwLDBgNDRgyIRwhMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjIyMjL/wAARCAABAAEDASIAAhEBAxEB/8QAHwAAAQUBAQEBAQEAAAAAAAAAAAECAwQFBgcICQoL/8QAtRAAAgEDAwIEAwUFBAQAAAF9AQIDAAQRBRIhMUEGE1FhByJxFDKBkaEII0KxwRVS0fAkM2JyggkKFhcYGRolJicoKSo0NTY3ODk6Q0RFRkdISUpTVFVWV1hZWmNkZWZnaGlqc3R1dnd4eXqDhIWGh4iJipKTlJWWl5iZmqKjpKWmp6ipqrKztLW2t7i5usLDxMXGx8jJytLT1NXW19jZ2uHi4+Tl5ufo6erx8vP09fb3+Pn6/9oADAMBAAIRAxEAPwD3+iiigD//2Q==');
        // Strip JPEG EOI and pad to >2MB, re-append EOI
        $jpeg = substr($jpeg, 0, -2).str_repeat("\0", 2200 * 1024)."\xFF\xD9";

        $path = tempnam(sys_get_temp_dir(), 'big');
        file_put_contents($path, $jpeg);
        $big = new UploadedFile($path, 'big.jpg', 'image/jpeg', null, true);

        $response = $this->actingAs($this->admin())
            ->post('/admin/instructors', ['name' => 'Y', 'image' => $big]);

        $response->assertSessionHasErrors('image');
        $this->assertDatabaseCount('instructors', 0);
    }

    public function test_admin_can_edit_instructor_without_replacing_image(): void
    {
        Storage::fake('public');

        $relative = 'instructors/old.jpg';
        Storage::disk('public')->put($relative, file_get_contents($this->jpgFile()->getRealPath()));

        $instructor = Instructor::create([
            'name' => 'Old Name',
            'bio' => 'Old bio',
            'image' => $relative,
        ]);

        $response = $this->actingAs($this->admin())->put("/admin/instructors/{$instructor->id}", [
            'name' => 'New Name',
            'bio' => 'New bio',
        ]);

        $response->assertRedirect(route('admin.instructors.index'));
        $instructor->refresh();
        $this->assertSame('New Name', $instructor->name);
        $this->assertSame('New bio', $instructor->bio);
        $this->assertSame($relative, $instructor->image);
        Storage::disk('public')->assertExists($relative);
    }

    public function test_admin_can_replace_image_on_edit(): void
    {
        Storage::fake('public');

        $oldPath = 'instructors/old.jpg';
        Storage::disk('public')->put($oldPath, file_get_contents($this->jpgFile()->getRealPath()));

        $instructor = Instructor::create([
            'name' => 'Replace Me',
            'image' => $oldPath,
        ]);

        $this->actingAs($this->admin())->put("/admin/instructors/{$instructor->id}", [
            'name' => 'Replace Me',
            'image' => $this->pngFile('new.png'),
        ]);

        $instructor->refresh();
        $this->assertNotNull($instructor->image);
        $this->assertNotSame($oldPath, $instructor->image);
        Storage::disk('public')->assertExists($instructor->image);
        Storage::disk('public')->assertMissing($oldPath);
    }

    public function test_admin_can_delete_instructor_and_image_file(): void
    {
        Storage::fake('public');

        $path = 'instructors/del.jpg';
        Storage::disk('public')->put($path, file_get_contents($this->jpgFile()->getRealPath()));
        $instructor = Instructor::create(['name' => 'Delete Me', 'image' => $path]);

        $response = $this->actingAs($this->admin())->delete("/admin/instructors/{$instructor->id}");

        $response->assertRedirect(route('admin.instructors.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('instructors', ['id' => $instructor->id]);
        Storage::disk('public')->assertMissing($path);
    }

    public function test_index_shows_placeholder_when_image_missing(): void
    {
        Instructor::create(['name' => 'No Photo']);

        $this->actingAs($this->admin())
            ->get('/admin/instructors')
            ->assertOk()
            ->assertSee('bi-person-fill');
    }

    public function test_index_does_not_break_if_image_file_missing_from_disk(): void
    {
        Instructor::create(['name' => 'Ghost', 'image' => 'instructors/missing.jpg']);

        $this->actingAs($this->admin())
            ->get('/admin/instructors')
            ->assertOk()
            ->assertSee('bi-person-fill');
    }
}

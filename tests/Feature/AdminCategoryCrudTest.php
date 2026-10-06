<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryCrudTest extends TestCase
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

    public function test_guest_is_redirected_to_login_from_admin_pages(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
        $this->get('/admin/categories')->assertRedirect('/login');
    }

    public function test_student_gets_403_on_admin_pages(): void
    {
        $student = $this->student();

        $this->actingAs($student)->get('/admin/dashboard')->assertForbidden();
        $this->actingAs($student)->get('/admin/categories')->assertForbidden();
        $this->actingAs($student)->get('/admin/categories/create')->assertForbidden();
        $this->actingAs($student)->post('/admin/categories', ['name' => 'X'])->assertForbidden();
    }

    public function test_admin_dashboard_displays_stats(): void
    {
        User::factory()->count(2)->create(['role' => 'student']);
        Category::create(['name' => 'Programming']);

        $response = $this->actingAs($this->admin())->get('/admin/dashboard');

        $response->assertOk()->assertSee('stat-value');

        $this->assertSame(2, User::where('role', 'student')->count());
        $this->assertSame(1, Category::count());
        $this->assertSame(0, \App\Models\Course::count());
        $this->assertSame(0, \App\Models\Enrollment::count());
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/categories', [
            'name' => 'Design',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Design']);
        $response->assertSessionHas('success');
    }

    public function test_admin_cannot_create_duplicate_category(): void
    {
        Category::create(['name' => 'Design']);

        $response = $this->actingAs($this->admin())->post('/admin/categories', [
            'name' => 'Design',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertSame(1, Category::count());
    }

    public function test_admin_cannot_create_category_without_name(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/categories', [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseCount('categories', 0);
    }

    public function test_admin_can_update_category(): void
    {
        $category = Category::create(['name' => 'Old Name']);

        $response = $this->actingAs($this->admin())->put("/admin/categories/{$category->id}", [
            'name' => 'New Name',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_admin_can_delete_category(): void
    {
        $category = Category::create(['name' => 'Temp']);

        $response = $this->actingAs($this->admin())->delete("/admin/categories/{$category->id}");

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_admin_can_view_index_create_and_edit_pages(): void
    {
        $category = Category::create(['name' => 'Existing']);
        $admin = $this->admin();

        $this->actingAs($admin)->get('/admin/categories')->assertOk()->assertSee('Existing');
        $this->actingAs($admin)->get('/admin/categories/create')->assertOk();
        $this->actingAs($admin)->get("/admin/categories/{$category->id}/edit")->assertOk()->assertSee('Existing');
    }
}

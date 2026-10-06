<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactMessageTest extends TestCase
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

    public function test_guest_is_redirected_to_login_from_admin_messages(): void
    {
        $this->get('/admin/messages')->assertRedirect('/login');
    }

    public function test_student_gets_403_on_admin_messages(): void
    {
        $student = $this->student();

        $this->actingAs($student)->get('/admin/messages')->assertForbidden();
    }

    public function test_landing_page_shows_contact_section(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('تواصل معنا')
            ->assertSee(route('contact.store'), false)
            ->assertSee('name="name"', false)
            ->assertSee('name="email"', false)
            ->assertSee('name="message"', false);
    }

    public function test_visitor_can_submit_contact_form(): void
    {
        $response = $this->post('/contact', [
            'name' => 'أحمد',
            'email' => 'ahmed@example.com',
            'message' => 'رسالة تجريبية',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('success', 'تم إرسال رسالتك بنجاح، شكراً لتواصلك معنا');

        $this->assertDatabaseHas('contact_messages', [
            'name' => 'أحمد',
            'email' => 'ahmed@example.com',
            'message' => 'رسالة تجريبية',
        ]);
    }

    public function test_landing_page_shows_success_alert(): void
    {
        $this->post('/contact', [
            'name' => 'Sara',
            'email' => 'sara@example.com',
            'message' => 'Hello',
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('تم إرسال رسالتك بنجاح، شكراً لتواصلك معنا', false);
    }

    public function test_contact_form_requires_all_fields(): void
    {
        $response = $this->post('/contact', [
            'name' => '',
            'email' => '',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_requires_valid_email(): void
    {
        $response = $this->post('/contact', [
            'name' => 'Test',
            'email' => 'not-an-email',
            'message' => 'Hi',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertDatabaseCount('contact_messages', 0);
    }

    public function test_contact_form_shows_arabic_validation_errors(): void
    {
        $response = $this->post('/contact', [
            'name' => '',
            'email' => 'bad',
            'message' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'message']);

        $errors = session('errors');
        $this->assertStringContainsString('الاسم', $errors->first('name'));
        $this->assertStringContainsString('البريد الإلكتروني', $errors->first('email'));
        $this->assertStringContainsString('الرسالة', $errors->first('message'));
    }

    public function test_admin_can_view_messages_newest_first(): void
    {
        ContactMessage::create([
            'name' => 'Older',
            'email' => 'older@example.com',
            'message' => 'First message',
        ]);
        ContactMessage::create([
            'name' => 'Newer',
            'email' => 'newer@example.com',
            'message' => 'Second message',
        ]);

        $response = $this->actingAs($this->admin())->get('/admin/messages');

        $response->assertOk()
            ->assertSee('Newer')
            ->assertSee('newer@example.com')
            ->assertSee('Second message')
            ->assertSee('Older')
            ->assertSee('الرسالة');

        $messages = $response->viewData('messages');
        $this->assertSame('Newer', $messages->first()->name);
        $this->assertSame('Older', $messages->last()->name);
    }

    public function test_admin_messages_page_shows_empty_state(): void
    {
        $this->actingAs($this->admin())
            ->get('/admin/messages')
            ->assertOk()
            ->assertSee('لا توجد رسائل بعد');
    }
}

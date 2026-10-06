<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    /**
     * Store a newly submitted contact message from the landing page.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ], [], [
            'name' => 'الاسم',
            'email' => 'البريد الإلكتروني',
            'message' => 'الرسالة',
        ]);

        ContactMessage::create($validated);

        return redirect()
            ->route('home.landing')
            ->with('success', 'تم إرسال رسالتك بنجاح، شكراً لتواصلك معنا');
    }
}

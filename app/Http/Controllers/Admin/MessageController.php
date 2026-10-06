<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of submitted contact messages (newest first).
     */
    public function index(): View
    {
        $messages = ContactMessage::orderByDesc('id')->get();

        return view('admin.messages.index', compact('messages'));
    }
}

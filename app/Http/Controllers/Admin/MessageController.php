<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Inertia\Inertia;
class MessageController extends Controller
{
    public function index()
    {
        $messages = ContactMessage::latest()->get();
        // Mark all unread as read
        ContactMessage::unread()->update(['read_at' => now()]);
        return Inertia::render('Admin/Messages/Index', ['messages' => $messages]);
    }
    public function destroy(ContactMessage $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}

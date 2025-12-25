<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Show chat interface
     */
    public function index()
    {
        return view('chat.index');
    }

    /**
     * Get available teachers/counselors (for students) or student contacts (for teachers)
     */
    public function getAvailableTeachers()
    {
        $currentUser = Auth::user();
        
        // If teacher, show students who have chatted with them
        if ($currentUser->role === 'teacher') {
            $studentIds = Message::where(function($query) use ($currentUser) {
                $query->where('sender_id', $currentUser->id)
                      ->orWhere('receiver_id', $currentUser->id);
            })
            ->get()
            ->pluck('sender_id', 'receiver_id')
            ->flatten()
            ->unique()
            ->filter(fn($id) => $id != $currentUser->id);
            
            $students = User::whereIn('id', $studentIds)
                ->where('role', 'student')
                ->select('id', 'name', 'email', 'updated_at')
                ->get()
                ->map(function ($student) {
                    return [
                        'id' => $student->id,
                        'name' => $student->name,
                        'email' => $student->email,
                        'online' => $student->updated_at >= now()->subMinutes(10),
                        'last_active' => $student->updated_at->diffForHumans(),
                    ];
                });
            
            return response()->json($students);
        }
        
        // If student, show all teachers except themselves
        $teachers = User::where('role', 'teacher')
            ->where('id', '!=', $currentUser->id)
            ->select('id', 'name', 'email', 'updated_at')
            ->get()
            ->map(function ($teacher) {
                $isOnline = $teacher->updated_at >= now()->subMinutes(10);
                return [
                    'id' => $teacher->id,
                    'name' => $teacher->name,
                    'email' => $teacher->email,
                    'online' => $isOnline,
                    'last_active' => $isOnline ? 'Online' : $teacher->updated_at->diffForHumans(),
                ];
            });

        return response()->json($teachers);
    }

    /**
     * Get messages with specific user
     */
    public function getMessages($userId)
    {
        $currentUserId = Auth::id();
        
        $messages = Message::conversation($currentUserId, $userId)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->get()
            ->map(function ($message) use ($currentUserId) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'is_mine' => $message->sender_id == $currentUserId,
                    'sender_name' => $message->sender->name,
                    'read_at' => $message->read_at,
                    'created_at' => $message->created_at->format('H:i'),
                    'created_at_full' => $message->created_at->format('Y-m-d H:i:s'),
                ];
            });

        // Mark received messages as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUserId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'receiver_id' => $message->receiver_id,
                'message' => $message->message,
                'is_mine' => true,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /**
     * Get unread message count
     */
    public function getUnreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Mark message as read
     */
    public function markAsRead($messageId)
    {
        $message = Message::findOrFail($messageId);
        
        // Only receiver can mark as read
        if ($message->receiver_id == Auth::id()) {
            $message->markAsRead();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 403);
    }
}

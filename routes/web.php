<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MoodDetectionController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Auth Routes
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Redirect Dashboard based on Role
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        if ($role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        } elseif ($role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Default to student
        return redirect()->route('student.dashboard'); 
    })->name('dashboard');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Shared Features
    Route::get('/mood-check', [MoodDetectionController::class, 'index'])->name('mood.check');
    
    // Journal Routes
    Route::get('/journal', [App\Http\Controllers\JournalController::class, 'index'])->name('journal.index');
    Route::post('/journal', [App\Http\Controllers\JournalController::class, 'store'])->name('journal.store');
    Route::delete('/journal/{id}', [App\Http\Controllers\JournalController::class, 'destroy'])->name('journal.destroy');

    // Chatbot Route
    Route::post('/chatbot/send', [App\Http\Controllers\ChatbotController::class, 'sendMessage'])->name('chatbot.send');

    // Chat/Counselor Routes
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/teachers', [App\Http\Controllers\ChatController::class, 'getAvailableTeachers'])->name('chat.teachers');
    Route::get('/chat/messages/{userId}', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('chat.messages');
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/read/{messageId}', [App\Http\Controllers\ChatController::class, 'markAsRead'])->name('chat.read');
    Route::get('/chat/unread', [App\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('chat.unread');


    // AI Tools Sandbox Page
    Route::get('/ai-tools', function () {
        return view('pages.ai-tools');
    })->name('ai-tools');

    // --- Student Routes ---
    Route::prefix('student')->name('student.')->group(function () {
        // TODO: Add 'role:student' middleware later
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
    });

    // --- Teacher Routes ---
    Route::prefix('teacher')->name('teacher.')->group(function () {
        // TODO: Add 'role:teacher' middleware later
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');
        Route::post('/analyze-conflicts', [TeacherDashboard::class, 'analyzeConflicts'])->name('analyze');
        
        // New Features
        Route::get('/risk-overview', [TeacherDashboard::class, 'riskOverview'])->name('risk.overview');
        Route::get('/student/{id}', [TeacherDashboard::class, 'showStudent'])->name('student.show');
    });

    // --- Admin Routes ---
    Route::prefix('admin')->name('admin.')->group(function () {
         Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
         Route::get('/users/create', [App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
         Route::post('/users', [App\Http\Controllers\AdminController::class, 'storeUser'])->name('users.store');
         Route::get('/conflict-mediator', [App\Http\Controllers\AdminController::class, 'conflictTool'])->name('conflict');
    });

});

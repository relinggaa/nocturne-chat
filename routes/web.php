<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ChatController;
use App\Events\MessageSent;
use App\Models\Message;
Route::get('/', [ChatController::class, 'showUsernameForm'])->name('username.form');
Route::post('/set-username', [ChatController::class, 'setUsername'])->name('set.username');
Route::get('/chat', [ChatController::class, 'showChat'])->name('chat');
// Route::get('/get-messages', [ChatController::class, 'getMessages']);

// Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');

// Route::post('/send-message', [ChatController::class, 'sendMessage']);
// Route::get('/get-messages', [ChatController::class, 'getMessages']);

Route::post('/send-message', [ChatController::class, 'sendMessage']);
Route::get('/get-messages', [ChatController::class, 'getMessages']);
Route::get('/get-new-messages', [ChatController::class, 'getNewMessages']);


Route::get('/test-pusher', function () {
    try {
        // Buat instance Message
        $message = new Message([
            'username' => 'TestUser',
            'message' => 'Testing Pusher connection'
        ]);

        // Kirim event
        broadcast(new MessageSent($message))->toOthers();

        // Log untuk debug
        logger()->info('Pusher broadcast sent', ['message' => $message]);

        return response()->json(['success' => 'Pusher event sent!']);
    } catch (\Exception $e) {
        logger()->error('Pusher broadcast failed', ['error' => $e->getMessage()]);
        return response()->json(['error' => $e->getMessage()], 500);
    }
});
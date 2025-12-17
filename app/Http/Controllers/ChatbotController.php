<?php

namespace App\Http\Controllers;

use App\Services\ChatbotService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ChatbotController extends Controller
{
    protected $chatbotService;
    
    public function __construct(ChatbotService $chatbotService)
    {
        $this->chatbotService = $chatbotService;
    }
    
    /**
     * Process chatbot message
     */
    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'message' => 'required|string|max:500'
        ]);
        
        $context = $request->input('context', []);
        $response = $this->chatbotService->processMessage($request->message, $context);
        
        return response()->json([
            'success' => true,
            'message' => $response // Changed key to 'message' to be clearer, or keep 'data' if frontend expects it. Let's send both to be safe.
        ]);
    }
}

<?php

namespace App\Http\Controllers;



use Inertia\Inertia;
use Inertia\Response;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function patientChatbot(): Response
    {
        return Inertia::render('patient/chat/chatbot');
    }

}

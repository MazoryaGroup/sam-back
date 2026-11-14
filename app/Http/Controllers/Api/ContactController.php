<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Mail\NewContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;

class ContactController extends Controller
{
    /**
     * Store new contact message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255',
            'phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^[0-9+\-\s()]{7,20}$/',
            ],
            'message' => 'required|string|max:5000',
        ]);

        try {
            $validated['name'] = strip_tags($validated['name']);
            $validated['message'] = strip_tags($validated['message']);

            $contact = Contact::create($validated);

            // Email delivery should not affect contact submission
            try {
                Mail::to($contact->email)
                    ->send(new NewContactMail($contact));

            } catch (Exception $e) {
                Log::warning('Contact email delivery failed', [
                    'contact_id' => $contact->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return response()->json([
                'status' => true,
                'message' => 'Your message was received successfully.',
                'data' => [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'email' => $contact->email,
                ],
            ], 201);

        } catch (Exception $e) {

            Log::error('Contact Store Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Error processing request.',
            ], 500);
        }
    }

    /**
     * List contacts (Admin only)
     */
    public function index()
    {
        $contacts = Contact::latest()->paginate(15);

        return response()->json([
            'status' => true,
            'data' => $contacts,
        ]);
    }

    /**
     * Show contact details (Admin only)
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'status' => false,
                'message' => 'Contact not found.',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $contact,
        ]);
    }
}

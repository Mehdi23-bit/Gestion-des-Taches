<?php
// app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\ContactMail;

class ContactController extends Controller
{
    // Afficher le formulaire de contact
    public function showContactForm()
    {
        return view('contact');
    }

    // Envoyer un email de contact
    public function sendContactEmail(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        // Envoyer l'email via la classe ContactMail
        Mail::to('abdotizguini3@gmail.com')->send(new ContactMail($details));

        return back()->with('success', 'Votre message a été envoyé avec succès.');
    }
}



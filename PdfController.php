<?php
// app/Http/Controllers/PdfController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\User;

class PdfController extends Controller
{
    public function downloadProfilePdf()
    {
        $user = auth()->user(); // Assuming you're using authentication to get the current user

        $pdf = Pdf::loadView('pdf.profile', ['user' => $user]);
        return $pdf->download('profile.pdf');
    }
}

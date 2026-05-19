<?php

namespace App\Http\Controllers;

use App\Mail\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'message' => ['required', 'string', 'min:10'],
            'company' => ['nullable', 'string', 'max:100'],
            'phone'   => ['nullable', 'string', 'max:30'],
        ]);

        Mail::to('contact@nusagrade.com')->send(new ContactSubmission($validated));

        return redirect()->route('home', '#contact')
            ->with('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda dalam 1 hari kerja.');
    }
}

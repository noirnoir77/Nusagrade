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

        try {
            Mail::to('contact@nusagrade.com')->send(new ContactSubmission($validated));
        } catch (\Throwable $e) {
            \Log::error('Contact form mail failed: ' . $e->getMessage());

            return redirect()->route('home', '#contact')
                ->with('error', 'Pesan Anda gagal terkirim karena masalah teknis. Silakan hubungi kami langsung melalui WhatsApp atau email contact@nusagrade.com.');
        }

        return redirect()->route('home', '#contact')
            ->with('success', 'Pesan Anda telah terkirim. Kami akan menghubungi Anda dalam 1 hari kerja.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class ResumeController extends Controller
{
    public function download()
    {
        $setting = Setting::get();
        $resumePath = $setting->resume_path ?? null;

        if (!$resumePath) {
            abort(404, 'Resume not found. Please upload a PDF in admin settings.');
        }

        $diskPath = str_replace('/storage/', '', $resumePath);

        if (!Storage::disk('public')->exists($diskPath)) {
            abort(404, 'Resume file not found on disk.');
        }

        return Storage::disk('public')->download($diskPath, 'resume.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}

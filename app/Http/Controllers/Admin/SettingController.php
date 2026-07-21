<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = Setting::get();

        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'site_title' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
            'hero_image_file' => ['nullable', 'file', 'image', 'max:5120'],
            'resume_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'social_links' => ['nullable', 'string'],
            'admin_email' => ['nullable', 'email', 'max:255'],
            'email_notifications' => ['boolean'],
            'contact_email_subject' => ['nullable', 'string', 'max:255'],
            // Home page
            'hero_greeting' => ['nullable', 'string', 'max:255'],
            'hero_name' => ['nullable', 'string', 'max:255'],
            'hero_subtitle' => ['nullable', 'string', 'max:255'],
            'hero_cta_text' => ['nullable', 'string', 'max:255'],
            'hero_cta_url' => ['nullable', 'string', 'max:255'],
            'about_title' => ['nullable', 'string', 'max:255'],
            'about_location' => ['nullable', 'string', 'max:255'],
            'about_role' => ['nullable', 'string', 'max:255'],
            'testimonials_title' => ['nullable', 'string', 'max:255'],
            'testimonials_subtitle' => ['nullable', 'string', 'max:255'],
            // Contact page
            'contact_title' => ['nullable', 'string', 'max:255'],
            'contact_description' => ['nullable', 'string'],
            'contact_email' => ['nullable', 'string', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:255'],
            'contact_address' => ['nullable', 'string'],
        ]);

        $data['email_notifications'] = $request->boolean('email_notifications');

        if ($request->hasFile('hero_image_file')) {
            $setting = Setting::get();
            $oldPath = str_replace('/storage/', '', $setting->hero_image);
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
            $data['hero_image'] = '/storage/' . $request->file('hero_image_file')->store('settings', 'public');
        } else {
            $data['hero_image'] = $request->input('hero_image_current');
        }

        unset($data['hero_image_file']);

        if ($request->hasFile('resume_file')) {
            $setting = Setting::get();
            $oldResume = str_replace('/storage/', '', $setting->resume_path ?? '');
            if ($oldResume && Storage::disk('public')->exists($oldResume)) {
                Storage::disk('public')->delete($oldResume);
            }
            $data['resume_path'] = '/storage/' . $request->file('resume_file')->store('resumes', 'public');
        } else {
            $data['resume_path'] = $request->input('resume_path_current');
        }

        unset($data['resume_file']);

        $socialLinks = $request->input('social_links', '');
        $socialLinks = trim($socialLinks);
        if ($socialLinks !== '' && $socialLinks !== 'null') {
            $decoded = json_decode($socialLinks, true);
            $data['social_links'] = $decoded ?? $socialLinks;
        } else {
            $data['social_links'] = null;
        }

        $setting = Setting::get();
        $setting->update($data);

        return to_route('admin.settings.edit')->with('success', 'Settings updated successfully.');
    }

    public function deleteFile(string $field)
    {
        $setting = Setting::get();
        $path = null;

        if ($field === 'hero_image' && $setting->hero_image) {
            $path = str_replace('/storage/', '', $setting->hero_image);
            $setting->update(['hero_image' => null]);
        } elseif ($field === 'resume_path' && $setting->resume_path) {
            $path = str_replace('/storage/', '', $setting->resume_path);
            $setting->update(['resume_path' => null]);
        }

        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }

        return back()->with('success', ucfirst(str_replace('_', ' ', $field)) . ' deleted successfully.');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = auth()->user();
        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully.');
    }
}

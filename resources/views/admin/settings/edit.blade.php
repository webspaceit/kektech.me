@extends('admin.layouts.app')

@section('title', 'Settings')

@section('content')
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Settings</h1>

    <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- General Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">General</h2>

            <div class="mb-4">
                <label for="site_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Site Title</label>
                <input type="text" id="site_title" name="site_title" value="{{ old('site_title', $setting->site_title) }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('site_title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            {{-- Logo Upload --}}
            <div class="mb-4">
                <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Dashboard Logo</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="hidden" name="logo_current" value="{{ $setting->logo }}">
                        <input type="file" id="logo_input" name="logo_file" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, SVG. Max 5MB. Recommended size: 200x50px</p>
                        @error('logo_file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div id="logo_preview" class="w-20 h-20 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if($setting->logo)
                            <img src="{{ $setting->logo }}" alt="Logo Preview" class="w-full h-full object-contain p-1">
                        @else
                            <span class="text-gray-400 text-xs">No logo</span>
                        @endif
                    </div>
                    @if($setting->logo)
                        <button type="button" onclick="if(confirm('Delete this logo?')) document.getElementById('delete-logo-form').submit();" class="shrink-0 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Delete logo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    @endif
                </div>
                <script>
                    document.getElementById('logo_input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                document.getElementById('logo_preview').innerHTML = '<img src="' + ev.target.result + '" alt="Logo Preview" class="w-full h-full object-contain p-1">';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            </div>

            {{-- Favicon Upload --}}
            <div class="mb-4">
                <label for="favicon" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Favicon</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="hidden" name="favicon_current" value="{{ $setting->favicon }}">
                        <input type="file" id="favicon_input" name="favicon_file" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">ICO, PNG. Max 2MB. Recommended size: 32x32px or 64x64px</p>
                        @error('favicon_file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div id="favicon_preview" class="w-20 h-20 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if($setting->favicon)
                            <img src="{{ $setting->favicon }}" alt="Favicon Preview" class="w-full h-full object-contain p-1">
                        @else
                            <span class="text-gray-400 text-xs">No favicon</span>
                        @endif
                    </div>
                    @if($setting->favicon)
                        <button type="button" onclick="if(confirm('Delete this favicon?')) document.getElementById('delete-favicon-form').submit();" class="shrink-0 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Delete favicon">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    @endif
                </div>
                <script>
                    document.getElementById('favicon_input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                document.getElementById('favicon_preview').innerHTML = '<img src="' + ev.target.result + '" alt="Favicon Preview" class="w-full h-full object-contain p-1">';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            </div>

            {{-- Login Logo Upload --}}
            <div class="mb-4">
                <label for="login_logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Login Page Logo</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="hidden" name="login_logo_current" value="{{ $setting->login_logo }}">
                        <input type="file" id="login_logo_input" name="login_logo_file" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG, SVG. Max 5MB. Recommended size: 300x100px</p>
                        @error('login_logo_file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div id="login_logo_preview" class="w-20 h-20 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if($setting->login_logo)
                            <img src="{{ $setting->login_logo }}" alt="Login Logo Preview" class="w-full h-full object-contain p-1">
                        @else
                            <span class="text-gray-400 text-xs">No logo</span>
                        @endif
                    </div>
                    @if($setting->login_logo)
                        <button type="button" onclick="if(confirm('Delete this login logo?')) document.getElementById('delete-login-logo-form').submit();" class="shrink-0 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Delete login logo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    @endif
                </div>
                <script>
                    document.getElementById('login_logo_input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                document.getElementById('login_logo_preview').innerHTML = '<img src="' + ev.target.result + '" alt="Login Logo Preview" class="w-full h-full object-contain p-1">';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            </div>

            <div class="mb-4">
                <label for="bio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bio</label>
                <textarea id="bio" name="bio" rows="4"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">{{ old('bio', $setting->bio) }}</textarea>
                @error('bio') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="hero_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Image</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="hidden" name="hero_image_current" value="{{ $setting->hero_image }}">
                        <input type="file" id="hero_image_input" name="hero_image_file" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">JPG, PNG, GIF, WebP. Max 5MB.</p>
                        @error('hero_image_file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    <div id="hero_image_preview" class="w-20 h-20 rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden shrink-0 bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                        @if($setting->hero_image)
                            <img src="{{ $setting->hero_image }}" alt="Preview" class="w-full h-full object-cover">
                        @else
                            <span class="text-gray-400 text-xs">No img</span>
                        @endif
                    </div>
                    @if($setting->hero_image)
                        <button type="button" onclick="if(confirm('Delete this image?')) document.getElementById('delete-hero-image-form').submit();" class="shrink-0 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Delete image">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    @endif
                </div>
                <script>
                    document.getElementById('hero_image_input').addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function(ev) {
                                document.getElementById('hero_image_preview').innerHTML = '<img src="' + ev.target.result + '" alt="Preview" class="w-full h-full object-cover">';
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                </script>
            </div>

            <div class="mb-4">
                <label for="social_links" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Social Links (JSON)</label>
                <textarea id="social_links" name="social_links" rows="3"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono text-xs">{{ old('social_links', $setting->social_links ? json_encode($setting->social_links, JSON_PRETTY_PRINT) : '') }}</textarea>
                @error('social_links') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">e.g. {"github": "https://...", "twitter": "https://...", "email": "you@email.com"}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Resume / CV (PDF)</label>
                <div class="flex items-start gap-4">
                    <div class="flex-1">
                        <input type="hidden" name="resume_path_current" value="{{ $setting->resume_path }}">
                        <input type="file" id="resume_input" name="resume_file" accept=".pdf"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 file:cursor-pointer">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PDF only. Max 5MB.</p>
                        @error('resume_file') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                    </div>
                    @if($setting->resume_path)
                        <a href="{{ $setting->resume_path }}" target="_blank" class="shrink-0 mt-1 inline-flex items-center gap-1 text-xs text-indigo-600 dark:text-indigo-400 hover:underline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Current PDF
                        </a>
                        <button type="button" onclick="if(confirm('Delete this resume?')) document.getElementById('delete-resume-form').submit();" class="shrink-0 mt-1 p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md transition-colors" title="Delete resume">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Home Page Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Home Page</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="hero_greeting" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Greeting</label>
                    <input type="text" id="hero_greeting" name="hero_greeting" value="{{ old('hero_greeting', $setting->hero_greeting) }}"
                        placeholder="Hi, I'm"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('hero_greeting') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="hero_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Name</label>
                    <input type="text" id="hero_name" name="hero_name" value="{{ old('hero_name', $setting->hero_name) }}"
                        placeholder="Mohammad Kudrat-E-Khuda"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('hero_name') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="hero_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hero Subtitle / Nickname</label>
                    <input type="text" id="hero_subtitle" name="hero_subtitle" value="{{ old('hero_subtitle', $setting->hero_subtitle) }}"
                        placeholder="(Sizu)"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('hero_subtitle') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="about_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Role / Title</label>
                    <input type="text" id="about_role" name="about_role" value="{{ old('about_role', $setting->about_role) }}"
                        placeholder="Full-Stack Developer"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('about_role') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="hero_cta_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CTA Button Text</label>
                    <input type="text" id="hero_cta_text" name="hero_cta_text" value="{{ old('hero_cta_text', $setting->hero_cta_text) }}"
                        placeholder="View Projects"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('hero_cta_text') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="hero_cta_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CTA Button URL</label>
                    <input type="text" id="hero_cta_url" name="hero_cta_url" value="{{ old('hero_cta_url', $setting->hero_cta_url) }}"
                        placeholder="/projects"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('hero_cta_url') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="about_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">About Section Title</label>
                <input type="text" id="about_title" name="about_title" value="{{ old('about_title', $setting->about_title) }}"
                    placeholder="About Me"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('about_title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="about_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                <input type="text" id="about_location" name="about_location" value="{{ old('about_location', $setting->about_location) }}"
                    placeholder="Based in Bangladesh"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('about_location') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="testimonials_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Testimonials Title</label>
                    <input type="text" id="testimonials_title" name="testimonials_title" value="{{ old('testimonials_title', $setting->testimonials_title) }}"
                        placeholder="What People Say"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('testimonials_title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="testimonials_subtitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Testimonials Subtitle</label>
                    <input type="text" id="testimonials_subtitle" name="testimonials_subtitle" value="{{ old('testimonials_subtitle', $setting->testimonials_subtitle) }}"
                        placeholder="Testimonials from clients and colleagues"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('testimonials_subtitle') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Contact Page Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Contact Page</h2>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="contact_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Page Title</label>
                    <input type="text" id="contact_title" name="contact_title" value="{{ old('contact_title', $setting->contact_title) }}"
                        placeholder="Get in Touch"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('contact_title') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Displayed Email</label>
                    <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $setting->contact_email) }}"
                        placeholder="hello@kektech.me"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('contact_email') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="contact_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Page Description</label>
                <textarea id="contact_description" name="contact_description" rows="3"
                    placeholder="Have a question or want to work together? Fill out the form and I'll get back to you."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">{{ old('contact_description', $setting->contact_description) }}</textarea>
                @error('contact_description') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone (optional)</label>
                    <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $setting->contact_phone) }}"
                        placeholder="+880 1234 567890"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('contact_phone') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="contact_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address (optional)</label>
                    <input type="text" id="contact_address" name="contact_address" value="{{ old('contact_address', $setting->contact_address) }}"
                        placeholder="Dhaka, Bangladesh"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                    @error('contact_address') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        {{-- Footer Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Footer Settings</h2>

            <div class="mb-4">
                <label for="footer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Footer Text (HTML allowed)</label>
                <textarea id="footer_text" name="footer_text" rows="3"
                    placeholder="&copy; 2026 KekTech. All rights reserved."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono">{{ old('footer_text', $setting->footer_text) }}</textarea>
                @error('footer_text') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Supports HTML tags like &lt;a&gt;, &lt;span&gt;, &lt;br&gt;, etc. Leave blank for default text.</p>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="footer_font" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Font Family</label>
                    <select id="footer_font" name="footer_font"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Default (Instrument Sans)</option>
                        <option value="Inter" {{ old('footer_font', $setting->footer_font) === 'Inter' ? 'selected' : '' }}>Inter</option>
                        <option value="Arial" {{ old('footer_font', $setting->footer_font) === 'Arial' ? 'selected' : '' }}>Arial</option>
                        <option value="Georgia" {{ old('footer_font', $setting->footer_font) === 'Georgia' ? 'selected' : '' }}>Georgia</option>
                        <option value="Times New Roman" {{ old('footer_font', $setting->footer_font) === 'Times New Roman' ? 'selected' : '' }}>Times New Roman</option>
                        <option value="Courier New" {{ old('footer_font', $setting->footer_font) === 'Courier New' ? 'selected' : '' }}>Courier New</option>
                        <option value="Verdana" {{ old('footer_font', $setting->footer_font) === 'Verdana' ? 'selected' : '' }}>Verdana</option>
                        <option value="Trebuchet MS" {{ old('footer_font', $setting->footer_font) === 'Trebuchet MS' ? 'selected' : '' }}>Trebuchet MS</option>
                        <option value="system-ui" {{ old('footer_font', $setting->footer_font) === 'system-ui' ? 'selected' : '' }}>System UI</option>
                    </select>
                    @error('footer_font') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="footer_font_size" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Font Size</label>
                    <select id="footer_font_size" name="footer_font_size"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="">Default (14px)</option>
                        <option value="12px" {{ old('footer_font_size', $setting->footer_font_size) === '12px' ? 'selected' : '' }}>12px - Small</option>
                        <option value="13px" {{ old('footer_font_size', $setting->footer_font_size) === '13px' ? 'selected' : '' }}>13px</option>
                        <option value="14px" {{ old('footer_font_size', $setting->footer_font_size) === '14px' ? 'selected' : '' }}>14px - Normal</option>
                        <option value="15px" {{ old('footer_font_size', $setting->footer_font_size) === '15px' ? 'selected' : '' }}>15px</option>
                        <option value="16px" {{ old('footer_font_size', $setting->footer_font_size) === '16px' ? 'selected' : '' }}>16px - Large</option>
                        <option value="18px" {{ old('footer_font_size', $setting->footer_font_size) === '18px' ? 'selected' : '' }}>18px</option>
                        <option value="20px" {{ old('footer_font_size', $setting->footer_font_size) === '20px' ? 'selected' : '' }}>20px - XL</option>
                    </select>
                    @error('footer_font_size') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="footer_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="footer_color" name="footer_color" value="{{ old('footer_color', $setting->footer_color ?: '#6b7280') }}"
                            class="w-10 h-10 rounded border border-gray-300 dark:border-gray-600 cursor-pointer">
                        <input type="text" id="footer_color_text" value="{{ old('footer_color', $setting->footer_color) }}"
                            placeholder="#6b7280"
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono"
                            oninput="document.getElementById('footer_color').value = this.value">
                    </div>
                    @error('footer_color') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="footer_bg_color" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Background Color</label>
                    <div class="flex items-center gap-2">
                        <input type="color" id="footer_bg_color" name="footer_bg_color" value="{{ old('footer_bg_color', $setting->footer_bg_color ?: '#f9fafb') }}"
                            class="w-10 h-10 rounded border border-gray-300 dark:border-gray-600 cursor-pointer">
                        <input type="text" id="footer_bg_color_text" value="{{ old('footer_bg_color', $setting->footer_bg_color) }}"
                            placeholder="#f9fafb"
                            class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm font-mono"
                            oninput="document.getElementById('footer_bg_color').value = this.value">
                    </div>
                    @error('footer_bg_color') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="footer_align" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Text Align</label>
                    <select id="footer_align" name="footer_align"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                        <option value="left" {{ old('footer_align', $setting->footer_align) === 'left' ? 'selected' : '' }}>Left</option>
                        <option value="center" {{ old('footer_align', $setting->footer_align) === 'center' ? 'selected' : '' }}>Center</option>
                        <option value="right" {{ old('footer_align', $setting->footer_align) === 'right' ? 'selected' : '' }}>Right</option>
                    </select>
                    @error('footer_align') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="p-3 rounded-md bg-gray-50 dark:bg-gray-700/50 border border-gray-200 dark:border-gray-600">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Preview:</p>
                <div id="footer-preview" style="padding: 12px 16px; border-radius: 6px; font-size: {{ old('footer_font_size', $setting->footer_font_size ?: '14px') }}; color: {{ old('footer_color', $setting->footer_color ?: '#6b7280') }}; background: {{ old('footer_bg_color', $setting->footer_bg_color ?: '#f9fafb') }}; font-family: {{ old('footer_font', $setting->footer_font ?: 'sans-serif') }}; text-align: {{ old('footer_align', $setting->footer_align ?: 'left') }};">
                    {!! old('footer_text', $setting->footer_text) ?: '© 2026 KekTech. All rights reserved.' !!}
                </div>
            </div>
        </div>

        {{-- Email Settings --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Email Notifications</h2>

            <div class="mb-4">
                <label for="admin_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Admin Email Address</label>
                <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email', $setting->admin_email) }}"
                    placeholder="your@email.com"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('admin_email') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Where contact form notifications will be sent.</p>
            </div>

            <div class="mb-4">
                <div class="flex items-center gap-3">
                    <input type="hidden" name="email_notifications" value="0">
                    <input type="checkbox" id="email_notifications" name="email_notifications" value="1"
                        {{ old('email_notifications', $setting->email_notifications) ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600">
                    <label for="email_notifications" class="text-sm font-medium text-gray-700 dark:text-gray-300">Enable email notifications</label>
                </div>
                @error('email_notifications') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label for="contact_email_subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Custom Email Subject</label>
                <input type="text" id="contact_email_subject" name="contact_email_subject" value="{{ old('contact_email_subject', $setting->contact_email_subject) }}"
                    placeholder="New Contact Message from {name}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('contact_email_subject') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave blank for default. Use {name} to include sender's name.</p>
            </div>
        </div>

        <div class="flex items-center gap-3 max-w-2xl mb-8">
            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-md transition">Save Settings</button>
        </div>
    </form>

    <form id="delete-logo-form" method="POST" action="{{ route('admin.settings.deleteFile', ['field' => 'logo']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="delete-favicon-form" method="POST" action="{{ route('admin.settings.deleteFile', ['field' => 'favicon']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="delete-login-logo-form" method="POST" action="{{ route('admin.settings.deleteFile', ['field' => 'login_logo']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="delete-hero-image-form" method="POST" action="{{ route('admin.settings.deleteFile', ['field' => 'hero_image']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form id="delete-resume-form" method="POST" action="{{ route('admin.settings.deleteFile', ['field' => 'resume_path']) }}" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    {{-- Change Password --}}
    <form method="POST" action="{{ route('admin.settings.changePassword') }}" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 max-w-2xl mb-6">
        @csrf
        @method('PUT')
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Change Password</h2>

        <div class="mb-4">
            <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Current Password</label>
            <input type="password" id="current_password" name="current_password" required
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
            @error('current_password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">New Password</label>
                <input type="password" id="new_password" name="new_password" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
                @error('new_password') <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Confirm New Password</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white text-sm">
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition">Update Password</button>
    </form>
@endsection

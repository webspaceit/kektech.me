<?php

use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SkillController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PublicBlogController;
use App\Http\Controllers\PublicProjectController;
use App\Http\Controllers\PublicSkillController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/projects', [PublicProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{slug}', [PublicProjectController::class, 'show'])->name('projects.show');

Route::get('/skills', [PublicSkillController::class, 'index'])->name('skills.index');

Route::get('/blog', [PublicBlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PublicBlogController::class, 'show'])->name('blog.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::get('/resume/download', [ResumeController::class, 'download'])->name('resume.download');

Route::get('/sitemap.xml', function () {
    $url = config('app.url', 'https://kektech.me');
    $projects = \App\Models\Project::where('is_active', true)->latest()->get();
    $posts = \App\Models\BlogPost::whereNotNull('published_at')->latest('published_at')->get();

    $xml = '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
    $xml .= '<url><loc>' . $url . '</loc><changefreq>weekly</changefreq><priority>1.0</priority></url>';
    $xml .= '<url><loc>' . $url . '/projects</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . $url . '/skills</loc><changefreq>monthly</changefreq><priority>0.6</priority></url>';
    $xml .= '<url><loc>' . $url . '/blog</loc><changefreq>weekly</changefreq><priority>0.8</priority></url>';
    $xml .= '<url><loc>' . $url . '/contact</loc><changefreq>monthly</changefreq><priority>0.5</priority></url>';

    foreach ($projects as $project) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url . '/projects/' . $project->slug . '</loc>';
        $xml .= '<lastmod>' . $project->updated_at->format('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.7</priority>';
        $xml .= '</url>';
    }

    foreach ($posts as $post) {
        $xml .= '<url>';
        $xml .= '<loc>' . $url . '/blog/' . $post->slug . '</loc>';
        $xml .= '<lastmod>' . $post->updated_at->format('Y-m-d') . '</lastmod>';
        $xml .= '<changefreq>monthly</changefreq>';
        $xml .= '<priority>0.7</priority>';
        $xml .= '</url>';
    }

    $xml .= '</urlset>';

    return response($xml, 200)->header('Content-Type', 'application/xml');
});

Route::get('/robots.txt', function () {
    $url = config('app.url', 'https://kektech.me');
    $txt = "User-agent: *\n";
    $txt .= "Allow: /\n";
    $txt .= "Disallow: /wsdashboard\n";
    $txt .= "Disallow: /api\n\n";
    $txt .= "Sitemap: " . $url . "/sitemap.xml\n";

    return response($txt, 200)->header('Content-Type', 'text/plain');
});

Route::middleware('guest')->group(function () {
    Route::get('/wsdashboard/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/wsdashboard/login', [AuthController::class, 'login']);
});

Route::post('/wsdashboard/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('wsdashboard')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('projects', ProjectController::class)->except('show');
    Route::post('projects/{project}/toggle', [ProjectController::class, 'toggle'])->name('projects.toggle');
    Route::delete('project-media/{media}', [ProjectController::class, 'destroyMedia'])->name('projects.media.destroy');
    Route::post('project-media/reorder', [ProjectController::class, 'reorderMedia'])->name('projects.media.reorder');
    Route::resource('skills', SkillController::class)->except('show');
    Route::resource('blog-posts', BlogPostController::class)->except('show');
    Route::resource('testimonials', TestimonialController::class)->except('show');

    Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('settings/delete-file/{field}', [SettingController::class, 'deleteFile'])->name('settings.deleteFile');
    Route::put('settings/change-email', [SettingController::class, 'changeEmail'])->name('settings.changeEmail');
    Route::put('settings/change-password', [SettingController::class, 'changePassword'])->name('settings.changePassword');

    Route::get('contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('contact-messages/{contactMessage}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::delete('contact-messages/{contactMessage}', [ContactMessageController::class, 'destroy'])->name('contact-messages.destroy');

    Route::get('media', [MediaController::class, 'index'])->name('media.index');
    Route::post('media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('media/{filename}', [MediaController::class, 'destroy'])->name('media.destroy');

    Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/rooms', [\App\Http\Controllers\Admin\ChatController::class, 'rooms'])->name('chat.rooms');
    Route::get('chat/{id}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'messages'])->name('chat.messages');
    Route::post('chat/{id}/send', [\App\Http\Controllers\Admin\ChatController::class, 'send'])->name('chat.send');
    Route::get('unread-chat-count', function () {
        $userId = auth()->id();
        $count = \Illuminate\Support\Facades\DB::table('chat_messages')
            ->join('chat_rooms', 'chat_messages.room_id', '=', 'chat_rooms.id')
            ->leftJoin('chat_participants', 'chat_rooms.id', '=', 'chat_participants.room_id')
            ->where(function ($q) use ($userId) {
                $q->whereNull('chat_messages.user_id')
                  ->orWhere('chat_messages.user_id', '!=', $userId);
            })
            ->whereNull('chat_messages.read_at')
            ->where(function ($q) use ($userId) {
                $q->where('chat_rooms.type', 'guest')
                  ->orWhere('chat_participants.user_id', $userId);
            })
            ->count();
        return response()->json(['count' => $count]);
    })->name('chat.unread-count');
});

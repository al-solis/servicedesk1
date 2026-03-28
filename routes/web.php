<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\KnowledgeBaseArticleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\NavController;
use App\Http\Middleware\ContentSecurityPolicy;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeamController;

Route::get('/', function () {
    return view('welcome');
})->middleware('clickjacking.protection')
    ->name('home');

Route::get('/dashboard', function () {
    return view('layouts.app', ['user' => Auth::user()]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('/dashboard', function () {
//     return view('layouts.sd', ['user' => Auth::user()]);
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {

    Route::get('layout/navbar/notification', [NavController::class, 'notification'])->name('navbar.notification');

    Route::resource('tickets', TicketController::class)->except(['destroy']);
    Route::delete('/ticket-images/{image}', [TicketController::class, 'deleteImage'])->name('ticket-images.destroy');

    Route::resource('categories', CategoryController::class)->except(['destroy']);
    Route::resource('users', UserController::class)->except(['destroy']);
    Route::post('/users/upload-profile/{id}', [UserController::class, 'uploadPicture'])->name('users.uploadPicture');
    Route::delete('/users/delete-picture/{id}', [UserController::class, 'deletePicture'])->name('users.deletePicture');
    Route::post('/users/toggle-status/{id}', [UserController::class, 'toggleStatus']);


    Route::resource('support', SupportController::class)->except(['destroy']);

    Route::resource('kb', KnowledgeBaseArticleController::class)->except(['destroy']);
    Route::post('kb/upload', [KnowledgeBaseArticleController::class, 'upload'])->name('kb.upload');

    Route::get('/articles', [ArticleController::class, 'index'])->name('articles.article-index');
    Route::get('/articles/article-show/{id}', [ArticleController::class, 'show'])->name('articles.article-show');

    Route::get('dashboard/index/get-ticket-counts', [DashboardController::class, 'getTicketCounts']);

    Route::resource('dashboard', DashboardController::class)
        ->except(['destroy'])
        ->name('index', 'dashboard');

    Route::resource('department', DepartmentController::class)->except(['destroy']);

    Route::get('ai/chat', [ChatbotController::class, 'showChat'])->name('ai.chat');
    Route::post('ai/chat', [ChatbotController::class, 'chat'])->name('ai.chat');

    Route::get('reports/index-detail', [ReportsController::class, 'indexDetail'])->name('reports.index-detail');
    Route::get('reports/index-summary', [ReportsController::class, 'indexSummary'])->name('reports.index-summary');
    Route::get('reports/index-export', [ReportsController::class, 'indexExport'])->name('reports.index-export');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('teams', TeamController::class)->except(['destroy']);
    Route::get('/teams/dashboard', [TeamController::class, 'dashboard']);
    // Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    // Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    // Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    // Route::get('/tickets/{id}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
    // Route::put('/tickets/{id}/update', [TicketController::class, 'update'])->name('tickets.update');    
    // Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');

});

// GET /tickets → index()
// POST /tickets → store()
// GET /tickets/create → create()
// GET /tickets/{id} → show()
// PUT/PATCH /tickets/{id} → update()
// DELETE /tickets/{id} → destroy()

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

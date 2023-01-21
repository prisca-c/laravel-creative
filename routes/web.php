<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([ 'auth', 'verified' ])->group(function () {
    Route::get('/dashboard', [ArticleController::class,'published'])->name('dashboard');
    Route::get('/articles', [ArticleController::class, 'index'])->name('articles');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/article', [ArticleController::class, 'user'])->name('articles.user');
    Route::delete('/articles/{id}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    Route::put('/articles/{id}', [ArticleController::class, 'update'])->name('articles.update');
    Route::get('/articles/tag/{tag}', [ArticleController::class, 'tag'])->name('articles.tag');

    Route::get('/users', [UserController::class, 'index'])->name('users');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard/users', function () {
        return Inertia::render('Users', [
            'admin' => Auth::user()->admin()
        ]);
    })->name('dashboard.users');
    Route::get('/articles/index', [ArticleController::class, 'articlesWithRelations'])->name('articles.index');
});
require __DIR__.'/auth.php';


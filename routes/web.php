<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');
Route::livewire('containers', 'pages::containers.index')
    ->middleware(['auth', 'verified'])
    ->name('containers');
Route::livewire('containers/{id}', 'pages::containers.show')
    ->middleware(['auth', 'verified'])
    ->name('container.show');
Route::livewire('parties', 'pages::parties.index')
    ->middleware(['auth', 'verified'])
    ->name('parties');
Route::livewire('parties/{id}', 'pages::parties.show')
    ->middleware(['auth', 'verified'])
    ->name('party.show');
require __DIR__.'/settings.php';

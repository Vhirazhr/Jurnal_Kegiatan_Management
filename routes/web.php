<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AgendaController;

Route::get('/', function () {
    return view('welcome'); // Halaman landing
});

Route::get('/agenda/create', [AgendaController::class, 'create'])->name('agenda.create');
Route::get('/agenda', [AgendaController::class, 'index'])->name('agenda.index');
Route::post('/agenda/store', [AgendaController::class, 'store'])->name('agenda.store');
Route::get('/agenda/output', [AgendaController::class, 'output'])->name('agenda.output');
Route::get('/agenda/print', [AgendaController::class, 'print'])->name('agenda.print');
Route::get('/agenda/{id}/edit', [AgendaController::class, 'edit'])->name('agenda.edit');
Route::put('/agenda/{id}', [AgendaController::class, 'update'])->name('agenda.update');
Route::delete('/agenda/{id}', [AgendaController::class, 'destroy'])->name('agenda.destroy');
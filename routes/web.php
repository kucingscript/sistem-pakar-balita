<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DiagnosisController;
use App\Http\Controllers\Admin\DiseaseController;
use App\Http\Controllers\Admin\SymptomController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->middleware([
    'auth',
    'verified',
    'role:superadmin',
])->group(function () {
    Route::get('users/trashed', [UserController::class, 'trashed'])->name('users.trashed');
    Route::post('users/restore/{id}', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/force-delete/{id}', [UserController::class, 'forceDelete'])->name('users.forceDelete');
    Route::resource('users', UserController::class);
});

Route::prefix('admin')->name('admin.')->middleware([
    'auth',
    'verified',
    'role:admin,superadmin',
])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('diseases/trashed', [DiseaseController::class, 'trashed'])->name('diseases.trashed');
    Route::post('diseases/restore/{id}', [DiseaseController::class, 'restore'])->name('diseases.restore');
    Route::delete('diseases/force-delete/{id}', [DiseaseController::class, 'forceDelete'])->name('diseases.forceDelete');
    Route::resource('diseases', DiseaseController::class);

    Route::get('symptoms/trashed', [SymptomController::class, 'trashed'])->name('symptoms.trashed');
    Route::post('symptoms/restore/{id}', [SymptomController::class, 'restore'])->name('symptoms.restore');
    Route::delete('symptoms/force-delete/{id}', [SymptomController::class, 'forceDelete'])->name('symptoms.forceDelete');
    Route::resource('symptoms', SymptomController::class);

    Route::get('diagnoses/trashed', [DiagnosisController::class, 'trashed'])->name('diagnoses.trashed');
    Route::post('diagnoses/restore/{id}', [DiagnosisController::class, 'restore'])->name('diagnoses.restore');
    Route::delete('diagnoses/force-delete/{id}', [DiagnosisController::class, 'forceDelete'])->name('diagnoses.forceDelete');
    Route::resource('diagnoses', DiagnosisController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

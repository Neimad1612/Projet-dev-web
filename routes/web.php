<?php
use Illuminate\Support\Facades\Route;

// Importation des contrôleurs
use App\Http\Controllers\PublicHomeController;
use App\Http\Controllers\FreeTourController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\Auth\{LoginController, RegisterController};
use App\Http\Controllers\Simple\{DashboardController, ProfileController, DeviceViewController, ExperienceController};
use App\Http\Controllers\Complex\{DeviceManagementController};
use App\Http\Controllers\Admin\{AdminDashboardController, AdminUserController, DeviceModerationController};
use App\Http\Controllers\UserProfileController;

// ── MODULE PUBLIC (Visiteurs) ─────────────────────────────────────────────
Route::prefix('')->name('public.')->group(function () {
    Route::get('/', [PublicHomeController::class, 'index'])->name('home');
    Route::get('/visite-guidee', [FreeTourController::class, 'index'])->name('tour.index');
    Route::get('/visite-guidee/{step}', [FreeTourController::class, 'show'])->name('tour.show');
    Route::get('/actualites', [NewsController::class, 'index'])->name('news.index');
    Route::get('/actualites/{slug}', [NewsController::class, 'show'])->name('news.show');
    Route::get('/inscription', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/connexion', [LoginController::class, 'showForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login'])->name('login.post');
    Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');
    Route::get('/deconnexion', function() { return redirect()->route('public.home');})->name('logout.get');});

// ── MODULE SIMPLE (Utilisateurs simples approuvés) ────────────────────────
Route::middleware(['auth', 'role:simple,complex,admin', 'track.login'])->prefix('espace')->name('simple.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/modifier', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profil/{pseudo}', [ProfileController::class, 'showPublic'])->name('profile.public');
    Route::get('/objets', [DeviceViewController::class, 'index'])->name('devices.index');
    Route::get('/objets/{device}', [DeviceViewController::class, 'show'])->middleware('track.device.view')->name('devices.show');
    Route::get('/experience', [ExperienceController::class, 'index'])->name('xp.index');
    Route::get('/utilisateurs', [UserProfileController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/{user}', [UserProfileController::class, 'show'])->name('users.show');
});

// ── MODULE COMPLEXE (Avancé/Expert uniquement) ────────────────────────────
Route::middleware(['auth', 'role:complex,admin', 'level:advanced', 'track.login'])->prefix('gestion')->name('complex.')->group(function () {
    Route::get('/objets', [DeviceManagementController::class, 'index'])->name('devices.index');
    Route::get('/objets/creer', [DeviceManagementController::class, 'create'])->name('devices.create');
    Route::post('/objets', [DeviceManagementController::class, 'store'])->name('devices.store');
    Route::get('/objets/{device}', [DeviceManagementController::class, 'show'])->name('devices.show');
    Route::get('/objets/{device}/modifier', [DeviceManagementController::class, 'edit'])->name('devices.edit');
    Route::put('/objets/{device}', [DeviceManagementController::class, 'update'])->name('devices.update');
    Route::delete('/objets/{device}', [DeviceManagementController::class, 'destroy'])->name('devices.destroy');
    Route::post('/objets/{device}/controle', [DeviceManagementController::class, 'control'])->name('devices.control');
    Route::put('/objets/{device}/zone', [DeviceManagementController::class, 'assignZone'])->name('devices.assign-zone');
    Route::post('/devices/{device}/request-delete', [DeviceViewController::class, 'requestDelete'])->name('devices.request-delete');
    // --- ROUTES PLACEHOLDERS (En construction) ---
    Route::get('/rapports', function() { return '<div style="text-align:center; padding:100px; font-family:sans-serif;"><h1>🚧 Module Rapports</h1><p>Prévu pour le Sprint 2.</p><a href="/gestion/objets">Retour</a></div>'; })->name('reports.index');
    Route::get('/zones', function() { return '<div style="text-align:center; padding:100px; font-family:sans-serif;"><h1>🚧 Gestion des Zones</h1><p>Prévu pour le Sprint 2.</p><a href="/gestion/objets">Retour</a></div>'; })->name('zones.index');
});

// ── MODULE ADMINISTRATION (Admin + Expert) ────────────────────────────────
Route::middleware(['auth', 'role:admin', 'level:expert', 'track.login'])->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/utilisateurs', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/utilisateurs/en-attente', [AdminUserController::class, 'pending'])->name('users.pending');
    Route::post('/utilisateurs/{user}/approuver', [AdminUserController::class, 'approve'])->name('users.approve');
    Route::post('/utilisateurs/{user}/xp', [AdminUserController::class, 'adjustXp'])->name('users.xp');
    Route::get('/utilisateurs/{user}/profil', [UserProfileController::class, 'show'])->name('users.show');
    Route::get('/objets/suppressions', [DeviceModerationController::class, 'deletionRequests'])->name('devices.deletion-requests');
    Route::post('/objets/{device}/approve-delete', [DeviceModerationController::class, 'approveDelete'])->name('devices.approve-delete');
    Route::post('/objets/{device}/reject-delete', [DeviceModerationController::class, 'rejectDelete'])->name('devices.reject-delete');

    // --- ROUTES PLACEHOLDERS (En construction) ---
    Route::get('/categories', function() { return '<div style="text-align:center; padding:100px; font-family:sans-serif;"><h1>🚧 Gestion des Catégories</h1><p>Prévu pour le Sprint 2.</p><a href="/administration">Retour</a></div>'; })->name('categories.index');
    Route::get('/zones', function() { return '<div style="text-align:center; padding:100px; font-family:sans-serif;"><h1>🚧 Administration des Zones</h1><p>Prévu pour le Sprint 2.</p><a href="/administration">Retour</a></div>'; })->name('zones.index');
    Route::get('/integrite', function() { return '<div style="text-align:center; padding:100px; font-family:sans-serif;"><h1>🚧 Intégrité des données</h1><p>Prévu pour le Sprint 2.</p><a href="/administration">Retour</a></div>'; })->name('integrity.index');
});

Route::get('/login', function () {
    return redirect()->route('public.login');
})->name('login');
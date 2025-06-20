<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashLecteursController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\NoticeExemplaireController;
use App\Http\Controllers\PretController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\LecteurController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CatalogueController; 
use App\Http\Controllers\DemandepretController;
use App\Http\Controllers\PretEmployerController;
use App\Http\Controllers\ReturnedPretController;

// Home page
Route::get('/', function () {
    return view('auth.login');
});


Route::post('/login/admin', [LoginController::class, 'adminLogin'])->name('login.admin');
Route::post('/login/employer', [LoginController::class, 'employerLogin'])->name('login.employer');
Route::post('/login/lecteur', [LoginController::class, 'lecteurLogin'])->name('login.lecteur');
// Authentication routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Registration routes
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);

// Password reset routes
Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// ADMIN DASHBOARD ROUTES
// Main dashboard route
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// Additional dashboard routes
Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/history', [DashboardController::class, 'history'])->name('dashboard.history');
Route::get('/dashboard/search', [DashboardController::class, 'search'])->name('dashboard.search');
Route::get('/dashboard/loans', [DashboardController::class, 'loans'])->name('dashboard.loans');
Route::get('/dashboard/profile', [DashboardController::class, 'profile'])->name('dashboard.profile');
Route::get('/dashboard/stats', [StatsController::class, 'statistics'])->name('dashboard.stats');
// Removed this redundant /catalog route, as it's defined below under CatalogueController
Route::get('/dashboard/users', [DashboardController::class, 'users'])->name('dashboard.users');
Route::get('/dashboard/returns', [DashboardController::class, 'returns'])->name('dashboard.returns');
Route::get('/dashboard/renewals', [DashboardController::class, 'renewals'])->name('dashboard.renewals');

// Put this BEFORE the resource route
Route::get('/lecteurs/dashboard', [LecteurController::class, 'dashboard'])->name('lecteurs.dashboard');

Route::get('/lecteur/profile', [LecteurController::class, 'profile'])->name('lecteur.profile');
Route::get('/lecteur/history', [LecteurController::class, 'history'])->name('lecteur.history');
Route::get('/lecteur/new-pret', [LecteurController::class, 'showNewPretForm'])->name('lecteur.new-pret');
// Resource routes
Route::resource('lecteurs', LecteurController::class);
Route::resource('notices', NoticeController::class);
Route::resource('notice-exemplaires', NoticeExemplaireController::class);
Route::resource('prets', PretController::class);
Route::resource('statistiques', StatsController::class);
Route::resource('lecteurs', LecteurController::class);


// Demand routes
Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
Route::patch('/demandes/{id}/statut', [DemandeController::class, 'updateStatut'])->name('demandes.updateStatut');

// Loan routes
Route::get('/loans', [PretController::class, 'index'])->name('loans');
Route::get('/loans/{pret}/edit', [PretController::class, 'edit'])->name('loans.edit');
Route::get('/returns', [PretController::class, 'returns'])->name('returns');

// Renewal routes
Route::get('/renewals', [RenewalController::class, 'index'])->name('renewals');// or whatever method you need
// Renewals routes
    Route::get('/', [RenewalController::class, 'index'])->name('index');
    
    // Process renewal approval
 Route::get('/dashboard/renewals', [App\Http\Controllers\DashboardController::class, 'renewals'])->name('dashboard.renewals');
// Renewal action routes - Updated to POST for data modification
    Route::match(['get', 'post'], '/dashboard/renewals/renew/{id}', [RenewalController::class, 'renew'])->name('renewals.renew');

    Route::post('/dashboard/renewals/reject/{id}', [RenewalController::class, 'reject'])->name('renewals.reject');
   
// Check if a book can be renewed (AJAX)
    Route::get('/check/{id}', [RenewalController::class, 'checkRenewability'])->name('check');
    
    // Get renewal statistics
    Route::get('/statistics', [RenewalController::class, 'statistics'])->name('statistics');





// Statistics route
Route::get('/stats', [StatsController::class, 'index'])->name('stats');

Route::get('/history', function () {
    return 'Historique des prêts';
})->name('history');
Route::get('/settings', function () {
    return 'settings';
})->name('settings');
Route::get('/requests', [DemandeController::class, 'index'])->name('requests');

Route::get('/users', [LecteurController::class, 'index'])->name('users');

Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
Route::get('/catalogue/{cat_id}', [CatalogueController::class, 'showSubcategories'])->name('catalogue.subcategories');
Route::get('/catalogue/{cat_id}/notices', [CatalogueController::class, 'showNotices'])->name('catalogue.notices');

Route::get('/catalogue/suggestions', [CategoryController::class, 'suggestions'])->name('catalogue.suggestions');
//lecteur routes

Route::get('/lecteur/pret', [LecteurController::class, 'pret'])->name('lecteur.pret');



Route::post('/prets', [PretController::class, 'store'])->name('prets.store');


Route::get('/NoticeExemplaire/search', [DashLecteursController::class, 'search'])->name('notices.search');
Route::get('/notices/search', [NoticeController::class, 'search']);
Route::post('/demandes', [DemandepretController::class, 'store'])->name('demandes.store');
Route::post('/prets', [PretController::class, 'store'])->name('prets.store');


// Demand routes
Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
Route::patch('/demandes/{id}/statut', [DemandeController::class, 'updateStatut'])->name('demandes.updateStatut');
Route::post('/demandes', [DemandepretController::class, 'store'])->name('demandes.store');
Route::get('/lecteurs/dashboard', [LecteurController::class, 'dashboard'])->name('lecteurs.dashboard');
Route::post('/demandes/renouveler', [DemandepretController::class, 'renouveler'])->name('demandes.renouveler');


Route::get('/lecteur/profile', [LecteurController::class, 'profile'])->name('lecteur.profile');
Route::get('/lecteur/history', [LecteurController::class, 'history'])->name('lecteur.history');
Route::post('/lecteur/retour', [LecteurController::class, 'retourner'])->name('lecteur.retour');
Route::post('/lecteur/prolongation', [LecteurController::class, 'prolongation'])->name('lecteur.prolongation');
Route::get('/profile/edit', [LecteurController::class, 'editProfile'])->name('profile.edit');
Route::put('/profile/edit/{lecteur}', [LecteurController::class, 'update'])->name('profile.update');




// Stats dashboard
Route::get('/dashboard/stats', [StatsController::class, 'index'])->name('dashboard.stats');
Route::get('/dashboard/stats', [StatsController::class, 'filter'])->name('dashboard.stats');

// Report generation
Route::get('/prets/rapport', [StatsController::class, 'rapport'])->name('prets.rapport');

// AJAX endpoint for filtering loans by period
Route::post('/dashboard/stats/filter', [StatsController::class, 'filterByPeriod'])->name('dashboard.stats.filter');

use App\Http\Controllers\SearchController;

Route::get('/search', [SearchController::class, 'search'])->name('search');


Route::get('/pret-employers/create', [PretEmployerController::class, 'create'])->name('dashboard.create');
Route::put('/pret-employers/{id}/return', [PretEmployerController::class, 'returnPret'])->name('dashboard.return');
Route::post('/pret-employers', [PretEmployerController::class, 'store'])->name('pret_employers.store');
Route::get('/pret-employers', [PretEmployerController::class, 'show'])->name('pret_employers.show');




Route::get('stats/filter/{period}', [StatsController::class, 'filter']);


Route::get('/search', [SearchController::class, 'search'])->name('search');

Route::get('/lecteurs/category/{category}', [LecteurController::class, 'showCategory'])->name('lecteurs.category');

Route::get('/notices/search', [App\Http\Controllers\NoticeController::class, 'search'])->name('notices.search');


Route::get('prets/returned/mark/{id}', [ReturnedPretController::class, 'returned'])->name('prets.returned.mark');

Route::get('/prets', [PretController::class, 'index'])->name('prets.index');
Route::put('/prets/{pret}/returned', [PretController::class, 'markReturned'])->name('prets.returned');


Route::get('/prets/returned', [PretsController::class, 'returned'])->name('prets.returned');

// Replace all the above with just these two:
Route::put('/prets/{pret}/return', [PretController::class, 'markReturned'])->name('prets.return');
Route::put('/prets/{pret}/returned', [PretController::class, 'Returned'])->name('prets.returned');

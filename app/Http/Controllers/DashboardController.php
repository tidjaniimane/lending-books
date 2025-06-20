<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecteur;
use App\Models\Pret;
use App\Models\Notice;
use App\Models\NoticeExemplaire;
use App\Models\DemandePret;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the admin dashboard homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        // Count stats for dashboard
        $totalLecteurs = Lecteur::count();
        $totalNotices = Notice::count();
        $totalExemplaires = NoticeExemplaire::count();
        $pretsActifs = Pret::whereIn('statut', ['en_cours', 'en_retard', 'renouvele'])->count();
        $pretsEnRetard = Pret::where('statut', 'en_retard')->count();
        $demandesEnAttente = DemandePret::where('statut', 'en_attente')->count();
        
        // Recent activities
        $derniersPretsRetournes = Pret::with('exemplaire.notice', 'lecteur')
                                     ->where('statut', 'retourne')
                                     ->orderBy('date_retour', 'desc')
                                     ->limit(5)
                                     ->get();
                                     
        $derniersPretsEmpruntes = Pret::with('exemplaire.notice', 'lecteur')
                                     ->whereIn('statut', ['en_cours', 'renouvele'])
                                     ->orderBy('date_pret', 'desc')
                                     ->limit(5)
                                     ->get();
        
        return view('dashboard.index', compact('totalLecteurs', 'totalNotices', 'totalExemplaires', 
                                            'pretsActifs', 'pretsEnRetard', 'demandesEnAttente',
                                            'derniersPretsRetournes', 'derniersPretsEmpruntes'));
    }

    /**
     * Show the admin catalog management.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function catalogue()
    {
        $notices = Notice::with('exemplaires')->orderBy('notice_titre')->paginate(15);
        return view('dashboard.catalogue', compact('notices'));
    }

    /**
     * Show the admin loans management.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function loans()
    {
        $prets = Pret::with('exemplaire.notice', 'lecteur')
                    ->orderBy('date_pret', 'desc')
                    ->paginate(15);
        return view('dashboard.loans', compact('prets'));
    }

    /**
     * Show the admin profile page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }

    /**
     * Show the loan renewals page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function renewals()
    {
        // Get all renewal requests from demandes_pret table with status 'renouveler'
        // and join with pret table to get related loan information
        $renewals = DB::table('demandes_pret')
            ->join('prets', function($join) {
                $join->on('demandes_pret.lec_id', '=', 'prets.lec_id')
                    ->on('demandes_pret.exp_id', '=', 'prets.exp_id');
            })
            ->join('lecteurs', 'demandes_pret.lec_id', '=', 'lecteurs.lec_id')
            ->join('notice_exemplaires', 'demandes_pret.exp_id', '=', 'notice_exemplaires.exp_id')
            ->join('notices', 'notice_exemplaires.doc_id', '=', 'notices.doc_id')
            ->select(
                'prets.pret_id',
                'prets.date_pret',
                'prets.date_retour',
                'lecteurs.lec_nom',
                'lecteurs.lec_prenom',
                'notices.doc_titre',
                'demandes_pret.id as demande_id'
            )
            ->where('demandes_pret.statu', '=', 'renouveler')
            ->get();
      
        return view('dashboard/renewals', compact('renewals'));
    }

    /**
     * Show the loan requests page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function requests()
    {
        $requests = DemandePret::with('notice', 'lecteur')
                              ->where('statut', 'en_attente')
                              ->where('demandes_pret.statu', '=', 'pret')
                              ->orderBy('date_demande', 'desc')
                              ->paginate(15);
        return view('dashboard.requests', compact('requests'));
    }

    /**
     * Show the returns page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function returns()
    {
        $returns = Pret::with('exemplaire.notice', 'lecteur')
                      ->where('statut', 'retourne')
                      ->orderBy('date_retour', 'desc')
                      ->paginate(15);
        return view('dashboard.returns', compact('returns'));
    }

    /**
     * Show the search page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function search()
    {
        return view('dashboard.search');
    }

    /**
     * Show the statistics page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function stats()
    {
        // Statistics calculations
        $totalLoans = Pret::count();
        $activeLoans = Pret::whereIn('statut', ['en_cours', 'en_retard', 'renouvele'])->count();
        $returnedLoans = Pret::where('statut', 'retourne')->count();
        $overdueLoans = Pret::where('statut', 'en_retard')->count();
        
        // Monthly stats
        $monthlyLoans = Pret::selectRaw('MONTH(date_pret) as month, COUNT(*) as count')
                            ->whereYear('date_pret', date('Y'))
                            ->groupBy('month')
                            ->orderBy('month')
                            ->get();
        
        return view('dashboard.stats', compact('totalLoans', 'activeLoans', 'returnedLoans', 
                                             'overdueLoans', 'monthlyLoans'));
    }

    /**
     * Show the users management page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function users()
    {
        $lecteurs = Lecteur::orderBy('lec_nom')->paginate(15);
        return view('dashboard.users', compact('lecteurs'));
    }

    
}
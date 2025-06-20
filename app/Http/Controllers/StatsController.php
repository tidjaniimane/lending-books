<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pret;
use App\Models\NoticeExemplaire;
use App\Models\Notice;
use App\Models\Categorie;
use App\Models\Recherche;
use App\Models\Statistique;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsController extends Controller
{
    public function index()
    {
        try {
            // Active prets
            $activeprets = Pret::whereIn('statut', ['en_cours', 'renouvele'])->count();
            
            // Delayed prets
            $delayedprets = Pret::where('statut', 'en_retard')->count();
            
            // Available books
            $availableBooks = NoticeExemplaire::whereNotExists(function ($query) {
                $query->select('*')
                      ->from('prets')
                      ->whereColumn('notice_exemplaires.exp_id', 'prets.exp_id')
                      ->whereIn('prets.statut', ['en_cours', 'en_retard']);
            })->count();
            
            // Pret history for the table
            $pretHistory = Pret::with(['lecteur', 'exemplaire.notice.categorie'])
                             ->orderBy('date_pret', 'desc')
                             ->limit(10)
                             ->get();
            
            // Popular books
            $popularBooks = DB::table('prets')
                ->join('notice_exemplaires', 'prets.exp_id', '=', 'notice_exemplaires.exp_id')
                ->join('notices', 'notice_exemplaires.doc_id', '=', 'notices.doc_id')
                ->select('notices.doc_id', 'notices.doc_titre', DB::raw('count(*) as pret_count'))
                ->groupBy('notices.doc_id', 'notices.doc_titre')
                ->orderByDesc('pret_count')
                ->limit(10)
                ->get();
            
            // Categories with pret counts
            $categories = DB::table('categories')
                ->leftJoin('notices', 'categories.cat_id', '=', 'notices.cat_id')
                ->leftJoin('notice_exemplaires', 'notices.doc_id', '=', 'notice_exemplaires.doc_id')
                ->leftJoin('prets', 'notice_exemplaires.exp_id', '=', 'prets.exp_id')
                ->select('categories.cat_id', 'categories.nom', DB::raw('count(prets.pret_id) as pret_count'))
                ->groupBy('categories.cat_id', 'categories.nom')
                ->orderByDesc('pret_count')
                ->get();
            
            // Popular searches
            $popularSearches = DB::table('recherches')
                ->select('query', DB::raw('count(*) as count'))
                ->groupBy('query')
                ->orderByDesc('count')
                ->limit(10)
                ->get();
            
            // Get daily statistics for the Arabic table
            $dailyStats = $this->getDailyStatsForTable('month');
            
            return view('dashboard.stats', compact(
                'activeprets',
                'delayedprets',
                'availableBooks',
                'pretHistory',
                'popularBooks',
                'categories',
                'popularSearches',
                'dailyStats'
            ));
        } catch (\Exception $e) {
            \Log::error('Stats Controller Error: ' . $e->getMessage());
            return view('dashboard.stats')->with('error', 'Unable to load statistics');
        }
    }

    /**
     * Get daily statistics for the Arabic statistics table
     */
   private function getDailyStatsForTable($period = 'month')
{
    try {
        $start = now();
        $end = now();

        switch ($period) {
            case 'day':
                $start = now()->startOfDay();
                $end = now()->endOfDay();
                break;
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                break;
        }

        $dailyStats = [];

        // Get all dates in the period
        $periodDates = new \DatePeriod(
            $start,
            new \DateInterval('P1D'),
            $end->copy()->addDay()
        );

        foreach ($periodDates as $date) {
            $dateStr = $date->format('Y-m-d');

            // Total requests from 'prets' table
            $totalRequestsPrets = Pret::whereDate('date_pret', $dateStr)->count();

            // Total requests from 'pret_employers' table
            $totalRequestsEmployers = \DB::table('pret_employers')->whereDate('created_at', $dateStr)->count();

            $totalRequests = $totalRequestsPrets + $totalRequestsEmployers;

            // Requests by category:
            // Readers (is_admin = 0) in 'prets'
            $readerRequests = Pret::whereDate('date_pret', $dateStr)
                ->whereHas('lecteur', fn($q) => $q->where('is_admin', 0))
                ->count();

            // Staff in 'prets' with is_admin=2
            $staffRequestsPrets = Pret::whereDate('date_pret', $dateStr)
                ->whereHas('lecteur', fn($q) => $q->where('is_admin', 2))
                ->count();

            // Staff requests from 'pret_employers' table
            $staffRequestsEmployers = \DB::table('pret_employers')->whereDate('created_at', $dateStr)->count();

            $staffRequests = $staffRequestsPrets + $staffRequestsEmployers;

            // Approved requests by category in 'prets' (statut != 'refuse')
            $approvedReaders = Pret::whereDate('date_pret', $dateStr)
                ->where('statut', '!=', 'refuse')
                ->whereHas('lecteur', fn($q) => $q->where('is_admin', 0))
                ->count();

            $approvedEmployeesPrets = Pret::whereDate('date_pret', $dateStr)
                ->where('statut', '!=', 'refuse')
                ->whereHas('lecteur', fn($q) => $q->where('is_admin', 2))
                ->count();

            // Assuming all 'pret_employers' requests are approved (adjust if you have status)
            $approvedEmployeesEmployers = $totalRequestsEmployers;

            $approvedEmployees = $approvedEmployeesPrets + $approvedEmployeesEmployers;

            // Returned books by category from 'returned' table
            $returnedReaders = \DB::table('returned')
                ->whereDate('created_at', $dateStr)
                ->where('is_admin', 0)
                ->count();

            $returnedEmployees = \DB::table('returned')
                ->whereDate('created_at', $dateStr)
                ->where('is_admin', 2)
                ->count();

            $totalReturned = $returnedReaders + $returnedEmployees;

            // Pending requests from 'demandes_pret' table (statut = 'En attente')
            $pendingRequests = \DB::table('demandes_pret')
                ->where('statut', 'En attente')
                ->whereDate('date_demande', $dateStr)
                ->count();

            // Only add days with some activity
            if ($totalRequests > 0 || $totalReturned > 0 || $pendingRequests > 0) {
                $dailyStats[] = [
                    'date' => $date->format('d/m/Y'),
                    'total_requests' => $totalRequests,
                    'reader_requests' => $readerRequests,
                    'employee_requests' => $staffRequests,
                    'approved_readers' => $approvedReaders,
                    'approved_employees' => $approvedEmployees,
                    'returned_readers' => $returnedReaders,
                    'returned_employees' => $returnedEmployees,
                    'total_returned' => $totalReturned,
                    'pending_requests' => $pendingRequests,
                ];
            }
        }

        return collect($dailyStats)->sortByDesc('date')->take(10);
    } catch (\Exception $e) {
        \Log::error('Daily Stats Error: ' . $e->getMessage());
        return collect([]);
    }
}

    public function rapport(Request $request)
    {
        $debut = $request->input('date_debut', now()->subMonth()->format('Y-m-d'));
        $fin = $request->input('date_fin', now()->format('Y-m-d'));
        
        $stats = [
            'totalPrets' => Pret::whereBetween('date_pret', [$debut, $fin])->count(),
            'pretsEnCours' => Pret::whereIn('statut', ['en_cours', 'renouvele'])->count(),
            'pretsRetournes' => Pret::where('statut', 'retourne')
                                ->whereBetween('date_retour_reelle', [$debut, $fin])
                                ->count(),
            'pretsEnRetard' => Pret::where('statut', 'en_retard')->count(),
            'renouvellements' => Pret::where('statut', 'renouvele')
                                ->whereBetween('updated_at', [$debut, $fin])
                                ->count(),
            'pretEployer' => Pret_employers::whereBetween('Created_at', [$debut, $fin])
                                ->count(),
        ];
        
        $livresPopulaires = Pret::with('exemplaire.notice')
                            ->whereBetween('date_pret', [$debut, $fin])
                            ->get()
                            ->groupBy('exemplaire.doc_id')
                            ->map(function ($group) {
                                return [
                                    'count' => $group->count(),
                                    'notice' => $group->first()->exemplaire->notice
                                ];
                            })
                            ->sortByDesc('count')
                            ->take(5)
                            ->values();
        
        return view('prets.rapport', compact('stats', 'livresPopulaires', 'debut', 'fin'));
    }
    
    /**
     * Record a statistic using the Statistique model
     */
    public function recordStat($type, $increment = 1)
    {
        Statistique::enregistrer($type, $increment);
    }

    public function filter($period)
    {
        try {
            \Log::info("Filter called with period: " . $period);
            
            $dailyStats = $this->getDailyStatsForTable($period);
            \Log::info("Daily stats count: " . $dailyStats->count());
            
            $table_html = view('partials.stats_table', ['dailyStats' => $dailyStats])->render();
            \Log::info("Table HTML generated successfully");
            
            return response()->json([
                'success' => true,
                'table_html' => $table_html,
                'period' => $period,
                'count' => $dailyStats->count()
            ]);
        } catch (\Exception $e) {
            \Log::error('Filter Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error filtering data: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DemandePret; // Make sure this matches your model name exactly
use App\Models\Pret;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RenewalController extends Controller
{
    /**
     * Display a listing of renewal requests
     */
    public function index(Request $request)
{
    $search = $request->input('search');

    $query = DB::table('demandes_pret')
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
        ->where('demandes_pret.statu', '=', 'renouveler');

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('prets.pret_id', 'like', '%' . str_replace('P-', '', $search) . '%')
              ->orWhere('lecteurs.lec_nom', 'like', '%' . $search . '%')
              ->orWhere('lecteurs.lec_prenom', 'like', '%' . $search . '%')
              ->orWhere('notices.doc_titre', 'like', '%' . $search . '%');
        });
    }

    $renewals = $query->get();

    return view('dashboard.renewals', compact('renewals'));
}


    /**
     * Approve a renewal request
     */
    public function renew($id)
    {
        // Start a database transaction to ensure data consistency
        DB::beginTransaction();
        
        try {
            // Find the loan in pret table
            $pret = Pret::findOrFail($id);
            
            // Calculate new return date (extend for 14 days from current return date)
            $newReturnDate = Carbon::parse($pret->date_retour)->addDays(14);
                
            // Update the pret with the new return date
            $pret->date_retour = $newReturnDate;
            $pret->save();
            
            // Delete the renewal request from demandes_pret table
            DemandePret::where('lec_id', $pret->lec_id)
               ->where('exp_id', $pret->exp_id)
               ->where('statu', 'renouveler')
               ->delete();
            
            DB::commit();
                
            return redirect()->route('dashboard.renewals')
                ->with('success', 'Renouvellement approuvé avec succès. La date de retour a été prolongée de 14 jours.');
        } catch (\Exception $e) {
            DB::rollBack();
                
            return redirect()->route('dashboard.renewals')
                ->with('error', 'Une erreur est survenue lors du renouvellement: ' . $e->getMessage());
        }
    }

    /**
     * Reject a renewal request
     */
    public function reject($id)
    {
        try {
            $pret = Pret::findOrFail($id);
            // Delete the renewal request from demandes_pret table without modifying the pret
             DemandePret::where('lec_id', $pret->lec_id)
               ->where('exp_id', $pret->exp_id)
               ->where('statu', 'renouveler')
               ->delete();
                
            return redirect()->route('dashboard.renewals')
                ->with('success', 'Demande de renouvellement rejetée avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('dashboard.renewals')
                ->with('error', 'Une erreur est survenue lors du rejet de la demande: ' . $e->getMessage());
        }
    }
}
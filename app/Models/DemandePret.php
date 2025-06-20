<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandePret extends Model
{
    use HasFactory;

    /**
     * Le nom de la table associée au modèle.
     *
     * @var string
     */
    protected $table = 'demandes_pret';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lec_id',
        'doc_id',
        'exp_id',
        'date_demande',
        'statut',
        'motif_refus',
        'pret_id', 
        'statu' ,
           
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_demande' => 'date',
    ];

    /**
     * Récupère le lecteur associé à cette demande.
     */
    
    public function lecteur() {
        return $this->belongsTo(Lecteur::class, 'lec_id');
    }
    
    public function notice() {
        return $this->belongsTo(Notice::class, 'doc_id');
    }
    
    /**
     * Récupère la notice (livre) associée à cette demande.
     */
   

    /**
     * Accepte la demande de prêt et crée un nouveau prêt.
     */
    public function accepter()
    {
        $this->statut = 'acceptee';
        $this->save();
        
        // Cherche un exemplaire disponible
        $exemplaire = $this->notice->getExemplaireDisponible();
        
        if (!$exemplaire) {
            return false; // Aucun exemplaire disponible
        }
        
        // Marque l'exemplaire comme emprunté
        $exemplaire->etat = 'emprunte';
        $exemplaire->save();
        
        // Crée un nouveau prêt
        return Pret::create([
            'lec_id' => $this->lec_id,
            'exp_id' => $exemplaire->exp_id,
            'date_pret' => now(),
            'date_retour' => now()->addDays(14),
            'date_reservation' => $this->date_demande,
            'statut' => 'en_cours'
        ]);
    }

    /**
     * Refuse la demande de prêt.
     */
    public function refuser($motif = null)
    {
        $this->statut = 'refusee';
        $this->motif_refus = $motif;
        return $this->save();
    }
    public function NoticeExemplaire()
{
    return $this->belongsTo(NoticeExemplaire::class, 'exp_id');
}





}

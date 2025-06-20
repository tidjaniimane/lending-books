<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Pret extends Model
{
    use HasFactory;

    /**
     * La clé primaire associée à la table.
     *
     * @var string
     */
    protected $primaryKey = 'pret_id';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lec_id',
        'exp_id',
        'date_pret',
        'date_retour',
        'date_retour_reelle',
        'date_reservation',
        'statut',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_pret' => 'date',
        'date_retour' => 'date',
        'date_retour_reelle' => 'date',
        'date_reservation' => 'date',
    ];

    
    /**
     * Récupère le lecteur associé à ce prêt.
     */
    public function lecteur()
    {
        return $this->belongsTo(Lecteur::class, 'lec_id', 'lec_id');
    }

    /**
     * Récupère l'exemplaire associé à ce prêt.
     */
    public function exemplaire()
    {
        return $this->belongsTo(NoticeExemplaire::class, 'exp_id', 'exp_id');
    }

    
    /**
     * Vérifie si le prêt est en retard.
     */
    public function estEnRetard()
    {
        return !$this->date_retour && Carbon::now()->greaterThan($this->date_retour);
    }

    /**
     * Renouvele un prêt en ajoutant 14 jours à la date de retour.
     * Utilise une transaction pour assurer l'intégrité des données.
     */
    public function renouveler()
    {
        // Vérifie que le prêt n'a pas déjà été retourné
        if ($this->date_retour) {
            throw new \Exception('Le prêt a déjà été retourné.');
        }

        // Commence une transaction pour assurer l'intégrité des données
        return DB::transaction(function () {
            $this->date_retour = Carbon::parse($this->date_retour)->addDays(14);
            $this->statut = 'renouvele';
            $this->save();

            // Ajouter plus de logique si nécessaire, comme notifier l'utilisateur ou enregistrer des logs

            return $this;
        });
    }

    /**
     * Marque le prêt comme retourné.
     * Utilise une transaction pour assurer l'intégrité des données.
     */
    public function retourner()
    {
        // Commence une transaction pour assurer l'intégrité des données
        return DB::transaction(function () {
            $this->date_retour = Carbon::now();
            $this->statut = 'retourne';
            $this->save();

            // Met à jour l'état de l'exemplaire
            $exemplaire = $this->exemplaire;
            $exemplaire->etat = 'disponible';
            $exemplaire->save();

            return $this;
        });
    }

    /**
     * Accesseur pour obtenir le livre (notice) associé au prêt.
     */
    public function getLivreAttribute()
    {
        return $this->exemplaire->notice ?? null;
    }

    /**
     * Met à jour le statut des prêts en retard.
     */
    public static function updateStatutsRetard()
{
    $today = Carbon::today();

    // Find loans where the return date is in the past and still not returned
    $pretsEnRetard = self::whereNull('date_retour') 
        ->where('date_retour', '<', $today) // This is the due date
        ->where('statut', '!=', 'en_retard')
        ->get();

    foreach ($pretsEnRetard as $pret) {
        $prets->statut = 'en_retard';
        $prets->save();
    }
}

    public function livre()
{
    return $this->belongsTo(Notice::class, 'notice_id'); // ou 'livre_id' selon ta base
}
public function noticeExemplaire() {
    return $this->belongsTo(NoticeExemplaire::class, 'notice_exemplaire_id');
}
public function notices() {
    return $this->hasMany(Notice::class); // ajuste selon ta clé étrangère
}
public function notice()
{
    return $this->belongsTo(Notice::class, 'exp_id', 'doc_id');
}

    public function demandesRenouvellement()
    {
        return $this->hasMany(DemandesPret::class, 'pret_id');
    }
    
}



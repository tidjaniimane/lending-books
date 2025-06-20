<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    /**
     * La clé primaire associée à la table.
     *
     * @var string
     */
    protected $primaryKey = 'doc_id';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doc_titre',
        'doc_auteur',
        'isbn',
        'annee_publication',
        'description',
        'editeur',
    ];

    /**
     * Récupère tous les exemplaires de cette notice.
     */
    public function exemplaires()
    {
        return $this->hasMany(NoticeExemplaire::class, 'doc_id', 'doc_id');
    }

    /**
     * Récupère toutes les demandes de prêt pour cette notice.
     */
    public function demandesPret()
    {
        return $this->hasMany(DemandePret::class, 'doc_id', 'doc_id');
    }

    /**
     * Calcule le nombre d'exemplaires disponibles pour cette notice.
     */
    public function nombreExemplairesDisponibles()
    {
        return $this->exemplaires()->where('etat', 'disponible')->count();
    }

    /**
     * Obtient un exemplaire disponible de cette notice.
     */
    public function getExemplaireDisponible()
    {
        return $this->exemplaires()->where('etat', 'disponible')->first();
    }
    
     public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'cat_id', 'cat_id');
    }
     // Define the category this notice belongs to
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id', 'cat_id');
    }
}

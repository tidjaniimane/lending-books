<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticeExemplaire extends Model
{
    use HasFactory;

    protected $primaryKey = 'exp_id';

    protected $fillable = [
        'doc_id',
        'exp_cote',
        'etat',
    ];

    public function prets()
    {
        return $this->hasMany(Pret::class, 'exp_id', 'exp_id');
    }
public function pret()
{
    return $this->hasOne(Pret::class, 'exp_id', 'exp_id');
}

    public function notice()
    {
        return $this->belongsTo(Notice::class, 'doc_id', 'doc_id');
    }
    

    public function pretActuel()
    {
        return $this->hasOne(Pret::class, 'exp_id', 'exp_id')
                    ->whereIn('statut', ['en_cours', 'en_retard', 'renouvele']);
    }

    public function historiquePrets()
    {
        return $this->hasMany(Pret::class, 'exp_id', 'exp_id');
    }

    public function estDisponible()
    {
        return $this->etat === 'disponible';
    }
}

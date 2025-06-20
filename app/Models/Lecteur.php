<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Use Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Lecteur extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'lecteurs';
    protected $primaryKey = 'lec_id';
    public $incrementing = true;
    protected $keyType = 'int';  // ← et ceci

    protected $fillable = [
        'lec_nom', 
        'lec_prenom',
        'lec_adress', 
        'lec_tel', 
        'lec_email', 
        'lec_password', 
        'is_admin',
    ];

    protected $hidden = [
        'lec_password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'lec_password' => 'hashed',
    ];

    public function getRouteKeyName()
    {
        return 'lec_id';
    }
    
    public function getAuthIdentifierName()
    {
        return 'lec_id';
    }

    public function getAuthPassword()
    {
        return $this->lec_password;
    }

    public function prets()
    {
        return $this->hasMany(Pret::class, 'lec_id', 'lec_id');
    }

    public function demandesPret()
    {
        return $this->hasMany(DemandePret::class, 'lec_id', 'lec_id');
    }

    public function getNomCompletAttribute()
    {
        return "{$this->lec_prenom} {$this->lec_nom}";
    }
    
    public function employe()
    {
        return $this->belongsTo(Employe::class, 'emp_id', 'emp_id');
    }
    /**
     * Scope for reader type lecteurs
     */
    public function scopeLecteurs($query)
    {
        return $query->where('is_admin', 0);
    }

    /**
     * Scope for employee type lecteurs
     */
    public function scopeEmployes($query)
    {
        return $query->where('is_admin', 2);
    }

    /**
     * Get full name attribute
     */
    public function getFullNameAttribute()
    {
        return $this->lec_prenom . ' ' . $this->lec_nom;
    }

    /**
     * Get display name (includes employee info if applicable)
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->full_name;
        
        if ($this->type_lecteur === 'employe' && $this->employe) {
            $name .= ' (' . $this->employe->emp_departement . ')';
        }
        
        return $name;
    }

// In Lecteur.php
public function isAdmin() {
    return $this->is_admin == 1;
}

public function isEmploye() {
    return $this->is_admin == 2;
}
    public function isLecteur() {
        return $this->is_admin == 0;
    }
}

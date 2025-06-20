<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretEmployer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'prenom',
        'doc_titre',
        'exp_id',
        'post',
        'num_tel',
        'status',
    ];
}

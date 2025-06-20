<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistique extends Model
{
    use HasFactory;

    /**
     * La clé primaire associée à la table.
     *
     * @var string
     */
    protected $primaryKey = 'sta_id';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sta_type',
        'sta_nboccurence',
        'sta_date',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'sta_date' => 'date',
    ];

    /**
     * Enregistre ou met à jour une statistique.
     */
    public static function enregistrer($type, $increment = 1)
    {
        $today = now()->format('Y-m-d');
        
        $stat = self::where('sta_type', $type)
                    ->where('sta_date', $today)
                    ->first();
                    
        if ($stat) {
            $stat->sta_nboccurence += $increment;
            $stat->save();
        } else {
            self::create([
                'sta_type' => $type,
                'sta_nboccurence' => $increment,
                'sta_date' => $today
            ]);
        }
    }

    /**
     * Obtient les statistiques par période.
     */
    public static function getByPeriod($type, $days)
    {
        $startDate = now()->subDays($days);
        
        return self::where('sta_type', $type)
                   ->where('sta_date', '>=', $startDate)
                   ->orderBy('sta_date')
                   ->get();
    }
}

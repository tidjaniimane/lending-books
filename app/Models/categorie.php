<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $primaryKey = 'cat_id';
    public $incrementing = false;
    protected $keyType = 'integer';
    protected $fillable = [
       'nom', // based on the SQL query showing "order by nom asc"
        'parent_id',
        // add other fillable fields as needed
    ];


   public function parent()
    {
        return $this->belongsTo(Categorie::class, 'parent_id', 'cat_id');
    }

    public function children()
    {
        return $this->hasMany(Categorie::class, 'parent_id', 'cat_id');
    }
    public function notices()
    {
        return $this->hasMany(Notice::class, 'cat_id', 'cat_id');
    
    }

     // Specify the primary key since it's not 'id'
 
    
    // Specify the table name if it's not the plural of the model name
    protected $table = 'categories';

   public function subcategories()
    {
        return $this->hasMany(Categorie::class, 'parent_id', 'cat_id');
    }

    /**
     * Get the parent category.
     */
    
}

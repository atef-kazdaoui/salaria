<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;
use App\Models\Category;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nom',
        'type',
        'chemin_fichier',
    ];

    // Ajout du chiffrement sur le champ chemin_fichier
    public function setCheminFichierAttribute($value)
    {
        $this->attributes['chemin_fichier'] = Crypt::encryptString($value);
    }

    public function getCheminFichierAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Relation : un document appartient à un utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

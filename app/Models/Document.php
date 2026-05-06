<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'platform_id', 'cree_par', 'titre',
        'statut', 'version', 'chemin', 'cree_le'
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id');
    }

    public function createur()
    {
        return $this->belongsTo(Utilisateur::class, 'cree_par');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'document_id');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'document_id');
    }
}
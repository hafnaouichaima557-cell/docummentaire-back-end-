<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Utilisateur extends Authenticatable
{
    use HasApiTokens;

    protected $authPassword = 'motdepasse'; // ← ajoute cette ligne

    protected $fillable = [
        'equipe_id', 'nom', 'email', 
        'tel', 'motdepasse', 'avatar', 'statut'
    ];

    protected $hidden = ['motdepasse', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->motdepasse;
    }

    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'utilisateur_role');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'cree_par');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'utilisateur_id');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'utilisateur_id');
    }
}
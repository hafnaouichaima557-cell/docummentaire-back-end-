<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Equipe extends Model
{
    protected $fillable = ['nom', 'description'];

    public function utilisateurs()
    {
        return $this->hasMany(Utilisateur::class, 'equipe_id');
    }

    public function platforms()
    {
        return $this->hasMany(Platform::class, 'equipe_id');
    }
}
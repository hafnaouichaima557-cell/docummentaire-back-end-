<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Historique extends Model
{
    protected $table = 'historique'; // ← ajoute ici

    protected $fillable = [
        'utilisateur_id', 'document_id', 'notification_id',
        'role_id', 'action', 'table_concernee',
        'valeur_avant', 'valeur_apres', 'date_heure'
    ];

    protected $casts = [
        'valeur_avant' => 'array',
        'valeur_apres' => 'array',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'utilisateur_id', 'document_id', 'role_id',
        'message', 'type', 'lue', 'date_envoi'
    ];

    public function utilisateur()
    {
        return $this->belongsTo(Utilisateur::class, 'utilisateur_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function historiques()
    {
        return $this->hasMany(Historique::class, 'notification_id');
    }
}
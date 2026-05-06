<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Platform extends Model
{
    protected $fillable = ['equipe_id', 'nom', 'description', 'url'];

    public function equipe()
    {
        return $this->belongsTo(Equipe::class, 'equipe_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'platform_id');
    }
}
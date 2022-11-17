<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuperAdmin extends Model
{
    use HasFactory;
    protected $table = "user";
    protected $fillable = [
        'id_user',
        'nama',
        'username',
        'password',
        'foto',
        'level'
    ];

    // public function level_user(): BelongsTo
    // {
    //     return $this->belongsTo()
    // }
}

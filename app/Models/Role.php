<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'deleted_at'];

    // Relaciones

    // Muchos a muchos
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

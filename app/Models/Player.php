<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $table = 'players';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'club',
        'email',
        'position',
        'age',
        'salary',
        'created_at',
        'updated_at'
    ];
}

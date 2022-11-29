<?php

namespace App\Models;

use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use TraitUuid, HasFactory;

    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'name' => 'string'
    ];
}

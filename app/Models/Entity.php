<?php

namespace App\Models;

use App\Casts\Base64;
use App\Casts\Json;
use App\Traits\TraitUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use TraitUuid, HasFactory;
    use SoftDeletes;

    protected $table = 'entities';
    //protected $hidden = ['id', 'created_at', 'updated_at'];

    protected $fillable = [
        'id',
        'code',
        'divisions',
        'group',
        'attribute_values'
    ];

    protected $casts = [
        'id' => 'string',
        'attribute_values' => 'array',
        'code' => Base64::class,
        'division' => 'array',
        'group' => 'string'
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group', 'id');
    }

    public function divisions(): BelongsTo
    {
        return $this->belongsTo(Division::class, 'divisions', 'id');
    }
}

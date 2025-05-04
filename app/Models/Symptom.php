<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Symptom extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'code',
        'description',
        'weight',
        'disease_id',
    ];

    public function disease()
    {
        return $this->belongsTo(Disease::class);
    }
}

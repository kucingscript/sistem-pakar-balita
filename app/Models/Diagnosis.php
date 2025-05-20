<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosis extends Model
{
    use HasFactory, SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $cast = ['symptoms' => 'array'];

    protected $fillable = ['patient_name', 'result_disease', 'result_percentage', 'result_treatment', 'symptoms'];
}

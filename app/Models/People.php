<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class People extends Model
{
    protected $fillable = ['name','lastname','email','phone',
                           'legalperson', 'cpf', 'cnpj'];
    public $timestamps = false;

}

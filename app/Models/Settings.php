<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $table = 'settings';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['name','mobile','email','address','city','state','zip','country','fcm_token','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}

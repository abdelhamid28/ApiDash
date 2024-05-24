<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terms extends Model
{
    use HasFactory;

    protected $table = 'terms';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['name','cover','study_id','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}

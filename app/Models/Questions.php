<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    use HasFactory;

    protected $table = 'exam_question';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['exam_id','questionsList','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}

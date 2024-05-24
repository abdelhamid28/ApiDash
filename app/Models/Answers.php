<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answers extends Model
{
    use HasFactory;

    protected $table = 'answer_key';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['exam_id','keys','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}

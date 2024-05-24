<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptedExam extends Model
{
    use HasFactory;

    protected $table = 'attempted_exam';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['exam_id','uid','gained','total','result','user_answer','real_anwer','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];
}

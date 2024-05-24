<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MyLearning extends Model
{
    use HasFactory;

    protected $table = 'my_learning';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['name','cover','study_id','term_id','subject_id','content','creator_name',
    'creator_phone','creator_position','totalQuestions','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'study_id' => 'integer',
        'term_id' => 'integer',
        'subject_id' => 'integer',
        'totalQuestions' => 'integer',
        'status' => 'integer',
    ];
}

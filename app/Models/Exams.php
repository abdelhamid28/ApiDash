<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    use HasFactory;

    protected $table = 'exam_list';

    public $timestamps = true; //by default timestamp false

    protected $fillable = ['name','cover','study_id','term_id','subject_id','passingMarks','negativeMarks','startTime','endTime','examinerName',
    'examinerPhone','examinerPosition','totalQuestions','haveNegative','status','extra_field'];

    protected $hidden = [
        'updated_at', 'created_at',
    ];

    protected $casts = [
        'study_id' => 'integer',
        'term_id' => 'integer',
        'subject_id' => 'integer',
        'totalQuestions' => 'integer',
        'haveNegative' => 'integer',
        'status' => 'integer',
    ];
}

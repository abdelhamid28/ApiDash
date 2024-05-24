<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AttemptedExam;
use App\Models\Terms;
use App\Models\Studies;
use App\Models\User;
use App\Models\Subjects;
use App\Models\Questions;
use App\Models\Exams;
use Validator;
class AttemptedExamController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'uid' => 'required',
            'gained' => 'required',
            'total' => 'required',
            'result' => 'required',
            'user_answer' => 'required',
            'real_anwer' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = AttemptedExam::create($request->all());
        if (is_null($data)) {
            $response = [
            'data'=>$data,
            'message' => 'error',
            'status' => 500,
        ];
        return response()->json($response, 200);
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = AttemptedExam::find($request->id);

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = AttemptedExam::find($request->id)->update($request->all());

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = AttemptedExam::find($request->id);
        if ($data) {
            $data->delete();
            $response = [
                'data'=>$data,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'success' => false,
            'message' => 'Data not found.',
            'status' => 404
        ];
        return response()->json($response, 404);
    }

    public function getAll(){
        $data = AttemptedExam::all();
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getActive(Request $request){
        $data = AttemptedExam::where('status',1)->get();

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getMyStats(Request $request){
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'uid' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = AttemptedExam::where(['exam_id'=>$request->exam_id,'uid'=>$request->uid])->first();
        $questions = Questions::find($request->exam_id);
        $response = [
            'data'=>$data,
            'question'=>$questions,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getUserInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = User::find($request->id);
        $data->studies = Studies::where(['id'=>$data->study_id])->first();
        $data->terms = Terms::where(['id'=>$data->term_id])->first();
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $attempated = AttemptedExam::select('id','exam_id','uid','gained','total','result','status')->where('uid',$request->id)->get();
        foreach($attempated as $loop){
            $examInfo = Exams::select('id','name','study_id','term_id','subject_id')->where('id',$loop->exam_id)->first();
            $loop->studies = Studies::where(['id'=>$examInfo->study_id])->first();
            $loop->terms = Terms::where(['id'=>$examInfo->term_id])->first();
            $loop->subjects = Subjects::where(['id'=>$examInfo->subject_id])->first();
            $loop->examInfo = $examInfo;
        }
        $response = [
            'data'=>$data,
            'attempated'=>$attempated,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getDashboard(Request $request){
        $exam = Exams::count();
        $users = User::where('type',1)->count();
        $studies = Studies::count();
        $terms = Terms::count();
        $subjects = Subjects::count();
        $recentStudents = User::where('type',1)->limit(10)->orderBy('id','desc')->get();
        $recentExam = Exams::limit(10)->orderBy('id','desc')->get();
        foreach($recentExam as $loop){
            $loop->studies = Studies::where(['id'=>$loop->study_id])->first();
            $loop->terms = Terms::where(['id'=>$loop->term_id])->first();
            $loop->subjects = Subjects::where(['id'=>$loop->subject_id])->first();
        }
        foreach($recentStudents as $loop){
            $loop->studies = Studies::where(['id'=>$loop->study_id])->first();
            $loop->terms = Terms::where(['id'=>$loop->term_id])->first();
        }
        $response = [
            'exam'=>$exam,
            'users'=>$users,
            'studies'=>$studies,
            'terms'=>$terms,
            'subjects'=>$subjects,
            'recentStudents'=>$recentStudents,
            'recentExam'=>$recentExam,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}

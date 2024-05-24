<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Answers;
use App\Models\Exams;
use App\Models\AttemptedExam;
use Validator;
class AnswersController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'exam_id' => 'required',
            'keys' => 'required',
            'status' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Answers::create($request->all());
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

        $data = Answers::find($request->id);

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
        $data = Answers::find($request->id)->update($request->all());

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
        $data = Answers::find($request->id);
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
        $data = Answers::all();
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

    public function getMyResult(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'keys'=> 'required',
            'uid'=> 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = Answers::where('exam_id',$request->id)->first();
        $examValiations = Exams::find($request->id);
        $realAnswer = json_decode($data->keys);
        $userAnswer =json_decode($request->keys);
        $total = 0;
        foreach($realAnswer as $real){
            foreach($userAnswer as $user){
                if($real->id == $user->id){
                    if($real->answer == $user->answer){
                        $total = $total + 1;
                    }else if($real->answer != $user->answer){
                        if($examValiations->haveNegative == 1){
                            $total = $total - $examValiations->negativeMarks;
                        }else{
                            $total = $total - 1;
                        }
                    }
                }
            }
        }

        AttemptedExam::create([
            "exam_id"=>$request->id,
            'uid' => $request->uid,
            'gained' => $total,
            'total' => $examValiations->totalQuestions,
            'result' => $total < $examValiations->passingMarks ? 0 : 1,
            'user_answer' => $request->keys,
            'real_anwer' => $data->keys,
            'status' => 1,
        ]);

        $response = [
            'success' => true,
            'status' => 200,
            'total'=>$total,
            'result' => $total < $examValiations->passingMarks ? 0 : 1,
        ];
        return response()->json($response, 200);
    }
}

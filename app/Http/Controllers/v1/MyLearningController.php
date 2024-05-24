<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MyLearning;
use App\Models\Terms;
use App\Models\Studies;
use App\Models\Subjects;
use Validator;
class MyLearningController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cover' => 'required',
            'study_id' => 'required',
            'term_id' => 'required',
            'subject_id' => 'required',
            'content' => 'required',
            'creator_name' => 'required',
            'creator_phone' => 'required',
            'creator_position' => 'required',
            'totalQuestions' => 'required',
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

        $data = MyLearning::create($request->all());
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

        $data = MyLearning::find($request->id);

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
        $data = MyLearning::find($request->id)->update($request->all());

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
        $data = MyLearning::find($request->id);
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

    public function getAll(Request $request){
        $data = MyLearning::select('id','name','cover','study_id','term_id','subject_id','creator_name','creator_phone','creator_position','totalQuestions','status')->get();
        foreach($data as $loop){
            $loop->studies = Studies::where(['id'=>$loop->study_id])->first();
            $loop->terms = Terms::where(['id'=>$loop->term_id])->first();
            $loop->subjects = Subjects::where(['id'=>$loop->subject_id])->first();
        }
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

    public function getMyLearningList(Request $request){
        $validator = Validator::make($request->all(), [
            'study_id' => 'required',
            'term_id' => 'required',
            'subject_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = MyLearning::select('id','name','cover','study_id','term_id','subject_id','creator_name','creator_phone','creator_position','totalQuestions','status')->where(['study_id'=>$request->study_id,'term_id'=>$request->term_id,'subject_id'=>$request->subject_id,'status'=>1])->get();

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}

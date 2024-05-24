<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exams;
use App\Models\Terms;
use App\Models\Studies;
use App\Models\User;
use App\Models\Subjects;
use App\Models\AttemptedExam;
use Validator;
use DB;
class ExamsController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'cover' => 'required',
            'study_id' => 'required',
            'term_id' => 'required',
            'subject_id' => 'required',
            'passingMarks' => 'required',
            'negativeMarks' => 'required',
            'startTime' => 'required',
            'endTime' => 'required',
            'examinerName' => 'required',
            'examinerPhone' => 'required',
            'examinerPosition' => 'required',
            'totalQuestions' => 'required',
            'haveNegative' => 'required',
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

        $dataExam = Exams::create($request->all());
        if (is_null($dataExam)) {
            $response = [
            'data'=>$dataExam,
            'message' => 'error',
            'status' => 500,
        ];
        return response()->json($response, 200);
        }
        $data = DB::table('settings')
        ->select('*')->first();
        $ids = explode(',',$request->id);
        $allIds = DB::table('users')->select('fcm_token')->get();
        $fcm_ids = array();
        foreach($allIds as $i => $i_value) {
            if($i_value->fcm_token !='NA'){
                array_push($fcm_ids,$i_value->fcm_token);
            }
        }
        $regIdChunk=array_chunk($fcm_ids,1000);
        foreach($regIdChunk as $RegId){
            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: key=' . $data->fcm_token;

            $payload = [
                'registration_ids' => $RegId,
                'priority'=>'high',
                'notification' => [
                'title' => "New Exam Uploaded",
                'body' => $request->name,
                'image'=>$request->cover,
                "sound" => "wave.wav",
                "channelId"=>"fcm_default_channel"
                ],
                'android'=>[
                    'notification'=>[
                        "sound" => "wave.wav",
                        "defaultSound"=>true,
                        "channelId"=>"fcm_default_channel"
                    ]
                ]
            ];

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($crl, CURLOPT_POST,true);
                curl_setopt($crl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($crl, CURLOPT_POSTFIELDS, json_encode( $payload ) );

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true );

            $rest = curl_exec($crl);
            if ($rest === false) {
                return curl_error($crl);
            }
            curl_close($crl);
        }
        $response = [
            'data'=>$dataExam,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function sendNotification(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'descriptions' => 'required',
            'cover' => 'required',
            'study_id' => 'required',
            'term_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = DB::table('settings')
        ->select('*')->first();
        $ids = explode(',',$request->id);
        $allIds = DB::table('users')->where(['study_id'=>$request->study_id,'term_id'=>$request->term_id])->select('fcm_token')->get();
        $fcm_ids = array();
        foreach($allIds as $i => $i_value) {
            if($i_value->fcm_token !='NA'){
                array_push($fcm_ids,$i_value->fcm_token);
            }
        }
        $regIdChunk=array_chunk($fcm_ids,1000);
        foreach($regIdChunk as $RegId){
            $header = array();
            $header[] = 'Content-type: application/json';
            $header[] = 'Authorization: key=' . $data->fcm_token;

            $payload = [
                'registration_ids' => $RegId,
                'priority'=>'high',
                'notification' => [
                'title' => $request->title,
                'body' => $request->descriptions,
                'image'=>$request->cover,
                "sound" => "wave.wav",
                "channelId"=>"fcm_default_channel"
                ],
                'android'=>[
                    'notification'=>[
                        "sound" => "wave.wav",
                        "defaultSound"=>true,
                        "channelId"=>"fcm_default_channel"
                    ]
                ]
            ];

            $crl = curl_init();
            curl_setopt($crl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($crl, CURLOPT_POST,true);
                curl_setopt($crl, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($crl, CURLOPT_POSTFIELDS, json_encode( $payload ) );

            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true );

            $rest = curl_exec($crl);
            if ($rest === false) {
                return curl_error($crl);
            }
            curl_close($crl);
        }
        $response = [
            'data'=>true,
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

        $data = Exams::find($request->id);

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
        $data = Exams::find($request->id)->update($request->all());

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
        $data = Exams::find($request->id);
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
        $data = Exams::all();
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

    public function getMyExamList(Request $request){
        $validator = Validator::make($request->all(), [
            'study_id' => 'required',
            'term_id' => 'required',
            'subject_id' => 'required',
            'uid' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = Exams::where(['study_id'=>$request->study_id,'term_id'=>$request->term_id,'subject_id'=>$request->subject_id,'status'=>1])->get();
        foreach($data as $loop){
            $attempted = AttemptedExam::select('id','exam_id','uid','gained','total','result','status')->where(['exam_id'=>$loop->id,'uid'=>$request->uid])->first();
            if($attempted && $attempted->id && $attempted->uid == $request->uid){
                $loop->attempted = true;
                $loop->stats = $attempted;
            }else{
                $loop->attempted = false;
            }
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getExamStats(Request $request){
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
        $data = Exams::find($request->id);
        $data->studies = Studies::where(['id'=>$data->study_id])->first();
        $data->terms = Terms::where(['id'=>$data->term_id])->first();
        $data->subjects = Subjects::where(['id'=>$data->subject_id])->first();
        $attempted = AttemptedExam::select('id','exam_id','uid','gained','total','result','status')->where('exam_id',$request->id)->get();
        $topper = AttemptedExam::select('id','exam_id','uid','gained','total','result','status')->where(['exam_id'=>$request->id,'result'=>1])->orderBy('gained','desc')->get();
        foreach($attempted as $loop){
            $loop->user = User::select('enroll','email','first_name','last_name')->where(['id'=>$loop->uid])->first();
        }
        foreach($topper as $loop){
            $loop->user = User::select('enroll','email','first_name','last_name')->where(['id'=>$loop->uid])->first();
        }
        $response = [
            'data'=>$data,
            'attempted'=>$attempted,
            'topper'=>$topper,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}

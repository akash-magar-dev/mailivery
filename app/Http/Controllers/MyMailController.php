<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Mail\MyMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\MailHistroyModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MyMailController extends Controller
{
    public static $mail_attachement_dir = 'mail_attachment/';

    public function index()
    {
        // $users = User::all()->pluck('email')->toJson();
        $users = User::all()->pluck('email');
        return view('compose_mail', compact('users'));
    }

    public function send_mail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_emails' => 'required',
            'to_emails.*' => 'email',
            'cc_emails' => 'sometimes|required',
            'cc_emails.*' => 'email',
            'subject' => 'required',
            'message' => 'required',
            'attachment'=>'sometimes|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()
            ]);
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        Mail::to(implode(',', $validated['to_emails']))
            ->cc(implode(',', $validated['to_emails']))
            ->send(new MyMail($validated));

        $sent_mail = new MailHistroyModel();
        $sent_mail->to_recipient = implode(',', $validated['to_emails']);
        $sent_mail->cc_recipient = (@$validated['cc_emails'] ? implode(',', $validated['cc_emails']) : NULL);
        $sent_mail->subject = $validated['subject'];
        $sent_mail->message = htmlspecialchars($validated['message']);
        $sent_mail->attachment = (@$validated['attachment'] ? implode(',', $validated['attachment']) : NULL);

        $sent_mail->save();

        return response()->json([
            'status'=>TRUE,
            'msg'=>'Mail send successfully'
        ]);
    }

    public function store_attachment(Request $request)
    {
        $output_dir = public_path(self::$mail_attachement_dir);
        File::isDirectory($output_dir) or File::makeDirectory($output_dir, 0777, true, true);
     
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpeg,bmp,png,pdf|max:2000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'jquery-upload-file-error' => $validator->messages()->first()
            ]);
        }

        $name = $request->file('file')->getClientOriginalName();
        $fileName = pathinfo($name,PATHINFO_FILENAME); 
        $ext = $request->file('file')->getClientOriginalExtension();
        $uniquefileName = $fileName .'_'.time().'.'.$ext;
        $upload = $request->file->move($output_dir, $uniquefileName);
        return response()->json($uniquefileName);

        // if (isset($_FILES["myfile"])) {
        //     $ret = array();

        //     $error = $_FILES["myfile"]["error"];
        //     //You need to handle  both cases
        //     //If Any browser does not support serializing of multiple files using FormData() 
        //     if (!is_array($_FILES["myfile"]["name"])) //single file
        //     {
        //         $fileName = $_FILES["myfile"]["name"];
        //         move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir . $fileName);
        //         $ret[] = $fileName;
        //     } else  //Multiple files, file[]
        //     {
        //         $fileCount = count($_FILES["myfile"]["name"]);
        //         for ($i = 0; $i < $fileCount; $i++) {
        //             $fileName = $_FILES["myfile"]["name"][$i];
        //             move_uploaded_file($_FILES["myfile"]["tmp_name"][$i], $output_dir . $fileName);
        //             $ret[] = $fileName;
        //         }

        //     }
        //     echo json_encode($ret);
        // }
    }

    public function mail_histrory() {
        $all_sent_mail = MailHistroyModel::orderBy('created_at', 'desc')->get();;
        return view('mail_histrory',compact('all_sent_mail'));
    }

    public function mail_detail($id) {
        $mail_detail = MailHistroyModel::find($id);
        $attachement_dir = self::$mail_attachement_dir;
        return view('mail_detail',compact('mail_detail','attachement_dir'));
    }

    public function dashboard(){
        $year = Carbon::now()->year;
    
        $data = MailHistroyModel::whereYear('created_at',$year)->get()->count();
        $monthly_mail = MailHistroyModel::
            select(DB::raw('count(id'))
            ->select(DB::raw('DATE_FORMAT(created_at, "%M") as formatted_dob'))
            ->whereYear('created_at',$year)
            ->get()
            ->groupBy('formatted_dob')->toarray();
            // ->groupBy(function($val) {
            //         return Carbon::parse($val->created_at)->format('m');
            // })->toarray();
        // select( "id" ,
        //     DB::raw("(DATE_FORMAT(created_at, '%m-%Y')) as month_year")
        // )
        // ->orderBy('created_at')
        // ->groupBy(DB::raw("DATE_FORMAT(created_at, '%m-%Y')"))
        // ->get();
        return view('dashboard' , compact('monthly_mail') );
    }
}

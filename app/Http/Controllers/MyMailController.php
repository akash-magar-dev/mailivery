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
use Illuminate\Support\Facades\Log;

class MyMailController extends Controller
{
    /**
     * The application's mail attachement dir.
     *
     * @var string
     */
    public static $mail_attachement_dir = 'mail_attachment/';

    /**
     * Display the compose mail view.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::all()->pluck('email');
        return view('compose_mail', compact('users'));
        
    }

    /**
     * Handle an incoming send mail request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send_mail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'to_emails' => 'required',
            'to_emails.*' => 'email',
            'cc_emails' => 'sometimes|required',
            'cc_emails.*' => 'email',
            'subject' => 'required',
            'message' => 'required',
            'attachment' => 'sometimes|required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'error' => $validator->messages()
            ]);
        }

        // Retrieve the validated input...
        $validated = $validator->validated();

        try{
            Mail::to(implode(',', $validated['to_emails']))
            ->cc(implode(',', $validated['to_emails']))
            ->send(new MyMail($validated));
        }catch(\Exception $e){
            Log::channel('maillog')->error('mail fail : '.json_encode($validated));
            Log::channel('maillog')->error('mail fail : '.json_encode($e->getMessage()));
        }
       

        Log::channel('maillog')->info('mail sent : '.json_encode($validated));

        $sent_mail = new MailHistroyModel();
        $sent_mail->to_recipient = implode(',', $validated['to_emails']);
        $sent_mail->cc_recipient = (@$validated['cc_emails'] ? implode(',', $validated['cc_emails']) : NULL);
        $sent_mail->subject = $validated['subject'];
        $sent_mail->message = htmlspecialchars($validated['message']);
        $sent_mail->attachment = (@$validated['attachment'] ? implode(',', $validated['attachment']) : NULL);

        $sent_mail->save();

        return response()->json([
            'status' => TRUE,
            'msg' => 'Mail send successfully'
        ]);
    }

    /**
     * Handle an incoming store attachment request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
        $fileName = pathinfo($name, PATHINFO_FILENAME);
        $ext = $request->file('file')->getClientOriginalExtension();
        $uniquefileName = $fileName . '_' . time() . '.' . $ext;
        $upload = $request->file->move($output_dir, $uniquefileName);
        return response()->json($uniquefileName);
    }

    /**
     * Display the sent mail list view.
     * @param mixed $email
     * @return \Illuminate\View\View
     */
    public function mail_histrory($email = null)
    {
        if($email){
            $all_sent_mail =MailHistroyModel::select('id','to_recipient','subject','created_at')
            ->where('to_recipient', 'LIKE', '%'.$email.'%')->get();
        }else{
            $all_sent_mail = MailHistroyModel::orderBy('created_at', 'desc')->get();
        }
        return view('mail_histrory', compact('all_sent_mail'));
    }

    /**
     * Display the mail detail view.
     * 
     * @param  $id
     * @return \Illuminate\View\View
     */
    public function mail_detail($id)
    {
        $mail_detail = MailHistroyModel::find($id);
        $attachement_dir = self::$mail_attachement_dir;
        return view('mail_detail', compact('mail_detail', 'attachement_dir'));
    }

    /**
     * Display the dashboard view.
     *
     * @return \Illuminate\View\View
     */
    public function mail_dashboard()
    {
        $year = Carbon::now()->year;
        $monthly_mail = MailHistroyModel::
            select(DB::raw('count(id)'))
            ->select(DB::raw('DATE_FORMAT(created_at, "%M") as formatted_dob'))
            ->whereYear('created_at', $year)
            ->get()
            ->groupBy('formatted_dob')->toarray();

        $user = User::all();
        $all_user_mail_count = [];
        foreach($user as $k=>$v){
            $user_mail_count = MailHistroyModel::
            select(DB::raw('count(id) as mail_Sent'))
            ->where('to_recipient','like', "%".$v->email."%")
            // ->orwhere('cc_recipient','like', '%'.$v->email.'%')
            ->get()->toarray();
            $all_user_mail_count[$v->email] = $user_mail_count[0];
        }

        return view('mail_dashboard', compact('monthly_mail','all_user_mail_count'));
    }
}

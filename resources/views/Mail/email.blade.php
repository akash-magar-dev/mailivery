
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8"><title>{!! $mail_data['subject'] !!}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    </head>
    <body>
        <img src="{{route('mail_tracking_img', ['tracking_id'=>$mail_data['tracking_id']] )}} " style="size: 1px;width:1px;display:none;">
        {!! $mail_data['message'] !!}
    </body>
</html>
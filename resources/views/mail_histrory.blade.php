@extends('layout')

@section('content')
<div class="email-inbox-header">
    <div class="row">
        <div class="col-lg-6">
            <div class="email-title mailivery font-weight-bold"><span class="icon"><i class="fas fa-fw  fa-envelope"></i></span> Sent mail</div>
        </div>
        <div class="col-lg-6">
            <div class="email-search">
                <div class="input-group input-search">
                    <input class="form-control" id="mail_search" type="text" placeholder="Search mail..."><span
                        class="input-group-btn">
                        <button class="btn btn-secondary" type="button"><i class="fas fa-search"></i></button></span>
                </div>
            </div>
        </div>
    </div>
</div>
<hr>
<div class="email-list">
    @if(count($all_sent_mail) > 0)
        @foreach ($all_sent_mail as $mail)
            <a href="{{Route('mail_detail',['id'=>$mail->id])}}">
                <div class="email-list-item email-list-item{{($mail->tracking_info)?'':'--unread' }}">
                    <h6 class="search_target d-none">{{$mail->to_recipient}} {{$mail->to_recipient}} {{$mail->subject}}</h6>
                    <div class="email-list-detail">
                        <span class="date float-right">
                            <span class="icon">
                                <i class="fas fa-paperclip {{$mail->attachment ? : 'd-none'}}"></i>
                            </span>{{$mail->created_at->format('d F')}}</span>
                        <span class="from">{{$mail->to_recipient}}</span>
                        <p class="msg font-weight-bold">{{$mail->subject}}</p>
                    </div>
                </div>
            </a>
        @endforeach
    @else
    <div style="min-height: 60vh;">
        <div class="email-list-i tem email-list-item" >
            <h1 class="mailivery">You have not send any mail now send it with mailivery </h1>
        </div>
    </div>
    @endif
</div>

<script>
    $(function(){
            $('#mail_search').on('keyup',function(e){
                // console.log(e);
                search = $(this).val().toLowerCase().trim();
                target = $('.search_target');
                if(search.length > 0){
                    $.each(target,function(i,e){
                        // console.log('search on'+$(e).text().toLowerCase());
                        // console.log($(e).text());
                        if( ($(e).text().toLowerCase()).includes(search) ){
                            console.log($(e).parent());
                            $(e).parent().addClass('text-danger')
                            $(e).parent().removeClass('d-block');
                        }else{
                            // console.log('np'+$(e).parent());
                            $(e).parent().removeClass('text-danger')
                            $(e).parent().addClass('d-none');
                        }
                    });
                }else{
                    $.each(target,function(i,e){
                        $(e).parent().removeClass('d-none text-danger');
                    });
                }
            });
        })
</script>
@endsection
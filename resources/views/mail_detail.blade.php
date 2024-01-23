@extends('layout')

@section('content')
<div class="container-fluid p-0">
    <div class="email-head">
        <div class="email-head-subject">
            <div class="title">
                <span>{{$mail_detail->subject}}</span>
            </div>
            @if(($mail_detail->tracking_info))
                <i class="text-danger">Mail has been read by the user &nbsp; <i class="hover-hand fas fa-info-circle show_detail"></i></i>
                <div class="mailinfo d-none">
                    @foreach(explode('::',$mail_detail->tracking_info) as $k=>$v)
                    <p class="mb-0">{{$v}}</p>
                    @endforeach
                </div>
                </div>
            @endif
        </div>
        <div class="email-head-sender">
            <div class="date">{{$mail_detail->created_at->format('d F H:i')}}</div>
             <div class="sender">
                <span>To :</span> <h4 class="d-inline">{{$mail_detail->to_recipient}}</h4> 
                @if($mail_detail->cc_recipient)
                <br>
                <span>cc :</span> <h4 class="d-inline">{{$mail_detail->cc_recipient}}</h4> 
                @endif
            </div>
        </div>
    </div>
    <div class="email-body">
        {!!htmlspecialchars_decode($mail_detail->message)!!}
    </div>
    @if($mail_detail->attachment)
    <div class="email-attachments">
        @php
            $attachment = explode(',',$mail_detail->attachment);
        @endphp
        <div class="title">Attachments <span class="text-danger">(files : {{count($attachment)}})</span></div>
        <ul>
            @foreach($attachment as $k=>$v)
            <li>
                {{-- {{File::size(public_path('/'.$attachement_dir.$v))}} --}}
                <a target="_blank" class="text-underline" href="{{URL::to('/'.$attachement_dir.$v)}}"><span class="icon mdi mdi-attachment-alt"></span> {{$v}} </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif
</div>
<script>
    $(function(){
        $('.show_detail').on('click',function(e){
            $('.mailinfo').toggleClass('d-none');
        })
    })
</script>
@endsection


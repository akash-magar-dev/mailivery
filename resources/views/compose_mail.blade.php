@extends('layout')
<script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>

@section('content')
<div class="email-head">
    <div class="email-head-title mailivery font-weight-bold">Compose new message<span class="icon mdi mdi-edit"></span></div>
</div>
<form id="send_mail_form">
<div class="email-compose-fields">
    <div class="to">
        <div class="form-group row pt-0">
            <label class="col-md-1 control-label pt-2">To:</label>
            <div class="col-md-11">
                <select class="select2 to_address" id="to_address" multiple="multiple">
                </select>
                <p class="text-danger error to_address_error"></p>
            </div>
        </div>
    </div>
    <div class="to cc">
        <div class="form-group row pt-2">
            <label class="col-md-1 control-label pt-2">Cc</label>
            <div class="col-md-11">
                <select class="select2 cc_address" id="cc_address" multiple="multiple">
                </select>
                <p class="text-danger error cc_address_error"></p>
            </div>
        </div>
    </div>
    <div class="subject">
        <div class="form-group row pt-2">
            <label class="col-md-1 control-label pt-2">Subject</label>
            <div class="col-md-11">
                <input class="form-control" id="subject" type="text">
                <p class="subject_error error text-danger"></p>
            </div>
        </div>
    </div>
</div>
<div class="email editor">
    <div class="col-md-12 p-0">
        <div class="form-group">
            <label class="control-label sr-only" for="message">Descriptions </label>
            <textarea class="form-control" id="message" name="editordata" placeholder="Enter message"></textarea>
            <p class="message_error error text-danger"></p>
        </div>
        <div>
            <div id="fileuploader" class="p-3">Upload</div>
        </div>
    </div>
    <div class="email action-send pt-0">
        <div class="col-md-12 ">
            <div class="form-group">
                <button id="send_mail" class="btn btn-primary btn-space" type="button" ><i class="icon s7-mail"></i>
                    Send</button>
                <button class="btn btn-secondary btn-space" id="reset" type="button" onclick="reset_form()"><i class="icon s7-close"></i> Reset</button>
            </div>
        </div>
    </div>
</div>
</form>

<div class="alert_holder" style="position: fixed;bottom: 10px;right: 10px;"></div>

<script>
    $email_add = {!! json_encode($users->toArray()) !!};
    let $send_mail = '{{Route('sendmail')}}';
    let $upload_attchment = '{{Route('upload_attachment')}}';
    let editor;
    let policy = /mad|shit/;
    fileupload = '';
    var attachment_data = [];

    function showMessage(type, message){
        $(`<div class="alert alert-${type} alert-dismissible fade show" role="alert">${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>`).appendTo('.alert_holder').fadeOut(1500);
    }

    function reset_form(){
        $('#send_mail_form').trigger('reset');
        $('.Select2').val(null).trigger('change');
        editor.setData('');
        $('#subject').val('');
        fileupload.reset();
    }

    
    ClassicEditor
        .create( document.querySelector( '#message' ),{
            // toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote' ],
            toolbar: ['alignment','heading','|','bold','italic','link','bulletedList','numberedList','|','outdent','indent','|','undo','redo'],
        } )
        .then( newEditor => {
            editor = newEditor;
        } )
        .catch( error => {
            console.error( error );
        } );

        
    $(document).ready(function() {

        //hide errors on change
       
        $('#to_address,#subject').on('change',function(e) {
            if($(this).hasClass('to_address')){
                $('.to_address_error').text('');
            }else{
                $('.subject_error').text('');
            }
        })

        editor.model.document.on( 'change:data', () => {
            $('.message_error').text('');
        });

        $('.select2').select2({ 
            tags: true,
            data : $email_add,
            createTag: function(term, data) {
                var value = term.term;
                if(validateEmail(value)) {
                    return {
                    id: value,
                    text: value
                    };
                }
                return null;            
            }
        });

        $('#send_mail').on('click',function(e){

            to_add = $('#to_address').select2('data');
            cc_add = $('#cc_address').select2('data');
            subject = $('#subject').val().trim();
            message = editor.getData();
            plain_msg = $(editor.getData()).text().trim();

            if(to_add.length == 0 ){
                $('#to_address').focus();
                $('.to_address_error').text('Please enter TO address');
            }else if(subject.length == 0){
                $('#subject').focus();
                $('.subject_error').text('Please enter subject line');
            }else if(plain_msg == ''){
                $('#message').focus();
                $('.message_error').text('Please type message');
            }else if(message.match(policy)){
                $('#message').focus();
                word = message.match(policy);
                $('.message_error').text('Bad words not allowed like '+word[0]);
            }else{

                var to_emails = to_add.map((toadd) => {return toadd.text});
                var cc_emails = cc_add.map((ccadd) => {return ccadd.text});
               
                $.ajax({
                    url:$send_mail,
                    type:'post',
                    data:{to_emails,cc_emails,subject,message,'attachment':attachment_data},
                    beforeSend:function(){
                        $('#send_mail').text('Sending...').attr('disabled',true);
                        $('#reset').attr('disabled',true);
                    },
                    complete:function(){
                        $('#send_mail').text('Send').removeAttr('disabled');
                        $('#reset').removeAttr('disabled');
                    },
                    success:function(e){
                        if(e.status){
                            showMessage('success',e.msg);
                            reset_form();
                        }else{
                            showMessage('danger','Something went wrong');
                        }
                    },
                    error:function(e){
                        showMessage('danger','Something went wrong');
                    }
                });
            }
            
        });

        fileupload = $("#fileuploader").uploadFile({
            url:$upload_attchment,
            multiple:true,
            dragDrop:false,
            fileName:"file",
            acceptFiles:"image/*,.pdf",
            showDelete:true,
            showStatusAfterSuccess:true,
            showError:true,
            showPreview:false,
            returnType:'json',
            uploadStr:'Add attachment',
            // maxFileSize:2000,
            acceptFiles:'image/jpeg,image/gif,image/png,application/pdf',
            // allowedTypes:'jpeg,bmp,png,pdf',
            onError: function(files,status,errMsg,pd)
            {
                $(".ajax-file-upload-filename:contains("+files+")").parent().remove();
                // $('.ajax-file-upload-error').parent().remove();
                // $('.ajax-file-upload-error').last().parent().remove();
            },
            onSuccess:function(files,data,xhr,pd)
            {
                console.log(files);
                console.log(data);
                console.log(xhr);
                console.log(pd);
                attachment_data.push(data);  
            },
            deleteCallback: function(data,pd)
            {
                attachment_data.splice(attachment_data.indexOf(data), 1);
                console.log(data,pd);
            }
        });
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }
</script>
@endsection
@extends('layouts.master')

@section('page-inline-styles')
<style>
    .margin-r-5 {
        margin-right: 5px;
    }

    input[type=checkbox] {
        transform: scale(1.7);
    }
</style>
@endsection

@section('title', 'Support Ticket Detail')

@section('page-content')
    <div class="page-header">
        <h1>Support Ticket Detail</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('tickets.index') }}';">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            <p class="text-center">
                <img src="{{ asset('images/loading51.gif') }}" alt="Loading" id="loading-container"
                    style="display: none;" />
            </p>

            <div class="row">
                <div class="col-sm-5">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Ticket Number </div>
                            <div class="info-div-value"><span>{{ $ticketResponse['ticket_number'] }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Type </div>
                            <div class="info-div-value"><span>{{ $ticketResponse['type']['description'] }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value"><span>{{ $ticketResponse['status']['description'] }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Priority </div>
                            <div class="info-div-value"><span>{{ $ticketResponse['priority']['description'] }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Raised By </div>
                            <div class="info-div-value">
                                <span>
                                    {{ $ticketResponse['account_contact']['firstname'] }} {{ $ticketResponse['account_contact']['lastname'] }} of 
                                    {{ $ticketResponse['account']['name'] }}
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Logged At </div>
                            <div class="info-div-value"><span>{{ Carbon\Carbon::parse($ticketResponse['created_at'])->format('d/m/Y H:i:s') }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Recently Modified At </div>
                            <div class="info-div-value"><span>{{ Carbon\Carbon::parse($ticketResponse['updated_at'])->format('d/m/Y H:i:s') }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7">

                    <div class="timeline-container">
                        
                        <div class="timeline-items">
                            <div class="timeline-item clearfix">
                                <div class="timeline-info">
                                    <i class="timeline-indicator ace-icon fa fa-envelope btn btn-primary no-hover green"></i>
                                </div>

                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h5 class="widget-title smaller">
                                            <span class="text-info">
                                                <i class="fa fa-user"></i> 
                                                {{ $ticketResponse['account_contact']['firstname'] }} {{ $ticketResponse['account_contact']['lastname'] }}
                                            </span>                                            
                                        </h5>
                                        <span class="widget-toolbar no-border">
                                            <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                            {{ Carbon\Carbon::parse($ticketResponse['created_at'])->format('d/m/Y H:i:s') }}
                                        </span>                                        
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <h5 class="widget-title smaller">
                                                {{ $ticketResponse['subject'] }} 
                                            </h5>
                                            {!! nl2br(e($ticketResponse['description'])) !!}
                                            @if (isset($ticketResponse['attachments']) && count($ticketResponse['attachments']) > 0)
                                                @foreach ($ticketResponse['attachments'] AS $attachment)
                                                <br><i class="fa fa-file"></i> 
                                                <span style="cursor:pointer; text-decoration: underline;" 
                                                    onclick="downloadFile('{{ $attachment['download_url'] }}', '{{ $attachment['original_filename'] }}', '{{ $attachment['extension'] }}');">
                                                    {{ $attachment['original_filename'] }} {{ $attachment['extension'] }}
                                                </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @foreach ($ticketResponse['ticket_notes'] as $ticketNote)
                            <div class="timeline-item clearfix">
                                <div class="timeline-info">
                                    <i class="timeline-indicator ace-icon fa fa-envelope btn btn-primary no-hover green"></i>
                                </div>

                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h5 class="widget-title smaller">
                                            {!! $ticketNote['account_contact_id'] == '' ? '<i class="fa fa-user-md"></i> ' : '<i class="fa fa-user"></i> ' !!}
                                            {{ $ticketNote['account_contact_id'] == '' ? 'Perspective Support' : $ticketResponse['account_contact']['firstname'] . ' ' . $ticketResponse['account_contact']['lastname'] }}
                                        </h5>

                                        <span class="widget-toolbar no-border">
                                            <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                            {{ Carbon\Carbon::parse($ticketNote['created_at'])->format('d/m/Y H:i:s') }}
                                        </span>
                                        
                                    </div>

                                    <div class="widget-body">
                                        <div class="widget-main">
                                            {!! nl2br(e($ticketNote['notes'])) !!}
                                            @if (isset($ticketNote['attachments']) && count($ticketNote['attachments']) > 0)
                                                @foreach ($ticketNote['attachments'] AS $attachment)
                                                <br><i class="fa fa-file"></i> 
                                                <span style="cursor:pointer; text-decoration: underline;" 
                                                    onclick="downloadFile('{{ $attachment['download_url'] }}', '{{ $attachment['original_filename'] }}', '{{ $attachment['extension'] }}');">
                                                    {{ $attachment['original_filename'] }} {{ $attachment['extension'] }}
                                                </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div><!-- /.timeline-items -->

                    </div>

                    @if( in_array(strtolower($ticketResponse['status']['description']), ['assigned', 'awaiting client', 'awaiting confirmation', 'requires additional requirements']) )
                    {!! Form::open([
                        'url' => '', 
                        'class' => 'form-horizontal', 
                        'files' => true, 'name' => 
                        'frmTicketFeedback', 
                        'id' => 'frmTicketFeedback'
                        ]) !!}

                        {!! Form::hidden('firstname', auth()->user()->firstnames) !!}
                        {!! Form::hidden('lastname', auth()->user()->surname) !!}
                        {!! Form::hidden('ticket_id', $ticketResponse['id']) !!}
        
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="widget-title lighter">Your Feedback:</h4>
                            </div>
                            <div class="alert alert-danger alert-dismissible" id="errorPanel" style="display: none;">
                                <h4><i class="icon fa fa-exclamation-triangle"></i> Alert!</h4>
                                <ul id="errorList"></ul>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main" style="margin: 1%">
                                    <p class="text-center" id="loading" style="display: none;">
                                        <img src="{{ asset('images/loading51.gif') }}" alt="Loading" />
                                    </p>
                                    <div class="form-group">
                                        {!! Form::label('account_contact_email', 'Email address: *', ['class' => 'control-label no-padding-right required']) !!}
                                        {!! Form::email('account_contact_email', auth()->user()->primary_email, ['class' => 'form-control', 'required', 'maxlength' => '70', 'placeholder' => 'Enter your email', 'maxlength' => 255]) !!} 
                                        <p class="text-danger" id="error-account_contact_email"></p>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('notes', 'Notes/Details:', ['class' => 'control-label no-padding-right required']) !!}
                                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'maxlength' => '800', 'required']) !!}
                                        <p class="text-danger" id="error-notes"></p>
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('evidence_file', 'File Attachment:', ['class' => 'control-label no-padding-right']) !!}
                                        {!! Form::file('evidence_file', ['class' => 'form-control', 'id' => 'evidence_file']) !!}
                                        <p class="text-danger" id="error-evidence_file"></p>
                                        <span class="text-info small">
                                            <p>
                                                If there is a screenshot or document you can send us to help us understand your query, please attach it here.<br>
                                                If you want to upload multiple files, zip them to make single zip file. 
                                            </p>
                                        </span>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="control-group">
                                                <div class="checkbox">
                                                    <label class="text-success">
                                                        <input name="close_ticket"  type="checkbox" value="1" >
                                                        <span class="lbl bolder"> &nbsp; Happy to close this ticket.</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-toolbox padding-8 clearfix">
                                    <div class="center">
                                        <button class="btn btn-sm btn-success btn-round" type="button" id="btnSubmit">
                                            <i class="ace-icon fa fa-sent bigger-110"></i>
                                            Send your feedback
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                    @endif
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

@section('page-inline-scripts')
    <script language="JavaScript">
        const TokenID = '{{ $X_TokenID }}';

        $('button#btnSubmit').on('click', function (e){
            e.preventDefault;

            const form = document.getElementById('frmTicketFeedback');
            const loadingMessage = document.getElementById('loading');
            const errorPanel = document.getElementById('errorPanel');
            const errorList = document.getElementById('errorList');
            const btnSubmit = document.getElementById('btnSubmit');

            errorList.innerHTML = '';
            loadingMessage.style.display = 'block';
            const formData = new FormData(form);
            const apiUrl = 'https://tickets.sunesis.uk.net/api/v1/tickets/create_note';

            btnSubmit.disabled = true;

            axios.post(apiUrl, formData, {
                headers: {
                    'X-TokenID': TokenID,
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then(function(response) {
                bootbox.alert({
                    title: "Success",
                    message: "Note has been saved.",
                    callback: function () {
                        window.location.reload();
                    }
                });
            })
            .catch(function(error) {
                // Hide loading message
                loadingMessage.style.display = 'none';
                btnSubmit.disabled = false;

                // Handle validation errors
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;

                    // Display validation errors in the modal
                    for (const field in errors) {
                        const errorMessage = errors[field][0];
                        const errorItem = document.createElement('li');
                        errorItem.innerText = errorMessage;
                        errorList.appendChild(errorItem);
                        $("p#error-" + field).html(errorMessage);
                    }

                    // Show the error modal
                    errorPanel.style.display = 'block';
                    window.scrollTo(0, 0);
                } else {
                    // Handle other errors (e.g., display a general error message)
                    var message = error.message + '<br>';
                    if(error.response.data.message)
                    {
                        message += error.response.data.message + '<br>';
                    }
                    if(error.response.status && error.response.statusText)
                    {
                        message += error.response.status + ': ' + error.response.statusText + '<br>';
                    }
                    bootbox.alert({
                        title: "Error: " + error.code,
                        message: message !== undefined ? message : 'Something went wrong, try again.'
                    });
                }
            });

        });

        $(function() {
            $('#evidence_file').ace_file_input({
                    'maxSize': (1024 * 1024 * 2)+1048576,
                    'allowExt': ['jpeg','png','doc','docx','pdf','txt','zip'],
                })
                .on('file.error.ace', function(event, info) {
                    if(info.error_count['size'] > 0)
                    {
                        alert('File size exceeds maximum allowed file size of 2MB.')
                    }
                    if(info.error_count['ext'] > 0)
                    {
                        alert('File type is not allowed.')
                    }
                    
                    event.preventDefault();
                });
        });

        function downloadFile(downloadUrl, fileName, fileExtenstion) 
        {
            axios({
                headers: {
                    'X-TokenID': TokenID,
                    'Content-Type': 'multipart/form-data',
                },
                url: downloadUrl, 
                method: 'GET',
                responseType: 'blob',
            })
            .then((response) => {
                const href = URL.createObjectURL(response.data);
                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', fileName+'.'+fileExtenstion); 
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            });
        }
    </script>
@endsection


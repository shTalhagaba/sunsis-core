@extends('layouts.master')

@section('page-inline-styles')
<style>
    .margin-r-5 {
        margin-right: 5px;
    }
</style>
@endsection

@section('title', 'Support Tickets')

@section('page-content')
    <div class="page-header">
        <h1>Support Tickets</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('tickets.index') }}';">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            <div class="row">
                <div class="col-sm-8 col-sm-offset-2">
                    <p class="text-center">
                        <img src="{{ asset('images/loading51.gif') }}" alt="Loading" id="loading-container"
                            style="display: none;" />
                    </p>
        
                    <div class="alert alert-danger alert-dismissible" id="errorPanel" style="display: none;">
                        <h4><i class="icon fa fa-exclamation-triangle"></i> Alert!</h4>
                        <ul id="errorList"></ul>
                    </div>
        
                    <div class="alert alert-success alert-dismissible" id="successPanel" style="display: none;">
                        <h4>Thank you for your query</h4>
                        <p>Your support ticket has been sent to Perspective Support.</p>
                        <p>Your case number is: <strong style="font-size: x-large;"><span class="text-bold" id="caseNumber">12121</span></strong><br />
                        (This number should be used in all future communications regarding this query)</p> <br/>
                        <p>You have been sent a copy of the Support Request details to the email address you provided.</p>
                        <p>A member of the Support Team will contact you at the earliest opportunity</p>
                        <p>Support Hours are 9am until 5pm Monday to Friday</p>
                        <br/>
                        <p>You can check on your support requests by visting the <a href="{{ route('tickets.index') }}">Your Support Requests</a> section on Sunesis.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        {!! Form::open([
                            'url' => '', 
                            'class' => 'form-horizontal', 
                            'files' => true, 
                            'name' => 'frmTicket', 
                            'id' => 'frmTicket'
                            ]) !!}
                            {!! Form::hidden('firstname', auth()->user()->firstnames) !!}
                            {!! Form::hidden('lastname', auth()->user()->surname) !!}
                            {!! Form::hidden('product_id', 3) !!}
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="widget-title lighter">Support Ticket</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h4 class="widget-title lighter">Your Details:</h4>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main" style="margin: 1%">
                                                        <p class="text-center" id="loading" style="display: none;">
                                                            <img src="{{ asset('images/loading51.gif') }}" alt="Loading" />
                                                        </p>
                                                        <div class="form-group">
                                                            {!! Form::label('email', 'Email address: *', ['class' => 'control-label no-padding-right required']) !!}
                                                            {!! Form::email('email', auth()->user()->primary_email, ['class' => 'form-control', 'required', 'maxlength' => '70', 'placeholder' => 'Enter your email']) !!} 
                                                            <p class="text-danger" id="error-email"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('phone', 'Telephone', ['class' => 'control-label no-padding-right']) !!}
                                                            {!! Form::text('phone', null, ['class' => 'form-control', 'maxlength' => '25', 'placeholder' => 'Enter your telephone']) !!} 
                                                            <p class="text-danger" id="error-phone"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('job_role', 'Job Role', ['class' => 'control-label no-padding-right']) !!}
                                                            {!! Form::text('job_role', null, ['class' => 'form-control', 'maxlength' => '70', 'placeholder' => 'Enter your job role']) !!} 
                                                            <p class="text-danger" id="error-job_role"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('department', 'Department', ['class' => 'control-label no-padding-right']) !!}
                                                            {!! Form::text('department', null, ['class' => 'form-control', 'maxlength' => '70', 'placeholder' => 'Enter your department']) !!} 
                                                            <p class="text-danger" id="error-department"></p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class="widget-box">
                                                <div class="widget-header">
                                                    <h4 class="widget-title lighter">Ticket Details:</h4>
                                                </div>
                                                <div class="widget-body">
                                                    <div class="widget-main" style="margin: 1%">
                                                        <p class="text-center" id="loading" style="display: none;">
                                                            <img src="{{ asset('images/loading51.gif') }}" alt="Loading" />
                                                        </p>
                                                        <div class="form-group">
                                                            {!! Form::label('type', 'Type: *', ['class' => 'control-label no-padding-right required']) !!}
                                                            {!! Form::select('type', $typesList, null, ['class' => 'form-control ', 'required']) !!}
                                                            <p class="text-danger" id="error-type"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('customer_priority', 'Customer Priority: *', ['class' => 'control-label no-padding-right required']) !!}
                                                            {!! Form::select('customer_priority', $prioritiesList, null, ['class' => 'form-control ', 'required']) !!}
                                                            <p class="text-danger" id="error-customer_priority"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('subject', 'Subject: *', ['class' => 'control-label no-padding-right required']) !!}
                                                            {!! Form::text('subject', null, ['class' => 'form-control', 'maxlength' => '255']) !!} 
                                                            <p class="text-danger" id="error-subject"></p>
                                                        </div>
                                                        <div class="form-group">
                                                            {!! Form::label('description', 'Description: *', ['class' => 'control-label no-padding-right required']) !!}
                                                            {!! Form::textarea('description', null, ['class' => 'form-control', 'maxlength' => '800', 'required']) !!}
                                                            <p class="text-danger" id="error-description"></p>
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
                                                    </div>                                                    
                                                </div>
                                            </div>
                                        </div>                
                                    </div>
                                </div>
                                <div class="widget-toolbox padding-8 clearfix">
                                    <div class="center">
                                        <button class="btn btn-sm btn-success btn-round" type="button" id="btnSubmit" onclick="submitForm();">
                                            <i class="ace-icon fa fa-sent bigger-110"></i>
                                            Send your query
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
@endsection

@section('page-inline-scripts')
    <script language="JavaScript">
        const TokenID = '{{ $X_TokenID }}';
            
        function submitForm() {
            const form = document.getElementById('frmTicket');
            const loadingMessage = document.getElementById('loading');
            const successPanel = document.getElementById('successPanel');
            const errorPanel = document.getElementById('errorPanel');
            const errorList = document.getElementById('errorList');
            const caseNumber = document.getElementById('caseNumber');

            errorList.innerHTML = '';
            loadingMessage.style.display = 'block';
            const formData = new FormData(form);
            const apiUrl = 'https://tickets.sunesis.uk.net/api/v1/tickets';

            axios.post(apiUrl, formData, {
                headers: {
                    'X-TokenID': TokenID,
                    'Content-Type': 'multipart/form-data',
                },
            })
            .then(function(response) {

                caseNumber.innerText = response.data.data.ticket_number;
                
                loadingMessage.style.display = 'none';

                successPanel.style.display = 'block';

                form.reset();

                window.scrollTo(0, 0);

                return axios.post("{{ route('tickets.save_account_contact_id') }}", {
                    support_contact_id: response.data.data.account_contact.id
                }, {
                        headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
            })
            .then(function(response) {
                console.log(response.data);
            })
            .catch(function(error) {
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
                    console.error(error);
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

                // Hide loading message
                loadingMessage.style.display = 'none';
            });
        }
    </script>
@endsection


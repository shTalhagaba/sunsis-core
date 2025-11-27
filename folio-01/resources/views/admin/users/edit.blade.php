@extends('layouts.master')

@section('title', 'Edit User')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('users.edit', $user) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Edit User</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('users.show', $user) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
                </div>
            </div>
        </div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="row">
            <div class="col-xs-12">

                {!! Form::model($user->getAttributes(), [
                    'method' => 'PATCH',
                    'url' => route('users.update', $user),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'id' => 'frmUser'
                    ]) !!}

                    @include('admin.users.form')

                {!! Form::close() !!}


            </div><!-- /.span -->
         </div><!-- /.user-profile -->


      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

   <script type="text/javascript">
    var employerUserType = "{{ App\Models\User::TYPE_EMPLOYER_USER }}";

      $('#email').on('focus', function(){
            // if this is blank and primary email is not then just copy
            if(this.value.trim() == '' && $('#primary_email').val().trim() != '')
            {
               this.value = $('#primary_email').val().trim();
            }
      });

    $(function(){
        $('.inputLimiter').inputlimiter();

        $('#frmUser').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                ni: {
                    niUK: true
                },
                work_postcode: {
                    postcodeUK: true
                },
                home_postcode: {
                    postcodeUK: true
                },
                work_telephone: {
                    phoneUK: true
                },
                home_telephone: {
                    phoneUK: true
                },
                employer_location: {
                    required: function (element){
                        return $("#user_type").val() == employerUserType
                    }
                }
            },

            messages: {
                email: {
                    required: "Please provide a valid email.",
                    email: "Please provide a valid email."
                },
                primary_email: {
                    required: "Please provide a valid email.",
                    email: "Please provide a valid email."
                },
                secondary_email: {
                    email: "Please provide a valid email."
                },
                work_postcode: {
                    postcodeUK: "Please provide a valid work UK postcode."
                },
                home_postcode: {
                    postcodeUK: "Please provide a valid home UK postcode."
                },
                work_telephone: {
                    postcodeUK: "Please provide a valid UK telephone."
                },
                home_telephone: {
                    postcodeUK: "Please provide a valid UK telephone."
                },
                ni: {
                    postcodeUK: "Please provide a valid UK National Insurance."
                }
            },

            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                }
                else
                    error.insertAfter(element);
            }
        });

        $.validator.addMethod('phoneUK', function(phone_number, element) {
			return this.optional(element) || phone_number.length > 9 &&
				phone_number.match(/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}$/);
		}, 'Please specify a valid phone number');

		$.validator.addMethod("niUK", function(value, element) {
			return this.optional(element) || /^\s*[a-zA-Z]{2}(?:\s*\d\s*){6}[a-zA-Z]?\s*$/i.test(value);
		}, "Please specify a valid National Insurance Number");
    });

    $("select[name=user_type]").on("change", function() {
        $("select[name=employer_location]").val("");
        $("#divEmployer").hide();
        $("#btnPopulateWorkAddressFromEmployer").hide();
        if (this.value == 18) {
            $("#divEmployer").show();
            $("#btnPopulateWorkAddressFromEmployer").show();
        }
    });

    @if($user->getOriginal('user_type') == App\Models\User::TYPE_EMPLOYER_USER)
        $("#divEmployer").show();
    @endif

   </script>

@endsection


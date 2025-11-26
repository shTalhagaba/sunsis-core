@extends('layouts.master')

@section('title', 'Create Student')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.create') }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Create Student
      <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         add new student in the system
      </small>
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.index') }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                </button>
                <div class="hr hr-12 hr-dotted"></div>
            </div>
        </div>
        @include('partials.session_message')
        @include('partials.session_error')
        <div class="row">
           <div class="col-xs-12">
               <div class="space"></div>
               {!! Form::open(['url' => route('students.store'), 'class' => 'form-horizontal', 'files' => true, 'id' => 'frmStudent']) !!}
                     @include('students.form')
               {!! Form::close() !!}

            </div><!-- /.span -->
        </div><!-- /.user-profile -->
      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

    $(function(){

        $('#frmStudent').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                username: { required: true },
                firstnames: { required: true },
                surname: { required: true },
                primary_email: { required: true },
                ni: { niUK: true },
                work_postcode: { postcodeUK: true },
                home_postcode: { postcodeUK: true },
                work_telephone: { phoneUK: true },
                home_telephone: { phoneUK: true }
            },

            messages: {
                primary_email: {
                    required: "Please provide a valid email.",
                    email: "Please provide a valid email."
                },
                secondary_email: { email: "Please provide a valid email." },
                work_postcode: { postcodeUK: "Please provide a valid work UK postcode." },
                home_postcode: { postcodeUK: "Please provide a valid home UK postcode." },
                work_telephone: { postcodeUK: "Please provide a valid UK telephone." },
                home_telephone: { postcodeUK: "Please provide a valid UK telephone." },
                ni: { postcodeUK: "Please provide a valid UK National Insurance." }
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

    $('#btnPopulateWorkAddressFromEmployer').on('click', function(e) {
        e.preventDefault();
        var employer_location = $('#employer_location').val();
        if(employer_location == '')
        {
            $.alert({
                title: 'Validation Error!',
                icon: 'fa fa-warning',
                type: 'red',
                content: 'Please select an employer to bring its address.',
                onDestroy: function(){
                    $("[name=employer_location]").focus();
                }
            });
            return false;
        }

        $(this).html('<i class="fa fa-refresh fa-spin"></i> Loading ...');

        $.ajax({
            url: '{{ route("getOrganisationLocation") }}',
            data: {location_id: employer_location},
            type: 'get',
        }).done(function(data) {
            $("[name=work_address_line_1]").val(data.address_line_1);
            $("[name=work_address_line_2]").val(data.address_line_2);
            $("[name=work_address_line_3]").val(data.address_line_3);
            $("[name=work_address_line_4]").val(data.address_line_4);
            $("[name=work_postcode]").val(data.postcode);
            $("[name=work_telephone]").val(data.telephone);
            $("[name=work_mobile]").val(data.mobile);
        }).fail(function(jqXHR, textStatus, errorThrown){
            $.alert({
                title: 'Encountered an error!',
                content: textStatus + ': '+ errorThrown ,
                icon: 'fa fa-warning',
                theme: 'supervan',
                type: 'red'
            });
        }).always(function(){
            $("#btnPopulateWorkAddressFromEmployer").html('Populate from selected employer');
        });

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        const $genderSelect = $('#gender-select');
        const $selfDescribeWrapper = $('#self-describe-wrapper');
        const $selfDescribeInput = $selfDescribeWrapper.find('input');

        function toggleSelfDescribe() {
            if ($genderSelect.val() === 'SELF') {
                $selfDescribeWrapper.show();
            } else {
                $selfDescribeWrapper.hide();
                $selfDescribeInput.val('');
            }
        }

        $genderSelect.on('change', toggleSelfDescribe);
        toggleSelfDescribe(); // run on page load
    });

   </script>

@endsection


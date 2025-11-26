@extends('layouts.master')

@section('title', 'Create Programme')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('programmes.create') }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Create Programme
      <small>
         <i class="ace-icon fa fa-angle-double-right"></i>
         add new programme in the system
      </small>
   </h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('programmes.index') }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        <div id="row">
            <div class="col-xs-12">
               <div class="space"></div>

               {!! Form::open(['url' => route('programmes.store'), 'class' => 'form-horizontal']) !!}
               @include('programmes.form', ['showQualDDL' => true])
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
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

$('.inputLimiter').inputlimiter();

if(!ace.vars['touch'])
{
    $('.chosen-select').chosen({allow_single_deselect:true});
    //resize the chosen on window resize

    $(window)
    .off('resize.chosen')
    .on('resize.chosen', function() {
        $('.chosen-select').each(function() {
                var $this = $(this);
                $this.next().css({'width': $this.parent().width()});
        })
    }).trigger('resize.chosen');
    //resize chosen on sidebar collapse/expand
    $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
        if(event_name != 'sidebar_collapsed') return;
        $('.chosen-select').each(function() {
                var $this = $(this);
                $this.next().css({'width': $this.parent().width()});
        })
    });


    $('#chosen-multiple-style .btn').on('click', function(e){
        var target = $(this).find('input[type=radio]');
        var which = parseInt(target.val());
        if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
            else $('#form-field-select-4').removeClass('tag-input-style');
    });
}

</script>

@endsection


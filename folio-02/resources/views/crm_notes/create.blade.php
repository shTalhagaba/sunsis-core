@extends('layouts.master')

@section('title', 'Create CRM NOte')

@section('page-content')
   <div class="page-header">
        <h1>Create CRM Note</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ $backUrl }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('crm_notes.entity_details', ['student' => $student, 'training' => $training])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::open([
                        'url' => route('crm_notes.store', [$noteableType, $noteable->id]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'name' => 'frmCrmNote',
                        'id' => 'frmCrmNote',
                    ]) !!}
                    @include('crm_notes.form', ['noteable' => $noteable])
                    {!! Form::close() !!}

                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div>
@endsection


@push('after-scripts')
    <script>
        $("form[name=frmCrmNote]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });
    </script>
@endpush
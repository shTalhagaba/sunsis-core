@extends('layouts.master')

@section('title', 'Edit OTLA Record')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit OTLA Record
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('otla.show', $otla) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="space"></div>
                    {!! Form::model($otla, [
                        'method' => 'PATCH',
                        'url' => route('otla.update', $otla),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmOTLA',
                        'id' => 'frmOTLA',
                    ]) !!}
                    @include('otla.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $("form[name=frmOTLA]").on('submit', function(){
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $(function(){
           
        });
    </script>
@endpush
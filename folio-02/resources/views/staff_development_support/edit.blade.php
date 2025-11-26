@extends('layouts.master')

@section('title', 'Edit Staff Development Support Entry')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Development Support Entry
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('staff_development_support.show', ['staff_development_support' => $staffDevelopmentSupport]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($staffDevelopmentSupport, [
                        'url' => route('staff_development_support.update', ['staff_development_support' => $staffDevelopmentSupport]),
                        'method' => 'PATCH',
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmStaffDevelopmentSupport',
                    ]) !!}
                    @include('staff_development_support.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@push('after-scripts')
    <script>
        $("form[name=frmStaffDevelopmentSupport]").on('submit', function() {
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });
    </script>
@endpush

@extends('layouts.master')

@section('title', 'Edit User')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('users.edit', $user) }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Edit User</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('users.show', $user) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            {!! Form::model($user->getAttributes(), [
                'method' => 'PATCH',
                'url' => route('users.update', $user),
                'class' => 'form-horizontal',
                'role' => 'form',
                'id' => 'frmUser',
            ]) !!}

            @include('admin.users.form')

            {!! Form::close() !!}

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

    @include('admin.users.scripts')

@endsection

@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            {{ \Auth::user()->surname }}, {{ \Auth::user()->firstnames }}
            <small class="small">
                <i class="ace-icon fa fa-angle-double-right"></i>
                Last login at {{ \Auth::user()->previousLoginAt() }} from {{ \Auth::user()->previousLoginIp() }}
            </small>
        </h1>
    </div>
@endsection

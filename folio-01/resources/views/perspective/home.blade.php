@extends('layouts.perspective.master')

@section('title', 'Perspective Support - Home Page')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection


@section('page-content')


<h3>You are logged into {{ \Session::get('configuration')['FOLIO_CLIENT_NAME'] }}'s system.</h3>


<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-4 pricing-box">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">License Information</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            @php
                                $license = \App\Models\License::latest('id')->first();
                                $stats = \App\Models\License::getStats();
                            @endphp
                            @if ($license)
                            <ul class="list-unstyled spaced2">
                                <li>
                                    <i class="ace-icon fa fa-check green"></i>
                                    Number of licenses: <span class="price">{{ $license->number_of_licenses }}</span>
                                    <small class="blue">
                                        <i class="ace-icon fa fa-angle-double-right"></i> with
                                        {{ $license->levy }}% levy ({{ floor($license->number_of_licenses += $license->number_of_licenses*($license->levy/100))  }})
                                    </small>
                                </li>
                                <li><i class="ace-icon fa fa-check green"></i>PO Number: {{ $license->po_number }}</li>
                                <li><i class="ace-icon fa fa-check green"></i>Expiry Date: {{ !is_null($license->expiry_date) ? \Carbon\Carbon::parse($license->expiry_date)->format('d/m/Y') : '' }}</li>
                                <li><i class="ace-icon fa fa-check green"></i>Created By: {{ $license->creator->full_name }}</li>
                                <li><i class="ace-icon fa fa-check green"></i>Creation Date: {{ \Carbon\Carbon::parse($license->created_at)->format('d/m/Y H:i:s') }}</li>
                            </ul>
                            <hr>
                            <ul class="list-unstyled spaced2">
                                <li><i class="ace-icon fa fa-check green"></i>Used: <span class="price">{{ $stats['used'] }}</span></li>
                                <li><i class="ace-icon fa fa-check green"></i>
                                    Remaining: <span class="price">{{  $stats['remaining'] }}</span>
                                </li>
                            </ul>
                            @else
                            <i class="fa fa-warning"></i> No license information added.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script>

    $(function(){

        var SITEURL = "{{url('/')}}/";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

    });

</script>

@endsection


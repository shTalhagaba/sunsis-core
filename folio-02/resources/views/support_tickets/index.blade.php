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
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('tickets.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Raise Support Ticket
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            <div class="widget-box transparent ui-sortable-handle">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a title="Export view to Excel" href="">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;
                    </div>
                </div>
                
                <div class="widget-body" style="padding: 0%">
                    <div class="widget-main" style="-webkit-transform:scale(0.85);-moz-transform:scale(0.85);-ms-transform:scale(0.85);transform:scale(0.85);padding: 0px;">
                        <form method="GET" action="" accept-charset="UTF-8"
                            class="form-horizontal" role="form" name="frmFilters" id="frmFilters">

                            <div class="row">
                                <div class="col-xs-3">
                                    {!! Form::label('ticket_number', 'Number', ['class' => 'control-label']) !!}
                                    {!! Form::number('ticket_number', null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-xs-3">
                                    {!! Form::label('ticket_status', 'Status', ['class' => 'control-label']) !!}
                                    {!! Form::select('ticket_status', $statusList, null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                                </div>
                                <div class="col-xs-3">
                                    {!! Form::label('type', 'Type', ['class' => 'control-label']) !!}
                                    {!! Form::select('type', $typesList, null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                                </div>
                                <div class="col-xs-3">
                                    {!! Form::label('customer_priority', 'Priority', ['class' => 'control-label']) !!}
                                    {!! Form::select('customer_priority', $prioritiesList, null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-xs-3">
                                    {!! Form::label('subject', 'Subject', ['class' => 'control-label']) !!}
                                    {!! Form::text('subject', null, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                </div>
                                <div class="col-xs-3">
                                    {!! Form::label('resolved', 'Resolved', ['class' => 'control-label']) !!}
                                    {!! Form::select('resolved', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control ', 'placeholder' => '']) !!}
                                </div>
                                <div class="col-xs-3">
                                    {!! Form::label('exclude_closed_tickets', 'Exclude Closed Tickets', ['class' => 'control-label']) !!}
                                    {!! Form::select('exclude_closed_tickets', [1 => 'Yes', 0 => 'No'], null, ['class' => 'form-control ']) !!}
                                </div>
                            </div>
                            <div class="clearfix" style="margin-top: 5px;">
                                <button class="btn btn-sm btn-round btn-primary" type="submit">
                                    <i class="ace-icon fa fa-search bigger-110"></i>
                                    Search
                                </button>
                                &nbsp; &nbsp; &nbsp;
                                <button class="btn btn-sm btn-round btn-default" type="reset">
                                    <i class="ace-icon fa fa-undo bigger-110"></i>
                                    Reset
                                </button>
                            </div>
                        </form>
                        <hr>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <p class="text-center">
                <img src="{{ asset('images/loading51.gif') }}" alt="Loading" id="loading-container"
                    style="display: none;" />
            </p>

            <div class="row">
                <div class="col-sm-12">
                    <div align="center" class="viewNavigator">
                        <table width="450" id="tblPaginator">
                            <tbody>
                                <tr>
                                    <td width="20%" align="right" id="leftTd">
                                        <button type="button" class="btn btn-sm btn-default" id="firstPage">
                                            <i class="fa fa-step-backward"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default" id="prevPage">
                                            <i class="fa fa-caret-left"></i>
                                        </button>
                                    </td>
                                    <td align="center" width="60%" valign="middle">
                                        page
                                        <div id="divPageSelector" style="display: inline;"><select
                                                id="pageSelector"></select></div>
                                        of <span id="lastPageNumber"></span> (<span id="totalRecords"></span> records)
                                    </td>
                                    <td width="20%" align="left" id="rightTd">
                                        <button type="button" class="btn btn-sm btn-default" id="nextPage">
                                            <i class="fa fa-caret-right"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-default" id="lastPage">
                                            <i class="fa fa-step-forward"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered" id="ticket-table">
                    <thead>
                        <tr>
                            <th>Number</th>
                            <th>Subject</th>
                            <th>Raised By</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Priority</th>
                            <th>Resolved</th>
                            <th>Due Date</th>
                            <th>Logged At</th>
                            <th>Recent Updated At</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
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
        var requestFilters = '{{ json_encode($filters) }}';
    </script>
@endsection

@include('support_tickets.scripts')

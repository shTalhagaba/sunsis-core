@php
$listOfFams = ['ECF', 'MCF', 'EHC', 'LSR'];
foreach($listOfFams AS $fam)
{
    foreach($ilr->LearnerFAM AS $_lFAM)
    {
        if($_lFAM->LearnFAMType->__toString() == $fam && trim($_lFAM->LearnFAMCode->__toString()) != '')
            $batch_file_xml .= "<LearnerFAM><LearnFAMType>{$fam}</LearnFAMType><LearnFAMCode>" . $_lFAM->LearnFAMCode->__toString() . "</LearnFAMCode></LearnerFAM>";
    }
}
@endphp

@extends('layouts.master')
@section('title', 'Profile')
@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<style>
.info-div {
    width: 100%;
    display: table;
    width: 98%;
    width: calc(100% - 24px);
    margin: 0 auto;
}
.info-div-row {
    display: table-row
}
.info-div-name,
.info-div-value {
    display: table-cell;
    border-top: 1px dotted #D5E4F1
}
.info-div-name {
    text-align: right;
    padding: 6px 10px 6px 4px;
    font-weight: 400;
    color: #667E99;
    background-color: transparent;
    /* width: 210px; */
    vertical-align: middle
}
.info-div-value {
    padding: 6px 4px 6px 6px
}
.info-div-value>span+span:before {
    display: inline;
    content: ",";
    margin-left: 1px;
    margin-right: 3px;
    color: #666;
    border-bottom: 1px solid #FFF
}
.info-div-value>span+span.editable-container:before {
    display: none
}
.info-div-row:first-child .info-div-name,
.info-div-row:first-child .info-div-value {
    border-top: none
}
.info-div-striped {
    border: 1px solid #DCEBF7
}

.info-div-striped .info-div-name {
    color: #336199;
    background-color: #EDF3F4;
    border-top: 1px solid #F7FBFF
}

.info-div-striped .info-div-value {
    border-top: 1px dotted #DCEBF7;
    padding-left: 12px
}
@media only screen and (max-width:480px) {

}
</style>
@endsection
@section('breadcrumbs')

@endsection
@section('page-content')



<hr>

<div class="widget-box widget-color-orange">
    @php
        echo Config::get('app.DB_NAME') . '<br>';
        echo getenv('PERSPECTIVE_DB_PASSWORD') . '<br>';
    @endphp
    <div class="widget-header"><span class="widget-title strong">Test Evidence</span><span class="label label-success arrowed-in arrowed-in-right pull-right">Assessor Accepted</span></div>
    <div class="widget-body">
        <div class="widget-main">







            <div class="profile-user-info profile-user-info-striped">
                <div class="profile-info-row">
                    <div class="profile-info-name"> Assessment Method </div>
                    <div class="profile-info-value"><span></span></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-name"> Description </div>
                    <div class="profile-info-value"><span></span></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-name"> Learner Comments </div>
                    <div class="profile-info-value"><span></span></div>
                </div>
                <div class="profile-info-row">
                    <div class="profile-info-name"> Assessor Comments </div>
                    <div class="profile-info-value"><span></span></div>
                </div>
            </div>
            <span class="pull-left">Created: 21/11/2019 16:56:20</span>
            <span class="pull-right">Last Updated: 21/11/2019 16:56:20</span>
            <p></p>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <span onclick="window.location.href='http://folio3/files/17/download';" style="cursor: pointer">
                <i
                data-trigger="hover"
                data-rel="popover"
                data-original-title="5dd6c1336cab3_Blank-File.docx"
                data-content="<small>Desc.: I have uploded two files.&lt;br&gt;Learner Comm.: Please check the uploaded evidences.&lt;br&gt;Assessor Comm.: I am happy.&lt;br&gt;File Size: 12.26 KB&lt;br&gt;&lt;i class=&#039;fa fa-clock-o&#039;&gt;&lt;/i&gt; 21/11/2019 16:55:30&lt;br&gt;</small>"
                class='fa fa-file-word-o fa-2x'>
                </i>
            </span> &nbsp;
        </div>
    </div>
</div>

<hr>
<div class="row">
    <div class="col-sm-4">
        <div class="info-div info-div-striped">
            <div class="info-div-row">
                <div class="info-div-name"> Assessment Method </div>
                <div class="info-div-value"><span>Value of assessment method</span></div>
            </div>
        </div>

    </div>
</div>
@endsection


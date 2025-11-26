@extends('layouts.master')

@section('title', 'Assessor Risk Assessment')

@section('page-content')
    <div class="page-header">
        <h1>Assessors Risk Assessments</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            @if(auth()->user()->isAdmin() || auth()->user()->isVerifier())
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('assessor_risk_assessment.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Risk Assessment
            </button>
            @endif
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-responsive">
                <table id="tblRiskAssessment" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Assessor</th>
                            <th>Date of Observation</th>
                            <th>Date of Last Observation</th>
                            <th>Total Score</th>
                            <th>Overall Grade</th>
                            <th>Completed</th>
                            <th>Created By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records AS $record)
                            <tr onclick="window.location.href='{{ route('assessor_risk_assessment.show', $record) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $record->assessor->full_name }}</td>
                                <td>{{ $record->date_of_observation->format('d/m/Y') }}</td>
                                <td>{{ optional($record->date_of_last_observation)->format('d/m/Y') }}</td>
                                <td>{{ $record->total_score }}</td>
                                <td>{{ $record->overall_grade }}</td>
                                <td>{{ $record->completed ? 'Yes' : 'No' }}</td>
                                <td>{{ $record->creator->full_name }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">No record found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $records])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

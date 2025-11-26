@extends('layouts.master')

@section('title', 'Create FS Test')
@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection


@section('page-content')
    <div class="page-header">
        <h1>
            Create FS Test Session
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                here you can create functional skills test session by selecting functional skills course for the learner.
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', [
                'showOverallPercentage' => true,
                'training' => $training,
            ])

            @include('partials.session_message')
            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                Learner Tests ({{ $training->fsTestSessions()->count() }})
                            </h5>                
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Attempt No.</th>
                                            <th>Complete By</th>
                                            <th>Status</th>
                                            <th>Score</th>
                                            <th>Started At</th>
                                            <th>Completed At</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($training->fsTestSessions as $fsTest)
                                            <tr>
                                                <td>
                                                    {{ $fsTest->course->title }}
                                                </td>
                                                <td>
                                                    {{ $fsTest->attempt_no }}
                                                </td>
                                                <td>
                                                    {{ optional($fsTest->complete_by)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ $fsTest->status }}
                                                </td>
                                                <td>
                                                    {{ $fsTest->score }}
                                                </td>
                                                <td>
                                                    {{ optional($fsTest->started_at)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    {{ optional($fsTest->completed_at)->format('d/m/Y') }}
                                                </td>
                                                <td>
                                                    @if ($fsTest->canBeDeleted() && (auth()->user()->isAdmin() || $fsTest->allocated_by === auth()->user()->id))
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'url' => route('trainings.fs_tests.destroy', [$training, $fsTest]),
                                                        'style' => 'display: inline;',
                                                        'class' => 'form-inline',
                                                    ]) !!}
                                                    {!! Form::button('<i class="ace-icon fa fa-trash-o"></i>', [
                                                        'class' => 'btn btn-xs btn-danger btn-round btnDeleteTest',
                                                        'type' => 'submit',
                                                        'style' => 'display: inline',
                                                    ]) !!}
                                                    {!! Form::close() !!}
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6"><i>No tests have been created for this learner yet.</i></td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::open([
                        'url' => route('trainings.fs_tests.store', $training),
                        'class' => 'form-horizontal',
                        'id' => 'frmFsTest',
                    ]) !!}
                    @include('trainings.fs_tests.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div>
    </div>
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
@endsection
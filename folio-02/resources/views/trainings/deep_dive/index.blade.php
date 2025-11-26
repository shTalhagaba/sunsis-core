@extends('layouts.master')

@section('title', 'Deep Dives')

@section('page-content')
    <div class="page-header">
        <h1>Deep Dives for {{ $training->student->full_name }}</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])
            @include('partials.session_message')
            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Deep Dives</h5>
                            <div class="widget-toolbar">
                                <a class="btn btn-sm btn-round btn-primary"
                                    href="{{ route('trainings.deep_dives.create', ['training' => $training]) }}">
                                    <i class="ace-icon fa fa-plus"></i> Create New Record
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Created At</th>
                                            <th>Date of Deep Dive</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deepDives as $deepDive)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $deepDive->created_at->format('d/m/Y') }}</td>
                                                <td>{{ optional($deepDive->deep_dive_date)->format('d/m/Y') }}</td>
                                                <td>
                                                    <button class="btn btn-xs btn-default btn-round"
                                                        onclick="window.location.href='{{ route('trainings.deep_dives.show', ['training' => $training, 'deep_dive' => $deepDive]) }}'"><i
                                                            class="fa fa-folder-open"></i> View Details</button>
                                                    <button class="btn btn-xs btn-info btn-round"
                                                        onclick="window.location.href='{{ route('trainings.deep_dives.edit', ['training' => $training, 'deep_dive' => $deepDive]) }}'"><i
                                                            class="fa fa-edit"></i> Edit Information</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection



@section('page-inline-scripts')
    <script></script>
@endsection

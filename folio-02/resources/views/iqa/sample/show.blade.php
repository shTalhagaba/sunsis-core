@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-content')
    <div class="page-header">
        <h1>View IQA Sample Plan</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if(!$plan->isCompleted())
            <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.edit', $plan) }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i> Edit 
            </button>
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-5">
                    @include('iqa.sample.plan_basic_details')
                </div>
                <div class="col-sm-7">
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active">
                                <a class="tabbedLink" href="#tabUnits" data-toggle="tab">Units</a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabTrainings" data-toggle="tab">Training Records</a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabIqa" data-toggle="tab">IQA Assessment</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane in active" id="tabUnits">
                                @include('iqa.sample.partials.tab_units')
                            </div>
                            <div class="tab-pane" id="tabTrainings">
                                @include('iqa.sample.partials.tab_trainings')
                            </div>
                            <div class="tab-pane" id="tabIqa">
                                @include('iqa.sample.partials.tab_assessment')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('page-inline-scripts')
    <script>

        $(function(){
            var selectedTab = "{{ session()->get('read_iqa_sample_plan_tab') }}";
            if (selectedTab != '') {
                $('#myTab a[href="#' + selectedTab + '"]').tab('show');
            }

        });

        $('.tabbedLink').click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('saveTabInSession') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    screen: 'read_iqa_sample_plan',
                    selectedTab: $(this).attr('href').replace('#', '')
                }
            });
        }); 

    </script>
@endsection

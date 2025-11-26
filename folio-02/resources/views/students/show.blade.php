@extends('layouts.master')

@section('title', 'Student')

@section('page-plugin-styles')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@if(auth()->user()->isStaff())
@section('breadcrumbs')
    {{ Breadcrumbs::render('students.show', $student) }}
@endsection
@endif

@section('page-content')
    <div class="page-header">
        <h1>Student Detail</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-sm-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ auth()->user()->isStudent() ? route('home') : route('students.index') }}'">
                        <i class="ace-icon fa fa-times bigger-110"></i> Close
                    </button>
                    @can('update-student')
                        <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                            onclick="window.location.href='{{ route('students.edit', $student) }}'">
                            <i class="ace-icon fa fa-edit bigger-120"></i> Edit Student
                        </button>
                    @endcan
                    @can('update-student')
                        <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                            onclick="window.location.href='{{ route('students.manage-access', $student->id) }}'">
                            <i class="ace-icon fa fa-key bigger-120"></i> Manage Access
                        </button>
                    @endcan
                    @can('enrol-student')
                        @if(App\Helpers\AppHelper::enrolmentAllowed())
                        <button class="btn btn-sm btn-primary btn-bold btn-round" type="button" onclick="enrolLearner();">
                            <i class="ace-icon fa fa-graduation-cap bigger-120"></i> Enrol
                        </button>
                        @else
                        <button class="btn btn-sm btn-primary btn-bold btn-round disabled" type="button" disabled="disabled" title="No more available licenses.">
                            <i class="ace-icon fa fa-graduation-cap bigger-120"></i> Enrol
                        </button>
                        @endif                        
                    @endcan

		@if(App\Helpers\AppHelper::requestFromOffice())
                    @can('delete-student')
                        {!! Form::open([
                            'method' => 'DELETE',
                            'url' => route('students.destroy', [$student]),
                            'style' => 'display: inline;',
                            'class' => 'form-inline',
                            'id' => 'frmDeleteStudent',
                        ]) !!}
                        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete', [
                            'class' => 'btn btn-sm btn-danger btn-bold btn-round btnDeleteStudent',
                            'type' => 'submit',
                            'style' => 'display: inline',
                        ]) !!}
                        {!! Form::close() !!}
                    @endcan
		@endif
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-4">
                    <div class="center">
                        <img class="img-responsive img-thumbnail" width="150px;" height="150px;"
                            alt="{{ $student->firstnames }}'s Avatar" id="avatar2" src="{{ $student->avatar_url }}" />
                        <div class="space-4"></div>
                        @include('partials.user_login_status', ['user' => $student])
                        <div class="space-4"></div>
                    </div>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Firstname(s) </div>
                            <div class="info-div-value"><span>{{ $student->firstnames }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Surname </div>
                            <div class="info-div-value"><span>{{ $student->surname }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Gender </div>
                            <div class="info-div-value"><span>{{ $student->gender }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Date of Birth </div>
                            <div class="info-div-value">
                                <span>{{ optional($student->date_of_birth)->format('d/m/Y') }}</span><br>
                                <i class="small">{{ App\Helpers\AppHelper::calculateAge($student->date_of_birth) }}</i>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Ethnicity </div>
                            <div class="info-div-value">
                                <span>{{ App\Models\Lookups\EthnicityLookup::getDescription($student->ethnicity) }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> National Insurance </div>
                            <div class="info-div-value"><span>{{ $student->ni }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> ULN </div>
                            <div class="info-div-value"><span>{{ $student->uln }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Primary Email </div>
                            <div class="info-div-value"><span>{{ $student->primary_email }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Secondary Email </div>
                            <div class="info-div-value"><span>{{ $student->secondry_email }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Employer </div>
                            <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Home Address </div>
                            <div class="info-div-value">
                                {!! $homeAddress->address_line_1 != '' ? '<span>' . $homeAddress->address_line_1 . '</span><br>' : '' !!}
                                {!! $homeAddress->address_line_2 != '' ? '<span>' . $homeAddress->address_line_2 . '</span><br>' : '' !!}
                                {!! $homeAddress->address_line_3 != '' ? '<span>' . $homeAddress->address_line_3 . '</span><br>' : '' !!}
                                {!! $homeAddress->address_line_4 != '' ? '<span>' . $homeAddress->address_line_4 . '</span><br>' : '' !!}
                                {!! $homeAddress->postcode != ''
                                    ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span> ' . $homeAddress->postcode . '</span><br>'
                                    : '' !!}
                                {!! $homeAddress->telephone != ''
                                    ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $homeAddress->telephone . '</span><br>'
                                    : '' !!}
                                {!! $homeAddress->mobile != ''
                                    ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $homeAddress->mobile . '</span><br>'
                                    : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Work Address </div>
                            <div class="info-div-value">
                                {!! $workAddress->address_line_1 != '' ? '<span>' . $workAddress->address_line_1 . '</span><br>' : '' !!}
                                {!! $workAddress->address_line_2 != '' ? '<span>' . $workAddress->address_line_2 . '</span><br>' : '' !!}
                                {!! $workAddress->address_line_3 != '' ? '<span>' . $workAddress->address_line_3 . '</span><br>' : '' !!}
                                {!! $workAddress->address_line_4 != '' ? '<span>' . $workAddress->address_line_4 . '</span><br>' : '' !!}
                                {!! $workAddress->postcode != ''
                                    ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $workAddress->postcode . '</span><br>'
                                    : '' !!}
                                {!! $workAddress->telephone != ''
                                    ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $workAddress->telephone . '</span><br>'
                                    : '' !!}
                                {!! $workAddress->mobile != ''
                                    ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $workAddress->mobile . '</span><br>'
                                    : '' !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"></div>
                            <div class="info-div-value">
                                @if ($student->fb_id != '')
                                    <a target="_blank" href="https://www.facebook.com/{{ $student->fb_id }}"><i
                                            class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i></a>
                                @endif
                                &nbsp;&nbsp;&nbsp;
                                @if ($student->twitter_handle != '')
                                    <a target="_blank" href="https://twitter.com/{{ $student->twitter_handle }}"><i
                                            class="middle ace-icon fa fa-twitter-square bigger-150 light-blue"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Access & Login</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Username </div>
                                        <div class="info-div-value"><span>{{ $student->username }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Web Access </div>
                                        <div class="info-div-value">
                                            {!! $student->isActive() == '1'
                                                ? '<span class="label label-success">Enabled</span>'
                                                : '<span class="label label-danger">Disabled</span>' !!}
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Last Login </div>
                                        <div class="info-div-value">
                                            @if ($student->authentications()->count() > 0)
                                                <span>{{ optional($student->latestAuth)->login_at }} from
                                                    {{ optional($student->latestAuth)->ip_address }}</span>
                                            @else
                                                <i>Not yet logged in to the system</i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-8">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h5 class="widget-title">Tags</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if( auth()->user()->isStaff() )
                                    @include('partials.modal-assign-tag', [
                                        'tagged_entity' => $student,
                                        'modal_assign_tags_options' => [
                                            'Student' => App\Models\Tags\Tag::whereType('Student')->whereNotIn('id', $student->tags()->pluck('id')->toArray())->pluck('name', 'id')->toArray(),
                                        ],
                                    ])    
                                @endif

                                <div class="space-4"></div>
                                @forelse ($student->tags as $tag)
                                    <h5 role="student_tag" style="display: inline;">
                                        <span class="label label-info label-xlg" style="display: margin-right: 5px; margin-bottom: 5px;">
                                            <i class="fa fa-tag"></i> {{ $tag->name }} &nbsp;
                                            @if( auth()->user()->isStaff() )
                                            <i class="fa fa-times" style="cursor: pointer;"
                                                title="Detach this tag"
                                                onclick="remove_tag('{{ $tag->id }}', 'App\\Models\\User', '{{ $student->id }}');"></i>
                                            @endif
                                        </span>
                                    </h5>
                                @empty
                                    <span class="text-info">
                                        <i class="fa fa-info-circle"></i> <i>No tags have been assigned to this
                                            record.</i>
                                    </span>
                                @endforelse
                                
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-4"></div>
                    
                    <div class="widget-box widget-color-green" id="widget-box-trs">
                        <div class="widget-header">
                            <h5 class="widget-title">Training Records <span
                                    class="badge badge-info">{{ $student->training_records->count() }}</span></h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @foreach ($trs as $training_record)
                                    <div class="table-responsive">
                                        <div class="widget-box" id="widget-box-tr">
                                            <div class="widget-header">
                                                <h5 class="widget-title smaller">
                                                    {{ $training_record->system_ref }} |
                                                    {{ $training_record->start_date->format('d/m/Y') }} -
                                                    {{ $training_record->planned_end_date->format('d/m/Y') }} |
                                                    <span
                                                        class="label label-sm label-info arrowed-in arrowed-in-right">{{ $training_record->trainingStatus->description }}</span>
                                                </h5>
                                                <div class="widget-toolbar">
                                                    @can('read-training-record')
                                                        <a class="btn btn-xs btn-round btn-primary" href="{{ route('trainings.show', $training_record) }}">
                                                            <i class="ace-icon fa fa-folder-open"></i> View Record
                                                        </a>                                                            
                                                    @endcan
                                                    <span class="label label-success">
                                                        {{ $training_record->signedOffPercentage() }}% </span>
                                                </div>
                                            </div>

                                            <div class="widget-body">
                                                <div class="widget-main">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <strong>Employer:</strong>
                                                            @php $loc = $training_record->location @endphp
                                                            {{ $training_record->employer->legal_name }} |
                                                            {!! $loc->postcode != ''
                                                                ? '<i class="fa fa-map-marker light-orange bigger-110"></i><span>' . $loc->postcode . '</span><br>'
                                                                : '' !!}
                                                            <div class="table-responsive">
                                                                <table
                                                                    class="table table-striped table-bordered table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Learning Aim</th>
                                                                            <th>Progress</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($training_record->portfolios as $portfolio)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $portfolio->qan }}<br>{{ $portfolio->title }}<br>
                                                                                    @include(
                                                                                        'trainings.partials.entity_progress_bar',
                                                                                        [
                                                                                            'entity' => $portfolio,
                                                                                        ]
                                                                                    )
                                                                                </td>
                                                                                <td>
                                                                                    <div class="easy-pie-chart percentage"
                                                                                        data-percent="{{ $portfolio->signedOffPCsPercentage() }}"
                                                                                        data-color="#CA5952">
                                                                                        <span
                                                                                            class="percent">{{ $portfolio->signedOffPCsPercentage() }}</span>%
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div> {{-- table-responsive --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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
    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $('.easy-pie-chart.percentage').each(function() {
            var barColor = '#50C878';
            var trackColor = '#E2E2E2';
            var size = parseInt($(this).data('size')) || 72;
            $(this).easyPieChart({
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size / 10),
                animate: false,
                size: size
            }).css('color', barColor);
        });

	@if(App\Helpers\AppHelper::requestFromOffice())	
        $(".btnDeleteStudent").on('click', function(e) {
            e.preventDefault();
            
            var form = this.closest('form');

            $.confirm({
                title: 'Confirm!',
                content: 'This action is irreversible, are you sure you want to continue?',
                icon: 'fa fa-question-circle',
                animation: 'scale',
                closeAnimation: 'scale',
                theme: 'supervan',
                opacity: 0.5,
                buttons: {
                    'confirm': {
                        text: 'Yes',
                        btnClass: 'btn-red',
                        action: function() {
                            $.ajax({
                                url: form.action,
                                type: form.method,
                                data: $(form).serialize()
                            }).done(function(response, textStatus) {
                                console.log(response);
                                console.log(textStatus);
                                $.alert({
                                    title: (textStatus == 'success' && response
                                        .success) ? 'Success' : 'Error',
                                    content: response.message,
                                    type: (textStatus == 'success' && response
                                        .success) ? 'green' : 'red',
                                    icon: (textStatus == 'success' && response
                                        .success) ? 'fa fa-check' : 'fa fa-warning',
                                    buttons: {
                                        'OK': {
                                            action: function() {
                                                if (textStatus == 'success' &&
                                                    response.success)
                                                    window.location.href =
                                                    '{{ route('students.index') }}';
                                                else
                                                    window.location.reload();
                                            }
                                        }
                                    }
                                });
                            }).fail(function(jqXHR, textStatus, errorThrown) {
                                $.alert({
                                    title: 'Encountered an error!',
                                    content: textStatus + ': ' + errorThrown,
                                    icon: 'fa fa-warning',
                                    theme: 'supervan',
                                    type: 'red'
                                });
                            });
                        }
                    },
                    cancel: function() {}
                }
            });
        });
	@endif

        function enrolLearner() {
            window.location.href = '{{ route('students.singleEnrolment.step1', $student->id) }}';
        }
    </script>

@endsection

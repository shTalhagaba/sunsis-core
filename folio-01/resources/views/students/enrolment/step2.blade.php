@extends('layouts.master')

@section('title', 'Single Enrolment - Step 2')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
    hr {
        padding: 0px;
        margin: 0px;
    }

    input[type=checkbox] {
        transform: scale(1.4);
    }
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.singleEnrolment.step2.show', $student) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Enrol Single Learner - Step 2</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Step 2<br>
                    <i class="fa fa-hand-o-right"></i> <small>Select units and elements for each qualification (portfolio) which you have selected in Step 1.</small><br>
                    <i class="fa fa-hand-o-right"></i> <small>Click on 'Continue to Step 3' to proceed to the next step.</small>
                 </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="smaller">Student Details</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Name </div>
                                    <div class="profile-info-value"><span>{{ $student->full_name }}</span></div>
                                    <div class="profile-info-name"> Employer </div>
                                    <div class="profile-info-value"><span>{{ $student->employer->legal_name }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> ULN </div>
                                    <div class="profile-info-value"><span>{{ $student->uln }}</span></div>
                                    <div class="profile-info-name"> NI </div>
                                    <div class="profile-info-value"><span>{{ $student->ni }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="smaller">Selected Qualification(s)</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            @foreach($portfolios AS $key => $value)
                            @php $_q = App\Models\Qualifications\Qualification::findOrFail($key) @endphp
                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> QAN & Title </div>
                                    <div class="profile-info-value"><span>{{ $_q->qan }} {{ $_q->title }}</span></div>
                                    <div class="profile-info-name"> Start Date </div>
                                    <div class="profile-info-value">
                                        <span>{{ \Carbon\Carbon::parse($value['start_date'])->format('d/m/Y') }}</span></div>
                                    <div class="profile-info-name"> Planned End Date </div>
                                    <div class="profile-info-value">
                                        <span>{{ \Carbon\Carbon::parse($value['planned_end_date'])->format('d/m/Y') }}</span></div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="space-4"></div>

        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> All mandatory units will be added. You can select the optional units to add into the training record.
                </div>
            </div>
        </div>

        {!! Form::open([
            'url' => route('students.singleEnrolment.step2.store', $student),
            'class' => 'form-horizontal',
            'role' => 'form',
            'id' => 'frmEnrolmentS2',
            'method' => 'POST'
        ]) !!}
        {!! Form::hidden('portfolios', json_encode($portfolios)) !!}

        @foreach($portfolios AS $key => $value)
        @php $_q = App\Models\Qualifications\Qualification::findOrFail($key) @endphp
        <div class="col-sm-12">
            <div class="widget-box transparent collapsed">
                <div class="widget-header">
                    <h5 class="widget-title"><i class="fa fa-graduation-cap"></i> {{ $_q->qan }} {{ $_q->title }}</h5> &nbsp;
                    <span class="badge badge-success">M: {{ $_q->units()->where('unit_group', 1)->count() }}</span>
                    <span class="badge badge-info">O: {{ $_q->units()->where('unit_group', 2)->count() }}</span>
                    <div class="widget-toolbar">
                        <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                @foreach($_q->units()->orderBy('unit_sequence')->get() AS $unit)
                                    <tr>
                                        @if ($unit->getOriginal('unit_group') == 1)
                                        <th class="center">
                                            <i class="fa fa-check-circle green fa-2x" data-rel="tooltip" title="This unit is mandatory and will be added automatically."></i>
                                            <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" type="checkbox" checked style="display: none;" />
                                        </th>
                                        <th class="text-success"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @else
                                        <th class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}"
                                                        class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-info"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @endif
                                    </tr>
                                    @foreach($unit->pcs AS $pc)
                                    <tr>
                                        @if ($unit->getOriginal('unit_group') == 1)
                                        <td class="center">
                                            <i class="fa fa-check-circle green  fa-1x" data-rel="tooltip" title="This pc is mandatory and will be added automatically."></i>
                                            <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}" type="checkbox" checked style="display: none;" />
                                        </td>
                                        <td class="text-success"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                        @else
                                        <td class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}"
                                                        value="{{ $pc->id }}" class="ace ace-checkbox-2 chkPC" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-info"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="space-8"></div>
        </div>
        @endforeach
        <div class="col-sm-12 form-actions center">
            <button class="btn btn-sm btn-success btn-round" type="submit">
                <i class="ace-icon fa fa-arrow-right bigger-110"></i> Continue to Step 3
            </button> &nbsp; &nbsp; &nbsp;
            <button class="btn btn-sm btn-round" type="reset">
                <i class="ace-icon fa fa-undo bigger-110"></i> Reset
            </button>
        </div>

        {!! Form::close() !!}

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
$(function(){

    $('input[type="checkbox"][name="chkPC[]"]').each(function(){
        if(this.checked)
        {
            var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
    });

    $('input[type=checkbox][id^=chkUnit]').on('click', function(){
        var unit_number = this.id.replace('chkUnit', '');
        if(this.checked)
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', true);
            });
        }
        else
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', false);
            });
        }

    });

    $('input[type="checkbox"][name="chkPC[]"]').on('click', function(){
        var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
        if(this.checked) // if pc is clicked then check the Unit checkbox too.
        {
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
        else // if all pcs of a unit are unticked then untick the unit
        {
            var allPCUnChecked = true;
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                if(this.checked)
                {
                    allPCUnChecked = false;
                    return false;
                }
            });
            if(allPCUnChecked)
            {
                $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', false);
            }
        }

    });

});

function refreshSelectedUnitsTotal(e)
{
    var $lbl = $(e).closest('table').find('i#qu');

    var selectedUnits = 0;
    $('input[type=checkbox][id^=chkUnit]').each(function(){
        if(this.checked)
            selectedUnits++;
    });

    $lbl.html(selectedUnits);
}

$(function(){
    $('#frmEnrolmentS2').validate({
        rules: {
            "chkPC[]": {
                required: true,
                minLength: 1
            }
        },
        messages: {
            "chkPC[]": "Please select at least one performance criteria."
        },
        errorPlacement: function (error, element) {
            $.alert(error.text(), 'Validation Error');
        }
    });
});
</script>

@endsection

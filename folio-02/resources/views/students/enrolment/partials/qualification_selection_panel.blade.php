<div class="widget-box ">
    <div class="widget-header">
        <h4 class="widget-title bolder">
            <i class="fa fa-graduation-cap"></i> 
            {{ $programmeQualification->qan }} {{ $programmeQualification->title }}
        </h4>
        
        <div class="widget-toolbar">
            <a href="#" data-action="collapse"><i
                    class="ace-icon fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row">
                {!! Form::label('qualification', 'Add this qualification', [
                    'class' => 'col-sm-4 control-label',
                ]) !!}
                <div class="col-sm-8">
                    <label>
                        <input name="qualifications[]" class="ace ace-switch ace-switch-6 input-lg" type="checkbox" value="{{ $programmeQualification->id }}" 
                        {{ (is_array(old('qualifications')) && in_array($programmeQualification->id, old('qualifications'))) ? ' checked' : '' }} >
                        <span class="lbl"></span>
                    </label>
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('start_date_qual_' . $programmeQualification->id, 'Start Date', [
                    'class' => 'col-sm-4 control-label',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::date('start_date_qual_' . $programmeQualification->id, $programmeQualificationStartDate, ['class' => 'form-control']) !!}
                    {!! $errors->first('start_date_qual_' . $programmeQualification->id, '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row">
                {!! Form::label('planned_end_date_qual_' . $programmeQualification->id, 'Planned End Date', [
                    'class' => 'col-sm-4 control-label',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::date('planned_end_date_qual_' . $programmeQualification->id, $programmeQualificationPlannedEndDate, [
                        'class' => 'form-control',
                    ]) !!}
                    {!! $errors->first('planned_end_date_qual_' . $programmeQualification->id, '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
	        @if ($programmeQualification->isFsQualification())
            <div class="form-group row">
                {!! Form::label('tutor_qual_' . $programmeQualification->id, 'Tutor', [
                    'class' => 'col-sm-4 control-label'
                    ]) !!}
                <div class="col-sm-8">
                    {!! Form::select('tutor_qual_' . $programmeQualification->id, ['' => ''] + $tutors,  null, ['class' => 'form-control']) !!}
                    {!! $errors->first('tutor_qual_' . $programmeQualification->id, '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            @endif
            <div class="form-group row">
                {!! Form::label('verifier_qual_' . $programmeQualification->id, 'Verifier', [
                    'class' => 'col-sm-4 control-label'
                    ]) !!}
                <div class="col-sm-8">
                    {!! Form::select('verifier_qual_' . $programmeQualification->id, ['' => ''] + $verifiers,  null, ['class' => 'form-control']) !!}
                    {!! $errors->first('verifier_qual_' . $programmeQualification->id, '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <p class="text-info">
                <i class="fa fa-info-circle-o"></i> 
                @if($programmeQualification->optionalUnitsCount() > 0)
                This qualification has {{ $programmeQualification->optionalUnitsCount() }} optional {{ \Str::plural('unit', $programmeQualification->optionalUnitsCount()) }}. 
                You can select which optional units to add.
                @else
                This qualification has no optional units. 
                @endif
            </p>
            {{-- @foreach ($programmeQualification->units()->orderBy('unit_sequence')->get() as $unit) --}}
            @foreach ($programmeQualification->units as $unit)
            <table class="table table-bordered">
                <tr>
                    <td class="center" style="width: 2%" align="center">
                        @if ($unit->isMandatory())
                        <br>
                            <i class="fa fa-check-circle green fa-2x" data-rel="tooltip"
                                title="This unit is mandatory and will be added automatically."></i>
                            <input name="chkUnit[]" id="chkUnit{{ $unit->id }}"
                                value="{{ $unit->id }}" type="checkbox" checked
                                style="display: none;" /> 
                            &nbsp;                             
                        @else
                        <br>
                            <div class="checkbox" title="Optional unit">
                                <label>
                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}"
                                        value="{{ $unit->id }}"
                                        class="chkUnit ace input-lg"
                                        {{ (is_array(old('chkUnit')) && in_array($unit->id, old('chkUnit'))) ? ' checked' : '' }}
                                        type="checkbox" />
                                    <span class="lbl"> </span>
                                </label>
                            </div> 
                            &nbsp; 
                        @endif
                    </td>
                    <td>
                        <div class="widget-box transparent collapsed">
                            <div class="widget-header">
                                <h5 class="widget-title">
                                    <i class="fa fa-folder fa-lg"></i>
                                    [{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}]
                                    {{ $unit->title }}
                                </h5>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="collapse" title="Click to view the list of criteria of this unit."><i
                                            class="ace-icon fa fa-chevron-down"></i></a>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main">
                                    {{-- @forelse ($unit->pcs()->orderBy('pc_sequence')->get() as $pc) --}}
                                    @forelse ($unit->pcs as $pc)
                                        [{{ $pc->reference }}] {!! nl2br(e($pc->title)) !!}<hr style="margin-top: 10px; margin-bottom: 10px">
                                    @empty
                                        <span class="text-danger">
                                            <i class="fa fa-triangle"></i> 
                                            <i>This unit ([{{ $unit->unit_owner_ref }},&nbsp;{{ $unit->unique_ref_number }}] {{ $unit->title }}) has no criteria.</i>
                                        </span>                                        
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>            
            @endforeach
        </div>
    </div>
</div>

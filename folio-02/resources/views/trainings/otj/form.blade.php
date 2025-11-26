@push('after-styles')
    <style>
        input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>
@endpush

<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('title', null, [
                                        'class' => 'form-control',
                                        'required',
                                        'rows' => '5',
                                        'id' => 'title',
                                        'maxlength' => 500,
                                    ]) !!}
                                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('type') ? 'has-error' : '' }}">
                                {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('type', $otjTypes, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('date') ? 'has-error' : '' }}">
                                {!! Form::label('date', 'Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('date', null, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_time') ? 'has-error' : '' }}">
                                {!! Form::label('start_time', 'Start Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time(
                                        'start_time',
                                        isset($otj->start_time) ? \Carbon\Carbon::parse($otj->start_time)->format('H:i') : null,
                                        ['class' => 'form-control', 'required'],
                                    ) !!}
                                    {!! $errors->first('start_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('duration') ? 'has-error' : '' }}">
                                {!! Form::label('duration', 'Duration', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('duration', isset($otj->duration) ? \Carbon\Carbon::parse($otj->duration)->format('H:i') : null, [
                                        'class' => 'form-control',
                                        'required',
                                    ]) !!}
				                    <p class="small text-info">
                                        <i class="fa fa-info-circle"></i>
                                        Please only record the active time spent on the activity. Breaks and idle time should not be included.
                                    </p>
                                    {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            @if(!isset($otj))
                            <div class="form-group row {{ $errors->has('otj_evidence') ? 'has-error' : '' }}">
                                {!! Form::label('otj_evidence', 'Evidence', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    @include(
                                        'partials.ace_file_control',
                                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'otj_evidence', 'aceFileControlName' => 'otj_evidence']
                                    )
                                    {!! $errors->first('otj_evidence', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row {{ $errors->has('details') ? 'has-error' : '' }}">
                                {!! Form::label('details', 'Details', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('details', null, [
                                        'class' => 'form-control',
                                        'rows' => '15',
                                        'id' => 'details',
                                        'maxlength' => 1200,
                                    ]) !!}
                                    {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

		    <div class="row">
                        <div class="col-sm-12">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title">Select KSB Elements if required</h4>
                                    <div class="widget-toolbar">
                                        <div class="widget-menu">
                                            <a href="#" data-action="collapse">
                                                <i class="ace-icon fa fa-chevron-up"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div class="table-responsive">
                                            <table id="tblPcs" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th class="center"></th>
                                                        <th>Category</th>
                                                        <th>Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($ksbElements AS $ksbElement)
                                                    <tr class="{{ in_array($ksbElement->portfolio_pc_id, $selectedKsbElements) ? 'bg-info' : '' }}">
                                                        <td class="center">
                                                            <label class="pos-rel">
                                                                <input type="checkbox" name="ksbElements[]" {{ in_array($ksbElement->portfolio_pc_id, $selectedKsbElements) ? 'checked' : '' }}
                                                                    value="{{ $ksbElement->portfolio_pc_id }}" />
                                                                <span class="lbl"></span>
                                                            </label>
                                                        </td>
                                                        <td>{{ $ksbElement->portfolio_pc_category != '' ? \App\Models\LookupManager::getQualificationUnitPcCategory($ksbElement->portfolio_pc_category) : '' }}</td>
                                                        <td>{!! nl2br(e($ksbElement->portfolio_pc_title)) !!}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>                                            
                                        </div>            
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            @if ( auth()->user()->isAssessor() )
                                <div class="form-group row required {{ $errors->has('status') ? 'has-error' : '' }}">
                                    {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('status', ['Accepted' => 'Accepted', 'Referred' => 'Referred'], null, [
                                            'class' => 'form-control',
                                            'placeholder' => '',
                                            'required'
                                        ]) !!}
                                        {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('is_otj') ? 'has-error' : '' }}">
                                    {!! Form::label('is_otj', 'Is OTJ Log', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('is_otj', [1 => 'Yes', 0 => 'No'], null, [
                                            'class' => 'form-control',
                                            'required'
                                        ]) !!}
                                        {!! $errors->first('is_otj', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('assessor_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('assessor_comments', null, [
                                            'class' => 'form-control',
                                            'rows' => '10',
                                            'id' => 'assessor_comments',
                                            'maxlength' => 1200,
                                            'required'
                                        ]) !!}
                                        {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                {{-- <div class="form-group row">
                                    <div class="col-sm-4"></div>
                                    <div class="col-sm-8">
                                        <span class="text-info">
                                            <i class="fa fa-info-circle"></i>
                                            Once and evidence is accepted, it cannot be edited afterwards.
                                        </span>
                                    </div>
                                </div> --}}
                            	@elseif(isset($otj))
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <h4>Feedback Details:</h4>
                                        <div class="info-div info-div-striped">
                                            <div class="info-div-row">
                                                <div class="info-div-name">Status</div>
                                                <div class="info-div-value">{{ $otj->status }}</div>
                                            </div>
                                            <div class="info-div-row">
                                                <div class="info-div-name">Assessor Comments</div>
                                                <div class="info-div-value">{!! nl2br(e($otj->assessor_comments)) !!}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($isEditable)
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                        </button>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('after-styles')
    <style>
        input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>
@endpush

<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="widget-title">Session Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('session_number') ? 'has-error' : ''}}">
                        {!! Form::label('session_number', 'Session Number', ['class' => 'col-sm-3 control-label']) !!}
                        <div class="col-sm-9">
                            {!! Form::text('session_number', null, ['class' => 'form-control', 'required', 'maxlength' => 5]) !!}
                            {!! $errors->first('session_number', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    {{-- <div class="form-group row required {{ $errors->has('session_sequence') ? 'has-error' : ''}}">
                        {!! Form::label('session_sequence', 'Session Sequence', ['class' => 'col-sm-3 control-label', 'required']) !!}
                        <div class="col-sm-9">
                            {!! Form::number('session_sequence', null, ['class' => 'form-control', 'min' => 1]) !!}
                            {!! $errors->first('session_sequence', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div> --}}
                    <div class="form-group row required {{ $errors->has('session_start_date') ? 'has-error' : '' }}">
                        {!! Form::label('session_start_date', 'Start/Planned Date', ['class' => 'col-sm-3 control-label no-padding-right']) !!}
                        <div class="col-sm-9">
                            {!! Form::date('session_start_date', null, ['class' => 'form-control', 'required']) !!}
                            {!! $errors->first('session_start_date', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                     <div class="form-group required row {{ $errors->has('session_type') ? 'has-error' : '' }}">
                                    {!! Form::label('session_type', 'Visit Type', ['class' => 'col-sm-3 control-label no-padding-right']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::select('session_type', [
                                            'face_to_face' => 'Face-to-Face',
                                            'remote' => 'Remote'
                                        ], null, [
                                            'class' => 'form-control',
                                            'required'
                                        ]) !!}
                                        {!! $errors->first('session_type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                            </div>
                    <div class="form-group row {{ $errors->has('session_details_1') ? 'has-error' : ''}}">
                        {!! Form::label('session_details_1', 'Details / Heading 1', ['class' => 'col-sm-3 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('session_details_1', null, ['class' => 'form-control inputLimiter',
                            'maxlength' => '1600']) !!}
                            {!! $errors->first('session_details_1', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('session_details_2') ? 'has-error' : ''}}">
                        {!! Form::label('session_details_2', 'Details / Heading 2', ['class' => 'col-sm-3 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-9">
                            {!! Form::textarea('session_details_2', null, ['class' => 'form-control inputLimiter',
                            'maxlength' => '1600']) !!}
                            {!! $errors->first('session_details_2', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('session_pcs') ? 'has-error' : ''}}">
                        {!! Form::label('session_pcs', 'Performance Criteria', ['class' => 'col-sm-3 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-9">
			    {{-- 
                            <div class="table-responsive">
                                <table id="tblPcs" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="center"></th>
                                            <th>Category</th>
                                            <th>Description</th>
                                            <th>Hours</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($elements AS $element)
                                        <tr class="{{ in_array($element->id, $selectedElements) ? 'bg-info' : '' }}">
                                            <td class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" name="elements[]" {{ in_array($element->id, $selectedElements) ? 'checked' : '' }}
                                                        value="{{ $element->id }}" />
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>{{ $element->category != '' ? \App\Models\LookupManager::getQualificationUnitPcCategory($element->category) : '' }}</td>
                                            <td>{!! nl2br(e($element->title)) !!}</td>
                                            <td class="center"><h4 class="larger">{{ $element->delivery_hours }}</h4></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            --}}
                            @include('trainings.sessions.partials.pc_selection_table')
                            {!! $errors->first('session_pcs', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                </div>

                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-round btn-success" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>
                            Save Information
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

@push('after-scripts')
    <script>
        $(function(){
            $('input[name="elements[]"]').on('change', function(){
                if(this.checked)
                {
                    $(this).closest('tr').addClass('bg-info');
                }
                else
                {
                    $(this).closest('tr').removeClass('bg-info');
                }
            });
        });
    </script>
@endpush

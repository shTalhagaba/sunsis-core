@push('after-styles')
    <style>
        input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>
@endpush

<div class="widget-box">
    <div class="widget-header">
        <h4 class="smaller">Task Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                {!! Form::label('title', 'Task Title', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => '255']) !!}
                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : '' }}">
                {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::date('start_date', $task->start_date ?? null, ['class' => 'form-control', 'required']) !!}
                    {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('complete_by') ? 'has-error' : '' }}">
                {!! Form::label('complete_by', 'Completion Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::date('complete_by', $task->complete_by ?? null, ['class' => 'form-control', 'required']) !!}
                    {!! $errors->first('complete_by', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('details') ? 'has-error' : '' }}">
                {!! Form::label('details', 'Details', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('details', null, [
                        'class' => 'form-control',
                        'rows' => '15',
                        'id' => 'details',
                    ]) !!}
                    {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('tr_task_files') ? 'has-error' : '' }}">
                {!! Form::label('tr_task_files', 'File / Resource', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    @include(
                        'partials.ace_file_control',
                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'tr_task_files', 'aceFileControlName' => 'tr_task_files']
                    )
                    {!! $errors->first('tr_task_files', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('task_pcs') ? 'has-error' : ''}}">
                {!! Form::label('task_pcs', 'Performance Criteria', ['class' => 'col-sm-3 control-label
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
                                            <input type="checkbox" name="task_pcs[]" {{ in_array($element->id, $selectedElements) ? 'checked' : '' }}
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
                    @include('trainings.sessions.partials.pc_selection_table', [
                        'already_selected_pcs' => $already_selected_pcs ?? [],
                        'already_selected_units_ids' => $already_selected_units_ids ?? [],
                    ])
                    {!! $errors->first('task_pcs', '<p class="text-danger">:message</p>') !!}
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

@push('after-scripts')
    <script>
        $(function(){
            $('input[name="task_pcs[]"]').on('change', function(){
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

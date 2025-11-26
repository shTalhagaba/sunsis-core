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
                            <div class="form-group row {{ $errors->has('type_of_contact') ? 'has-error' : '' }}">
                                {!! Form::label('type_of_contact', 'Type of Contact', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('type_of_contact', App\Models\LookupManager::getCrmTypeOfContacts(), null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type_of_contact', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('subject') ? 'has-error' : '' }}">
                                {!! Form::label('subject', 'Subject', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('subject', App\Models\LookupManager::getCrmSubjects(), null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('subject', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('date_of_contact') ? 'has-error' : '' }}">
                                {!! Form::label('date_of_contact', 'Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date(
                                        'date_of_contact', 
                                        isset($crmNote) ? optional($crmNote->date_of_contact)->format('Y-m-d') : null, 
                                        ['class' => 'form-control', 'required']) 
                                    !!}
                                    {!! $errors->first('date_of_contact', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('time_of_contact') ? 'has-error' : '' }}">
                                {!! Form::label('time_of_contact', 'Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('time_of_contact', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('time_of_contact', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div
                                class="form-group row required {{ $errors->has('by_whom') ? 'has-error' : '' }}">
                                {!! Form::label('by_whom', 'By Whom', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('by_whom', $byWhom ?? auth()->user()->full_name, ['class' => 'form-control ', 'required', 'maxlength' => '70']) !!}
                                    {!! $errors->first('by_whom', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row {{ $errors->has('crm_note_attachment') ? 'has-error' : '' }}">
                                {!! Form::label('crm_note_attachment', 'Attach File', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    @include(
                                        'partials.ace_file_control',
                                        ['aceFileControlRequired' => false, 'aceFileControlId' => 'crm_note_attachment', 'aceFileControlName' => 'crm_note_attachment']
                                    )
                                    {!! $errors->first('crm_note_attachment', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>                            
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group row required {{ $errors->has('details') ? 'has-error' : '' }}">
                                {!! Form::label('details', 'Details *', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('details', null, [
                                        'class' => 'form-control',
                                        'rows' => '15',
                                        'required'
                                    ]) !!}
                                    {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

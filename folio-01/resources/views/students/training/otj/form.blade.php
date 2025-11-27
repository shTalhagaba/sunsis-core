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
                            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                                 {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::text('title', null, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => '250']) !!}
                                     {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('type') ? 'has-error' : ''}}">
                                {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('type', $otj_types, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('date') ? 'has-error' : ''}}">
                                 {!! Form::label('date', 'Date', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::date('date', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('date', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_time') ? 'has-error' : ''}}">
                                 {!! Form::label('start_time', 'Start Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::time('start_time', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('start_time', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('duration') ? 'has-error' : ''}}">
                                 {!! Form::label('duration', 'Duration', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::time('duration', null, ['class' => 'form-control', 'required']) !!}
                                     {!! $errors->first('duration', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            <div class="form-group row {{ $errors->has('otj_evidence') ? 'has-error' : ''}}">
                                 {!! Form::label('otj', 'Evidence', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                 <div class="col-sm-8">
                                     {!! Form::file('otj_evidence', null, ['class' => 'form-control']) !!}
                                     {!! $errors->first('otj_evidence', '<p class="text-danger">:message</p>') !!}
                                 </div>
                            </div>
                            @if(isset($otj) && $otj->media->count() > 0)
                            <div class="form-group row">
                                {!! Form::label('otj', 'Evidence Uploaded', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    <a href="{{ route('files.download',  $otj->media->first()) }}" target="_blank" style="cursor: pointer;">
                                        <i class="fa fa-cloud-download"></i> {{ $otj->media->first()->file_name }}
                                    </a>
                                </div>
                            </div>
                            @endif
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group row {{ $errors->has('details') ? 'has-error' : ''}}">
                                {!! Form::label('details', 'Details', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('details', null, ['class' => 'form-control inputLimiter', 'rows' => '15', 'id' => 'details', 'maxlength' => 1200]) !!}
                                    {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                                </div>
                           </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr>
                            @if(!auth()->user()->isStudent())
                            <div class="form-group row required {{ $errors->has('status') ? 'has-error' : ''}}">
                                {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('status', ['Submitted' => 'Submitted', 'Accepted' => 'Accepted', 'Referred' => 'Referred'], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                                </div>
                           </div>
                            <div class="form-group row {{ $errors->has('assessor_comments') ? 'has-error' : ''}}">
                                {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('assessor_comments', null, ['class' => 'form-control inputLimiter', 'rows' => '10', 'id' => 'details', 'maxlength' => 1200]) !!}
                                    {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                </div>
                           </div>
                           @elseif(auth()->user()->isStudent() && isset($otj) )
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
                                            <div class="info-div-value">{!! nl2br($otj->assessor_comments) !!}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           @endif
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

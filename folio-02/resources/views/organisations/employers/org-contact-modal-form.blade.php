{!! Form::hidden('id', null, ['id' => 'id']) !!}
{!! Form::hidden('organisation_id', $organisation->id) !!}


<div class="form-group row required {{ $errors->has('location_id') ? 'has-error' : ''}}">
   {!! Form::label('location_id', 'Location', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::select('location_id',  $organisation->locations()->orderBy('title')->pluck('title', 'id'), null, ['class' => 'form-control col-xs-10 col-sm-5', 'required' => 'required']) !!}
       {!! $errors->first('location_id', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
   {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('title', null, ['class' => 'form-control col-xs-10 col-sm-5', 'required' => 'required', 'maxlength' => '8']) !!}
       {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row required {{ $errors->has('firstnames') ? 'has-error' : ''}}">
   {!! Form::label('firstnames', 'First Name', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('firstnames', null, ['class' => 'form-control col-xs-10 col-sm-5', 'required' => 'required', 'maxlength' => '70']) !!}
       {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row required {{ $errors->has('surname') ? 'has-error' : ''}}">
   {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('surname', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '70']) !!}
       {!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row {{ $errors->has('job_title') ? 'has-error' : ''}}">
   {!! Form::label('job_title', 'Job Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('job_title', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '50']) !!}
       {!! $errors->first('job_title', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row {{ $errors->has('department') ? 'has-error' : ''}}">
   {!! Form::label('department', 'Department', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('department', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '70']) !!}
       {!! $errors->first('department', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row required {{ $errors->has('telephone') ? 'has-error' : ''}}">
   {!! Form::label('telephone', 'Telephone', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('telephone', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '20', 'required' => 'required']) !!}
       {!! $errors->first('telephone', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row {{ $errors->has('mobile') ? 'has-error' : ''}}">
   {!! Form::label('mobile', 'Mobile', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::text('mobile', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '20']) !!}
       {!! $errors->first('mobile', '<p class="text-danger">:message</p>') !!}
   </div>
</div>
<div class="form-group row {{ $errors->has('email') ? 'has-error' : ''}}">
   {!! Form::label('email', 'Email', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
   <div class="col-sm-8">
       {!! Form::email('email', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
       {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
   </div>
</div>

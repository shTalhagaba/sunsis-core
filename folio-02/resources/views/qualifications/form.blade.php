<div class="row">

   <div class="col-sm-12 ">
      <div class="widget-box widget-color-green">
         <div class="widget-header"><h4 class="smaller">Qualification Details</h4></div>
         <div class="widget-body">
            <div class="widget-main">

            	<div class="row">
            		<div class="col-sm-6">
            			<div class="form-group row required {{ $errors->has('qan') ? 'has-error' : ''}}">
	                         {!! Form::label('qan', 'Qualification Number', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
	                         <div class="col-sm-8">
	                             {!! Form::text('qan', null, ['class' => 'form-control inputLimiter', 'required', 'maxlength' => '8']) !!}
	                             {!! $errors->first('qan', '<p class="text-danger">:message</p>') !!}
	                         </div>
	                    </div>
	                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
			               {!! Form::label('title', 'Qualification Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('title', null, ['class' => 'form-control  inputLimiter', 'required', 'maxlength' => '250']) !!}
			                   {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('total_credits') ? 'has-error' : ''}}">
			               {!! Form::label('total_credits', 'Total Credits', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('total_credits', null, ['class' => 'form-control ', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
			                   {!! $errors->first('total_credits', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('regulation_start_date') ? 'has-error' : ''}}">
		                   {!! Form::label('regulation_start_date', 'Regulation Start Date', ['class' => 'col-sm-4 control-label']) !!}
		                   <div class="col-sm-8">
		                       {!! Form::date('regulation_start_date', null, ['class' => 'form-control']) !!}
		                       {!! $errors->first('regulation_start_date', '<p class="text-danger">:message</p>') !!}
		                   </div>
		               </div>
		               <div class="form-group row {{ $errors->has('operational_start_date') ? 'has-error' : ''}}">
		                   {!! Form::label('operational_start_date', 'Operational Start Date', ['class' => 'col-sm-4 control-label']) !!}
		                   <div class="col-sm-8">
		                       {!! Form::date('operational_start_date', null, ['class' => 'form-control']) !!}
		                       {!! $errors->first('operational_start_date', '<p class="text-danger">:message</p>') !!}
		                   </div>
		               </div>
		               <div class="form-group row {{ $errors->has('operational_end_date') ? 'has-error' : ''}}">
		                   {!! Form::label('operational_end_date', 'Operational End Date', ['class' => 'col-sm-4 control-label']) !!}
		                   <div class="col-sm-8">
		                       {!! Form::date('operational_end_date', null, ['class' => 'form-control']) !!}
		                       {!! $errors->first('operational_end_date', '<p class="text-danger">:message</p>') !!}
		                   </div>
		               </div>
		               <div class="form-group row {{ $errors->has('certification_end_date') ? 'has-error' : ''}}">
		                   {!! Form::label('certification_end_date', 'Certification End Date', ['class' => 'col-sm-4 control-label']) !!}
		                   <div class="col-sm-8">
		                       {!! Form::date('certification_end_date', null, ['class' => 'form-control']) !!}
		                       {!! $errors->first('certification_end_date', '<p class="text-danger">:message</p>') !!}
		                   </div>
		               </div>
		               <div class="form-group row {{ $errors->has('min_glh') ? 'has-error' : ''}}">
			               {!! Form::label('min_glh', 'Min GLH', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('min_glh', $qualification->min_glh ?? 0, ['class' => 'form-control col-xs-10 col-sm-5', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
			                   {!! $errors->first('min_glh', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('max_glh') ? 'has-error' : ''}}">
			               {!! Form::label('max_glh', 'Max GLH', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('max_glh', $qualification->max_glh ?? 0, ['class' => 'form-control col-xs-10 col-sm-5', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
			                   {!! $errors->first('max_glh', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row required {{ $errors->has('glh') ? 'has-error' : ''}}">
			               {!! Form::label('glh', 'GLH', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('glh', $qualification->glh ?? 0, ['class' => 'form-control col-xs-10 col-sm-5', 'required', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!}
			                   {!! $errors->first('glh', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('total_qual_time') ? 'has-error' : ''}}">
			               {!! Form::label('total_qual_time', 'Total Qual. Time', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('total_qual_time', $qualification->total_qual_time ?? 0, ['class' => 'form-control col-xs-10 col-sm-5', 'onkeypress' => 'return isNumberKey(event)', 'maxlength' => '4']) !!} hours
			                   {!! $errors->first('total_qual_time', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('overall_grading_type') ? 'has-error' : ''}}">
			               {!! Form::label('overall_grading_type', 'Overall Grading Type', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::text('overall_grading_type', null, ['class' => 'form-control col-xs-10 col-sm-5', 'maxlength' => 15]) !!}
			                   {!! $errors->first('overall_grading_type', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('assessment_methods') ? 'has-error' : ''}}">
			               {!! Form::label('assessment_methods', 'Assessment Methods', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::textarea('assessment_methods', null, ['class' => 'form-control  inputLimiter', 'maxlength' => '255']) !!}
			                   {!! $errors->first('assessment_methods', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row {{ $errors->has('link_to_specs') ? 'has-error' : ''}}">
			               {!! Form::label('link_to_specs', 'Link to Specification', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
			               <div class="col-sm-8">
			                   {!! Form::textarea('link_to_specs', null, ['class' => 'form-control']) !!}
			                   {!! $errors->first('link_to_specs', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
            		</div>
            		<div class="col-sm-6">
            			<div class="form-group row required {{ $errors->has('status') ? 'has-error' : ''}}">
	                         {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
	                         <div class="col-sm-8">
	                             {!! Form::select('status', $status, null, ['class' => 'form-control', 'required']) !!}
	                             {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
	                         </div>
	                    </div>
	                    <div class="form-group row required {{ $errors->has('owner_org_rn') ? 'has-error' : ''}}">
			               {!! Form::label('owner_org_rn', 'Owner', ['class' => 'col-sm-4 control-label']) !!}
			               <div class="col-sm-8">
			                   {!! Form::select('owner_org_rn', $owners, null, ['class' => 'form-control', 'required']) !!}
			                   {!! $errors->first('owner_org_rn', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row required {{ $errors->has('level') ? 'has-error' : ''}}">
			               {!! Form::label('level', 'Level', ['class' => 'col-sm-4 control-label', 'required']) !!}
			               <div class="col-sm-8">
			                   {!! Form::select('level', $levels, null, ['class' => 'form-control']) !!}
			                   {!! $errors->first('level', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row required {{ $errors->has('type') ? 'has-error' : ''}}">
			               {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
			               <div class="col-sm-8">
			                   {!! Form::select('type', $types, null, ['class' => 'form-control', 'required']) !!}
			                   {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
			          <div class="form-group row required {{ $errors->has('ssa') ? 'has-error' : ''}}">
			               {!! Form::label('ssa', 'SSA', ['class' => 'col-sm-4 control-label', 'title' => 'Sector Subject Area']) !!}
			               <div class="col-sm-8">
			                   {!! Form::select('ssa', $ssa, null, ['class' => 'form-control', 'required']) !!}
			                   {!! $errors->first('ssa', '<p class="text-danger">:message</p>') !!}
			               </div>
			          </div>
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




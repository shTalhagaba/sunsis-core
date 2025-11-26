<div class="row">
    <div class="col-sm-12">
        <div class="widget-box widget-color-blue">
            <div class="widget-header">
                <h5 class="widget-title">Unit Details</h5>
            </div>
            <div class="widget-body">
                @if (!isset($unit))
                    {!! Form::open([
                        'name' => 'frmAddEditUnit',
                        'id' => 'frmAddEditUnit',
                        'url' => $frmAddEditUnitUrl,
                        'class' => 'form-horizontal',
                        'method' => 'post',
                        'onsubmit' => 'return submitForm();',
                    ]) !!}
                @else
                    {!! Form::model($unit->getAttributes(), [
                        'name' => 'frmAddEditUnit',
                        'id' => 'frmAddEditUnit',
                        'url' => $frmAddEditUnitUrl,
                        'class' => 'form-horizontal',
                        'method' => 'PATCH',
                        'onsubmit' => 'return submitForm();',
                    ]) !!}
                @endif
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('unit_sequence') ? 'has-error' : '' }}">
                        {!! Form::label('unit_sequence', 'Sequence', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('unit_sequence', $unit->unit_sequence ?? count($qualification->units) + 1, [
                                'class' => 'form-control col-sm-8',
                                'required',
                                'onkeypress' => 'return isNumberKey(event)',
                                'maxlength' => '4',
                            ]) !!}
                            {!! $errors->first('glh', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('unit_owner_ref') ? 'has-error' : '' }}">
                        {!! Form::label('unit_owner_ref', 'Onwer Reference', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('unit_owner_ref', $unit->unit_owner_ref ?? 'Ref' . (count($qualification->units) + 1), [
                                'class' => 'form-control col-sm-8 ',
                                'required',
                                'maxlength' => '15',
                            ]) !!}
                            {!! $errors->first('unit_owner_ref', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('unique_ref_number') ? 'has-error' : '' }}">
                        {!! Form::label('unique_ref_number', 'Unique Reference', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('unique_ref_number', $unit->unique_ref_number ?? null, [
                                'class' => 'form-control col-sm-8 ',
                                'required',
                                'maxlength' => '15',
                            ]) !!}
                            {!! $errors->first('unique_ref_number', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('title', $unit->title ?? null, [
                                'class' => 'form-control col-sm-8 ',
                                'required',
                                'maxlength' => '850',
                                'rows' => 3,
                            ]) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('unit_group') ? 'has-error' : '' }}">
                        {!! Form::label('unit_group', 'Unit Group', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select(
                                'unit_group',
                                \App\Models\Qualifications\QualificationUnit::getDDLUnitGroups(false),
                                isset($unit) ? $unit->getOriginal('unit_group') : null,
                                [
                                    'class' => 'form-control col-sm-8',
                                    'required',
                                ],
                            ) !!}
                            {!! $errors->first('unit_group', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('glh') ? 'has-error' : '' }}">
                        {!! Form::label('glh', 'GLH', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('glh', $unit->glh ?? 0, [
                                'class' => 'form-control col-sm-8',
                                'required',
                                'onkeypress' => 'return isNumberKey(event)',
                                'maxlength' => '4',
                            ]) !!}
                            {!! $errors->first('glh', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('unit_credit_value') ? 'has-error' : '' }}">
                        {!! Form::label('unit_credit_value', 'Unit Credit Value', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::number('unit_credit_value', $unit->unit_credit_value ?? 0, [
                                'class' => 'form-control col-sm-8',
                                'required',
                                'onkeypress' => 'return isNumberKey(event)',
                                'maxlength' => '4',
                            ]) !!}
                            {!! $errors->first('unit_credit_value', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('learning_outcomes') ? 'has-error' : '' }}">
                        {!! Form::label('learning_outcomes', 'Learning Outcomes', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('learning_outcomes', $unit->learning_outcomes ?? '', [
                                'class' => 'form-control col-sm-8 ',
                                'rows' => 3,
                            ]) !!}
                            {!! $errors->first('learning_outcomes', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <hr>
                    <h4 class="bolder">Add Performance Criteria</h4>
                    <div class="table-responsive">
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Please note that each pc row is only saved if
                            <strong>PC Title</strong> is given for that row.<br>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Seq.</th>
                                    <th>PC Reference</th>
                                    <th>
                                        PC Category
                                        {!! Form::select(
                                            'th_category',
                                            \App\Models\Qualifications\QualificationUnitPC::getDDLEvidenceCategories(),
                                            null,
                                            ['class' => 'form-control'],
                                        ) !!}
                                    </th>
                                    <th style="width: 35%">PC Title</th>
                                    <th style="width: 10%">
                                        <abbr title="Minimum number of required evidences for this PC">Min.
                                            Req.</abbr><br>
                                        {!! Form::select('min_req_evidences', range(0, 10), '') !!}
                                    </th>
                                    <th>PC Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($unit))
                                    @foreach ($unit->pcs()->orderBy('pc_sequence')->get() as $pc)
                                        @include('partials.create_edit_pc_table_row', ['pc' => $pc, 'pcSequence' => $loop->iteration])
                                    @endforeach
                                    @for ($i = count($unit->pcs) + 1; $i <= count($unit->pcs) + 20; $i++)
                                        @include('partials.create_edit_pc_table_row', ['pc' => null, 'pcSequence' => $i])
                                    @endfor
                                @else
                                    @for ($i = 1; $i <= 20; $i++)
                                        @include('partials.create_edit_pc_table_row', ['pc' => null, 'pcSequence' => $i])
                                    @endfor
                                @endif
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Save Unit Details
                        </button>&nbsp; &nbsp; &nbsp;
                    </div>
                </div>
                {!! Form::hidden('number_of_pcs', $i - 1) !!}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        var savedRefs = @json($savedRefs);
        var savedOwnerRefs = @json($savedOwnerRefs);

        function submitForm()
        {
            if ($.inArray($('input[name=unit_owner_ref]').val().trim(), savedOwnerRefs) !== -1) {
                $('input[name=unit_owner_ref]').focus();
                alert('Unit owner reference ' + $('input[name=unit_owner_ref]').val().trim() +
                        ' is not unique. Please expand existing units panel to see the used references.');
                $('input[name=unit_owner_ref]').focus();
                return false;
            }

            if ($.inArray($('input[name=unique_ref_number]').val().trim(), savedRefs) !== -1) {
                alert('Unit unique reference ' + $('input[name=unique_ref_number]').val().trim() +
                        ' is not unique. Please expand existing units panel to see the used references.');
                $('input[name=unique_ref_number]').focus();
                return false;
            }

            var valid_form = true;
            var pc_references = [];
            $("textarea[name^=pc][name$=title]").each(function(index, element) {
                if (element.value.trim() != '') {
                    var n = element.name.split('_');
                    var pc_reference = 'pc_' + n[1] + '_reference';
                    if ($.inArray($("input[name=" + pc_reference + "]").val().trim(), pc_references) !== -
                        1) // if in the references
                    {
                        alert('PC reference "' + $("input[name=" + pc_reference + "]").val().trim() +
                                '" is duplicate in this form.');
                        $("input[name=" + pc_reference + "]").focus();
                        valid_form = false;
                        return false;
                    } else {
                        pc_references.push($("input[name=" + pc_reference + "]").val().trim());
                    }
                }
            });

            return valid_form;
        }

        $("select[name=min_req_evidences]").on('change', function() {
            $("select[name$=min_req_evidences]").val(this.value);
        });

        $("select[name=th_category]").on('change', function() {
            $("select[name$=_category]").val(this.value);
        });

        
    </script>
@endpush

@php
    $dynamicAcceptableAnswersCount = old('dynamic_acceptable_answers_count', 0);
    $dynamicMcqOptionsCount = old('dynamic_mcq_options_count', 2);
    $type = old('type', 'descriptive');
    $showMcq = $type === 'multiple_choice' ? true : false;
@endphp
{!! Form::hidden('dynamic_acceptable_answers_count', 1) !!}
{!! Form::hidden('dynamic_mcq_options_count', 2) !!}

<div class="widget-box">
    <div class="widget-header">
        <h4 class="smaller">Enter Question Details</h4>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="form-group row required {{ $errors->has('question_order') ? 'has-error' : '' }}">
                {!! Form::label('question_order', 'Question Order/Number', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::number('question_order', $fsCourse->questions()->count() + 1, [
                        'class' => 'form-control required',
                    ]) !!}
                    {!! $errors->first('question_order', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('type') ? 'has-error' : '' }}">
                {!! Form::label('type', 'Question Type', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('type', ['descriptive' => 'Descriptive', 'multiple_choice' => 'Multiple Choice'], null, [
                        'class' => 'form-control ',
                    ]) !!}
                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row required {{ $errors->has('question_text') ? 'has-error' : '' }}">
                {!! Form::label('question_text', 'Question Text', [
                    'class' => 'col-sm-4 control-label
                                no-padding-right',
                ]) !!}
                <div class="col-sm-8">
                    {!! Form::textarea('question_text', null, ['class' => 'form-control', 'required', 'rows' => 5]) !!}
                    {!! $errors->first('question_text', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('question_image') ? 'has-error' : '' }}">
                {!! Form::label('question_image', 'Upload File', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                <div class="col-sm-8">
                    @include(
                        'partials.ace_file_control',
                        [
                            'aceFileControlRequired' => false, 
                            'aceFileControlId' => 'question_image', 
                            'aceFileControlName' => 'question_image', 
                            'maxSize' => 1024*1024*2, 
                            'allowExt' => ['gif','jpeg','jpg','png']
                        ]
                    )
                    {!! $errors->first('question_image', '<p class="text-danger">:message</p>') !!}
                    <span class="small text-info"><i class="fa fa-info-circle"></i> Allowed file types: .jpeg, .jpg, .png, .gif</span>
                </div>
            </div>
            <div id="correctAnswerContainer"
                style="{{ $showMcq ? 'display:none' : '' }}">
                <div class="form-group row {{ $errors->has('correct_answer') ? 'has-error' : '' }}">
                    <div class="col-sm-4">
                        <label for="correct_answer" class="col-sm-12 control-label no-padding-right">Correct Answer</label><br>
                        <span class="btn btn-xs btn-info btn-round  pull-right" id="btnAddAcceptableAnswer"><i
                            class="fa fa-plus"></i></span>
                    </div>
                    <div class="col-sm-8">
                        {!! Form::text('correct_answer', null, ['class' => 'form-control', 'maxlength' => 255]) !!}
                        {!! $errors->first('correct_answer', '<p class="text-danger">:message</p>') !!}
                        
                    </div>
                </div>
                <div class="form-group row {{ $errors->has('acceptable_answers') ? 'has-error' : '' }}">
                    {!! Form::label('acceptable_answers', 'Acceptable Answer 1', [
                        'class' => 'col-sm-4 control-label
                                        no-padding-right',
                    ]) !!}
                    <div class="col-sm-8">
                        {!! Form::text('acceptable_answers[]', null, ['class' => 'form-control', 'maxlength' => 255]) !!}                        
                    </div>
                </div>

                <div id="additionalAnswersContainer">
                    @for ($i = 1; $i < $dynamicAcceptableAnswersCount; $i++)
                        <div class="form-group row ">
                            <label for="acceptable_answers_{{ $i + 1 }}"
                                class="col-sm-4 control-label no-padding-right">Acceptable Answer
                                {{ $i + 1 }}</label>
                            <div class="col-sm-8">
                                <input class="form-control" maxlength="255" name="acceptable_answers[]"
                                    type="text" id="acceptable_answers_{{ $i + 1 }}"
                                    value="{{ old('acceptable_answers.' . $i) }}">
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div id="mcqOptionsContainer"
                style="{{ $showMcq ? 'display:block' : 'display:none' }}">
                <div class="form-group row">
                    <div class="col-sm-4">
                        <label for="mcq_options" class="col-sm-12 control-label no-padding-right">Options</label><br>
                        <span class="btn btn-xs btn-info btn-round  pull-right" id="btnAddMcqOption"><i
                            class="fa fa-plus"></i></span>
                    </div>
                    <div class="col-sm-8">
                        <table class="table table-bordered" id="tblMcqOtions">
                            <thead>
                                <tr>
                                    <th>Option Text</th><th class="text-center">Correct</th>
                                </tr>
                            </thead>
                            <tbody>
                                @for ($i = 1; $i <= $dynamicMcqOptionsCount; $i++)
                                    <tr>
                                        <td>
                                            {!! Form::text("options[{$i}][text]", null, ['class' => 'form-control', 'maxlength' => 255]) !!}
                                        </td>
                                        <td class="text-center">
                                            <div class="radio">
                                                <label>
                                                    <input type="radio" name="correct_option" class="ace input-lg" 
                                                        value="{{ $i }}" {{ old('correct_option') == $i ? 'checked' : '' }}>
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>                                    
                                @endfor
                            </tbody>
                        </table>
                        {!! $errors->first('options', '<p class="text-danger">:message</p>') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-success btn-round" type="submit">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save Information
                </button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script>
        $(document).ready(function() {
            let answerCount = $("input[name=dynamic_acceptable_answers_count]").val();
            answerCount = parseInt(answerCount, 10) || 1;

            $('#btnAddAcceptableAnswer').click(function() {
                answerCount++;

                const newLabel =
                    `<label for="acceptable_answers_${answerCount}" class="col-sm-4 control-label no-padding-right">Acceptable Answer ${answerCount}</label>`;
                const newInputField = `<div class="col-sm-8">
                                    <input class="form-control" maxlength="255" name="acceptable_answers[]" type="text" id="acceptable_answers_${answerCount}">
                               </div>`;

                const newFormGroup =
                    `<div class="form-group row">${newLabel}${newInputField}</div>`;

                $('#additionalAnswersContainer').append(newFormGroup);

                $("input[name=dynamic_acceptable_answers_count]").val(answerCount);
            });

            let optionCount = $("input[name=dynamic_mcq_options_count]").val();
            optionCount = parseInt(optionCount, 10) || 2;

            $('#btnAddMcqOption').click(function() {
                optionCount++;

                const tdOptionTextfield = `<td><input class="form-control" maxlength="255" name="options[${optionCount}][text]" type="text"></td>`;
                const tdOptionRadio = `<td class="text-center"><div class="radio">
                                            <label>
                                                <input name="correct_option" type="radio" class="ace input-lg" value="${optionCount}">
                                                <span class="lbl"> </span>
                                            </label>
                                        </div></td>`;

                const newOptionRow =
                    `<tr>${tdOptionTextfield}${tdOptionRadio}</tr>`;

                $('#tblMcqOtions').append(newOptionRow);

                $("input[name=dynamic_mcq_options_count]").val(optionCount);
            });

            $('select[name="type"]').on('change', function(){
                $('div#correctAnswerContainer').hide();
                $('div#mcqOptionsContainer').hide();
                if(this.value == 'descriptive')
                {
                    $('div#correctAnswerContainer').show();
                }
                else if(this.value == 'multiple_choice')
                {
                    $('div#mcqOptionsContainer').show();
                }
            });
        });
    </script>
@endpush

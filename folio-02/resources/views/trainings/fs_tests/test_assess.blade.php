@push('after-styles')
    <style>
        .custom-list {
            list-style-type: none;
            counter-reset: custom-counter;
            padding-left: 0;
        }

        .custom-list li {
            counter-increment: custom-counter;
            margin-bottom: 10px;
        }

        .custom-list li::before {
            content: counter(custom-counter, lower-alpha) ") ";
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
@endpush

<div class="row" style="margin-top: 2%">
    <div class="col-sm-12">
        <p class="text-center">{{ $fsTest->responses()->correct()->count() }} / {{ $fsTest->responses()->count() }}  </p>
        @foreach ($fsTest->responses as $response)
        @php
            $question = $response->question;
        @endphp
        <div class="widget-box widget-color-{{ $response->is_correct ? 'green' : 'red' }}" style="margin-bottom: 2%">
            <div class="widget-header">
                <span class="widget-title"><strong>Question {{ $loop->iteration }}</strong></span>
            </div>
            <div class="widget-body">
                <div class="widget-main " style="padding: 2%">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="form-group">
                                <label for="answer_for_question_{{ $question->id }}" class="">
                                    {!! nl2br(e($question->question_text)) !!}
                                </label>
        
                                @if (!is_null($question->file_name))
                                    <p>
                                        <a href="{{ $question->getImageSrc() }}">
                                            <img src="{{ $question->getImageSrc() }}" alt="Question Image"
                                                class="img img-responsive">
                                        </a>
                                    </p>
                                @endif
        
                                @if ($question->isMcq())
                                    <div class="control-group">
                                        @foreach ($question->question_options as $option)
                                            @php
                                                $savedOption = optional($fsTest->responses->where('question_id', $question->id)->first())->answer_mcq_option_id;

                                            @endphp
                                            <div class="radio">
                                                <label>
                                                    <input disabled type="radio"
                                                        class="ace input-lg" value="{{ $option->id }}"
                                                        {{ $option->id == $savedOption ? 'checked' : '' }}>
                                                    <span class="lbl bigger-120">
                                                        {{ $option->option_text }}
                                                    </span>
                                                </label>
                                            </div>
                                        @endforeach
                                        <blockquote>
                                            <span class="badge badge-{{ $response->is_correct ? 'success' : 'danger' }}">{{ $response->is_correct ? 'Correct' : 'Wrong' }} </span>
                                        </blockquote>
                                    </div>
                                @elseif($question->isDescriptive())
                                
                                <blockquote>
                                    <p>
                                        <span class="bolder text-info">Answer Given: </span>
                                        {{ $response->answer_text }}
                                    </p>
                                    <span class="badge badge-{{ $response->is_correct ? 'success' : 'danger' }}">{{ $response->is_correct ? 'Correct' : 'Wrong' }} </span>
                                </blockquote>  
                                    
                                @else
                                    {{-- show blank --}}
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <span class="bolder text-info">Correct Answer: </span> 
                            {{ $question->correct_answer }}
                            <br>
                            @if(is_array($question->acceptable_answers) && count($question->acceptable_answers) > 0)
                            <span class="bolder text-info">Other Acceptable Answers: </span>
                            <ol class="custom-list">
                                @foreach ($question->getAcceptableAnswers() as $acceptableAnswer)
                                    <li>
                                        {{ $acceptableAnswer }}
                                    </li>
                                @endforeach
                            </ol> 
                            @endif
                            @if($question->isMcq())
                            <ol class="custom-list">
                                @foreach ($question->question_options as $option)
                                    <li>
                                        {{ $option->option_text }}
                                        {!! $option->isCorrect() ? '<span class="badge badge-success">Correct Option</span>' : '' !!}
                                    </li>
                                @endforeach
                            </ol>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="col-sm-12">
        {!! Form::model($fsTest->getAttributes(), [
            'method' => 'PATCH',
            'url' => route('trainings.fs_tests.update', [$training, $fsTest]),
            'class' => 'form-horizontal',
            'files' => true,
            'role' => 'form',
        ]) !!}

        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="widget-title">Enter assessment information</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('status') ? 'has-error' : '' }}">
                        {!! Form::label('status', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('status', ['approved' => 'Approved', 'needs_redo' => 'Redo Test'], null, ['class' => 'form-control', 'required']) !!}
                            {!! $errors->first('status', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('complete_by') ? 'has-error' : '' }}" style="display: none;" id="redoTestNotif">
                        {!! Form::label('complete_by', 'Complete By', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                        <div class="col-sm-8">
                            <span class="text-info small">
                                <i class="fa fa-info-circle"></i> 
                                Status: Redo Test. The system will generate a new test for this learner in this course.
                            </span>
                            {!! Form::date('complete_by', null, ['class' => 'form-control']) !!}
                            {!! $errors->first('complete_by', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('comments') ? 'has-error' : '' }}">
                        {!! Form::label('comments', 'Enter Your Comments', [
                            'class' => 'col-sm-4 control-label no-padding-right',
                        ]) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('comments', null, ['class' => 'form-control', 'required', 'maxlength' => 1000]) !!}
                            {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
                
    </div>

    
</div>



@push('after-scripts')
<script>
    function setAction(submissionStatus) {
        $("input[type=hidden][name=submission_status]").val(submissionStatus);
    }

    @if(Session::has('alert-success'))
        toastr.options.positionClass = 'toast-bottom-right';
        toastr.success("{{ Session::get('alert-success') }}");
    @endif

    @error('complete_by')
    toggleRedoSection("show");
    @enderror

    $("select[name=status]").on("change", function() {
        toggleRedoSection("hide");
        if(this.value === 'needs_redo')
        {
            toggleRedoSection("show");
        }
    });

    function toggleRedoSection(status)
    {
        const redoDiv = $("#redoTestNotif");
        redoDiv.hide();
        redoDiv.removeClass("required");
        $("#complete_by").attr("required", false);
        if(status === 'show')
        {
            redoDiv.addClass("required");
            $("#complete_by").attr("required", true);
            redoDiv.show();
        }
    }

</script>
@endpush


<div class="row" style="margin-top: 5%">
    <div class="col-sm-12">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h5 class="widget-title">
                    Answer the following questions.
                </h5>
            </div>
            <div class="widget-body">
                {!! Form::open([
                    'url' => route('trainings.fs_tests.save_test', [$training, $fsTest]),
                    'class' => 'form-horizontal',
                    'name' => 'frmSignoffProgress',
                ]) !!}
                {!! Form::hidden('tr_id', $training->id) !!}
                {!! Form::hidden('submission_status') !!}

                <div class="widget-main">
                    @foreach ($fsCourse->questions()->where('active', true)->get() as $question)
                        <div class="widget-box " style="margin-bottom: 2%">
                            <div class="widget-header">
                                <span class="widget-title"><strong>Question {{ $loop->iteration }}</strong></span>
                                <div class="widget-toolbar">
                                    <button class="btn btn-xs btn-primary btn-round" type="submit" onclick="setAction('incomplete')">
                                        <i class="ace-icon fa fa-save bigger-120"></i> Save
                                    </button>
                                </div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main" style="padding: 2%">
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
                                                        $savedOption = optional($fsTest->responses->where('question_id', $question->id)->first())->answer_mcq_option_id
                                                    @endphp
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio"
                                                                name="answer_for_question_{{ $question->id }}"
                                                                class="ace input-lg" value="{{ $option->id }}"
                                                                {{ $option->id == $savedOption ? 'checked' : '' }}>
                                                            <span class="lbl bigger-120">
                                                                {{ $option->option_text }}
                                                            </span>
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @elseif($question->isDescriptive())
                                            <input type="text" class="form-control"
                                                name="answer_for_question_{{ $question->id }}"
                                                id="answer_for_question_{{ $question->id }}" maxlength="255"
                                                value="{{ optional($fsTest->responses->where('question_id', $question->id)->first())->answer_text }}">
                                        @else
                                            {{-- show blank --}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-primary btn-round" type="submit" onclick="setAction('incomplete')">
                            Save and Come Back Later
                        </button>
                        <button class="btn btn-sm btn-success btn-round" type="submit" onclick="setAction('complete')">
                            Save and Complete
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
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

</script>
@endpush


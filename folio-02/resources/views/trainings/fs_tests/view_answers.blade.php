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
                        @if($showCorrectAnsInfo)
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
    
</div>

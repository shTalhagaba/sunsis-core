<div class="widget-box">
    <div class="widget-header widget-header-flat">
        <span class="widget-title">
            <strong>Question {{ $questionNumber ?? null }}</strong> 
            @if (!$question->isActive())
                <span class="label label-danger">Not Active</span>
            @endif
        </span>
        <div class="widget-toolbar">
            @if( auth()->user()->isTutor() )
            <button class="btn btn-xs btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.questions.edit', [$fsCourse, $question]) }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i> Edit
            </button>
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('fs_courses.questions.destroy', [$fsCourse, $question]),
                'style' => 'display: inline;',
                'class' => 'form-inline frmDeleteQuestion',
            ]) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete', [
                'class' => 'btn btn-xs btn-danger btn-round',
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
            @endif
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <table class="table table-bordered {{ $question->isActive() ? '' : 'text-muted' }}">
                <col width="30%" />
                <col width="70%" />
                <tr>
                    <th>Type</th>
                    <td>{{ $question->type }}</td>
                </tr>
                <tr>
                    <th> Created By </th>
                    <td>{{ optional($question->creator)->full_name }}</td>
                </tr>
                <tr>
                    <th> Question Text {{ $question->id }}</th>
                    <td>
                        <strong>{!! nl2br(e($question->question_text)) !!}</strong>
                        @if(!is_null($question->file_name))
                        <a href="{{ $question->getImageSrc()  }}">
                            <img src="{{ $question->getImageSrc()  }}" alt="Question Image" class="img-sm img-thumbnail" width="100" height="100">
                        </a>
                        @endif
                    </td>
                </tr>
                @if ($question->isMcq())
                    <tr>
                        <th> Options </th>
                        <td>
                            <ol class="custom-list">
                                @foreach ($question->question_options as $option)
                                    <li>
                                        {{ $option->option_text }}
                                        {!! $option->isCorrect() ? '<span class="badge badge-success">Correct Option</span>' : '' !!}
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                @elseif($question->isDescriptive())
                    <tr>
                        <th> Answer </th>
                        <td>{{ $question->correct_answer }}</td>
                    </tr>
                    <tr>
                        <th> Other Acceptable Answers</th>
                        <td>
                            <ol class="custom-list">
                                @foreach ($question->getAcceptableAnswers() as $acceptableAnswer)
                                    <li>
                                        {{ $acceptableAnswer }}
                                    </li>
                                @endforeach
                            </ol>
                        </td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>

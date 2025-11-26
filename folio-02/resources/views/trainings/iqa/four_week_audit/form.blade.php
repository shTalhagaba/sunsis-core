@push('after-styles')
    <style>
        .bg-gold {
            background-color: #FFFACD
        }
        input[type=radio], input[type=checkbox] {
			transform: scale(1.4);
		}
    </style>
@endpush

    <div class="form-group {{ $errors->has('date_of_portfolio_audit') ? 'has-error' : '' }}" style="margin: 1%">
        {!! Form::label('date_of_portfolio_audit', 'Date of Portfolio Audit: ', ['class' => 'control-label']) !!}
        {!! Form::date('date_of_portfolio_audit', $audit->date_of_portfolio_audit ?? null, ['class' => '']) !!}
        {!! $errors->first('date_of_portfolio_audit', '<p class="text-danger">:message</p>') !!}
    </div>

<div class="table-responsive">

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>IQA Portfolio Check</th>
                <th class="text-center">Yes</th>
                <th class="text-center">No</th>
                <th class="text-center">N/A</th>
                <th>Comments</th>
                <th>Actioned By</th>
                <th>Date Completed</th>
            </tr>
        </thead>
        <tbody>
            <col width="30%">
            <col width="5%">
            <col width="5%">
            <col width="5%">
            <col width="30%">
            <col width="10%">
            <col width="10%">
            @foreach ($questions as $q)
                <tr id="trQues{{ $q->id }}">
                    <td class="bg-{{ $q->bg_color }}">
                        {!! nl2br($q->description) !!}
                    </td>
                    <td class="text-center">
                        @php $ansQues = "ansQues{$q->id}"; @endphp
                        <input type="radio" name="{{ $ansQues }}" value="Yes" 
                            {{ ( isset($formData->$ansQues) && $formData->$ansQues == 'Yes' ) ? ' checked' : '' }}>
                    </td>
                    <td class="text-center">
                        <input type="radio" name="ansQues{{ $q->id }}" value="No" 
                            {{ ( isset($formData->$ansQues) && $formData->$ansQues == 'No' ) ? ' checked' : '' }}>
                    </td>
                    <td class="text-center">
                        <input type="radio" name="ansQues{{ $q->id }}" value="N/A" 
                            {{ ( isset($formData->$ansQues) && $formData->$ansQues == 'N/A' ) ? ' checked' : '' }}>
                    </td>
                    <td>
                        {!! Form::textarea('commentsQues' . $q->id, isset($formData->{"commentsQues$q->id"}) ? nl2br(e($formData->{"commentsQues$q->id"})) : null, [
                            'class' => 'form-control',
                            'rows' => '5',
                            'id' => 'commentsQues' . $q->id,
                            'maxlength' => 500,
                        ]) !!}
                        {!! $errors->first('commentsQues' . $q->id, '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {{-- {!! Form::text('actionByQues' . $q->id, isset($formData->{"actionByQues$q->id"}) ? $formData->{"actionByQues$q->id"} : null, ['class' => 'form-control']) !!} --}}
                        {!! Form::select(
                                'actionByQues' . $q->id,
                                $usersCategorisedList, 
                                isset($formData->{"actionByQues$q->id"}) ? $formData->{"actionByQues$q->id"} : null, 
                                ['class' => 'form-control', 'placeholder' => '']
                                ) !!}
                        {!! $errors->first('actionByQues' . $q->id, '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::date('dateQues' . $q->id, isset($formData->{"dateQues$q->id"}) ? $formData->{"dateQues$q->id"} : null, ['class' => 'form-control']) !!}
                        {!! $errors->first('dateQues' . $q->id, '<p class="text-danger">:message</p>') !!}
                    </td>
                </tr>
            @endforeach
            <tr>
                <th>Strengths</th>
                <td colspan="4">
                    {!! Form::textarea('commentsStrengths', isset($formData->commentsStrengths) ? nl2br(e($formData->commentsStrengths)) : null, [
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'commentsStrengths',
                        'maxlength' => 500,
                    ]) !!}
                    {!! $errors->first('commentsStrengths', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {{-- {!! Form::text('actionByStrengths', isset($formData->actionByStrengths) ? $formData->actionByStrengths : null, ['class' => 'form-control']) !!} --}}
                    {!! Form::select(
                                'actionByStrengths',
                                $usersCategorisedList, 
                                isset($formData->actionByStrengths) ? $formData->actionByStrengths : null, 
                                ['class' => 'form-control', 'placeholder' => '']
                                ) !!}
                    {!! $errors->first('actionByStrengths', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('dateStrengths', isset($formData->dateStrengths) ? $formData->dateStrengths : null, ['class' => 'form-control']) !!}
                    {!! $errors->first('dateStrengths', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Actions relating to the portfolio</th>
                <td colspan="4">
                    {!! Form::textarea('commentsPortfolio', isset($formData->commentsPortfolio) ? nl2br(e($formData->commentsPortfolio)) : null, [
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'commentsPortfolio',
                        'maxlength' => 500,
                    ]) !!}
                    {!! $errors->first('commentsPortfolio', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {{-- {!! Form::text('actionByPortfolio', isset($formData->actionByPortfolio) ? $formData->actionByPortfolio : null, ['class' => 'form-control']) !!} --}}
                    {!! Form::select(
                                'actionByPortfolio',
                                $usersCategorisedList, 
                                isset($formData->actionByPortfolio) ? $formData->actionByPortfolio : null, 
                                ['class' => 'form-control', 'placeholder' => '']
                                ) !!}
                    {!! $errors->first('actionByPortfolio', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('datePortfolio', isset($formData->datePortfolio) ? $formData->datePortfolio : null, ['class' => 'form-control']) !!}
                    {!! $errors->first('datePortfolio', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Good practice identified to be shared</th>
                <td colspan="4">
                    {!! Form::textarea('commentsPractice', isset($formData->commentsPractice) ? nl2br(e($formData->commentsPractice)) : null, [
                        'class' => 'form-control',
                        'rows' => '5',
                        'id' => 'commentsPractice',
                        'maxlength' => 500,
                    ]) !!}
                    {!! $errors->first('commentsPractice', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {{-- {!! Form::text('actionByPractice', isset($formData->actionByPractice) ? $formData->actionByPractice : null, ['class' => 'form-control']) !!} --}}
                    {!! Form::select(
                                'actionByPractice',
                                $usersCategorisedList, 
                                isset($formData->actionByPractice) ? $formData->actionByPractice : null, 
                                ['class' => 'form-control', 'placeholder' => '']
                                ) !!}
                    {!! $errors->first('actionByPractice', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('datePractice', isset($formData->datePractice) ? $formData->datePractice : null, ['class' => 'form-control']) !!}
                    {!! $errors->first('datePractice', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>

        </tbody>
    </table>
    @if(!$audit->signed())
    <div class="control-group text-center">
        <div class="checkbox">
            <label>
                <input name="iqa_signed"  type="checkbox" value="1" >
                <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                <div class="space-2"></div>
                <span class="text-info small" style="margin-left: 2%"> 
                    &nbsp; <i class="fa fa-info-circle"></i> 
                    After you tick this option and save then form will be locked for further changes.
                </span>
            </label>
        </div>
    <br>
    {!! $errors->first('assessor_signed', '<p class="text-danger">:message</p>') !!}
    @else
    <div class="control-group text-center">
        <div class="checkbox">
            <label>
                <input name="iqa_signed"  type="checkbox" value="1" checked disabled>
                <span class="lbl bolder"> &nbsp; This form is signed and locked for further changes.</span>
            </label>
        </div>
        <span class="bolder text-info">Signed By: </span>{{ $audit->completedBy() }}
        <br>
        <span class="bolder text-info">Signed Date: </span>{{ optional($audit->completed_by_date)->format('d/m/Y') }}
    </div>
    @endif
    </div>
</div>

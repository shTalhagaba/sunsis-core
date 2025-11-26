@php
    $prefix = 'pc_' . $pcSequence . '_';
@endphp
<tr>
    <td>
        {{ $pcSequence }}
        {!! Form::hidden($prefix . 'sequence', $pcSequence) !!}
        {!! Form::hidden($prefix . 'savedId', optional($pc)->id) !!}
    </td>
    <td>
        {!! Form::text($prefix . 'reference', $pc->reference ?? '', ['class' => 'form-control', 'maxlength' => '15']) !!}
    </td>
    <td>
        {!! Form::select(
            $prefix . 'category',
            \App\Models\Qualifications\QualificationUnitPC::getDDLEvidenceCategories(),
            optional($pc)->getOriginal('category') ?? '',
            ['class' => 'form-control'],
        ) !!}
    </td>
    <td>
        {!! Form::textarea($prefix . 'title', $pc->title ?? '', [
            'class' => 'form-control inputLimiter',
            'maxlength' => '850',
            'rows' => '3',
        ]) !!}
    </td>
    <td>
        {!! Form::select(
            $prefix . 'min_req_evidences',
            array_combine(range(1, 10), range(1, 10)),
            optional($pc)->getOriginal('min_req_evidences') ?? '',
            ['class' => 'form-control', 'required'],
        ) !!}
    </td>
    <td>
        {!! Form::textarea($prefix . 'description', $pc->description ?? '', [
            'class' => 'form-control inputLimiter',
            'maxlength' => '500',
            'rows' => 3,
        ]) !!}
    </td>
</tr>

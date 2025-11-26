@php
    $_dc = '';
    if($_evidence_popover->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
    {
        $totalMediaFiles = count($_evidence_popover->media);
        $_dc .= '<strong>' . Str::plural('File', $totalMediaFiles) . ':</strong> ' . Str::limit(optional($_evidence_popover->media->first())->file_name, 50);
        if($totalMediaFiles > 1)
        {
            $_dc .= ' + ' . ($totalMediaFiles-1) . ' more <br>';
        }
        else 
        {
            $_dc .= '<br>';
        }
    }
    $_dc .= '<strong>Status:</strong> ' . $_evidence_popover->evidence_status . '<br>';
    $_dc .= '<strong>Description:</strong> ' . Str::limit($_evidence_popover->evidence_desc, 100) .'<br>';
    $_dc .= '<strong>Learner Comments: </strong> : ' . Str::limit($_evidence_popover->learner_comments, 100) .'<br>';
    $_dc .= '<strong>Assessor Comments: </strong> : ' . Str::limit($_evidence_popover->assessor_comments, 100) .'<br>';
    $_dc .= '<i class=\'fa fa-clock-o\'></i> Created At: ' . \Carbon\Carbon::parse($_evidence_popover->created_at)->format('d/m/Y H:i:s') . '<br>';
    $_dc .= '<i class=\'fa fa-clock-o\'></i> Last Modified At: ' . \Carbon\Carbon::parse($_evidence_popover->updated_at)->format('d/m/Y H:i:s') . '<br>';
    $icon_color = 'orange';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
        $icon_color = 'blue';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
        $icon_color = 'red';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED)
        $icon_color = 'orange';
    $circle_icon = '';
    if(isset($current_screen_evidence) && $current_screen_evidence->getOriginal('id') == $_evidence_popover->id)
    {
        $circle_icon = 'circle-icon';
    }
@endphp
<span class="btn btn-xs btn-white btn-round" onclick="window.open('{{ route('trainings.evidences.show', [$training, $_evidence_popover]) }}', '_blank')"
    data-trigger="hover"
    data-rel="popover"
    data-original-title="{{ $_evidence_popover->getIcon() }}  {{ Str::limit($_evidence_popover->evidence_name, 50) }}"
    data-content="<small>{{ $_dc }}</small>"
    data-placement="auto">
    {{-- <i class='fa {{ isset($_evidence_popover->media->first()->file_name) ? \App\Helpers\AppHelper::getFileIcon($_evidence_popover->media->first()->file_name) : \App\Helpers\AppHelper::getFileIcon() }} fa-2x {{ $icon_color }} {{ $circle_icon }}'></i> --}}
    {!! $_evidence_popover->getIcon(['fa-2x', $icon_color, $circle_icon ]) !!}
</span> &nbsp;

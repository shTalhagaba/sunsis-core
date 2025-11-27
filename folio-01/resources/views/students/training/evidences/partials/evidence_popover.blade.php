@php
    $_dc = '<strong>Type:</strong> ' . $_evidence_popover->evidence_type;
    if($_evidence_popover->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
        $media = $_evidence_popover->media->first(); // may be null

        if ($media) {
            $_dc .= '<br><strong>File:</strong> ' . \Str::limit($media->file_name, 50);
        } else {
            $_dc .= '<br><strong>File:</strong> <em>No file uploaded</em>';
        }
        //$_dc .= '<br><strong>File:</strong> ' . \Str::limit($_evidence_popover->media->first()->file_name, 50);
    $_dc .= '<br><strong>Status:</strong> ' . $_evidence_popover->evidence_status . '<br>';
    $_dc .= '<strong>Desc.:</strong> ' . \Str::limit($_evidence_popover->evidence_desc, 100) .'<br>';
    $_dc .= '<strong> Learner Comm.</strong> : ' . \Str::limit($_evidence_popover->learner_comments, 100) .'<br>';
    $_dc .= '<strong> Assessor Comm.</strong> : ' . \Str::limit($_evidence_popover->assessor_comments, 100) .'<br>';
    $_dc .= '<i class=\'fa fa-clock-o\'></i> ' . \Carbon\Carbon::parse($_evidence_popover->created_at)->format('d/m/Y H:i:s') . '<br>';
    $icon_color = 'orange';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
        $icon_color = 'blue';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
        $icon_color = 'red';
    if($_evidence_popover->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED)
        $icon_color = 'orange';
    $circle_icon = '';
    if(isset($current_screen_evidence) && $current_screen_evidence->id == $_evidence_popover->id)
        $circle_icon = 'circle-icon';
@endphp
<span style="cursor: pointer;" onclick="window.open('{{ route('students.training.evidence.show', [$student, $training_record, $_evidence_popover]) }}', '_blank')"
    data-trigger="hover"
    data-rel="popover"
    data-original-title="{{ $_evidence_popover->evidence_name }}"
    data-content="<small>{{ $_dc }}</small>"
    data-placement="auto">
    <i class='fa {{ isset($_evidence_popover->media->first()->file_name) ? \App\Helpers\AppHelper::getFileIcon($_evidence_popover->media->first()->file_name) : \App\Helpers\AppHelper::getFileIcon() }} fa-2x {{ $icon_color }} {{ $circle_icon }}'></i>
</span> &nbsp;

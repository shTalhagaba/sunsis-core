@php
    $listEvidenceCategories = App\Models\Lookups\TrainingEvidenceCategoryLookup::getSelectData();
    $evidenceMappedCategories = $evidenceMapped->categories->pluck('id')->toArray();
@endphp

<div class="well well-sm" style="border-radius: 10px">
    <p class="text-center text-info bolder">{!! $evidenceMapped->getIcon() !!} Evidence</p>
    <span class="text-info">Name: </span>{{ $evidenceMapped->evidence_name }}<br>
    <span class="text-info">{{ Str::plural('Category', count($evidenceMappedCategories)) }}: </span>{{ collect($listEvidenceCategories)->only($evidenceMappedCategories)->values()->implode(', ') }}<br>
    <span class="text-info">Description: </span>{{ Str::limit($evidenceMapped->evidence_desc, 125) }}<br>
    <span class="text-info">Status: </span>{{ $evidenceMapped->evidence_status }}<br>
    @if ($evidenceMapped->isFileUpload())
        <span class="text-info">Number of Files Uploaded: </span>{{ count($evidenceMapped->media) }}<br>
    @endif
    <span class="ace-settings-btn tn btn-xs btn-info" style="cursor: pointer"
        onclick="window.open('{{ route('trainings.evidences.show', [$training, $evidenceMapped]) }}', '_blank')">
            <i class="fa fa-folder-open"></i> View Detail
    </span>
    <span class="pull-right">
        <label>
            <input 
                class="ace ace-switch ace-switch-6 chkEvidenceStatus" 
                type="checkbox" 
                data-tr-evidence-id="{{ $evidenceMapped->id }}"
                value="1"
                {{ $evidenceMapped->evidence_checked_status ? 'checked' : '' }} >
            <span class="lbl"></span>
        </label>
    </span>
</div>
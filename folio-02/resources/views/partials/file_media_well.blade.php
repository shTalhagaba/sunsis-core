@php
    $fileMediaName = \Str::limit($fileMedia->name, 45);
@endphp
<div class="well well-sm pull-left" style="margin-right: 5px; border-radius: 10px">
    <span>
        @if(auth()->user()->isStudent() || auth()->user()->can('download-evidence'))
        <a href="{{ route('files.download', encrypt($fileMedia->id)) }}"
            target="_blank" style="cursor: pointer;">
            <i class="fa fa-cloud-download"></i> 
            {{ $fileMediaName }}
        </a>    
        @else
        {{ $fileMediaName }}
        @endif        
    </span><br>
    <span class="small">Type: {{ File::extension($fileMedia->file_name) }}</span><br>
    <span class="small">Size: {{ $fileMedia->human_readable_size }}</span><br>
    <span class="small"><i class="fa fa-clock-o"></i>
        {{ $fileMedia->updated_at->format('d/m/Y H:i:s') }}</span>
    <br>
</div>
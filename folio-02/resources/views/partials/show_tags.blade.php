@forelse ($entity->tags as $tag)
    <h5 style="display: inline;">
        <span class="badge badge-info" style="display: margin-right: 5px; margin-bottom: 5px;">
            <i class="fa fa-tag"></i> {{ $tag->name }} &nbsp;
            <i class="fa fa-times" style="cursor: pointer;" title="Detach this tag"
                onclick="remove_tag('{{ $tag->id }}', '{{ str_replace('\\', '\\\\', get_class($entity)) }}', '{{ $entity->id }}');"></i>
        </span>
    </h5>
@empty
    <span class="text-info">
        <i class="fa fa-info-circle"></i> <i>No tags have been assiged to this
            record.</i>
    </span>
@endforelse

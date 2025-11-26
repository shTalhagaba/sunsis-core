<div class="widget-box">
    <div class="widget-header">
        <h5 class="widget-title">Tags</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            @if( auth()->user()->isStaff() )
                @include('partials.modal-assign-tag', [
                    'tagged_entity' => $_entity,
                    'modal_assign_tags_options' => [
                        $tagTypeDesc => App\Models\Tags\Tag::orderBY('name')
                            ->whereType($tagTypeDesc)
                            ->whereNotIn('id', $_entity->tags()->pluck('id')->toArray())
                            ->pluck('name', 'id')
                            ->toArray(),
                    ],
                ])
            @endif

            <div class="space-4"></div>
            @forelse ($_entity->tags as $tag)
                <h5 role="entity_tag" style="display: inline;">
                    <span class="label label-info label-xlg" style="display: margin-right: 5px; margin-bottom: 5px;">
                        <i class="fa fa-tag"></i> {{ $tag->name }} &nbsp;
                        @if( auth()->user()->isStaff() )
                        <i class="fa fa-times" style="cursor: pointer;" 
                            title="Remove this tag" 
                            onclick="remove_tag('{{ $tag->id }}', '{{ str_replace("\\", "\\\\", get_class($_entity) ) }}', '{{ $_entity->id }}');"></i>
                        @endif
                    </span>
                </h5>
            @empty
                <span class="text-info">
                    <i class="fa fa-info-circle"></i> <i>No tags have been assigned to this
                        record.</i>
                </span>
            @endforelse
        </div>
    </div>
</div>
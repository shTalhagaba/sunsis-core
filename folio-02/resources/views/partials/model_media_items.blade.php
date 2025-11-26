@foreach ($mediaFiles as $media)
    <div class="well well-sm pull-left" style="margin-right: 10px; border-radius: 10px">
        <span title="{{ $media->name }}">
            <a href="{{ route('files.download', encrypt($media->id)) }}" target="_blank" style="cursor: pointer;">
                {{ \Str::limit($media->name, 35, '...') }}
            </a>
        </span><br>
        <span class="small">{{ \Str::upper($media->extension) }}</span><br>
        <span class="small">{{ $media->human_readable_size }}</span><br>
        <span class="small"><i class="fa fa-clock-o"></i>
            {{ $media->updated_at->format('d/m/Y H:i:s') }}</span>

        @if (auth()->check() && (auth()->user()->isAdmin() || $media->getCustomProperty('uploaded_by') == auth()->user()->id))
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('media.remove', ['model' => $model->id, 'media' => $media]),
                'style' => 'display: inline;',
                'class' => 'form-inline',
                'id' => 'frmDeleteMedia',
            ]) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o"></i>', [
                'class' => 'btn btn-xs btn-danger btn-round btnDeleteMedia',
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
            <br>
        @endif
    </div>
@endforeach

@push('after-scripts')
    <script>
        if (typeof window.btnDeleteMediaInitialized === 'undefined') {
            window.btnDeleteMediaInitialized = true;

            $('.btnDeleteMedia').on('click', function(e) {
                e.preventDefault();
                var form = this.closest('form');
                bootbox.confirm({
                    title: 'Confirm Delete?',
                    message: 'This action is irreversible, are you sure you want to continue?',
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel',
                            className: 'btn-xs btn-round'
                        },
                        confirm: {
                            label: '<i class="fa fa-trash-o"></i> Yes Delete',
                            className: 'btn-danger btn-xs btn-round'
                        }
                    },
                    callback: function(result) {
                        if (result) {
                            $(form).find(':submit').attr("disabled", true);
                            $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                            form.submit();
                        }
                    }
                });
            });
        }
    </script>
@endpush


{!! Form::file($aceFileControlName ?? 'file', ['class' => 'form-control', 'id' => $aceFileControlId ?? 'file', $aceFileControlRequired ? 'required' : '']) !!}

@push('after-scripts')
<script type="text/javascript">
    $(function() {
        $('#{{ $aceFileControlId ?? 'file' }}').ace_file_input({
                'maxSize': {{ $maxSize ?? config('medialibrary.max_file_size') }},
                'allowExt': @if(isset($allowExt)) [{!! "'" . implode("','", $allowExt) . "'" !!}] @else [{!! "'" . implode("','", config('medialibrary.allowed_extensions')) . "'" !!}] @endif,
            })
            .on('file.error.ace', function(event, info) {
                if(info.error_count['size'] > 0)
                {
                    alert('File size exceeds maximum allowed file size of 22MB.')
                }
                if(info.error_count['ext'] > 0)
                {
                    alert('File type is not allowed.')
                }
                
                event.preventDefault();
            });
    });
</script>

@endpush

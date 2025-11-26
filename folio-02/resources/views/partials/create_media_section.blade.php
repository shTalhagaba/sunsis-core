<form name="frmAddMediaSection" method="POST" action="{{ route('createMediaSection') }}">
    @csrf
    <input type="hidden" name="model_type" value="{{ get_class($model) }}">
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <input type="hidden" name="new_media_section_name">
</form>

@push('after-scripts')
    <script>
        $('#btnCreateSection').on('click', function(e) {
            e.preventDefault();
            bootbox.prompt({
                title: 'Enter Section Name, use alphabets only',
                inputType: 'text',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-xs btn-round",
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: "btn-success btn-xs btn-round",
                    }
                },
                callback: function(result) {
                    if (result) {
                        var form = $("form[name=frmAddMediaSection]");
                        $("input[name=new_media_section_name]").val(result);
                        form.submit();
                    }
                }
            });
        });
    </script>
@endpush
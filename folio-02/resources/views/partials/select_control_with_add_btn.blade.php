<div class="input-group">
    {!! Form::select($lookupDbTableCtrlName, $lookupDbTableOptions, null, [
        'class' => 'form-control',
        'id' => $lookupDbTableCtrlName . '_id',
        'placeholder' => '',
    ]) !!}
    {!! $errors->first($lookupDbTableCtrlName, '<p class="text-danger">:message</p>') !!}
    <span class="input-group-addon" id="addOption{{ $lookupDbTableCtrlName }}" title="Add new option" style="cursor: pointer" data-list-table="{{ $lookupDbTable }}">
        <i class="ace-icon fa fa-plus"></i>
    </span>
</div>

@push('after-scripts')
    <script>
        $('#addOption{{ $lookupDbTableCtrlName }}').on('click', function(e) {
            e.preventDefault();
            const dataListTable = $(this).attr('data-list-table');

            bootbox.prompt({
                title: 'Please enter the option',
                message: '<p>Enter new option below:</p>',
                inputType: 'textarea',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm",
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: "btn-success btn-sm",
                    }
                },
                callback: function(result) {
                    if (result) {
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: '{{ route('createSelectOption') }}',
                            data: {
                                newOption: result,
                                optionTable: dataListTable
                            },
                            success: function(data) {
                                $("select[name={{ $lookupDbTableCtrlName }}]").append('<option value="' + data
                                    .value + '" selected>' + data.text + '</option>');
                            },
                            error: function(errorInfo) {
                                bootbox.alert({
                                    title: "Error: " + errorInfo.statusText,
                                    message: errorInfo.responseJSON.message !==
                                        undefined ? errorInfo.responseJSON.message :
                                        'Option is not added.'
                                });
                            }
                        });
                    }
                }
            });


        });
    </script>
@endpush

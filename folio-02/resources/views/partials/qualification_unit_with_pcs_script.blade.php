
<script>
    $(function() {
        $('.frmDeleteUnit, .frmDeleteUnitPC').submit(function(e) {
            var currentForm = this;
            e.preventDefault();

            $confirmMessage = 'Are you sure you want to remove this unit and its associated criteria?';
            if ($(currentForm).hasClass('frmDeleteUnitPC')) {
                $confirmMessage = 'Are you sure you want to remove this criteria?';
            }

            bootbox.confirm({
                title: "Confirmation",
                message: $confirmMessage,
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm btn-round",
                    },
                    confirm: {
                        label: '<i class="fa fa-trash"></i> Confirm',
                        className: "btn-danger btn-sm btn-round",
                    }
                },
                callback: function(result) {
                    if (result) {
                        $(currentForm).find(':submit').attr("disabled", true);
                        $(currentForm).find(':submit').attr("title", "Deleting ... ");
                        $(currentForm).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');

                        currentForm.submit();
                    }
                }
            });
        });
    });
</script>

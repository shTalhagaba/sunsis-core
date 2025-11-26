@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
    $(function(){
        $('#tblPcs').DataTable({
            "lengthChange": false,
            "paging" : false,
            "info" : false,
            "order": false
        });

        $('.dataTables_filter input[type="search"]').css({
            'width':'350px','display':'inline-block'
        });
        
        $('#frmProgrammeSessionTask').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                session_number: {
                    required: true
                },
                session_sequence: {
                    required: true,
                    digits: true
                },
                session_details_1: {
                    maxlength: 1600
                },
                session_details_2: {
                    maxlength: 1600
                }
            },

            highlight: function (e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function (error, element) {
                error.insertAfter(element);
            }
        });

    });
</script>

@endsection
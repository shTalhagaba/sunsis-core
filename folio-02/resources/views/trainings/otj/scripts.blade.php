<script type="text/javascript">
    $(function() {
        $('#otj_evidence').ace_file_input({
                'maxSize': 12000000,
                'allowExt': ['pdf', 'doc', 'docx', 'txt', 'zip', 'xls', 'xlsx', 'jpg', 'png'],
            })
            .on('file.error.ace', function(event, info) {
                if(info.error_count['size'] > 0)
                {
                    alert('File size exceeds maximum allowed file size of 12MB.')
                }
                if(info.error_count['ext'] > 0)
                {
                    alert('File type is not allowed.')
                }
                
                event.preventDefault();
            });
    });
</script>

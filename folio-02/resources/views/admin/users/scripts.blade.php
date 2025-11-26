<script>
    var employerUserType = "{{ App\Models\Lookups\UserTypeLookup::TYPE_EMPLOYER_USER }}";
    
    $("select[name=user_type]").on("change", function() {
        $("select[name=employer_location]").val("");
        $("#divEmployer").hide();
        $("#btnPopulateWorkAddressFromEmployer").hide();
        if (this.value == 18) {
            $("#divEmployer").show();
            $("#btnPopulateWorkAddressFromEmployer").show();
        }
    });

    $('#btnPopulateWorkAddressFromEmployer').on('click', function(e) {
        e.preventDefault();
        var employer_location = $('#employer_location').val();
        if (employer_location == '') {
            $.alert({
                title: 'Validation Error!',
                icon: 'fa fa-warning',
                type: 'red',
                content: 'Please select an employer to bring its address.',
                onDestroy: function() {
                    $("[name=employer_location]").focus();
                }
            });
            return false;
        }

        $(this).html('<i class="fa fa-refresh fa-spin"></i> Loading ...');

        $.ajax({
            url: '{{ route('getOrganisationLocation') }}',
            data: {
                location_id: employer_location
            },
            type: 'get',
        }).done(function(data) {
            $("[name=work_address_line_1]").val(data.address_line_1);
            $("[name=work_address_line_2]").val(data.address_line_2);
            $("[name=work_address_line_3]").val(data.address_line_3);
            $("[name=work_address_line_4]").val(data.address_line_4);
            $("[name=work_postcode]").val(data.postcode);
            $("[name=work_telephone]").val(data.telephone);
            $("[name=work_mobile]").val(data.mobile);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            $.alert({
                title: 'Encountered an error!',
                content: textStatus + ': ' + errorThrown,
                icon: 'fa fa-warning',
                theme: 'supervan',
                type: 'red'
            });
        }).always(function() {
            $("#btnPopulateWorkAddressFromEmployer").html('Populate from selected employer');
        });

    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {

        $('#frmUser').validate({
            errorElement: 'div',
            errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                username: { required: true },
                firstnames: { required: true },
                surname: { required: true },
                primary_email: { required: true },
                work_postcode: { postcodeUK: true },
                home_postcode: { postcodeUK: true },
                employer_location: {
                    required: function (element){
                        return $("#user_type").val() == employerUserType
                    }
                }
            },

            messages: {
                primary_email: {
                    required: "Please provide a valid email.",
                    email: "Please provide a valid email."
                },
                secondary_email: { email: "Please provide a valid email." },
                work_postcode: { postcodeUK: "Please provide a valid work UK postcode." },
                home_postcode: { postcodeUK: "Please provide a valid home UK postcode." },
                ni: { niUK: "Please provide a valid UK National Insurance." }
            },

            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function(error, element) {
                if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                    var controls = element.closest('div[class*="col-"]');
                    if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                    else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                } else
                    error.insertAfter(element);
            }
        });        
    });

    $(document).ready(function () {
        const $genderSelect = $('#gender-select');
        const $selfDescribeWrapper = $('#self-describe-wrapper');
        const $selfDescribeInput = $selfDescribeWrapper.find('input');

        function toggleSelfDescribe() {
            if ($genderSelect.val() === 'SELF') {
                $selfDescribeWrapper.show();
            } else {
                $selfDescribeWrapper.hide();
                $selfDescribeInput.val('');
            }
        }

        $genderSelect.on('change', toggleSelfDescribe);
        toggleSelfDescribe(); // run on page load
    });
    
    function toggleAssessorType() {
        const userType = document.getElementById('user_type').value;
        const wrapper = document.getElementById('assessor-type-wrapper');

        if (userType == 3) { // Assessor
             wrapper.style.display = 'flex';
        } else {
             wrapper.style.display = 'none';
        }
    }

    document.getElementById('user_type')?.addEventListener('change', toggleAssessorType);

    // Trigger on page load (for edit screen)
    toggleAssessorType();
    

</script>

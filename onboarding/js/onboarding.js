function saveForm()
{
    $.ajax({
        type:'POST',
        url:'do.php',
        data: $('#frmOnboarding').serialize(),
        async: false,
        beforeSend: function(){
            //$("#loading").dialog('open').html("<p><i class=\"fa fa-refresh fa-spin\"></i> Busy ...</p>");
        },
        success: function(data, textStatus, xhr) {
            console.log(data);
        },
        error: function(data, textStatus, xhr){
            var myxml = data.responseText,
                xmlDoc = $.parseXML( myxml ),
                $xml = $( xmlDoc );
            $(data.responseXML).find('error').each(function(){
                //alert('Error: \n' + $(this).find('message').text());
                console.log('Error: ' + $(this).find('message').text());
                console.log('Error: ' + $(this).find('line').text());
                console.log('Error: ' + $(this).find('file').text());
            });
        }
    });
}

$(function(){

    $("select#fs_eng_opt_in").on("change", function (){
		$("tr#tr_eng_opt_out_reason").hide();
		if(this.value === 'No')
		{
			$("tr#tr_eng_opt_out_reason").show();
		}
	});
	$("select#fs_maths_opt_in").on("change", function (){
		$("tr#tr_maths_opt_out_reason").hide();
		if(this.value === 'No')
		{
			$("tr#tr_maths_opt_out_reason").show();
		}
	});

    $(".timebox").timepicker({ timeFormat: 'H:i'/*, minTime: '08:00:00', maxTime: '18:00:00'*/ });
    //$('.timebox').attr('class', 'timebox optional form-control');

    $("#loading").dialog({
        autoOpen: false,
        width: 'auto',
        height: 'auto',
        modal: true,
        closeOnEscape: false,
        resizable: false,
        draggable: false,
        buttons: {}
    });

    var frmOnboarding = $("#frmOnboarding").show();
    frmOnboarding.steps({
        headerTag:"h3",
        bodyTag:"step",
        transitionEffect:"slideLeft",
        stepsOrientation:"vertical",
        // startIndex: 3,
        onStepChanging:function (event, currentIndex, newIndex) {
            // Always allow previous action even if the current form is not valid!
            $('.loader').show();
            if (currentIndex > newIndex) {//back
                $('.loader').hide();
                return true;
            }
            if(currentIndex == 0) {
                var selected_rui = 0;
                $("input[name='RUI[]']").each( function () {
                    if( this.checked )
                    {
                        selected_rui++;
                    }
                });
                var selected_pmc = 0;
                $("input[name='PMC[]']").each( function () {
                    if( this.checked )
                    {
                        selected_pmc++;
                    }
                });
                if(selected_rui > 0 && selected_pmc == 0)
                {
                    alert('Please select the options how you want to be contacted (post, phone or email)');
                    $('.loader').hide();
                    return false;
                }

                var v = 0;
                $("input[name='disclaimer[]']").each( function () {
                    if( this.checked )
                    {
                        v++;
                    }
                });
                if(v != 3)
                {
                    custom_alert_OK_only('Please tick the consent options to continue.', 'Alert');
                    $('.loader').hide();
                    return false;
                }
            }
            if(currentIndex == 1) {
                selected_lldd = [];
                if($('#LLDD').val() == 'Y')
                {
                    var v = 0;
                    $("input[name='llddcat[]']").each( function () {
                        if(this.checked)
                        {
                            v++;
                            selected_lldd.push(this.value);
                        }
                    });
                    if(v == 0)
                    {
                        custom_alert_OK_only('Please select at least one option from applicable LLDD categories.', 'Alert');
                        $('.loader').hide();
                        return false;
                    }
                    if( $.inArray($('input[name="primary_lldd"]:checked').val(), selected_lldd) < 0)
                    {
                        custom_alert_OK_only('Please select Primary LLDD from your chosen LLDD categories.', 'Alert');
                        $('.loader').hide();
                        return false;
                    }
                }
                console.log(selected_lldd);
            }
	    if(currentIndex == 2) {
                if(window.phpCName == "am_ela")
                {
                    var notAsnwered = 0;
                    $('select[name^="als_answer"]').each(function(index, elem){
                        if(elem.value == '')
                            notAsnwered++;
                    });
                    $('select[name^="als_t2_answer"]').each(function(index, elem){
                        if(elem.value == '')
                            notAsnwered++;
                    });
                    if(notAsnwered > 0)
                    {
                        custom_alert_OK_only('Please answer all questions.', 'Alert');
                        $('.loader').hide();
                        return false;
                    }
                }
            }
	    
            if(currentIndex == 5) {
                var selected = $("input[type='radio'][name='EmploymentStatus']:checked");
                if(selected.length == 0){
                    custom_alert_OK_only('Please select your employment status', 'Alert');
                    $('.loader').hide();
                    return false;
                }
            }
            if(currentIndex == 7) {
                if(!$("input[name=agree_app_agreement]").prop('checked'))
                {
                    custom_alert_OK_only('Please agree to continue.', 'Alert');
                    $('.loader').hide();
                    return false;
                }
            }
            if(currentIndex == 8) {
                if(!$("input[name=roles_resp_desc]").prop('checked'))
                {
                    custom_alert_OK_only('Please agree to continue.', 'Alert');
                    $('.loader').hide();
                    return false;
                }
                var v = 0;
                $("input[name='learner_dec[]']").each( function () {
                    if( this.checked )
                    {
                        v++;
                    }
                });
                if(v != $("input[name='learner_dec[]']").length)
                {
                    custom_alert_OK_only('Please tick all declaration options to continue.', 'Alert');
                    $('.loader').hide();
                    return false;
                }
            }
            
            var validForm = frmOnboarding.valid();
            if(validForm)
            {
                if (currentIndex < newIndex)
                {//forward
                    frmOnboarding[0].elements['is_finished'].value = 'N';
                    saveForm();
                }
                return true;
            }
            else
            {
                $('.loader').hide();
                return false;
            }
        },
        onStepChanged:function (event, currentIndex, priorIndex) {
            $('.loader').hide();
            //window.scrollTo(0, 0);
            return true;
        },
        onFinishing:function (event, currentIndex) {
            if($("#learner_sign").val() == '')
                return alert('Your signature is required to complete this form, please sign the form.');

            return frmOnboarding.valid();
        },
        onFinished:function (event, currentIndex) {
            frmOnboarding[0].elements['is_finished'].value = 'Y';
            frmOnboarding.submit();
        }
    }).validate({
        errorPlacement: function (error, element)
        {
            element.after(error);
        },
        rules: {
            learner_title: {
                required:true
            },
            firstnames: {
                required:true
            },
            surname: {
                required:true
            },
            dob: {
                required:true,
                dateUK:true
            },
            ethnicity: {
                required:true
            },
            gender: {
                required:true
            },
            LLDD: {
                required:true
            },
            home_address_line_1: {
                required:true
            },
            home_address_line_3: {
                required:true
            },
            home_postcode: {
                postcodeUK:true,
                required: true
            },
            ni: {
                required: function(element){
                    //return $("#learner_age_in_years").val().trim() >= 16
                    return true
                },
                niUK:true
            },
            home_email: {
                required:true,
                emailCheck:true
            },
            home_telephone: {
                phoneUK: true
            },
            em_con_tel1: {
                required: function(element){
                    return $("input[type='text'][name='em_con_name1']").val().trim() != ''
                }
            },
            em_con_tel2: {
                required: function(element){
                    return $("input[type='text'][name='em_con_name2']").val().trim() != ''
                }
            },
            em_con_mob1: {
                required: function(element){
                    return $("input[type='text'][name='em_con_name1']").val().trim() != ''
                }
            },
            em_con_mob2: {
                required: function(element){
                    return $("input[type='text'][name='em_con_name2']").val().trim() != ''
                }
            },
            empStatusEmployer: {
                required: function(element){
                    return $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
                }
            },
            LOE: {
                required: function(element){
                    return $("#LOE").val() == '' && $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
                }
            },
            EII: {
                required: function(element){
                    return $("#EII").val() == '' && $("input[type='radio'][name='EmploymentStatus']:checked").val() == '10'
                }
            },
            LOU: {
                required: function(element){
                    return $("#LOU").val() == '' && ($("input[type='radio'][name='EmploymentStatus']:checked").val() == '11' || $("input[type='radio'][name='EmploymentStatus']:checked").val() == '12')
                }
            },
            gcse_english_date_completed: {
                dateUK:true
            },
            gcse_maths_date_completed: {
                dateUK:true
            },
            gcse_ict_date_completed: {
                dateUK:true
            },
            date_completed1: {
                dateUK:true
            },
            date_completed2: {
                dateUK:true
            },
            date_completed3: {
                dateUK:true
            },
            date_completed4: {
                dateUK:true
            },
            date_completed5: {
                dateUK:true
            },
            date_completed6: {
                dateUK:true
            },
            date_completed7: {
                dateUK:true
            },
            date_completed8: {
                dateUK:true
            },
            date_completed9: {
                dateUK:true
            },
            date_completed10: {
                dateUK:true
            },
            date_completed11: {
                dateUK:true
            },
            date_completed12: {
                dateUK:true
            },
            date_completed13: {
                dateUK:true
            },
            date_completed14: {
                dateUK:true
            },
            date_completed15: {
                dateUK:true
            },
            high_level: {
                required:true
            },
            fs_eng_opt_in: {
                required: function(element){
                    return $("#fs_eng_opt_in").val() == '' && window.phpAgeAtStart >= 19;
                }
            },
            fs_maths_opt_in: {
                required: function(element){
                    return $("#fs_maths_opt_in").val() == '' && window.phpAgeAtStart >= 19;
                }
            },
            gcse_english_grade_predicted: {
                required: function(element){
                    return $("#gcse_english_grade_actual").val() == ''
                }
            },
            gcse_english_grade_actual: {
                required: function(element){
                    return $("#gcse_english_grade_predicted").val() == ''
                }
            },
            gcse_maths_grade_predicted: {
                required: function(element){
                    return $("#gcse_maths_grade_actual").val() == ''
                }
            },
            gcse_maths_grade_actual: {
                required: function(element){
                    return $("#gcse_maths_grade_predicted").val() == ''
                }
            },
            evidence_pp: {
                filesize: 1048000
            },
            evidence_ilr: {
                filesize: 1048000
            },
            evidence_previous_uk_study_visa: {
                filesize: 1048000
            }
        }
    });

    jQuery.validator.addMethod("postcodeUK", function(value, element) {
        return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value) || value == 'EC1V 9EU';
    }, "Please specify a valid Postcode");

    jQuery.validator.addMethod('phoneUK', function(phone_number, element) {
        return this.optional(element) || phone_number.length > 9 &&
            phone_number.match(/^(((\+44)? ?(\(0\))? ?)|(0))( ?[0-9]{3,4}){3}$/);
    }, 'Please specify a valid phone number');

    jQuery.validator.addMethod("niUK", function(value, element) {
        //return this.optional(element) || /^\s*[a-zA-Z]{2}(?:\s*\d\s*){6}[a-zA-Z]?\s*$/i.test(value);
        return this.optional(element) || /^[A-CEGHJ-PR-TW-Z]{1}[A-CEGHJ-NPR-TW-Z]{1}[0-9]{6}[A-D]{0,1}$/i.test(value);
    }, "Please specify a valid National Insurance Number");

    jQuery.validator.addMethod("emailCheck", function(value, element) {
        return this.optional(element) || /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/i.test(value);
    }, "Please specify a valid Email address");

    jQuery.validator.addMethod("dateUK",function(value, element) {
            return value == ''?true:value.match(/^(?=\d)(?:(?:31(?!.(?:0?[2469]|11))|(?:30|29)(?!.0?2)|29(?=.0?2.(?:(?:(?:1[6-9]|[2-9]\d)(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00)))(?:\x20|$))|(?:2[0-8]|1\d|0?[1-9]))([-.\/])(?:1[012]|0?[1-9])\1(?:1[6-9]|[2-9]\d)\d\d(?:(?=\x20\d)\x20|$))?(((0?[1-9]|1[012])(:[0-5]\d){0,2}(\x20[AP]M))|([01]\d|2[0-3])(:[0-5]\d){1,2})?$/);
        }, "Please enter a date in the format dd/mm/yyyy."
    );

    jQuery.validator.addMethod("filesize",function (value, element, param) {
            return this.optional(element) || (element.files[0].size <= param)
        }, 'Maximum file size is 1MB'
    );

    $('.datecontrol').datepicker({
        dateFormat: 'dd/mm/yy',
        yearRange: 'c-50:c+50',
        changeMonth: false,
        changeYear: true,
        constrainInput: true,
        buttonImage: "/images/calendar-icon.gif",
        buttonImageOnly: true,
        buttonText: "Show calendar",
        showOn: "both",
        showAnim: "fadeIn"
    });

    $('input[name=dob]').datepicker("option", "yearRange", "-50:-10");
    $('input[name=dob]').datepicker("option", "defaultDate", "-18y");

    $(window).scroll(function() {
        if ($(".navbar").offset().top > 5) {
            $('.headerlogo').attr('src', window.phpHeaderLogo2); //change src
            $("#mainNav").fadeIn("slow", function(){
                $("#mainNav").css("opacity", "0.95");
            });
        } else {
            $('.headerlogo').attr('src', window.phpHeaderLogo1);
            $("#mainNav").fadeIn("slow", function(){
                $("#mainNav").css("opacity", "");
            });
        }
    });

    $('.clsICheck').each(function(){
        var self = $(this),
            label = self.next(),
            label_text = label.text();

        label.remove();
        self.iCheck({
            checkboxClass: 'icheckbox_line-blue',
            /*radioClass: 'iradio_line',*/
            insert: '<div class="icheck_line-icon"></div>' + label_text
        });

    });

    $('input[type=radio]').iCheck({
        radioClass: 'iradio_square-red'
    });

    $('a[href="#next"]').html($('a[href="#next"]').html()+' &nbsp; <i class="fa fa-forward"></i>');
    $('a[href="#previous"]').html('<i class="fa fa-backward"></i> &nbsp; ' + $('a[href="#previous"]').html());

    loadDefaultSignatures();

    $('#LLDD').change(function() {
        if($(this).val() == 'Y')
            $('#divLLDDCat').show();
        else
            $('#divLLDDCat').hide();
    });

    if($('#LLDD').val() == 'Y')
    {
        $('#divLLDDCat').show();
    }

    showEmploymentStatusFieldsIfAlreadySaved();

    $("input[name=EmploymentStatus]").on('ifChecked', function(event){
        if(this.value == 10)
        {
            $('#tbl_emp_status_10').show();
            $('#tbl_emp_status_11_12').hide();
            $('#row_working_pattern').show();
        }
        else if(this.value == 11 || this.value == 12)
        {
            $('#tbl_emp_status_10').hide();
            $('#tbl_emp_status_11_12').show();
            $('#row_working_pattern').hide();
        }
        else
        {
            $('#tbl_emp_status_10').hide();
            $('#tbl_emp_status_11_12').hide();
            $('#row_working_pattern').hide();
        }
    });

    $("input[name='EligibilityList[]'").on('change', function(){
       if(this.value == 2)
       {
            if(this.checked)
            {
                $("input[name=currently_enrolled_in_other]").prop('disabled', false);
            }
            else
            {
                $("input[name=currently_enrolled_in_other]").prop('disabled', true);
                $("input[name=currently_enrolled_in_other]").val('');
            }
       }
    });

});



var fonts = Array("Little_Days.ttf","ArtySignature.ttf","Signerica_Medium.ttf","Champignon_Alt_Swash.ttf","Bailey_MF.ttf","Carolina.ttf","DirtyDarren.ttf","Ruf_In_Den_Wind.ttf");
var sizes = Array(15,40,15,20,20,20,15,30);

function refreshSignature()
{
    for(var i = 1; i <= 8; i++)
        $("#img"+i).attr('src', 'images/loading.gif');

    for(var i = 0; i <= 7; i++)
        $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title='+$("#signature_text").val()+'&font='+fonts[i]+'&size='+sizes[i]);
}

function loadDefaultSignatures()
{
    for(var i = 1; i <= 8; i++)
        $("#img"+i).attr('src', 'images/loading.gif');

    for(var i = 0; i <= 7; i++)
        $("#img"+(i+1)).attr('src', 'do.php?_action=generate_image&title=Signature'+'&font='+fonts[i]+'&size='+sizes[i]);
}

function onlyAlphabets(e, t)
{
    try {
        if (window.event) {
            var charCode = window.event.keyCode;
        }
        else if (e) {
            var charCode = e.which;
        }
        else { return true; }
        if ((charCode > 64 && charCode < 91) || (charCode > 96 && charCode < 123) || charCode == 32 || charCode == 39 || charCode == 45 || charCode == 8 || charCode == 46)
            return true;
        else
            return false;
    }
    catch (err) {
        alert(err.Description);
    }
}

function getSignature()
{
    $( "#panel_signature" ).dialog( "open");
}

function SignatureSelected(sig)
{
    $('.sigboxselected').attr('class','sigbox');
    sig.className = "sigboxselected";
}

$(function() {
    $( "#panel_signature" ).dialog({
        autoOpen: false,
        modal: true,
        draggable: false,
        width: "auto",
        height: 500,
        buttons: {
            'Add': function() {
                $("#img_learner_sign").attr('src',$('.sigboxselected').children('img')[0].src);
                $("#learner_sign").val($('.sigboxselected').children('img')[0].src);
                $(this).dialog('close');
            },
            'Cancel': function() {$(this).dialog('close');}
        }
    });
});

$("#personal_info :input").change(function() {
    if(this.id == 'home_address_line_1' || this.id == 'home_address_line_2' || this.id == 'home_address_line_3' || this.id == 'home_address_line_4')
        $('#ilp_address').html($('#home_address_line_1').val() + ' ' + $('#home_address_line_2').val() + ' ' + $('#home_address_line_3').val() + ' ' + $('#home_address_line_4').val());
    else
        $('#ilp_'+this.id).html(this.value);
});

function custom_alert_OK_only(output_msg, title_msg)
{
    return alert(output_msg);
    if (!title_msg)
        title_msg = 'Alert';

    if (!output_msg)
        output_msg = 'No Message to Display.';

    $("<div></div>").html(output_msg).dialog({
        title: title_msg,
        resizable: false,
        modal: true,
        buttons: {"OK": function(){
                $( this ).dialog( "close" );
            }
        }
    });
}

function copyInfoForILP()
{
    $('#ilp_learner_title').html($('#learner_title').val());
    $('#ilp_firstnames').html($('#firstnames').val());
    $('#ilp_surname').html($('#surname').val());
    $('#ilp_input_dob').html($('#input_dob').val());
    $('#ilp_gender').html($('#gender').val());
    $('#ilp_address').html($('#home_address_line_1').val() + ' ' + $('#home_address_line_2').val() + ' ' + $('#home_address_line_3').val() + ' ' + $('#home_address_line_4').val());
    $('#ilp_home_postcode').html($('#home_postcode').val());
    $('#ilp_home_email').html($('#home_email').val());
    $('#ilp_home_telephone').html($('#home_telephone').val());
    $('#ilp_home_mobile').html($('#home_mobile').val());
    $('#ilp_em_con_title').html($('#em_con_title').val());
    $('#ilp_em_con_name').html($('#em_con_name').val());
    $('#ilp_em_con_rel').html($('#em_con_rel').val());
    $('#ilp_em_con_tel').html($('#em_con_tel').val());
    $('#ilp_em_con_mob').html($('#em_con_mob').val());

    $('#ilp_gcse_english').html('<td>GCSE - English Language</td><td>'+$("#input_gcse_english_date_completed").val()+'</td><td>'+$("#gcse_english_grade_actual option:selected").text()+'</td><td>&nbsp;</td>');
    $('#ilp_gcse_maths').html('<td>GCSE - Maths</td><td>'+$("#input_gcse_maths_date_completed").val()+'</td><td>'+$("#gcse_maths_grade_actual option:selected").text()+'</td><td>&nbsp;</td>');
    for(var i = 1; i <= 15; i++)
        $('#ilp_pa'+i).html('<td>'+$("#level"+i+" option:selected").text()+' - '+$("#subject"+i).val()+'</td><td>'+$("#input_date_completed"+i).val()+'</td><td>'+$("#actual_grade"+i+" option:selected").text()+'</td><td>&nbsp;</td>');
}

function showEmploymentStatusFieldsIfAlreadySaved()
{
    var EmpStatus = $("input[name=EmploymentStatus]:checked").val();

    if(EmpStatus == '10')
    {
        $('#tbl_emp_status_10').show();
        $('#tbl_emp_status_11_12').hide();
    }
    else if(EmpStatus == '11' || EmpStatus == '12')
    {
        $('#tbl_emp_status_10').hide();
        $('#tbl_emp_status_11_12').show();
    }
    else
    {
        $('#tbl_emp_status_10').hide();
        $('#tbl_emp_status_11_12').hide();
    }
}
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
        // startIndex: 4,
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

                
            }
            if(currentIndex == 2) {
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
                        alert('Please select at least one option from applicable LLDD categories.', 'Alert');
                        $('.loader').hide();
                        return false;
                    }
                    if( $.inArray($('input[name="primary_lldd"]:checked').val(), selected_lldd) < 0)
                    {
                        alert('Please select Primary LLDD from your chosen LLDD categories.', 'Alert');
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
                        alert('Please answer all questions.', 'Alert');
                        $('.loader').hide();
                        return false;
                    }
                }
            }
            if(currentIndex == 4) {
                var selected = $("input[type='radio'][name='employment_status']:checked");
                if(selected.length == 0){
                    alert('Please select your employment status', 'Alert');
                    $('.loader').hide();
                    return false;
                }
            }
            
            if(currentIndex == 5) {
                var v = 0;
                $("input[name='disclaimer[]']").each( function () {
                    if( this.checked )
                    {
                        v++;
                    }
                });
                if(v != 4)
                {
                    alert('Please tick the consent options to continue.', 'Alert');
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
                    //saveForm();
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
            window.scrollTo(0, 0);
            showNameHeading();
            return true;
        },
        onFinishing:function (event, currentIndex) {
            
            if($("input[type='checkbox'][name='hear_us[]']:checked").length  == 0)
            {
                return alert('We\'d like to know how did you hear about us, please select an option');
            }

            if($("#learner_sign").val() == '')
            {
                return alert('Your signature is required to complete this form, please sign the form.');
            }

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
            hear_us: {
                required:true
            },
            hhs: {
                required:true
            },
            criminal_conviction: {
                required:true
            },
            currently_caring: {
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
            prior_attainment: {
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
            employer_contact_email: {
                required:false,
                emailCheck:true
            },
            home_telephone: {
                phoneUK: true
            },
            confidential_interview: {
                required: true
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
            emp_status_employer: {
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            LOE: {
                required: function(element){
                    return $("#LOE").val() == '' && $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            EII: {
                required: function(element){
                    return $("#EII").val() == '' && $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            LOU: {
                required: function(element){
                    return $("#LOU").val() == '' && ($("input[type='radio'][name='employment_status']:checked").val() == '11' || $("input[type='radio'][name='employment_status']:checked").val() == '12')
                }
            },
            BSI: {
                required: function(element){
                    return $("#BSI").val() == '' && ($("input[type='radio'][name='employment_status']:checked").val() == '11' || $("input[type='radio'][name='employment_status']:checked").val() == '12')
                }
            },
            PEI: {
                required: function(element){
                    return $("#PEI").val() == '' && ($("input[type='radio'][name='employment_status']:checked").val() == '11' || $("input[type='radio'][name='employment_status']:checked").val() == '12')
                }
            },
            SEI: {
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            emp_status_employer: {
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            emp_status_employer_tel: {
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            workplace_postcode: {
                postcodeUK:true,
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            via_current_employer: {
                required: function(element){
                    return $("input[type='radio'][name='employment_status']:checked").val() == '10'
                }
            },
            plan_to_work_alongside: {
                required: true
            }

        }
    });

    jQuery.validator.addMethod("postcodeUK", function(value, element) {
        // return this.optional(element) || /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i.test(value) || value == 'EC1V 9EU';
        return this.optional(element) || /^[a-z]{1,2}\d[a-z\d]?\s*\d[a-z]{2}$/i.test(value) ;
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
});


$(function() {
    

    $("input[name=employment_status]").on('click', function(event){
        if(this.value == 10)
        {
            $('#tbl_emp_status_10').show();
            $('#shiftPattern').show();
            $('#tbl_emp_status_11_12').hide();
        }
        else if(this.value == 11 || this.value == 12)
        {
            $('#tbl_emp_status_10').hide();
            $('#shiftPattern').hide();
            $('#tbl_emp_status_11_12').show();
        }
        else
        {
            $('#tbl_emp_status_10').hide();
            $('#shiftPattern').hide();
            $('#tbl_emp_status_11_12').hide();
        }
    });
    
});

function showNameHeading()
{
    $("#nameHeading").hide();
    if($("#firstnames").val() != '' && $("#surname").val() != '')
    {
        $("#registrantName").html($("#firstnames").val() + ' ' + $("#surname").val());
        $("#nameHeading").show();
    }
}

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
 

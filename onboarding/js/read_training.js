$(function () {
  $(".timebox").timepicker({ timeFormat: 'H:i', minTime: '08:00:00', maxTime: '18:00:00' });
  $('.tabHyperlink').click(function(){
    var selected_tab = $(this).attr('href');
    selected_tab = selected_tab.replace('#', '');
    var client = ajaxRequest('do.php?_action=ajax_helper&subaction=saveTabInSession&selected_tab='+selected_tab);
  });

  $("#frmEmailBody").summernote({
    toolbar: [
      ["style", ["bold", "italic", "underline", "clear"]],
      ["fontsize", ["fontsize"]],
      ["para", ["ul", "ol", "paragraph"]],
      ["height", ["height"]],
      ["insert", ["link", "picture", "hr"]],
    ],
    height: 300,
    callbacks: {
      onImageUpload: function (files, editor, welEditable) {
        sendFile(files[0], editor, welEditable);
      },
    },
  });

  $(".datecontrol").datepicker({
    dateFormat: "dd/mm/yy",
    yearRange: "c-50:c+50",
    changeMonth: false,
    changeYear: true,
    constrainInput: true,
    buttonImage: "/images/calendar-icon.gif",
    buttonImageOnly: true,
    buttonText: "Show calendar",
    showOn: "both",
    showAnim: "fadeIn",
  });

  $('#dialogDeleteFile').dialog({
    modal: true,
    width: 450,
    closeOnEscape: true,
    autoOpen: false,
    resizable: false,
    draggable: false,
    buttons: {
        'Delete': function() {
            $(this).dialog('close');
            var client = ajaxRequest('do.php?_action=delete_file&f=' + encodeURIComponent($(this).data('filepath')));
            if (client) {
                window.location.reload();
            }
        },
        'Cancel': function() {
            $(this).dialog('close');
        }
    }
  });

});

function copyUrl(url, copy_ele) {
  var copyText = document.getElementById(url);
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  document.execCommand("copy");
  var tooltip = document.getElementById(copy_ele);
  tooltip.innerHTML = "Copied: " + copyText.value;
  $("#" + copy_ele)
    .show()
    .delay(1000)
    .hide(0);
}

function sendEmail() {
  var frmEmail = document.forms["frmEmail"];
  if (!validateForm(frmEmail)) {
    return;
  }

  var client = ajaxPostForm(frmEmail);
  if (client) {
    if (client.responseText == "success")
      alert("Email has been sent successfully.");
    else alert("Unknown Email Error: Email has not been sent.");
  } else {
    alert(client);
  }
  window.location.reload();
}

function load_email_template_in_frmEmail() {
  var frmEmail = document.forms["frmEmail"];
  var tr_id = window.phpTrainingId;
  var email_template_type = frmEmail.frmEmailTemplate.value;

  if (email_template_type == "") {
    alert("Please select template from templates list");
    frmEmail.frmEmailTemplate.focus();
    return false;
  }

  function loadAndPrepareLearnerEmailTemplateCallback(client) {
    if (client && client.status == 200) {
      var result = $.parseJSON(client.responseText);
      if (result.status == "error") {
        alert(result.message);
        return;
      }

      $("#frmEmailBody").summernote("code", result.email_content);
    }
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate" +
      "&entity_type=ob_learners&entity_id=" +
      tr_id +
      "&template_type=" +
      email_template_type,
    null,
    null,
    loadAndPrepareLearnerEmailTemplateCallback
  );
}

function viewEmail(email_id) {
  if (email_id == "") return;

  var postData =
    "do.php?_action=ajax_email_actions" +
    "&subaction=" +
    encodeURIComponent("getEmail") +
    "&email_id=" +
    encodeURIComponent(email_id);

  var req = ajaxRequest(postData);
  $("<div class='small'></div>")
    .html(req.responseText)
    .dialog({
      id: "dialogEmailView",
      title: "Email",
      resizable: false,
      modal: true,
      width: 750,
      height: 500,

      buttons: {
        Close: function () {
          $(this).dialog("close");
        },
      },
    });
}

function saveEligibility() {
  var frmEligibility = document.forms["frmEligibility"];
  if (!validateForm(frmEligibility)) {
    return false;
  }

  var client = ajaxPostForm(frmEligibility);
  if (client) {
    alert("Eligibility saved.");
    window.location.reload();
  }
}

function viewKsbLogInfo() {
  var tr_id = window.phpTrainingId;

  var postData =
    "do.php?_action=ajax_helper" +
    "&subaction=getOblearnerKsbLog" +
    "&tr_id=" +
    encodeURIComponent(tr_id);

  var req = ajaxRequest(postData);
  $("<div></div>")
    .html(req.responseText)
    .dialog({
      id: "dlg_info",
      title: "Log",
      resizable: false,
      modal: true,
      width: "auto",
      height: 550,

      buttons: {
        Close: function () {
          $(this).dialog("close");
        },
      },
    });
}

function frmEmailTemplate_onchange(frmEmailTemplate) {
  var template = frmEmailTemplate.value;

  var frmEmail = document.forms["frmEmail"];

  if (frmEmailTemplate.value == "EMPLOYER_SCHEDULE") {
    frmEmail.frmEmailSubject.value = "Employer Agreement - Schedule 1";
  } else if (frmEmailTemplate.value == "INITIAL_ASSESSMENT_MATH") {
    frmEmail.frmEmailSubject.value = "Initial Assessment Math";
  } else if (frmEmailTemplate.value == "INITIAL_ASSESSMENT_ENGLISH") {
    frmEmail.frmEmailSubject.value = "Initial Assessment English";
  } else if (frmEmailTemplate.value == "SKILLS_SCAN_URL") {
    frmEmail.frmEmailSubject.value = "Skills Analysis";
  } else if (frmEmailTemplate.value == "REMINDER_SKILLS_SCAN") {
    frmEmail.frmEmailSubject.value = "Skills Analysis Reminder";
  } else if (frmEmailTemplate.value == "SKILLS_SCAN_PASSED") {
    frmEmail.frmEmailSubject.value = "Skills Analysis - Pass";
  } else if (frmEmailTemplate.value == "SKILLS_SCAN_FAILED") {
    frmEmail.frmEmailSubject.value = "Skills Analysis Result";
  } else if (frmEmailTemplate.value == "ONBOARDING_URL") {
    frmEmail.frmEmailSubject.value = "Onboarding Documents";
  } else if (frmEmailTemplate.value == "ENROLMENT_URL") {
    frmEmail.frmEmailSubject.value = "Enrolment Documents";
  } else if (frmEmailTemplate.value == "FIRST_DAY_IN_LEARNING") {
    frmEmail.frmEmailSubject.value = "First Learning Activity";
  } else if (frmEmailTemplate.value == "APP_AGREEMENT_EMAIL_TO_EMPLOYER") {
    frmEmail.frmEmailSubject.value = window.clientName + " (Apprenticeship agreement) - to be signed";
  } else if(frmEmailTemplate.value == "OTJ_LEARNER_URL" || frmEmailTemplate.value == "DP_LEARNER_URL" || frmEmailTemplate.value == "OTJ_EMPLOYER_URL" || frmEmailTemplate.value == "DP_EMPLOYER_URL") {
    frmEmail.frmEmailSubject.value = window.clientName + " (OTJ Planner) - to be signed";
  } else if(frmEmailTemplate.value == "SKILLS_SCAN_EMPLOYER_URL") {
    frmEmail.frmEmailSubject.value = window.clientName + " (Skills Scan) - to be signed";
  } else {
    template = template.replaceAll("_", " ");
    template = template.toLowerCase().replace(/\b[a-z]/g, function (letter) {
      return letter.toUpperCase();
    });
    frmEmail.frmEmailSubject.value = template;
  }

  var primary_contact_email = "Primary Contact Email";
  var trainer_work_email = "Trainer Work Email";
  var learner_email = window.phpLearnerPersonalEmail;
  if (frmEmailTemplate.value == "EMPLOYER_SCHEDULE") {
    frmEmail.frmEmailTo.value = primary_contact_email;
  } else if (frmEmailTemplate.value == "SKILLS_SCAN_EMPLOYER_URL") {
    frmEmail.frmEmailTo.value = window.phpEmployerContactEmail;
  } else if (frmEmailTemplate.value == "REFERAL_BUSINESS_DEVELOPMENT") {
    frmEmail.frmEmailTo.value = "";
  } else if (frmEmailTemplate.value == "REMINDER_SKILLS_SCAN_TRAINER") {
    frmEmail.frmEmailTo.value = trainer_work_email;
  } else if (frmEmailTemplate.value == "APP_AGREEMENT_EMAIL_TO_EMPLOYER") {
    frmEmail.frmEmailTo.value = window.phpEmployerContactEmail;
  } else if (frmEmailTemplate.value == "OTJ_EMPLOYER_URL") {
    frmEmail.frmEmailTo.value = window.phpEmployerContactEmail;
  } else {
    frmEmail.frmEmailTo.value = learner_email;
  }
}

function remove_tr_qualification(row_id) {
  if (
    !confirm(
      "Are you sure you want to remove this qualification aim from this training record?"
    )
  )
    return;

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=remove_tr_qualification&row_id=" +
      row_id
  );
  if (client) {
    alert(
      "Qualification aim is removed from the training record successfully."
    );
    window.location.reload();
  }
}

function add_tr_qualification() {
  var frm_add_tr_qualification = document.forms["frm_add_tr_qualification"];
  if (frm_add_tr_qualification.qualification.value == "") {
    alert("Please select the qualification from the drop down.");
    return;
  }

  var client = ajaxPostForm(frm_add_tr_qualification);
  if (client) {
    alert("Qualification aim is added to the training record successfully.");
    window.location.reload();
  }
}

function load_and_prepare_initial_contract_email(initial_contract_id) {
  var frmEmailInitialContract = document.forms["frmEmailInitialContract"];
  frmEmailInitialContract.initial_contract_id.value = initial_contract_id;

  function getEmployerAgreementTemplateCallback(client) {
    if (client && client.status == 200) {
      var result = $.parseJSON(client.responseText);
      if (result.status == "error") {
        alert(result.message);
        return;
      }

      $("#frmEmailInitialContractEmailBody").summernote(
	"code", 
	result.email_content
	);
      //$("form[name=frmEmailInitialContract] #frmEmailTo").val('');	
      $("#emailModal").modal("show");
    }
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_email_actions&subaction=loadAndPrepareLearnerEmailTemplate" +
      "&entity_type=ob_learners&entity_id=" +
      frmEmailInitialContract.frmEmailEntityId.value +
      "&template_type=EMPLOYER_SCHEDULE" +
      "&schedule_id=" +
      initial_contract_id,
    null,
    null,
    getEmployerAgreementTemplateCallback
  );
}

function update_tr_from_sa(sa_id) {
  if (
    !confirm(
      "This action will update training prices, duration and dates as per this skills analysis. Are you sure you want to continue?"
    )
  ) {
    return false;
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=update_tr_from_sa&tr_id="+window.phpTrainingId+"&sa_id=" +
      sa_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

function save_prior_attainment() {
  var myForm = document.forms["frmPriorAttainment"];
  if (!validateForm(myForm)) {
    return false;
  }

  if (
    myForm.gcse_english_grade_predicted.value == "" &&
    myForm.gcse_english_grade_actual.value == ""
  ) {
    alert("Please provide predicted or actual grade for GCSE English");
    myForm.gcse_english_grade_predicted.focus();
    return false;
  }

  if (
    myForm.gcse_maths_grade_predicted.value == "" &&
    myForm.gcse_maths_grade_actual.value == ""
  ) {
    alert("Please provide predicted or actual grade for GCSE Maths");
    myForm.gcse_maths_grade_predicted.focus();
    return false;
  }

  var client = ajaxPostForm(myForm);

  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

function save_employments() {
  var myForm = document.forms["frmEmployments"];
  if (!validateForm(myForm)) {
    return false;
  }

  var client = ajaxPostForm(myForm);

  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

function save_learner_contacts() {
  var myForm = document.forms["frmLearnerContacts"];
  if (!validateForm(myForm)) {
    return false;
  }

  var client = ajaxPostForm(myForm);

  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
  
}

function save_learner_eligibility() {
  var myForm = document.forms["frmLearnerEligibility"];
  if (!validateForm(myForm)) {
    return false;
  }

  myForm.submit();
  // var client = ajaxPostForm(myForm);

  // if (client) {
  //   alert(client.responseText);
  //   window.location.reload();
  // }
}

$("button#btnEmailModalSave").click(function(){
  var frmEmailInitialContract = document.forms["frmEmailInitialContract"];
  var agreement_id = frmEmailInitialContract.initial_contract_id.value;

  if(!validateForm(frmEmailInitialContract))
  {
      return;
  }

  var client1 = ajaxPostForm(frmEmailInitialContract);
  if(client1 && client1.responseText == 'success')
  {
    window.location.reload();
  }
});

function generateDocumentPdf(document)
{
  if(!confirm("This action will create a new pdf document. Are you sure you want to continue?"))
  {
    return;
  }
  var client = ajaxRequest("do.php?_action=ajax_helper&subaction=generateDocumentPdf&tr_id="+window.phpTrainingId+"&document="+encodeURIComponent(document));
  if(client)
  {
    window.location.reload();
  }
}

function generateOtjSheet()
{
    window.location.href='do.php?_action=ajax_helper&subaction=generateOtjSheetPdf&tr_id='+window.phpTrainingId;
}

function generateEvidenceOfEmploymentPdf() {
  window.location.href =
    "do.php?_action=ajax_helper&subaction=generateEvidenceOfEmploymentPdf&tr_id=" +
    window.phpTrainingId;
}

function generateFdilPdf() {
  window.location.href =
    "do.php?_action=ajax_helper&subaction=generateFdilPdf&tr_id=" +
    window.phpTrainingId;
}

function generateAlsPdf() {
  window.location.href =
    "do.php?_action=ajax_helper&subaction=generateAlsPdf&tr_id=" +
    window.phpTrainingId;
}

function generatePlrPdf() {
  window.location.href =
    "do.php?_action=ajax_helper&subaction=generatePlrPdf&tr_id=" +
    window.phpTrainingId;
}

function createLearnerInBksb() {
  if (window.phpBksbUsername == "") {
    alert(
      "BKSB username is unavailable. Please edit the record and provide BKSB username."
    );
    return;
  }

  var qs =
    "&username=" +
    encodeURIComponent(window.phpBksbUsername) +
    "&ob_learner_id=" +
    encodeURIComponent(window.phpObLearnerId);
  var client = ajaxRequest(
    "do.php?_action=ajax_bksb&subaction=createLearnerInBksb" + qs
  );

  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

/////////////////////////

$("button#btnDownloadLearnerPlr").on("click", function () {
  
  if(window.lrs_request.ULN === null)
  {
    alert("ULN is blank for this learner. Please edit the record and provide ULN.");
    return false;
  }
  //event.preventDefault();
  $(this).attr("disabled", true);
  $(this).html('<i class="fa fa-refresh fa-spin"></i> Contacting LRS ...');

  $.ajax({
    url: "do.php?_action=ajax_lrs&subaction=getLearnerLearningEvents",
    type: "GET",
    data: window.lrs_request,
    dataType: "json",
    success: function (response) {
      $("button#btnDownloadLearnerPlr").attr("disabled", false);
      $("button#btnDownloadLearnerPlr").html('<i class="fa fa-cloud-download"></i> Download Learning Events from LRS');
      if (response.status == "WSRC0004" || response.status == "WSRC0003") {
        window.location.reload();
      }
      else
      {
        var html = "Response Code: " + response.status + "<br>" +
          "Description: " + response.lrs_code_description + "<br>";
        $("<div></div>")
          .html(html)
          .dialog({
            id: "dlg_lrs_result",
            title: response.status,
            resizable: false,
            modal: true,
            width: 750,
            height: 500,
            buttons: {
              Close: function () {
                $(this).dialog("close");
                return false;
              },
            },
          });
      }
      if (response.SOAP_faultcode !== undefined && response.SOAP_faultcode != "" ) {
        var fault = "SOAP faultcode: " + response.SOAP_faultcode + "<br>" +
          "LRS_ErrorCode: " + response.LRS_ErrorCode + "<br>" +
          "LRS_Description: " + response.LRS_Description + "<br>" +
          "LRS_FurtherDetails: " + response.LRS_FurtherDetails + "<br>";
        $("<div></div>")
          .html(fault)
          .dialog({
            id: "dlg_lrs_result",
            title: "Error",
            resizable: false,
            modal: true,
            width: 750,
            height: 500,
            buttons: {
              Close: function () {
                $(this).dialog("close");
                return false;
              },
            },
          });
      }
      console.log("success");
      console.log(response);
    },
    error: function (request, error) {
      $("button#btnDownloadLearnerPlr").attr("disabled", false);
      $("button#btnDownloadLearnerPlr").html(
        '<i class="fa fa-cloud-download"></i> Download Learning Events from LRS'
      );
      console.log("error");
      console.log("Request: " + JSON.stringify(request));
      console.log("Request: " + JSON.stringify(error));
    },
  });
});

//function uploadFile() {
//  var myForm = document.forms["frmUploadFile"];
//  if (validateForm(myForm) == false) {
//      return false;
//  }
//  myForm.submit();
//}

function deleteFile(path) {
  var $dialog = $('#dialogDeleteFile');

  $dialog.data('filepath', path);

  var filename = path.split('/').pop();
  $dialog.html("<p>Delete <b>" + filename + "</b>.</p><p>Deletion is permanent and irrecoverable.  Continue?</p>");

  $dialog.dialog("open");
}

function update_tr_duration_from_sa(sa_id) {
  if (
    !confirm(
      "This action will update duration and dates as per this skills analysis. Are you sure you want to continue?"
    )
  ) {
    return false;
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=update_tr_duration_from_sa&tr_id=" +
      window.phpTrainingId +
      "&sa_id=" +
      sa_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

function update_tr_price_from_sa(sa_id) {
  if (
    !confirm(
      "This action will update prices as per this skills analysis. Are you sure you want to continue?"
    )
  ) {
    return false;
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=update_tr_price_from_sa&tr_id=" +
      window.phpTrainingId +
      "&sa_id=" +
      sa_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

function submitFrmFdil()
{
  var frmFdil = document.forms["frmFdil"];
  if(!validateForm(frmFdil))
  {
    return;
  }
  frmFdil.submit();
}

function resendEmail(email_id)
{
  if (email_id == "") return;

  var postData =
    "do.php?_action=ajax_email_actions" +
    "&subaction=" +
    encodeURIComponent("getEmail") +
    "&email_id=" +
    encodeURIComponent(email_id);

  var req = ajaxRequest(postData);
  $("<div class='small'></div>")
    .html(req.responseText)
    .dialog({
      id: "dialogEmailView",
      title: "Confirmation: Sure to resend this email?",
      resizable: false,
      modal: true,
      width: 750,
      height: 500,

      buttons: [
        {
          text: "Close",
          class: "btn btn-default",
          click: function() {
            $( this ).dialog( "close" );
          }
        },
        {
          text: "Click to resend this email",
          class: "btn btn-primary",
          click: function() {
            var client = ajaxRequest("do.php?_action=ajax_email_actions&subaction=resendEmail&email_id=" + encodeURIComponent(email_id) );
            if(client)
            {
              window.location.reload();
            }
            else
            {
              alert("Something went wrong, please try again.");
              $( this ).dialog( "close" );
            }
          }
        }
      ],
    });
}

function deleteFdil(entry_id)
{
  if(!confirm("Are you sure you want to remove this FDIL entry?"))
  {
    return false;
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=delete_fdil_entry&id=" + entry_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
 
}

function delete_initial_contract(entry_id)
{
  if(!confirm("Are you sure you want to remove this entry?"))
  {
    return false;
  }

  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=delete_initial_contract&id=" + entry_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
 
}

//if(window.phpTrainingId == 1038)
//{
  refreshGridTotals();
//}

function refreshGridTotals()
{
    $('input[name^="total_section_"]').each(function(index, elem){
        var name_parts = elem.name.split('_');
        var col = name_parts[4];

        sum = 0;
        $("input[name$='_col_"+col+"']").not('input[name^="total_section_"]').each(function(){
            sum += Number( $(this).val() );
        });

        $(this).val( sum );
    }); 
}

function generateEmpAgrWithTr(tr_id)
{
  window.location.href = "do.php?_action=ajax_helper&subaction=generateEmpAgrWithTr&tr_id=" + window.phpTrainingId;
}

function reset_otj_planner_grid(tr_id)
{
  if(!confirm("This will reset the OTJ grid and set default values from the programme, are you sure you want to continue?"))
  {
    return false;
  }
  var client = ajaxRequest(
    "do.php?_action=ajax_helper&subaction=reset_otj_planner_grid&tr_id=" + tr_id
  );
  if (client) {
    alert(client.responseText);
    window.location.reload();
  }
}

document.getElementById('frmUploadFile').addEventListener('submit', function(event) {
  const fileInput = document.getElementById('input_uploaded_learner_file');
  const selectedFile = fileInput.files[0];
  const uploadFileButton = document.getElementById('uploadFileButton');

  if (!selectedFile) {
    alert('Please select a file to upload.');
    event.preventDefault();
    return;
  }

  const allowedTypes = [
    'image/jpeg',
    'image/png',
    'application/pdf', 
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/csv',
    'text/plain',
    'application/xml',
    'application/zip',
    'application/x-zip-compressed',
    'application/x-rar-compressed',
    'application/x-7z-compressed'
  ];
  
  if (!allowedTypes.includes(selectedFile.type)) {	
    alert('This file type is not allowed.');
    event.preventDefault();
    return;
  }

  const maxFileSizeInBytes = 5 * 1024 * 1024; 
  if (selectedFile.size > maxFileSizeInBytes) {
    alert('File size exceeds the maximum allowed (5MB).');
    event.preventDefault();
    return;
  }

  uploadFileButton.disabled = true;
  uploadFileButton.innerHTML = '<i class="fa fa-hourglass-2 fa-spin"></i> Uploading';

});

document.getElementById('frmUploadDpFile').addEventListener('submit', function(event) {
  const fileInput = document.getElementById('input_uploaded_learner_dp_file');
  const selectedFile = fileInput.files[0];
  const uploadFileButton = document.getElementById('uploadDpFileButton');

  if (!selectedFile) {
    alert('Please select a file to upload.');
    event.preventDefault();
    return;
  }

  const allowedTypes = [
    'image/jpeg',
    'image/png',
    'application/pdf', 
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/vnd.ms-excel',
    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    'text/csv',
    'text/plain',
    'application/xml',
    'application/zip',
    'application/x-zip-compressed',
    'application/x-rar-compressed',
    'application/x-7z-compressed'
  ];
  
  if (!allowedTypes.includes(selectedFile.type)) {	
    alert('This file type is not allowed.');
    event.preventDefault();
    return;
  }

  const maxFileSizeInBytes = 5 * 1024 * 1024; 
  if (selectedFile.size > maxFileSizeInBytes) {
    alert('File size exceeds the maximum allowed (5MB).');
    event.preventDefault();
    return;
  }

  uploadFileButton.disabled = true;
  uploadFileButton.innerHTML = '<i class="fa fa-hourglass-2 fa-spin"></i> Uploading';

});
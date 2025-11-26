function newCRMSubject()
{
	var value = window.prompt("Enter new crm subject: ");
	if(value == '') {alert('Enter CRM Subject');return;}
	if(value!=null)
	{

		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'subject=' + value;

			request.open("POST", expandURI('do.php?_action=save_crm_subjects'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);

			if(request.status == 200)
			{
				window.location.href='do.php?_action=edit_crm_subjects';
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}


}


function editCRMSubjectTitle(id)
{
	var previous_value = document.getElementById(id).value;
	var new_value = window.prompt("Existing title of CRM subject: \n" + previous_value + "\n\rEnter New title of CRM subject: ");
	if(new_value == '') {alert('Enter CRM Subject');return;}
	if(new_value!=null)
	{

		// Save elements by AJAX
		var request = ajaxBuildRequestObject();
		if(request != null)
		{
			var postData = 'subject=' + new_value + '&id='+id;

			request.open("POST", expandURI('do.php?_action=save_crm_subjects'), false); // (method, uri, synchronous)
			request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			request.setRequestHeader("x-ajax", "1"); // marker for server code
			request.send(postData);

			if(request.status == 200)
			{
				window.location.href='do.php?_action=edit_crm_subjects';
			}
			else
			{
				alert(request.responseText);
			}
		}
		else
		{
			alert("Could not create XMLHttpRequest object");
		}
	}


}


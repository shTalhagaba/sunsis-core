var fonts = Array(
    "Little_Days.ttf",
    "ArtySignature.ttf",
    "Signerica_Medium.ttf",
    "Champignon_Alt_Swash.ttf",
    "Bailey_MF.ttf",
    "Carolina.ttf",
    "DirtyDarren.ttf",
    "Ruf_In_Den_Wind.ttf"
);

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

function SignatureSelected(sig)
{
    $('.sigboxselected').attr('class','sigbox');
    sig.className = "sigboxselected";
}


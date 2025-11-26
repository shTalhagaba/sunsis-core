// Random phonetic password generator

var consonants = [
	'b',
	'c',
	'd',
	'f',
	'g',
	'h',
	'j',
	'k',
	'l',
	'm',
	'n',
	'p',
	'r',
	's',
	't',
	'v',
	'w',
	'y',
	'z'];

var vowels = [
	'a',
	'e',
	'i',
	'o',
	'u'];

function randomPassword(length)
{
	var pwd = '';
	var i = 1;

	while(pwd.length < length)
	{
		if(i++%2 == 0)
		{
			// Even numbers are vowels
			pwd += vowels[Math.floor(Math.random() * vowels.length)];
		}
		else
		{
			// Odd numbers are consonants
			pwd += consonants[Math.floor(Math.random() * consonants.length)];
		}
	}

	return pwd;
}


function dicewarePassword(words, min, max)
{
	var req = ajaxRequest('do.php?_action=ajax_diceware&words=' + words + '&min=' + min + '&max=' + max);

	if(req != null)
	{
		return req.responseText;
	}
	else
	{
		return null;
	}
}

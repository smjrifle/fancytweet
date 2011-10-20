/*
 * Javascript for form validation
 */

function $(id) { //shorthand for document.gelElementById
	return document.getElementById(id);
}

function checkButton() {//function to check if button should be enabled or disabled
        //check if all fields are valid
	if ($('valname').className == 'valid' && $('valemail').className == 'valid' && $('valmessage').className == 'valid') {
                //enable the submit button
		$('submitButton').disabled = false;
	}
	else {
                //disable the button if anything is invalid
		$('submitButton').disabled = true;
	}
}

function vaildateField(t, f) {//validate fields
        //for the e-mail field
	if (t.id == 'email') {
                //the regular expression for e-mail address
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/,
			address = t.value;
                //check if it matches
		if (reg.test(address) === false) {
                        //hide the OK image
			$('val' + t.id).innerHTML = "";
                        
			if (f) {
                                //display invalid message
				$('val' + t.id).innerHTML = "is not a valid email address!";
		                //set the invalid class name
				$('val' + t.id).className = "invalid";
			}
                        //check the button
			checkButton();
                        return false;
		} else {
                        //show the valid OK image
			$('val' + t.id).innerHTML = '<img src="images/ok.jpg">';
                        //and set the valid class name
			$('val' + t.id).className = "valid";
                        //check the button
			checkButton();
			return true;
		}
	}
	if (!t.value) { //checks if field is empty
		$('val' + t.id).innerHTML = "";
		if (f) {
                        //set the message for empty field
			$('val' + t.id).innerHTML = "needs to be filled!";
                        //set invalid class name
			$('val' + t.id).className = "invalid";
		}
                //check the button
		checkButton();
		return false;
	}
	else {
                //show the valid OK image
		$('val' + t.id).innerHTML = '<img src="images/ok.jpg">';
                //and set the valid class name
		$('val' + t.id).className = "valid";
                //check the button
		checkButton();
		return true;
	}
}

function sendMessage() {
        //write that message has been sent
	$('announce').innerHTML = '<blink><font color="green">Thank you very much!<br>Your message has been sent.</font></blink>';
}
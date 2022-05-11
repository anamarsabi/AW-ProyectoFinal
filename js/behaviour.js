


var loadFile = function(event) {
	var output = document.getElementById('output');

	Array.prototype.forEach.call(event.target.files, function(valor, indice, array) {
		var aux = URL.createObjectURL(valor);
		output.innerHTML += '<div class="tag"><img class="img-preview" src=' + aux + '></div>';
	});
};


// https://stackoverflow.com/questions/2507030/email-validation-using-jquery
function validateEmail($email) {
	var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
	return emailReg.test( $email );
}


$(document).ready(function() {
	$("#correo").change(function(){
		const campo = $("#correo");
		const err_msg = $("#email_err_msg");
		campo[0].setCustomValidity(""); // limpia validaciones previas

		var input = campo.val();
		campo.css("background-color", "");
		err_msg.text("");

		if(!validateEmail(input)){
			err_msg.text("Introduce un correo válido");
			err_msg.css("color", "red");
			campo.css("background-color", "#ff030338");
		}
	});


	$("#textarea-pisos").keydown(function(){
		const campo = $("#textarea-pisos");
		var $tam = campo.val().length;

		var contador = $("#wordcount");

		if($tam>=2024){
			campo.val(campo.val().substring(0, 1023));
			$tam=1024;
			contador.css("color","red");
			campo.css("border-color","red");
			campo[0].setCustomValidity("Has superado el máximo de caracteres permitidos");
		}
		else{
			contador.css("color","");
			campo.css("border-color","");
			campo[0].setCustomValidity("");
		}

		contador.text($tam+"/1024");
	});
});


function nextPrev(n) {
	// This function will figure out which tab to display
	var x = document.getElementsByClassName("tab");
	// Exit the function if any field in the current tab is invalid:
	if (n == 1 && !validateForm()) return false;
	// Hide the current tab:
	x[currentTab].style.display = "none";
	// Increase or decrease the current tab by 1:
	currentTab = currentTab + n;
	// if you have reached the end of the form...
	if (currentTab >= x.length) {
		// ... the form gets submitted:
		var form_id = "form_registro_usuario";
		document.getElementById(form_id).submit();
		return false;
	}
	// Otherwise, display the correct tab:
	showTab(currentTab);
}

function validateForm() {
	// This function deals with validation of the form fields
	var x, y, i, valid = true;
	x = document.getElementsByClassName("tab");
	y = x[currentTab].getElementsByTagName("input");

	// A loop that checks every input field in the current tab:
	for (i = 0; i < y.length; i++) {
		// If a field is empty...
		if (y[i].value == "" && y[i].required) {
			// add an "invalid" class to the field:
			y[i].className += " invalid";
			// and set the current valid status to false
			valid = false;
		}
	}
	msg = document.getElementsByClassName("err_msg")[0].style.display = "none";

	if(currentTab==1){
		if(y["pwd"].value!=y["cpwd"].value){
			valid=false;
			y["pwd"].className += " invalid";
			y["cpwd"].className += " invalid";

			msg = document.getElementsByClassName("err_msg")[0].style.display = "";
			valid = false;
		}
		if(!validateEmail(y["email"].value)){
			y["cpwd"].className += "invalid";
			valid = false;
		}
	}
	
	// If the valid status is true, mark the step as finished and valid:
	if (valid) {
		document.getElementsByClassName("step")[currentTab].className += " finish";
	}

	return valid; // return the valid status
}

function fixStepIndicator(n) {
	// This function removes the "active" class of all steps...
	var i, x = document.getElementsByClassName("step");
	
	for (i = 0; i < x.length; i++) {
		x[i].className = x[i].className.replace(" active", "");
	}
	//... and adds the "active" class on the current step:
	x[n].className += " active";
}

function showTab(n) {
	// This function will display the specified tab of the form...
	var x = document.getElementsByClassName("tab");
	x[n].style.display = "block";
	//... and fix the Previous/Next buttons:
	if (n == 0) {
		document.getElementById("prevBtn").style.display = "none";
	} else {
		document.getElementById("prevBtn").style.display = "inline";
	}
	if (n == (x.length - 1)) {
		document.getElementById("nextBtn").value = "Finalizar";
	} else {
		document.getElementById("nextBtn").value = "Siguiente";
	}
	//... and run a function that will display the correct step indicator:
	fixStepIndicator(n)
}


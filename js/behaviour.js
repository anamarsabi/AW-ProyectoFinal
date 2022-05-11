


var loadFile = function(event) {
	var output = document.getElementById('output');

	Array.prototype.forEach.call(event.target.files, function(valor, indice, array) {
		var aux = URL.createObjectURL(valor);
		output.innerHTML += '<div class="tag"><img class="img-preview" src=' + aux + '></div>';
	});
};


$(document).ready(function() {
	$("#textarea-pisos").keypress(function(){
		const campo = $("#textarea-pisos");
		var $tam = campo.val().length;

		var contador = $("#wordcount");
		contador.text(contador+"/2024");

		if(contador>=2024){
			campo.value = campo.value.substring(0, 2024);
			contador.css("color","red");
			campo.css("border-color","red");
			campo[0].setCustomValidity("Has superado el máximo de caracteres permitidos");
		}
		else{
			contador.css("color","");
			campo.css("border-color","");
			campo[0].setCustomValidity("");
		}
	});
});
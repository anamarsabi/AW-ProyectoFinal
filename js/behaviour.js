(function($) {
	$.sanitize = function(input) {
		var output = input.replace(/<script[^>]*?>.*?<\/script>/gi, '').
					 replace(/<[\/\!]*?[^<>]*?>/gi, '').
					 replace(/<style[^>]*?>.*?<\/style>/gi, '').
					 replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '').
           replace(/&nbsp;/g, '');
	    return output;
	};
})(jQuery);

function sanitize (input) {
	var output = input.replace(/<script[^>]*?>.*?<\/script>/gi, '').
					replace(/<[\/\!]*?[^<>]*?>/gi, '').
					replace(/<style[^>]*?>.*?<\/style>/gi, '').
					replace(/<![\s\S]*?--[ \t\n\r]*>/gi, '').
		replace(/&nbsp;/g, '');
	return output;
}



$(document).ready(function() {

	$("#registro-producto-name").change(function(){
		const campo = $("#registro-producto-name");
		var $input = campo.val();
		var $sanitized = $.sanitize($input);

		$(".check").remove();
		campo.css("background-color", "");
		if($input == $sanitized){
			campo[0].setCustomValidity("");
		}
		else{
			campo.css("background-color", "#ff030338");
			campo[0].setCustomValidity(
				"El nombre contiene caracteres no permitidos");
		}
	});

	$( function() {
		$( "#slider-range" ).slider({
		  range: true,
		  min: 0,
		  max: 500,
		  values: [ 75, 300 ],
		  slide: function( event, ui ) {
			$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		  }
		});
		$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
		  " - $" + $( "#slider-range" ).slider( "values", 1 ) );
	  } );


	$("#registro-producto-precio").change(function(){
		const campo = $("#registro-producto-precio");
		var $input = campo.val();

		$(".check").remove();
		campo.css("background-color", "");
		if($.isNumeric(input)){
			if($input<0){
				campo.css("background-color", "#ff030338");
				campo[0].setCustomValidity(
					"No permite números negativos");
			}
			else if ($input > 99999){
				campo.css("background-color", "#ff030338");
				campo[0].setCustomValidity(
					"Es demasiado caro");
			}
			else{
				campo[0].setCustomValidity("");
			}
		}
		else{
			campo.css("background-color", "#ff030338");
			campo[0].setCustomValidity(
				"introduce un valor numérico");

		}
		
	});


	$("#campoUser").change(function(){
		var url = "comprobarUsuario.php?user=" + $("#campoUser").val();
		$.get(url,usuarioExiste);
  	});




	//   https://codepen.io/MinzCode/pen/MWKgyqb

	$('#input-left').on('input', function() {
		var min_val =  $('#input-left').val();
		var max_val = $('#max_amount').val();
		if(min_val>max_val-1){
			min_val = parseInt(max_val)-5;
		}
		var div = $('#min_amount').val(min_val);

	});

	$('#input-right').on('input', function() {
		var max_val =  $('#input-right').val();
		var min_val = $('#min_amount').val();
		if(max_val-1<min_val){
			max_val = parseInt(min_val)+5;
		}
		var div = $('#max_amount').val(max_val);

	});



	var inputLeft = document.getElementById("input-left");
	var inputRight = document.getElementById("input-right");

	var thumbLeft = document.querySelector(".slider > .thumb.left");
	var thumbRight = document.querySelector(".slider > .thumb.right");
	var range = document.querySelector(".slider > .range");

	function setLeftValue() {
		var _this = inputLeft,
			min = parseInt(_this.min),
			max = parseInt(_this.max);

		_this.value = Math.min(parseInt(_this.value), parseInt(inputRight.value) - 5);

		var percent = ((_this.value - min) / (max - min)) * 100;

		thumbLeft.style.left = percent + "%";
		range.style.left = percent + "%";
	}
	setLeftValue();

	function setRightValue() {
		var _this = inputRight,
			min = parseInt(_this.min),
			max = parseInt(_this.max);

		_this.value = Math.max(parseInt(_this.value), parseInt(inputLeft.value) + 5);

		var percent = ((_this.value - min) / (max - min)) * 100;

		thumbRight.style.right = (100 - percent) + "%";
		range.style.right = (100 - percent) + "%";
	}
	setRightValue();

	inputLeft.addEventListener("input", setLeftValue);
	inputRight.addEventListener("input", setRightValue);

	inputLeft.addEventListener("mouseover", function() {
		thumbLeft.classList.add("hover");
	});
	inputLeft.addEventListener("mouseout", function() {
		thumbLeft.classList.remove("hover");
	});
	inputLeft.addEventListener("mousedown", function() {
		thumbLeft.classList.add("active");
	});
	inputLeft.addEventListener("mouseup", function() {
		thumbLeft.classList.remove("active");
	});

	inputRight.addEventListener("mouseover", function() {
		thumbRight.classList.add("hover");
	});
	inputRight.addEventListener("mouseout", function() {
		thumbRight.classList.remove("hover");
	});
	inputRight.addEventListener("mousedown", function() {
		thumbRight.classList.add("active");
	});
	inputRight.addEventListener("mouseup", function() {
		thumbRight.classList.remove("active");
	});


	var nClicks = 0;
	function acceptDelete(iden){
		var campo = document.getElementById(iden);
		nClicks++;
		if(nClicks<3){
			campo[0].setCustomValidity(nClicks + " clicks más para borrar este elemento");
		}
	}


})
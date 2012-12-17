$(document).ready(function() {


/*--| ANCHORS
------------------------------------------------------------------------------------------------------------------------ |--*/

$("a.anchorLink").anchorAnimate()




/*--| SLIDER
------------------------------------------------------------------------------------------------------------------------ |--*/

$(".servicios_bot-right figure").easySlider({
		auto: true,
		continuous: true 
	});



/*--| CYCLE
------------------------------------------------------------------------------------------------------------------------ |--*/

$('.slider').cycle({ 
	fx:   'fade', 
	speed: 2500 
	});


$('.ver-ubicacion').click(function(){
	$(".modal").removeClass('none');
	});

$('.close').click(function(){
	$(".modal").addClass('none');
	});

});
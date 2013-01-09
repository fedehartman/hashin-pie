$(document).ready(function() {



/*--| ANCHORS
----------------------------------------------------------------------------------------------------------------------- |--*/

$("body").queryLoader2({
	barColor: "#dbc16a",
	backgroundColor: "#2d2d2d",
	percentage: true,
	barHeight: 30,
	completeAnimation: "grow"
});



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




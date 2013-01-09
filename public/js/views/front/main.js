$(document).ready(function() {

	$('.apartamento').click(mostrarGaleriaApartamento);
	$('.bungalow').click(mostrarGaleriaBungalow);

	$(document).scroll(function()
	{

		/*switch($(document).scrollTop())
		{
			case 0: 
			$('.pelotita-active').removeClass('pelotita-active'); 
			$('.pelotitas .pelotita').eq(0).addClass('pelotita-active');
			break;

			case 980: 
			$('.pelotita-active').removeClass('pelotita-active'); 
			$('.pelotitas .pelotita').eq(1).addClass('pelotita-active');
			break;
		}*/

		if($(document).scrollTop() >= 0 && $(document).scrollTop() <= 979 )
		{
			activarNavegacion(0);
		}

		if($(document).scrollTop() >= 980 && $(document).scrollTop() <= 1959 )
		{
			activarNavegacion(1);
		}

		if($(document).scrollTop() >= 3077 && $(document).scrollTop() <= 4129 )
		{
			activarNavegacion(2);
		}

		if($(document).scrollTop() >= 5176  )
		{
			activarNavegacion(3);
		}
/*
		if($(document).scrollTop() >= 0 && $(document).scrollTop() <= 700 )
		{
			activarNavegacion(4);
		}

		*/
	});

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


function activarNavegacion(index)
{
	$('.active').removeClass('active');

	if(index > 0)
	{
		$('.main-menu ul li').eq(index-1).addClass('active');
	}


	$('.pelotita-active').removeClass('pelotita-active'); 
	$('.pelotitas .pelotita').eq(index).addClass('pelotita-active');	
}

function mostrarGaleriaApartamento(e)
{
	var personas = $(this).data('personas');
	$(this).parents('ul').find('.active').removeClass('active');

	$(this).parent('li').addClass('active');

	$('.galeria_apartamento').hide();

	$('.apartamento_'+personas).show();

	$('#personas').val(personas);
	return false;
}

function mostrarGaleriaBungalow(e)
{
	var personas = $(this).data('personas');
	$(this).parents('ul').find('.active').removeClass('active');
	$(this).parent('li').addClass('active');

	$('#personas').val(personas);

	return false;
}

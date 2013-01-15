$(document).ready(function() {

	$('.apartamento').click(mostrarGaleriaApartamento);
	$('.bungalow').click(mostrarGaleriaBungalow);

	$('.lista-apartamentos li :eq(0) a').trigger('click');
	$('.lista-bungalows li :eq(0) a').trigger('click');

	$(document).scroll(function()
	{



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

$('.motivo').click(function()
{
	var motivo = $(this).data('motivo');
	$('#motivo').val(motivo);
	return false;
});

$('#enviarContacto').click(function()
{
	var params = $("#formContacto").serialize();
	$.ajax({
		type: "post",
		url:  BASE_PATH+'/home/enviarContacto',
		dataType: "json",
		async: false,
		data: params,
		success: function(data) {
			if(!data.error)
			{
				$('.enviar').hide();
				$('.contacto-content-bot').append('<h4 style="color:white;">Gracias por comunicarte con nosotros.</h4>')
			}else
			{

			}
		},
		error: function(request,status,errorThrown) {
			
		}
	});
});

});


function activarNavegacion(index)
{
	$('.main-menu .active').removeClass('active');

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

	$('.galeria_bungalows').hide();

	$('.bungalows_'+personas).show();

	$('#personas').val(personas);

	return false;
}

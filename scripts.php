

<!--| SCRIPTS
======================================================================================================================== |-->

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js" type="text/javascript"></script>

	<script src="js/easing.js"></script>
	<script src="js/elastislide.js"></script>
	<script src="js/gallery.js"></script>
	<script src="js/jquery.tmpl.min.js"></script>
	<script src="js/plugins.js"></script>
	<script src="js/main.js"></script>




	<!--| JS TEMPLATE FOR THE GALLERY
	================================================================================ |-->

		<script id="img-wrapper-tmpl" type="text/x-jquery-tmpl">	
			<div class="rg-image-wrapper">
				{{if itemsCount > 1}}
				{{/if}}
				<div class="rg-image"></div>
				<div class="rg-loading"></div>
				<div class="rg-caption-wrapper">
				</div>
			</div>
		</script>



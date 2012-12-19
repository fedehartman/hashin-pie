
    var ratio = (thumb_height / thumb_width);

    function preview(img, selection) {
	var scaleX = thumb_width / selection.width;
	var scaleY = thumb_height / selection.height;

	$('#thumbnail + div > img').css({
            width: Math.round(scaleX * current_large_image_width) + 'px',
            height: Math.round(scaleY * current_large_image_height) + 'px',
            marginLeft: '-' + Math.round(scaleX * selection.x1) + 'px',
            marginTop: '-' + Math.round(scaleY * selection.y1) + 'px'
	});
	$('#x1').val(selection.x1);
	$('#y1').val(selection.y1);
	$('#x2').val(selection.x2);
	$('#y2').val(selection.y2);
	$('#w').val(selection.width);
        $('#h').val(selection.height);
    }

    $(document).ready(function () {
        $('#save_thumb').click(function() {
            var x1 = $('#x1').val();
            var y1 = $('#y1').val();
            var x2 = $('#x2').val();
            var y2 = $('#y2').val();
            var w = $('#w').val();
            var h = $('#h').val();
            if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
                alert("You must make a selection first");
                return false;
            }else{
                return true;
            }
        });
    });

    $(window).load(function () {
        $('#thumbnail').imgAreaSelect({ aspectRatio: '1:'+ratio, onSelectChange: preview });
        $('#thumb_cont').width(thumb_width);
        $('#thumb_cont').height(thumb_height);
        $('#thumb_cont').hide();
    });

    $(document).ready(function(){
        $("a[rel='open_colorbox']").colorbox({
                                                onOpen:function(){
                                                        var x1 = $('#x1').val();
                                                        $('#thumb_cont').show();
                                                        if(x1==""){
                                                            alert("You must make a selection first");
                                                            $.colorbox.close()
                                                            return false;
                                                        }
                                                },
                                                onCleanup:function(){
                                                        $('#thumb_cont').hide();
                                                },
                                                inline:true,
                                                href:"#thumb_cont"
                                             });
    });
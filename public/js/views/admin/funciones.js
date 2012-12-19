function roundNumber(num, dec) {
    var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
    return result;
}

	
function showMessage(msg){
    $('#msg_text').text(msg);
    $('.ui-state-highlight').slideDown(500);
    setTimeout(function(){
        $('.ui-state-highlight').fadeOut(1000)
    }, 3500); 
}

function urlencode(url){
    return encodeURIComponent(url);
}
	
	
this.imagePreview = function(){	
    /* CONFIG */
			
    xOffset = 10;
    yOffset = 30;
			
    // these 2 variable determine popup's distance from the cursor
    // you might want to adjust to get the right result
			
    /* END CONFIG */
    $("a.preview").hover(function(e){
        this.t = this.title;
        this.title = "";	
        var c = (this.t != "") ? "<br/>" + this.t : "";
        $("body").append("<p id='preview'><img src='"+ this.href +"' alt='Image preview' />"+ c +"</p>");								 
        $("#preview")
        .css("top",(e.pageY - xOffset) + "px")
        .css("left",(e.pageX + yOffset) + "px")
        .fadeIn("fast");						
    },
    function(){
        this.title = this.t;	
        $("#preview").remove();
    });	
    $("a.preview").mousemove(function(e){
        $("#preview")
        .css("top",(e.pageY - xOffset) + "px")
        .css("left",(e.pageX + yOffset) + "px");
    });			
};
	
        
function validateGeneric(formId)
{
    var status = true;
    $('#'+formId+' input.required').each(function(key, obj)
    {
        if($(obj).val() == '')
        {
            alert ($(obj).attr("name") + " is required!");
            status = false;
        }
    })

    return status;
}

function stripTables()
{
    $(".stripeMe tr").mouseover(function() {
        $(this).addClass("over");
    } ).mouseout(function() {
        $(this).removeClass("over");
    } );
    $(".stripeMe tr:even").removeClass("odd").addClass("even");
    $(".stripeMe tr:odd").removeClass("even").addClass("odd");
}

function pop_up(href,titulo,w,h,left,top){
    window.open(href,titulo,"menubar=0,toolbar=0,scrollbars=1,directories=0,resize=0,width="+w+",height="+h+",left="+left+",top="+top); 
}
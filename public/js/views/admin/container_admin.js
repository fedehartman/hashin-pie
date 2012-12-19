$(document).ready(function ()
{
    stripTables();
    initMenu();
});

// <![CDATA[
$(document).ready(function() {
  $.localScroll();
  $('.table :checkbox.toggle').each(function(i, toggle) {
    $(toggle).change(function(e) {
      $(toggle).parents('table:first').find(':checkbox:not(.toggle)').each(function(j, checkbox) {
        checkbox.checked = !checkbox.checked;
      })
    });
  });

});
// ]]>

function initMenu() 
{
    $('#menu ul').hide();
    $('#menu li a').click(
        function() 
        {
            $(this).next().slideToggle('normal');	
        }
    );
}
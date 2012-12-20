function delete_row(id)
{
	if(confirm('Are you sure you want to delete this row?'))
	{
		$.get(BASE_PATH+"/admin/promotion/delete/&id%5B%5D="+id,function(data) {
			if(data=='ok')
			{
				$('#'+id).remove();
				stripTables();
			}
			else
			{
				alert(data);
			}
		});
	}
}

function deleteSelected()
{
	if ($('input.checkbox_delete:checkbox:checked').size() == 0 )
	{
		alert ('No row selected');
		return false;
	}
		if(confirm('Are you sure you want to delete all selected rows?'))
		{
			var urlStr = '';
			$('input.checkbox_delete:checkbox:checked').each(function(index) {
				urlStr+= '&id%5B%5D='+$(this).val();
			});
			$.get(BASE_PATH+"/admin/promotion/delete/"+urlStr,function(data) {
				window.location.reload();
			});
		}
}

$(document).ready(function(){
});

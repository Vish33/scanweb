function aff_desaff(id)
	{
	if(document.getElementById('cache_cont_widget_'+id).style.display=='none')
		{
		document.getElementById('cache_cont_widget_'+id).style.display='block';
		}
	else
		{
		document.getElementById('cache_cont_widget_'+id).style.display='none';
		}
	}

function plie_item(k_it)
	{
	a_aff = document.getElementsByClassName('menu_p_'+k_it);
	for(var i = 0, length = a_aff.length; i < length; i++) 
		{
		a_aff[i].style.display = 'none';
		}
	clik = document.createAttribute("onclick");
	clik.nodeValue = "deplie_item("+k_it+");";
	document.getElementById('menu_p_'+k_it).setAttributeNode(clik);
	}	
	
function deplie_item(k_it)
	{
	a_aff = document.getElementsByClassName('menu_p_'+k_it);
	for(var i = 0, length = a_aff.length; i < length; i++) 
		{
		a_aff[i].style.display = 'table-row';
		}
	clik = document.createAttribute("onclick");
	clik.nodeValue = "plie_item("+k_it+");";
	document.getElementById('menu_p_'+k_it).setAttributeNode(clik);
	}
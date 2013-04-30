<?php
class affichage
{

function form_demo1($url ='')
	{
	$str = '<form method="post" action="demo_1.php">
	<table>
	<tr><td align="center"> Url à scanner</td></tr>
	<tr><td align="center"><input type="text" value="'.$url.'" name="url" style="width:95%;"/></td></tr> 
    <tr><td align="center"><input type="submit" value="scanner la page" name="scanner"/></td></tr> 		
	</table>
	</form>';	
	return $str;
	}
	
function form_demo2($url ='',$info = array(array('bal'=>'div','attribut'=>false)))
	{
	$str = '<form method="post" action="demo_2.php">
	<table>
	<tr><td align="center" colspan="3"> Url à scanner</td></tr>
	<tr><td align="center"  colspan="3"><input type="text" value="'.$url.'" name="url" style="width:95%;"/></td></tr> 
	<tr><td>Balise</td><td colspan="2"><input type="text" value="'.$info[0]['bal'].'" name="bal" style="width:30%;"/></td></tr>';
	if($info[0]['attribut'] !== false)
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="'.key($info[0]['attribut']).'" name="attribut" style="width:95%;"/></td><td><input type="text" value="'.$info[0]['attribut'][key($info[0]['attribut'])].'" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	else
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="" name="attribut" style="width:95%;"/> = </td><td><input type="text" value="" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	$str .= '
    <tr><td align="center" colspan="3"><input type="submit" value="scanner la page" name="scanner"/></td></tr> 		
	</table>
	</form>';	
	return $str;
	}
	
function form_demo3($url ='',$info = array(array('bal'=>'div','attribut'=>false)))
	{
	$str = '<form method="post" action="demo_3.php">
	<table>
	<tr><td align="center" colspan="3"> Url à scanner</td></tr>
	<tr><td align="center"  colspan="3"><input type="text" value="'.$url.'" name="url" style="width:95%;"/></td></tr> 
	<tr><td>Balise</td><td colspan="2"><input type="text" value="'.$info[0]['bal'].'" name="bal" style="width:30%;"/></td></tr>';
	if($info[0]['attribut'] !== false)
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="'.key($info[0]['attribut']).'" name="attribut" style="width:95%;"/></td><td><input type="text" value="'.$info[0]['attribut'][key($info[0]['attribut'])].'" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	else
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="" name="attribut" style="width:95%;"/> = </td><td><input type="text" value="" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	$str .= '
    <tr><td align="center" colspan="3"><input type="submit" value="scanner la page" name="scanner"/></td></tr> 		
	</table>
	</form>';	
	return $str;
	}

function form_demo4($url ='',$info = array(array('bal'=>'div','attribut'=>false)))
	{
	$str = '<form method="post" action="demo_4.php">
	<table>
	<tr><td align="center" colspan="3"> Url à scanner</td></tr>
	<tr><td align="center"  colspan="3"><input type="text" value="'.$url.'" name="url" style="width:95%;"/></td></tr> 
	<tr><td>Balise</td><td colspan="2"><input type="text" value="'.$info[0]['bal'].'" name="bal" style="width:30%;"/></td></tr>';
	if($info[0]['attribut'] !== false)
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="'.key($info[0]['attribut']).'" name="attribut" style="width:95%;"/></td><td><input type="text" value="'.$info[0]['attribut'][key($info[0]['attribut'])].'" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	else
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="" name="attribut" style="width:95%;"/> = </td><td><input type="text" value="" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	$str .= '
    <tr><td align="center" colspan="3"><input type="submit" value="scanner la page" name="scanner"/></td></tr> 		
	</table>
	</form>';	
	return $str;
	}	
	
function form_demo5($url ='',$info = array(array('bal'=>'div','attribut'=>false)))
	{
	$str = '<form method="post" action="demo_5.php">
	<table>
	<tr><td align="center" colspan="3"> Url à scanner</td></tr>
	<tr><td align="center"  colspan="3"><input type="text" value="'.$url.'" name="url" style="width:95%;"/></td></tr> 
	<tr><td>Balise</td><td colspan="2"><input type="text" value="'.$info[0]['bal'].'" name="bal" style="width:30%;"/></td></tr>';
	if($info[0]['attribut'] !== false)
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="'.key($info[0]['attribut']).'" name="attribut" style="width:95%;"/></td><td><input type="text" value="'.$info[0]['attribut'][key($info[0]['attribut'])].'" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	else
		{
		$str .= '<tr><td>Attribut</td><td><input type="text" value="" name="attribut" style="width:95%;"/> = </td><td><input type="text" value="" name="val_attribut" style="width:95%;"/></td></tr>'; 
		}
	$str .= '
    <tr><td align="center" colspan="3"><input type="submit" value="scanner la page" name="scanner"/></td></tr> 		
	</table>
	</form>';	
	return $str;
	}	
	
function fonction_1($tab)
	{
	echo '<pre>';
	print_r($tab);
	echo '</pre>';
	}	

function aff_list_select_comb($param,$tab,$sel=false)
	{
	$pro_selext = '';
	if(!empty($param))
		{
		foreach($param as $k=> $v)
			{
			$pro_selext.=$k.'="'.$v.'" ';
			}
		}
	$str= '<select '.$pro_selext.'>';
	foreach($tab as $k => $v)
		{
		if($sel !== false && $v == $sel)
			{
			$pref = 'selected="selected"';
			}
		else
			{
			$pref ='';
			}
		$str.='<option value="'.$k.'" '.$pref.'>'.$v.'</option>';		
		}
	$str.='</select>';
	return $str;
	}	
	
function fonction_137($text) // cette fonction permet de tester si une donnée est en UTF8 et de la convertir en utf8 si elle ne l'est pas
	{
if( ! mb_check_encoding($text,'UTF-8'))
     {
     $text = htmlentities($text,ENT_COMPAT,'ISO-8859-15');
     }
$text = html_entity_decode($text,ENT_COMPAT,'UTF-8');
return $text;
}	
	
function formate_string($string)
	{
	return  preg_split('/(?:\s+)|(http:\/\/[^\s]+|[^\s\-]+\'|,|\"|\(|\)|\-|«|»|\]|\[)/u',$string,-1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
	}
	
function aff_list_select_a($nom,$val,$cle,$sel=false)
	{
	$str= '<select name="'.$nom.'">';
	$cb = 0;
	foreach($val as $k => $v)
		{
		if($sel !== false && $v == $sel)
			{
			$pref = 'selected="selected"';
			}
		else
			{
			$pref ='';
			}
		if($cle === false)
			{
			$str.='<option value="'.$cb.'" '.$pref.'>'.$v.'</option>';
			$cb++;
			}
		else
			{
			$str.='<option value="'.$cle[$k].'" '.$pref.'>'.$v.'</option>';
			}
		}
	$str.='</select>';
	return $str;
	}	
	
function mise_en_forme_widget($titre,$cont,$id=1,$style=false) // fonction genérant widget
	{
	$str='<div class="widget">
<div class="widget-header">
<h3>'.$titre.'</h3>
<span style="float:right;right:10px;top:2px;position:relative;"><input type="image" src="images/plus.png" onclick="aff_desaff('.$id.');"/></span>
</div>';
	if($style=== false)
		{
		$str.='<div class="widget-content" id="cache_cont_widget_'.$id.'">';
		}
	else
		{
		$str.='<div class="widget-content" id="cache_cont_widget_'.$id.'" style="display:none">';
		}
	$str.=$cont.'</div>
</div>';
	return $str;
	}
	
function mise_en_forme_widget_cache($titre,$cont,$id=1,$style=false) // fonction affichant un widget cachable
	{
	$str='
	<div class="widget" id="cache_cont_supr_'.$id.'">
	<div class="widget-header">
	<h3>'.$titre.'</h3>
	<span style="float:right;right:10px;top:5px;position:relative;"><input type="image" src="images/suppr.png" onclick="cache_decache('.$id.');"/></span>
	</div>';
	$str.='<div class="widget-content">';
	$str.=$cont.'
		</div>
		</div>';
	return $str;
	}
}
?>
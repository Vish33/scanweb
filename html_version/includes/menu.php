<?php
$menu = array();
$menu['Accueil'] = 'index.php';
$menu['Démos'] = array('Démo 1'=>'demo_1.php','Démo 2'=>'demo_2.php','Démo 3'=>'demo_3.php','Démo 4'=>'demo_4.php','Démo 5'=>'demo_5.php');
echo'
<div id="contenu">
<div id="menu">
<table class="table_menu">';
$cb = 0;
foreach($menu as $k=> $v)
	{
	if(is_array($v))
		{
		echo '<tr id="menu_p_'.$cb.'" onclick="deplie_item('.$cb.');" style="cursor:pointer"><td>'.$k.'</td></tr>';
		foreach($v as $k2 => $v2)
			{
			echo'<tr class="menu_p_'.$cb.'" style="display:none;"><td><a href="'.$v2.'">+ '.$k2.'</a></td></tr>';
			}
		$cb++;
		}
	else
		{
		echo '<tr><td><a href="'.$v.'" class="a_menu">'.$k.'</a></td></tr>';
		}
	}
echo'
</table>
</div>
<div id="contenu-op">';
?>
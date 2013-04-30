<?php
include('includes/haut.php');
include('includes/menu.php');

include_once('includes/class/affichage.php');

$desc ='<p>Cet exemple permet de convertir une page web en tableau plat de type "flatweb"</p>';

$a = new affichage();
if(isset($_POST['scanner']))
	{
	echo $a->mise_en_forme_widget('Aide Démo 1',$desc,1,true);
	echo $a->mise_en_forme_widget('Afficher le tableau plat d\'une page web',$a-> form_demo1($_POST['url']),2);
	if(!empty($_POST['url']))
		{
		include_once('lib/scanweb.php');
		$b = new scanweb();
		$tab = $b -> page2tab($_POST['url']);
		$res = print_r($tab,true); 
		echo $a->mise_en_forme_widget('Tableau plat de '.$_POST['url'],'<div style="width:100%;height:200px;overflow:scroll;"><pre>'.$res.'</pre></div>',3);
		}
	}
else
	{
	echo $a->mise_en_forme_widget('Aide Démo 1',$desc);
	echo $a->mise_en_forme_widget('Afficher le tableau plat d\'une page web',$a-> form_demo1(),2);
	}
include('includes/bas.php');
?>
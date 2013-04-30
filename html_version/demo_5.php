<?php
include('includes/haut.php');
include('includes/menu.php');

include_once('includes/class/affichage.php');
$desc ='<p>Cet exemple permet d\'extraire le texte d\'une parti ou d\'une page web.</p>
<p>Dans cet exemple, le programme renvoie le texte contenu dans la zone de recherche de votre choix</p>';

$a = new affichage();
if(isset($_POST['scanner']))
	{
	echo $a->mise_en_forme_widget('Aide Démo 5',$desc,1,true);
	echo $a->mise_en_forme_widget('Extraire texte d\'une partie ou d\'une page web',$a-> form_demo5($_POST['url'],array(array('bal'=>$_POST['bal'],'attribut'=>array($_POST['attribut']=>$_POST['val_attribut'])))),2);
	if(!empty($_POST['url']))
		{
		include_once('lib/scanweb.php');
		$b = new scanweb();
		$tab = $b -> page2tab($_POST['url']);
		$a_match = array();
		if(!empty($_POST['bal']))
			{
			$a_match[0]['bal'] =$_POST['bal'];
			$a_match[0]['attributs'] = false;
			if(!empty($_POST['attribut']) && !empty($_POST['val_attribut']))
				{
				$a_match[0]['attributs'][$_POST['attribut']] = $_POST['val_attribut'];
				}
			}
		$match = $b-> rech_elem($tab,$a_match);
		$res = '<p>Aucun élément ne correspond à vos critères</p>';
		$cb_t = 0;
		if(!empty($match))
			{
			$res = '<table>';
			foreach($match[0] as $k=> $v)
				{
				$recup = $b->rec_text($v);
				$res .='<tr><td align="center">Capture balise : '.$_POST['bal'].' - '.$k.'</td></tr>
				<tr><td>'.$recup.'</td></tr>';
				
				}
			$res .= '</table>';
			$cb_t = sizeof(	$match[0]);		
			}
		echo $a->mise_en_forme_widget('('.$cb_t.') résultat(s) sur '.$_POST['url'],'<div style="width:100%;height:200px;overflow:scroll;">'.$res.'</div>',3);
		}
	}
else
	{
	echo $a->mise_en_forme_widget('Aide Démo 5',$desc);
	echo $a->mise_en_forme_widget('Extraire texte d\'une partie ou d\'une page web',$a-> form_demo5(),2);
	}
include('includes/bas.php');
?>
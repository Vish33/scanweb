<?php
include('includes/haut.php');
include('includes/menu.php');

include_once('includes/class/affichage.php');
$desc ='<p>Cet exemple permet d\'extraire les éléments d\'une page web tout comme dans la démo 2.</p>
<p>Dans cet exemple, le programme renvoie les portion capturée au format "flatweb" puis les transforme en contenu HTML</p>';

$a = new affichage();
if(isset($_POST['scanner']))
	{
	echo $a->mise_en_forme_widget('Aide Démo 3',$desc,1,true);
	echo $a->mise_en_forme_widget('Extraire partie d\'une page web et afficher son rendu HTML :',$a-> form_demo3($_POST['url'],array(array('bal'=>$_POST['bal'],'attribut'=>array($_POST['attribut']=>$_POST['val_attribut'])))),2);
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
				$res .='<tr><td align="center">Capture '.$k.'</td></tr>
				<tr><td>'.$b->reconstruit_page_pgrave($v).'</td></tr>';
				
				}
			$res .= '</table>';
			//$res = print_r($match,true);
			$cb_t = sizeof(	$match[0]);		
			}
		echo $a->mise_en_forme_widget('('.$cb_t.') résultat(s) sur '.$_POST['url'],'<div style="width:100%;height:200px;overflow:scroll;">'.$res.'</div>',3);
		}
	}
else
	{
	echo $a->mise_en_forme_widget('Aide Démo 3',$desc);
	echo $a->mise_en_forme_widget('Extraire partie d\'une page web et afficher son rendu HTML :',$a-> form_demo3(),2);
	}
include('includes/bas.php');
?>
<?php
include('includes/haut.php');
include('includes/menu.php');

include_once('includes/class/affichage.php');
$desc ='<p>Cet exemple permet d\'extraire les attributs des balises de la page.</p>
<p>Dans cet exemple, le programme renvoie la liste des attributs des balises corespondant à votre recherche sous forme d\'un array</p>';

$a = new affichage();
if(isset($_POST['scanner']))
	{
	echo $a->mise_en_forme_widget('Aide Démo 4',$desc,1,true);
	echo $a->mise_en_forme_widget('Extraire les attributs de balise d\'une page web',$a-> form_demo4($_POST['url'],array(array('bal'=>$_POST['bal'],'attribut'=>array($_POST['attribut']=>$_POST['val_attribut'])))),2);
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
				$recup = $b->rec_attribut($v,preg_replace('/b\|0$/','',key($v)));
				$resu = print_r($recup,true);
				$res .='<tr><td align="center">Capture balise : '.$_POST['bal'].' - '.$k.'</td></tr>
				<tr><td>'.$resu.'</td></tr>';
				
				}
			$res .= '</table>';
			$cb_t = sizeof(	$match[0]);		
			}
		echo $a->mise_en_forme_widget('('.$cb_t.') résultat(s) sur '.$_POST['url'],'<div style="width:100%;height:200px;overflow:scroll;">'.$res.'</div>',3);
		}
	}
else
	{
	echo $a->mise_en_forme_widget('Aide Démo 4',$desc);
	echo $a->mise_en_forme_widget('Extraire les attributs de balise d\'une page web',$a-> form_demo4(),2);
	}
include('includes/bas.php');
?>
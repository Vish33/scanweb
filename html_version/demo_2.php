<?php
include('includes/haut.php');
include('includes/menu.php');

include_once('includes/class/affichage.php');
$desc ='<p>Cet exemple permet d\'extraire les éléments d\'une page web</p>
<p>De façon générale, par type de balise (div,a,meta,...)</p>
<p>Ou plus spécifiquement en précisant un attribut et sa valeur : </p>
<p>Une balise de type "div" avec pour attribut "id" et valeur de cet attribut "toto"</p>
<p>Dans cet exemple, le programme renvoie les portion capturée au format "flatweb" </p>';

$a = new affichage();
if(isset($_POST['scanner']))
	{
	echo $a->mise_en_forme_widget('Aide Démo 2',$desc,1,true);
	echo $a->mise_en_forme_widget('Extraire partie d\'une page web et afficher son "flatweb" :',$a-> form_demo2($_POST['url'],array(array('bal'=>$_POST['bal'],'attribut'=>array($_POST['attribut']=>$_POST['val_attribut'])))),2);
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
			$res = print_r($match,true);
			$cb_t = sizeof(	$match[0]);		
			}
		echo $a->mise_en_forme_widget('('.$cb_t.') résultat(s) sur '.$_POST['url'],'<div style="width:100%;height:200px;overflow:scroll;"><pre>'.$res.'</pre></div>',3);
		}
	}
else
	{
	
	echo $a->mise_en_forme_widget('Aide Démo 2',$desc);
	echo $a->mise_en_forme_widget('Extraire partie d\'une page web et afficher son "flatweb" :',$a-> form_demo2(),2);
	}
include('includes/bas.php');
?>
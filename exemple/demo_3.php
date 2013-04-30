<?php
/** 
* Démo 3
* Extraire portion de la page :
* Cet exemple permet de découper les éléments du tableau suivant le type de balise et leurs attributs
* Fonctions clés :
* - page2tab()
* - rech_elem()
*/
 
include_once('src/scanweb.php'); // on charge la class
$scan = new scanweb(); // on l'appele 

$url = 'http://www.web2data.com/'; // url de la page à scanner 
/*
définition des paramètres de recherche :
Un motif se compose d'un type de balise ('bal'=>'balise_que_je_veux')
et d'une liste d'attributs ('attributs => array('nom_de_lattribut_1' => 'valeur_de_lattribut_1' , 'nom_attribut_x' => 'valeur_de_lattribut_x')
ici, on cherche toutes les balises div ayant pour attributs :
 - "id" avec pour valeur "cache_cont_widget_1" 
 - "class" avec pour valeur "widget-content"
 */
$motif_0 = array('bal'=>'div','attributs'=>array('id'=>'cache_cont_widget_1','class'=>'widget-content')); 
// on rajoute notre motif dans l'array de recherche (on peut mettre autant de motif que l'on veut) 
$a_match = array($motif_0); 
$flatweb =  $scan -> page2tab($url); // conversion de la page html en tableau flatweb
$match = $scan-> rech_elem($flatweb,$a_match); // recherche de l'array de motif dans flatweb 
$scan -> fonction_1($match); // affichage du tableau (printr)

?>
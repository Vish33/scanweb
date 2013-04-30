<?php
/** 
* Démo 2
* Extraire portion de la page :
* Cet exemple permet de découper les éléments du tableau suivant le type de balise
* Fonctions clés :
* - page2tab()
* - rech_elem()
*/
 
include_once('src/scanweb.php'); // on charge la class
$scan = new scanweb(); // on l'appele 

$url = 'http://www.web2data.com/'; // url de la page à scanner 
//définition des paramètres de recherche :
// un motif se compose d'un type de balise ('bal'=>'balise_que_je_veux')
// et d'une liste ou non d'attributs 
// ici, on cherche toutes les balises a sans préciser d'attribut. 
$motif_0 = array('bal'=>'a','attributs'=>false); 
// on rajoute notre motif dans l'array de recherche (on peut mettre autant de motifs que l'on veut) 
$a_match = array($motif_0); 
$flatweb =  $scan -> page2tab($url); // conversion de la page html en tableau flatweb
$match = $scan-> rech_elem($flatweb,$a_match); // recherche de l'array de motif dans flatweb 
$scan -> fonction_1($match); // affichage du tableau (printr)

?>
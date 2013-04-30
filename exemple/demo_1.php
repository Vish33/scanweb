<?php
/** 
* Démo 1
* Afficher flatweb :
* Cet exemple permet de se familiariser avec le format flatweb 
* Fonctions clés :
* - page2tab()
*/
 
include_once('src/scanweb.php'); // on charge la class
$scan = new scanweb(); // on l'appele 

$url = 'http://www.web2data.com/'; // url de la page à scanner 
$flatweb =  $scan -> page2tab($url); // conversion de la page html en tableau flatweb
$scan -> fonction_1($flatweb); // affichage du tableau (printr)

?>
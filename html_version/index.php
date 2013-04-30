<?php
include_once('includes/class/affichage.php');
include('includes/haut.php');
include('includes/menu.php');

$desc = '<p> ScanWeb est une class php dédiée aux applications de text-mining.</p>
<p>Cette class permet de convertir une page web en array unidimentionnel tout en conservant la structure en arborescence des pages HTML.</p>
<p> Grâce à ce tableau, au format "flatweb", de nombreuses opérations de recherche ou d\'exctraction sont réalisables</p>
<h3 style="text-align:center;font-weight:bold;" >Se familiariser avec Scanweb</h3>
<ul style="margin:15px;">
<li><a href="demo_1.php">Comprendre le format "flatweb"</a> : (démo 1)</li>
<li><a href="demo_2.php">Isoler des éléments dans le tableau et les afficher au format "flatweb"</a> : (démo 2)</li>
<li><a href="demo_3.php">Isoler des éléments dans le tableau et les afficher au format HTML</a> : (démo 3)</li>
<li><a href="demo_4.php">Isoler des éléments dans le tableau et les afficher la liste des attributs</a> : (démo 4)</li>
<li><a href="demo_5.php">Isoler des éléments dans le tableau et extraire le texte</a> : (démo 5)</li>
</ul>
';
$a = new affichage();
echo $a->mise_en_forme_widget('Scanweb',$desc);
include('includes/bas.php');
?>
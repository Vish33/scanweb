<?php

/**
 * Cette classe permet d'extraire le contenu d'une page web ou d'une partie de page
 *
 * @class scanweb
 * @version 1.0
 * @author Guillaume Ripoche <guillaume.ripoche@hotmail.fr>
 * @license GNU/GPL
 */

class scanweb
{

/* Fonctions de scan */


/**
* Récupère le code source d'une page web avec curl
* Retourne une chaine vide si une erreur survient.
* @param string $url Url de la page à récupérer
* @return string
*/
 
public function rec_p_curl($url)	
	{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
	curl_setopt($ch,CURLOPT_COOKIEJAR,realpath('cookie.txt'));
	curl_setopt($ch,CURLOPT_COOKIEFILE,"cookie.txt");
	curl_setopt($ch,CURLOPT_USERAGENT,"Firefox (WindowsXP) – Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.8.1.6) Gecko/20070725 Firefox/2.0.0.6");
	curl_setopt($ch,CURLOPT_REFERER,"http://www.google.fr");
	curl_setopt($ch,CURLOPT_FOLLOWLOCATION,TRUE);
	curl_setopt($ch,CURLOPT_COOKIESESSION,TRUE);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
	$recup = curl_exec($ch);
	curl_close($ch);
	if($recup === FALSE)
		{
		return '';
		}
	else 
		{
		return $recup;
		}
	}

/**
* Découpe le code source d'une page web suivant les marqueurs de balises
* @param string $string Code source de la page web
* @return array contenant la page web découpé suivant les marqueurs de balise 
*/

public function eclate_bal($string)
    {
    $tab = preg_split('/(<(?!!--|!\[)[^<]+>|-->|<!--)/Usi',$string, - 1,PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
    return $tab;
    }
	
/**
* Tansformer le tableau de balise en tableau unidimensionnel ordonné de la page web
* @param array $tab array de balise de la page web scanné revoyé par (eclate_bal)
* @param array $balise liste des balises html (include l_balise_html)
* @param bool $sec si (true) permet de protéger les scripts et les commentaires en les passant avec htmlspecialchars 
* @return array (tableau plat de la page web)
*/

public function flatweb($tab,$balise,$sec = true)
    {
	$stock_cle=array(); // notre stock de clés (utile pour affecter les bonnes clés au tableau et ainsi conserver l'ordre d'affichage des éléments de la page web
	$ouvert = array(); // notre stock de balise ouverte (et donc à fermer)
    $nvtab = array(); // tableau  renvoyé par la fonction (flatweb)
    $c2 = 0; // permet de compter les différents éléments de la page web 
	$t = sizeof($tab); // le nombre d'éléments dans notre tableau (balise/code) 
	$prefix = '0'; // le préfixe (permet de se repérer dans le tableau)
	$oul = 0; // profondeur de descente dans tab (simule l'aspect multidimensionnel de la page web)
	$inconnues = array(); // liste de balises trouvées dans la page mais inconnues du programme
	for($a = 0;$a < $t ;$a ++ )
        {
		$pb = 0;
		if(preg_match('/^<([a-z0-9]+)\s|^<([a-z0-9]+)\/*>/Usi',$tab[$a])) // si on a une balise 
		   {
			if(!preg_match('/>$/',$tab[$a])) // si elle ne se tremine pas 
				{
				$pb =1;
				$cor = 0; // nous sert à boucler dans le tab
				$temp = ' '; // notre stock d'attribut de la balise
				// on boucle notre tableau jusqu'à ce que l'on trouve la fermeture 
				do
					{
					$ok = 0;				
					$cor++; // on incrémente $cor pour tester les éléments suivants du tableau
					if(preg_match('/>/',$tab[$a+$cor]) && !preg_match('/^</',$tab[$a+$cor])) // si on a une fermeture et qu'on n'est pas dans une autre balise
						{
						$temp.=$tab[$a+$cor].' '; // on rajoute 
						$ok = 1; // on dit qu'on l'a trouvé 
						}
					else
						{
						$temp.=$tab[$a+$cor].' '; // on rajoute 
						}
					}while($ok ==0 && $a+$cor <$t); // si on trouve la fermeture ou qu'on n'a plus d'éléments à analyser , on arrête de boucler 
				}
            preg_match_all('/^<([a-z0-9]+)\s|^<([a-z0-9]+)\/*>/Usi',$tab[$a],$rec); // on extrait le nom de la balise
            if( ! empty($rec[2][0])) 
                {
                $a_tester = strtolower($rec[2][0]);
                }
            else 
                {
                $a_tester = strtolower($rec[1][0]);
                }
			// $a_tester contient le nom de la balise en minuscule
			if(in_array($a_tester,$balise['ouverte'])) // si on est dans une balise ouvrante
				{				
				$c2 ++ ;
				if($pb == 1 )
					{
					$reste = explode('>',$temp);
					$attribut = $this -> info_balise($tab[$a].htmlspecialchars($temp,ENT_NOQUOTES).'>'); // récupère la liste des attributs de la balise
					$a+= $cor; // on réajutse $a avec le décalage 
					$pb = 0;
					}
				else
					{
					$attribut = $this -> info_balise($tab[$a]); // récupère la liste des attributs de la balise
					}
				if(!isset($stock_cle[$oul])) // si il n'existe pas de clé pour ce niveau de profondeur
					{
					$stock_cle[$oul] = 0; // on créé ce niveau de profondeur et on lui affecte la valeur 0
					}
				else // sinon
					{
					$stock_cle[$oul]++; // on incrémente ce niveau de profondeur de 1  
					}
				$prefix = implode('|c|',$stock_cle);
				$nvtab[$prefix.'|b|0'] = $a_tester; // (b|0) nom de la balise 
				$nvtab[$prefix.'|b|1'] = htmlspecialchars($tab[$a]); // (b|1) < balise + attributs > 
				$nvtab[$prefix.'|b|2'] = $c2; // (b|2) numéro de l'élément 				
				foreach($attribut as $k => $v) // on rajoute au tableau les attributs (b|3)|*
					{
					$nvtab[$prefix.'|b|3|'.$k] = $v; // (b|3)|nom de l'attribut = valeur de l'attribut
					}
				// maintenant que l'on a isolé le nom de la balise et ces éventuels attibuts on se prépare à analiser le contenu 
				if($a_tester == 'script') // si c'est une balise "script"
					{
					// les balises script sont traitées séparement car bien qu'étant une balise ouvrante, cette balise ne contient pas d'autre balise
					// on capture le contnu de la balise 
					$temp = '';
					do 
						{
						$ok = 0;
						$a++;
						if(preg_match('/^<\/script\s|^<\/script>/Usi',$tab[$a]))
							{
							$ok = 1;
							}
						else
							{
							$temp.=$tab[$a];
							}
						}while($ok ==0 && $a <$t);
					if($sec === true) // si on veut protéger le contenu 
						{
						$nvtab[$prefix.'|c|0'] = htmlspecialchars($temp); // on rajoute le contenu du script dans le tableau
						}
					else // si on veut le garder tel quel 
						{
						$nvtab[$prefix.'|c|0'] = $temp; // on rajoute le contenu du script dans le tableau
						}
					}
				else // pour toutes les autres balises ouvrante, 
					{
					$ouvert[] = $a_tester; // on rajoute dans notre stock de balise ouverte notre balise
					$oul++; // on incrément $oul (position)
					$stock_cle[$oul] = -1; 
					}
				}
			elseif(in_array($a_tester,$balise['ferme'])) // si il s'agit d'une balise fermée
				{
				$c2 ++ ; 
				// on ajouste $stock_cle
				if($oul == 0)
					{
					if(!isset($stock_cle[0]))
						{
						$stock_cle[0] = 0;
						}
					else
						{
						$stock_cle[$oul]++;
						}
					}
				else
					{
					$stock_cle[$oul]++;
					}
					
				if($pb == 1) // si on a réajusté 
					{
					$reste = explode('>',$temp);
					$attribut = $this -> info_balise($tab[$a].htmlspecialchars($temp,ENT_NOQUOTES).'>'); // récupère la liste des attributs de la balise
					}
				else
					{
					$attribut = $this -> info_balise($tab[$a]); // récupère la liste des attributs de la balise
					}
				$prefix = implode('|c|',$stock_cle);
				$nvtab[$prefix.'|b|0'] = $a_tester;
				$nvtab[$prefix.'|b|1'] = htmlspecialchars($tab[$a]);
				$nvtab[$prefix.'|b|2'] = $c2;
				foreach($attribut as $k => $v)
					{
					$nvtab[$prefix.'|b|3|'.$k] = $v;
					}
				}
			else // si c'est une balise inconnue
				{
				$inconnues[] = $a_tester;
				// on ajoute cette balise dans la liste des balises inconnues 
				// Pour incorporer cette nouvelle balise au programme, ouvir l_balise_html.php et la rajouté dans le bon tableau (ferme) => balise fermée || (ouverte) => balise ouvrante 
				}
			}
		elseif(preg_match('/^<!--/Usi',$tab[$a])) // si on a un commentaire
			{
			$c2 ++ ;
			if($oul == 0)
				{
				if(!isset($stock_cle[0]))
					{
					$stock_cle[0] = 0;
					}
				else
					{
					$stock_cle[$oul]++;
					}
				}
			else
				{
				$stock_cle[$oul]++;
				}
			$prefix = implode('|c|',$stock_cle);
			$nvtab[$prefix.'|b|0'] = 'commentaire'; 
			$nvtab[$prefix.'|b|1'] = htmlspecialchars($tab[$a]);
			$nvtab[$prefix.'|b|2'] = $c2;	
			$temp = '';
			$z = 0;
			do 
				{
				$ok = 0;
				$a++;
				if(preg_match('/-->$/Usi',$tab[$a])) // si on tombe sur une fin de commentaire
					{
					$z--;
					if($z < 0)
						{
						$ok = 1;
						}
					}
				else
					{
					$temp.=$tab[$a]; // sinon, on continue de capturer le contenu du commentaire
					}
				}while($ok == 0 && $a <$t);
			if($sec === true) // si on veut protéger le contenu 
				{
				$nvtab[$prefix.'|c|0'] = htmlspecialchars($temp); // on rajoute le contenu du commentaire dans le tableau
				}
			else
				{
				$nvtab[$prefix.'|c|0'] = $temp; // on rajoute le contenu du commentaire dans le tableau
				}
			}
		elseif(preg_match('/^<\//Usi',$tab[$a])) // si on a une fermeture de balise
			{
			// si notre stock de balises en attente de fermeture n'est pas vide et que le dernier élément à fermer et cette balise
			if(!empty($ouvert) && preg_match('/<\/'.$ouvert[sizeof($ouvert)-1].'/i',$tab[$a]))
				{
				array_pop($ouvert); // on réajuste le stock 
				unset($stock_cle[$oul]); // on réajuste la profondeur
				$oul--; // on désincrémente $oul 
				}
			}
		else // sinon, c'est du texte
			{
			if( ! preg_match('/^\n+$|^\s+$|^</',$tab[$a])) //  si ce n'est pas une retour à la ligne ou un espace seul
				{
				// on capture le texte
				$c2 ++ ;
				if(!isset($stock_cle[$oul]))
					{
					$stock_cle[$oul] = 0;
					}
				else
					{
					$stock_cle[$oul]++;
					}
				$prefix = implode('|c|',$stock_cle);
				$nvtab[$prefix.'|b|0'] = 'tlibre';
				$nvtab[$prefix.'|b|1'] = 'tlibre';
				$nvtab[$prefix.'|b|2'] = $c2;
				$nvtab[$prefix.'|c|0'] =$tab[$a];
				}
			}
		if($pb == 1) // si on a besoin d'un réajustement 
			{
			$a+= $cor;
			$c2 ++ ;
			if(!isset($stock_cle[$oul]))
				{
				$stock_cle[$oul] = 0;
				}
			else
				{
				$stock_cle[$oul]++;
				}
			$prefix = implode('|c|',$stock_cle);
			$nvtab[$prefix.'|b|0'] = 'tlibre';
			$nvtab[$prefix.'|b|1'] = 'tlibre';
			$nvtab[$prefix.'|b|2'] = $c2;
			$nvtab[$prefix.'|c|0'] =$reste[sizeof($reste)-1];
			}
		}
	return $nvtab;
	}	 

/**
* renvoie les attributs d'une balise
* Si aucun attribut n'est detecté, la fonction renvoi array(0 => 'pas d\'attibuts pour cette balise !')
* @param string $balise string contenant le nom de la balise + ses attributs
* @return array array sous la forme (attribut => valeur)  	
*/	 
public function info_balise($balise)
    {
    $tab = array();
    preg_match_all('/<([^<]+?)\s+?([^<]+?)>/is',$balise,$preg0);
    if( ! empty($preg0[2][0])) // si on a des attributs
		{
		preg_match_all('/([^<>]+?)=\s*(\'(.*?)\'|\"(.*?)\"|[^\'\"\s]+\s?)/',$preg0[2][0],$preg); // on éclate le string pour avoir en $preg[1] le nom des attributs en $preg[2] la valeur de ces attributs
		for($a = 0;$a < sizeof($preg[0]);$a ++ ) // pour ts les attributs trouvés 
			{
			$tab[strtolower($this -> vir_esp_gd($preg[1][$a]))] = $this -> vir_quote_autour($preg[2][$a]);
			}
		}
	else 
		{
		$tab[] = 'pas d\'attibuts pour cette balise !';
		}
	return $tab;
	}

/**
* Supprime les espaces avant et après le nom de l'attribut	
* @param string $string string à purifier (nom de l'attribut)
* @return string string "purifié"
*/

public function vir_esp_gd($string) 
	{
	$reg = '/^\s+|\s+$/';
	$string = preg_replace($reg,'',$string);
	return $string;
    }

/**
* Supprime les quotes entourant la valeur d'un attribut
* @param string $string string à purifier (valeur de l'attribut)
* @return string "purifié"	
*/	

public function vir_quote_autour($string)
    {
    $reg_1 = '/^\"(.+?)\"$/si'; // regex pour cas : "
    $reg_2 = '/^\'(.+?)\'$/si'; // regex pour cas : '
    if(preg_match($reg_1,$string))
		{
		preg_match_all($reg_1,$string,$tab);
		return $tab[1][0];
		}
	elseif(preg_match($reg_2,$string))
		{
		preg_match_all($reg_2,$string,$tab);
		return $tab[1][0];
		}
	else 
		{
		return $string;
		}
	}

/**
* Moteur de base du programme, il permet de récupérer la page web et de la convertir en tableau plat
* @param string $url la page à scanner
* @param bool $sec (si true commentaire et script traités avec htmlspecialchars
* @return array le tableau plat de la page web (flatweb) 	
*/	
public function page2tab($url,$sec = true)
	{
	$info = array();
	$info['reception de la page'] = 0;
	$info['scan des balises'] = 0;
	$info['conversion de la page en tableau'] = 0;
    $t0 = microtime(true);
	$a = $this -> rec_p_curl($url);
	$t1 = microtime(true);
	$info['reception de la page'] = round($t1 - $t0,4);
    $bal = include('l_balise_html.php');
    $tab = $this -> eclate_bal($a);
	$t2 = microtime(true);
	$info['scan des balises'] = round($t2 - $t1,4);
	$of_s = $this-> flatweb($tab,$bal,$sec);
	$t3 = microtime(true);
	$info['conversion de la page en tableau'] = round($t3 - $t2,4);
	$info['total'] = array_sum($info);
	// pour tester la rapidité, décommenter la ligne suivante. 
	//$this->fonction_1($info);
	return $of_s;
	}
	
/* Fin des fonctions de scan */	
	
/* fonctions de tarvail sur flatweb :*/

/**
* Cherche une ou plusieurs balise(s) html en précisant ou non les attributs  
* @param array $tab  le tableau plat de la page web (ou celui issu d'une précèdente extraction)
* @param array $a_match  array de motif de recherche 
* @return array contient les portions correspondant aux motifs
*/ 

public function rech_elem($tab,$a_match)
	{
	$capture = array(); // liste des éléments capturés
	if(!empty($tab))
		{
		$t = sizeof($a_match);
		foreach($tab as $k=> $v)
			{
			for($a =0;$a<$t;$a++)
				{
				if(preg_match('/b\|0$/',$k) && $v == $a_match[$a]['bal']) 
					{
					if($a_match[$a]['attributs'] != false) // si on demande une recherche précise
						{
						$p_attr = preg_replace('/0$/','3|',$k); // on prépare la clé
						$ok = 0;
						foreach($a_match[$a]['attributs'] as $k2 =>$v2 ) // pour tous nos attribut
							{
							if(isset($tab[$p_attr.$k2])) // si on a bien l'attribut
								{
								if($tab[$p_attr.$k2] != $v2) // et que sa valeur ne corespond pas
									{
									$ok = 1; // on dit que c'est pas bon
									break; // on arrête de boucler
									}
								}
							else // si on n' a pas l'attribut, on arrête de checrher
								{
								$ok = 1; // on dit que c'est pas bon
								break; // on arrête de boucler
								}
							}
						if($ok == 0) // si la balise correspond aux critères 
							{
							$capture[$a][] = $this -> decoup_cont_balise($tab,preg_replace('/b\|0$/','',$k)); // on recupere sont contenu
							}
						}
					else
						{
						$capture[$a][] = $this -> decoup_cont_balise($tab,preg_replace('/b\|0$/','',$k)); // on recupere sont contenu
						}
					}
				}
			unset($tab[$k]); // super important sinon on relie le tableau a chaque fois :-(
			}
		}
	return $capture; // on renvoi la capture
	}
	
/**
* Découpe le tableau plat suivant la clé de départ 
* @param array $tab le tableau plat
* @param string $regex clé de l'élément 
* @return array protion de tableau contenant l'élément et sa déscendence
*/

public function decoup_cont_balise($tab,$regex)
	{
	$ok = 0;
	$motif = '/^'.preg_quote($regex).'/';
	$stock = array();
	foreach($tab as $cle => $val)
		{
		if(preg_match($motif,$cle))
			{
			if(empty($stock))
				{
				$ok = 1;
				}
			$stock[$cle] = $val; 
			}
		else
			{
			if($ok == 1)
				{
				break;
				}
			}
		}
	return $stock;
	}

/**
* Récupére le texte de la page ou de la zone donnée:
* @param array $tab tableau plat de la page web ou d'une zone donnée 	
* @param int $mode paramètre de retour : 0 => <br/> , 1 => \n
* @return string texte de la page web
*/	
public function rec_text($tab,$mode=0)
	{
	$rec = $this -> extrait_c2($tab);
	if($mode ==0)
		{
		return $this-> html_ver_texte($rec);
		}
	}

/**
* fonction de test, permet tout simplement d'afficher un tableau 
* mixed $tab un tableau
*/
public function fonction_1($tab)
	{
	echo '<pre>';
	print_r($tab);
	echo '</pre>';
	}
	
/**
* Détermine le premier élément bloquant (entrainant un retour à la ligne) 
* @param array $tab tableau de position et de type d'élément ( créé par  extrait_c2)
* @return array array de type (type de balise,position)
*/
public function ret_par_block($tab)
	{
	$block = array('li','div','html','body','p','tr','adress','pre','h1','h2','h3','h4','h5','h6');
	foreach($tab[1] as $cle => $val)
		{
		if(in_array($val,$block))
			{
			return array($val,$tab[0][$cle]);
			break;
			}
		}
	}

/**
* Convertie le flatweb en du texte (avec <br/> pour les retours à la ligne)
* @param array $tab tableau issu de extrait_c2
* @return string le texte de la page
*/	
public function html_ver_texte($tab)
	{
	$str = "";
	$det = '';
	$den = 0;
	if(!empty($tab))
		{
		foreach($tab['val'] as $cle => $v)
			{
			if($v == '<br/>')
				{
				$str .= "<br/>";
				}
			else 
				{
				if( ! preg_match('/^&lt;\/|^&amp;nbsp;$|^<\//',$v))
					{
					$info = $this->ret_par_block($tab['ram'][$cle]);
					if($info[0] != 'code' && $info[0] != 'pre')
						{
						$v = preg_replace('/\n+/',' ',$v);
						}
					if($det == '')
						{
						$det = $info[0];
						$den = $info[1];
						$str .= $v;
						}
					else 
						{
						if($info[1] == $den)
							{
							$str .= $v;
							}
						else 
							{
							$str .= "<br/>" . $v;
							}
						$det = $info[0];
						$den = $info[1];
						}
					}
				}
			}
		}
	return $str;
	}

/**
* Revoie l'arborescence d'un objet html
* @param string $cle clé de la balise
* @param array $tab flatweb
* @return array liste des balises contenant notre objet html ainsi que leur position (position,type de balise)
*/

public function genealogie($cle,$tab) 
	{
	$ache = explode('|',$cle);
	$ram = array(array(),array());
	$fin = 0;
	do
		{
		$ok = 0;
		$tem = sizeof($ache)-1;
		if($ache[$tem-1] === 'c')
			{
			$ok = 1;
			array_pop($ache);
			array_pop($ache);
			if(isset($tab[implode('|',$ache).'|b|2']))
				{
				$ram[0][] = $tab[implode('|',$ache).'|b|2'];
				$ache = array_merge($ache,array('b','0'));
				$ram[1][] = $tab[implode('|',$ache)];
				}
			else
				{
				$fin = 2;
				}
			}
		if($ok ==0 && $fin == 0 && $ache[sizeof($ache)-2] === 'b' && $ache[sizeof($ache)-1] === '0')
			{
			$ok = 0;
			array_pop($ache);
			array_pop($ache);
			}
		if(!in_array('c',$ache,true))
			{
			$fin = 2;
			}
		}
	while($fin == 0 && !empty($ache) );
	return $ram;
	}	

/**
* Récupère les balises utiles contenant du texte affichable 
* (NB : les textes des <select> ne sont pas récupérés)
* @param array $tab => flatweb 
* @return array array de type ('val'=>array(texte,texte),'ram'=>array(array(postion),(type de balise))) 
*/	
public function extrait_c2($tab)
	{
	$nvtab = array();
	$ne_pas = array('commentaire','script','style','noscript','title','select'); // liste des balises dont le contenu est à exclure
    if(!empty($tab))
		{
		foreach($tab as $cle => $val)
			{
			if(preg_match('/c\|[0-9]+$/',$cle)) // si on a du contenu clé |c|
				{
				$infosurp = $this -> genealogie($cle,$tab); // on regarde sa généalogie
				//$this-> fonction_1($infosurp);
				if( ! empty($infosurp[0])) // si oui
                    {
					$inter = array_intersect($infosurp[1],$ne_pas); // on regarde si il n'y a pas dans son arborescence un élément excluant ce texte
                    if(empty($inter)) // on rajoute le texte
						{
						$nvtab['val'][] = $val;
						$nvtab['ram'][] = $infosurp;
						}
                    }
				}
			if($val === 'br' && preg_match('/b\|[0-9]+$/',$cle)) // même système pour la balise <br/> mais comme c'est une balise, on la traite avec la clé |b|
				{
				$infosurp = $this -> genealogie($cle,$tab);
				if( ! empty($infosurp[0]))
                    {
					$inter = array_intersect($infosurp[1],$ne_pas);
                    if(empty($inter))
						{
						$nvtab['val'][] = '<br/>';
						$nvtab['ram'][] = $infosurp;
						}
                    }
				}
			}
		}
	return $nvtab;
	}	
	
/**
* Récupére les attributs d'une balise à partir de sa clé
* @param array $tab flatweb 
* @param string $regex clé de la balise
* @return array array de type (attribut=>valeur de l'attribut)
*/

public function rec_attribut($tab,$regex)
	{
	$ok = 0;
	$stock = array();
	$motif = '/^'.preg_quote($regex.'b|3').'/';
	foreach($tab as $cle => $val)
		{
		if(preg_match($motif,$cle))
			{
			if(empty($stock))
				{
				$ok = 1;
				}
			$stock[preg_replace('/.+\|/','',$cle)] = $val; 
			}
		else
			{
			if($ok == 1)
				{
				break;
				}
			}
		}
	return $stock;
	}
	
/**
* Génère un espace à dimension variable
* @paream int $cb => le palier 
* @return string un espace (pour identiter le code)
*/

public function gen_esp($cb)
	{
	$str = '';
	if($cb>0)
		{
		$str = implode('',array_fill(0,$cb*5,' '));
		}
	return $str;
	}
	
/**
* Reconstruit une page web à partir de flatweb
* @param array $tab => flatweb
* @return string la page web reconstruite (pour afficher, ou graver avec fopen() )
*/
public function reconstruit_page_pgrave($tab)
	{
	$bal = include('l_balise_html.php');
	$str = '';
	$der_bal = array();
	$der_bal['cle'] = array();
	$der_bal['type'] = array();
	foreach($tab as $k => $v)
		{
		if(preg_match('/b\|0$/',$k)) // si on a une balise
			{
			$pr_ram = preg_replace('/b\|0$/','',$k); // on récupère sa trame
			if($pr_ram != '' && $v!= 'tlibre') // si c'est pas la balise du début
				{
				if(!empty($der_bal['cle'])) // si notre stock n'est pas vide
					{
					$t = sizeof($der_bal['cle'])-1; // on se calle au niveau de la dernière balise
					for($a = $t;$a> -1;$a--) // on boucle vers le début
						{
						if(preg_match('/^'.preg_quote($der_bal['cle'][$a]).'/',$k)) // si on est toujours dans la balise
							{
							break; // on block
							}
						else // sinon ---> on est plus dedans
							{
							if($der_bal['type'][$a] =='commentaire')
								{
								$str.= $this-> gen_esp($a+1)."-->\n"; // on affiche la fermeture de balise de commentaire
								}
							else
								{
								$str.= $this-> gen_esp($a+1)."</".$der_bal['type'][$a].">\n"; // on affiche la fermeture de balise
								}
							array_pop($der_bal['cle']); // on gicle cette balise
							array_pop($der_bal['type']);
							}
						}
					}
				if(in_array($v,$bal['ouverte'],true)) // si c'est une balise ouvrante
					{
					$der_bal['cle'][] = $pr_ram; // on la met dans les balise a fermer
					$der_bal['type'][] = $v;
					}
				if($v == 'commentaire')
					{
					$der_bal['cle'][] = $pr_ram; // on la met dans les balise a fermer
					$der_bal['type'][] = $v;
					}
				$amet = preg_replace('/b\|0$/','b|1',$k);	
$str.= $this-> gen_esp(sizeof($der_bal['cle'])).htmlspecialchars_decode($tab[$amet])."\n"; // on affiche le code
				}
			}
		if(preg_match('/c\|0$/',$k) && !empty($v))
			{
			$str.= $this-> gen_esp(sizeof($der_bal['cle'])).$v."\n";
			}
		}
	if(!empty($der_bal['cle']))
		{
		$t = sizeof($der_bal['cle'])-1;
		for($a = $t;$a> -1;$a--)
			{
			if($der_bal['type'][$a] =='commentaire')
				{
				$str.= $this-> gen_esp($a+1)."-->\n"; // on affiche la fermeture de balise de commentaire
				}
			else
				{
				$str.= $this-> gen_esp($a+1)."</".$der_bal['type'][$a].">\n"; // on affiche la fermeture de balise
				}
			}
		}
	return $str;
	}


}
?>
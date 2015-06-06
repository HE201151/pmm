<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<?php
	
	include "maLib.php";
	afficheTitle();


	?>
	<link rel="stylesheet" type="text/css" href="monSite.css" media="all" />

	
</head>

<body>
<div id="logo">
<?php
	afficheLogo();


?>
</div>
<div id="titre">
<?php
	afficheTitre();
?>
</div>

<div id="menu">

<?php
	setSession();
	$resultat=creeMenu();
	

?>


</div>


<div class="sous_menu">
	

<div id="wikis">

<h2>Wikis</h2>


<?php

	$bdd = coBdd();

	$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
	switch ($action) {
		case 'consulter':
			if (!isset($_SESSION['id']) || isset($_SESSION['newmail'])) {
				echo "Vous devez être membre actif pour créer un wiki!";

			}
			else{
				echo'</br><a href="wikis.php?action=creer">Créer un wiki</a>';
			}
			
			rechercheWiki();
			echo "</br></br><div class='instruction'>Voici les wikis :</div> </br>";
			afficheWikis($bdd);


			if (!isset($_SESSION['id']) || isset($_SESSION['newmail'])) {
				echo "Vous devez être membre actif pour créer un wiki!";

			}
			else{
				echo'</br><a href="wikis.php?action=creer">Créer un wiki</a>';
			}

			break;

		case 'anonyme':
			echo "Vous devez être connecté pour avoir accès à la page, ou vous pouvez vous inscrire <a href='inscription.php'> ici</a>";
			break;

		case 'consulte1':
			$sujetid = $_GET['sujetid'];

			if (isset($_GET ['pageid'])) {
				$pageid=$_GET['pageid'];
				$requete = $bdd->query('SELECT * FROM page WHERE sujetid='.$sujetid.' AND pageid='.$pageid.'');
				$donnee = $requete->fetch();

			}
			else{
				$requete = $bdd->query('SELECT * FROM sujet INNER JOIN page WHERE sujet.sujetid='.$sujetid.' AND page.sujetid='.$sujetid.' AND page.pageKeyword IS NULL');
				$donnee = $requete->fetch();
				$pageid=$donnee['pageid'];
			}
			//$requete = $bdd->query('SELECT * FROM page WHERE sujetid='.$sujetid.'');

			$requete1 = $bdd->query('SELECT * FROM sujet WHERE sujetid='.$sujetid.'');
			$donnee1 = $requete1->fetch();
			if (isset($_SESSION['login']) && !isset($_SESSION['newmail'])) {
				if ($donnee1['authorid']==$_SESSION['id']) {
					echo '</br><a href="wikis.php?action=supprimerSujet&sujetid='.$sujetid.'">Supprimer le sujet</a>';
				}
			}
			$authorid=$donnee1['authorid'];
			//echo "auteur="; echo $authorid;
			$requete9 = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil WHERE user_profil.user_id='.$authorid.'');
			$donnee9 = $requete9->fetch();
			//echo "profil_id="; echo $donnee9['profil_id'];
			if ($donnee9['profil_id']==9) {
				echo '</br></br><div class="wrong_log">Auteur banni</div></br>';
			}

			
			echo '<h3>'.$donnee1['sujetTitle'].' : </h3>';
				$text=charSpe($donnee['pageContent']);
			    $text=baliseBase($text,$bdd);
				echo '<div class="text_trad"></br>'.$text.'</br></br></div>';

			

			if (isset($_SESSION['id']) && !isset($_SESSION['newmail'])) {
				if ($donnee1['authorid']==$_SESSION['id']) {
					if (isset($_GET ['pageid'])) {
						echo '</br><a href="wikis.php?action=modifier&sujetid='.$sujetid.'&pageid='.$pageid.'">Modifier</a>';
					}
					else{
						echo '</br><a href="wikis.php?action=modifier&sujetid='.$sujetid.'&pageid='.$pageid.'">Modifier</a>';
					}
					echo '</br><a href="wikis.php?action=supprimer&sujetid='.$sujetid.'&pageid='.$pageid.'">Supprimer la page</a>';
					
				}
				else{
					echo "</br></br></br>Seul l'auteur peut modifier la page";
				}
				if ($donnee9['profil_id']!=9) {
					echo '</br></br>';
					echo '<a href="wikis.php?action=signaler&sujetid='.$sujetid.'&pageid='.$pageid.'">Signaler</a>';
				}

			}
			else{
				echo "</br></br>Vous devez être connecté avec un compte actif pour éditer une page</br>";
			}


			break;

		case 'supprimer':
			$sujetid = $_GET['sujetid'];
			$pageid=$_GET['pageid'];

			if (empty($_POST)) {
				echo '<div class="wrong_log"> Etes vous sûr de vouloir supprimer la page?</div>';	

				echo'<form method="post" action="wikis.php?action=supprimer&sujetid='.$sujetid.'&pageid='.$pageid.'">
					 </br> <div class="instruction">
        			  Pour supprimer, veuillez entrer votre mot de passe : </div>
        			 <div class="modif">
					 <label for="pass">Mot de passe :</label> 
					 <input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
					 </br></div>

					 <input type="submit" value="Valider"/>';
			}
			else{
				$requete = $bdd->query('SELECT * FROM page INNER JOIN sujet WHERE page.sujetid='.$sujetid.' AND page.pageid='.$pageid.'');
				$donnee = $requete->fetch();


				if ($donnee['authorid']==$_SESSION['id']) {
					$id=$_SESSION['id'];
					$requete1 = $bdd->query('SELECT * FROM tbuser WHERE userid='.$id.'');
					$donnee1 = $requete1->fetch();
					$password = hash('sha256', $_POST['pass']);
					if ($donnee1['userpwd']==$password) {
						echo "Page supprimée";
						$chemin='wikis.php?action=consulte1&sujetid=18&pageid=10';
						$sujet ='Problème dans la page : <a href='.$chemin.'>10</a>';
						$requete = $bdd->query('DELETE FROM page WHERE sujetid='.$sujetid.' AND pageid='.$pageid.'');
						$requete=$bdd->prepare('UPDATE tbmessages SET messujet=?, msgSujetId=18, msgPageId=10 WHERE msgPageId=?');
						$requete->execute(array($sujet,$pageid));

					}
					else{
						echo "<div class='wrong_log'>Mot de passe incorrect</div>";
						echo "</br></br> Vous allez être redirigé au formulaire";
						header('Refresh:3; url="wikis.php?action=supprimer&sujetid='.$sujetid.'&pageid='.$pageid.'"');
					}
				}
				else{
					echo "Vous devez être l'auteur pour supprimer";
				}
			}



			break;

		case 'afficherPages':
				$sujetid = $_GET['sujetid'];
				$requete = $bdd->query('SELECT * FROM page WHERE page.sujetid='.$sujetid.'');
				while ($donnees = $requete->fetch()){
					$sujetid=$donnees['sujetid'];
					$pageid=$donnees['pageid'];
					if (!empty($donnees['pageKeyword'])) {
						$titre=$donnees['pageKeyword'];
					}
					else{
						$titre='Page principale';
					}
					echo "<a href='wikis.php?action=consulte1&sujetid=".$sujetid."&pageid=".$pageid."'>".$titre."</br>";
				}

				echo '</br><a href="wikis.php?action=supprimerSujet&sujetid='.$sujetid.'">Retour</a>';


				break;

		case 'visiAdminChoice':
			$sujetid = $_GET['sujetid'];
			if (isset($_SESSION['isadmin'])) {
				if (empty($_POST)) {

					echo '	<form method="post" action="wikis.php?action=visiAdminChoice&sujetid='.$sujetid.'">
							</br><label for="visible"> Visibilité :</label>
							<select name="visible" required>
							<option value="1">Anonyme</option> 
			  				<option value="2">Membre</option>';
			  		echo'   <option value="3">Modérateur</option>
			  				<option value="4">Admin</option>';
					echo'	</select>
							<input type="submit" name="valide" value="valider"/>		
							</form>';
				}
				else{
					$visi=$_POST['visible'];
					$requete = $bdd->query('UPDATE sujet SET visibilityAdminChoice='.$visi.' WHERE sujetid='.$sujetid.'');
					echo "Visibilité choisie";
					header('Refresh:3; url="wikis.php?"');
				}
			}
			else{
				echo "Seul l'admin peut accèder à ce contenu";
				die();
			}

			break;

		case 'visiModoChoice':
			$sujetid = $_GET['sujetid'];
			if (isset($_SESSION['ismodo'])) {
				if (empty($_POST)) {

					echo '	<form method="post" action="wikis.php?action=visiModoChoice&sujetid='.$sujetid.'">
							</br><label for="visible"> Visibilité :</label>
							<select name="visible" required>
							<option value="1">Anonyme</option> 
			  				<option value="2">Membre</option>';
					echo'	</select>
							<input type="submit" name="valide" value="valider"/>		
							</form>';
				}
				else{
					$visi=$_POST['visible'];
					$requete = $bdd->query('UPDATE sujet SET visibilityModoChoice='.$visi.' WHERE sujetid='.$sujetid.'');
					echo "Visibilité choisie";
					header('Refresh:3; url="wikis.php?"');
				}
			}
			else{
				echo "Seul le modérateur peut accèder à ce contenu";
				die();
			}

			break;

		case 'suppModo':
			$sujetid = $_GET['sujetid'];
			if (isset($_SESSION['isadmin'])) {
				$requete = $bdd->query('UPDATE sujet SET modoId=0 WHERE sujetid='.$sujetid.'');
				echo "Modo supprimé";
				header('Refresh:3; url="wikis.php?"');
			}
			else{
				echo "Seul l'admin peut accèder à ce contenu";
				die();
			}

			break;

		case 'assignModo':
			$sujetid = $_GET['sujetid'];
			if (isset($_SESSION['isadmin'])) {
				if (empty($_POST)) {
					echo'
					<form method="post" action="wikis.php?action=assignModo&sujetid='.$sujetid.'">
					</br> <div class="instruction">
					Choisissez un modo : </div>
					<div class="modif">
					<label for="modoid">ID du modérateur :</label> 
					<input type="text" name="modoid" id="modoid"  size="3" maxlength"3"  required />
					</br></div>

					 <input type="submit" value="Valider"/>';
				}
				else{
					$modoid=$_POST['modoid'];
					//echo $modoid;
					$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil WHERE tbuser.userid = user_profil.user_id AND tbuser.userid='.$modoid.'');
					$donnee = $requete->fetch();
					//echo myPrint($donnee);
					if ($donnee['profil_id']==2) {
						$requete = $bdd->query('UPDATE sujet SET modoId='.$modoid.' WHERE sujetid='.$sujetid.'');

						
						$ini_path = 'conf.ini.php';
						$ini = parse_ini_file($ini_path, true);
						$mail= $ini['Gestionnaire']['mail'];

						$sujet = "Assignation sujet";
						$message = "Vous avez été assigné à un sujet";
						$entete = 'From: '.$mail;
						$destinataire = $donnee['usermail'];
						mail($destinataire, $sujet, $message, $entete) ;

						echo "Modo assigné";
						header('Refresh:3; url="wikis.php?"');
					}
					else{
						echo ' Erreur : mauvais ID';
						header('Refresh:3; url="wikis.php?action=assignModo&sujetid='.$sujetid.'"');

					}
				}

			}
			else{
				echo "Seul l'admin peut accèder à ce contenu";
				die();
			}
			break;

		case 'supprimerSujet':
				$sujetid = $_GET['sujetid'];
				$i=0;
				$requete = $bdd->query('SELECT * FROM page WHERE page.sujetid='.$sujetid.'');
				while ($donnees = $requete->fetch()){
					$i++;
				}
				echo 'Il y a '.$i.' page(s) associée(s) à ce sujet</br>';
				echo '<a href="wikis.php?action=afficherPages&sujetid='.$sujetid.'">Consulter les pages</a></br>';
				echo 'Etes vous certain de vouloir continuer?</br>';
				echo '<a href="wikis.php?action=supprimerSujet1&sujetid='.$sujetid.'">Confirmer</a></br>';
			break;

		case 'supprimerSujet1':
			$sujetid = $_GET['sujetid'];
			

			if (empty($_POST)) {
				echo '<div class="wrong_log"> Etes vous sûr de vouloir supprimer le sujet ?</div>';	

				echo'<form method="post" action="wikis.php?action=supprimerSujet1&sujetid='.$sujetid.'">
					 </br> <div class="instruction">
        			  Pour supprimer, veuillez entrer votre mot de passe : </div>
        			 <div class="modif">
					 <label for="pass">Mot de passe :</label> 
					 <input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
					 </br></div>

					 <input type="submit" value="Valider"/>';
			}
			else{
				$requete = $bdd->query('SELECT * FROM  sujet WHERE sujetid='.$sujetid.'');
				$donnee = $requete->fetch();


				if ($donnee['authorid']==$_SESSION['id']) {
					$id=$_SESSION['id'];
					$requete1 = $bdd->query('SELECT * FROM tbuser WHERE userid='.$id.'');
					$donnee1 = $requete1->fetch();
					$password = hash('sha256', $_POST['pass']);

					if ($donnee1['userpwd']==$password) {

						$requete3 = $bdd->query('SELECT * FROM  page WHERE sujetid='.$sujetid.'');
						$chemin='wikis.php?action=consulte1&sujetid=18&pageid=10';
						$sujet ='Problème dans la page : <a href='.$chemin.'>10</a>';

						while ($donnees = $requete3->fetch()){

							$pageid=$donnees['pageid'];
							echo $pageid;
							$requete=$bdd->prepare('UPDATE tbmessages SET messujet=?, msgSujetId=18, msgPageId=10 WHERE msgPageId=?');
							$requete->execute(array($sujet,$pageid));


						}

						

						$requete = $bdd->query('DELETE FROM page WHERE sujetid='.$sujetid.'');
						$requete1 = $bdd->query('DELETE FROM sujet WHERE sujetid='.$sujetid.'');

						$chemin='wikis.php?action=consulte1&sujetid=18';
						$sujet ='Problème sujet : <a href='.$chemin.'>18</a>';
						$requete2=$bdd->prepare('UPDATE tbmessages SET messujet=?, msgSujetId=18 WHERE msgSujetId=?');
						$requete->execute(array($sujet,$pageid));
						




						echo "Sujet supprimé";
						
						
						
						
					}
					else{
						echo "<div class='wrong_log'>Mot de passe incorrect</div>";
						echo "</br></br> Vous allez être redirigé au formulaire";
						header('Refresh:3; url="wikis.php?action=supprimerSujet1&sujetid='.$sujetid.'"');
					}
				}
				else{
					echo "Vous devez être l'auteur pour supprimer le sujet";
				}
			}
			break;

		case 'signaler':
			if (isset($_SESSION['id'])) {
				if (!empty($_POST)) {
					//echo myPrint($_POST);
					postSignaler($bdd);				}
				else{
					signalerPage($bdd);
				}
			}
			else{
				echo 'Vous devez être connecté';
				die();
			}


			break;

		case 'signalerSujet':
			if (isset($_SESSION['id'])) {
				if (!empty($_POST)) {
					//echo myPrint($_POST);
					postSignalerSujet($bdd);				}
				else{
					signalerSujet($bdd);
				}
			}
			else{
				echo 'Vous devez être connecté';
				die();
			}
			break;

		case 'modifier':
			$sujetid = $_GET['sujetid'];
			if (isset($_GET ['pageid'])) {
				$pageid=$_GET['pageid'];
				$requete = $bdd->query('SELECT * FROM page WHERE sujetid='.$sujetid.' AND pageid='.$pageid.'');

			}
			else{
				$requete = $bdd->query('SELECT * FROM page WHERE sujetid='.$sujetid.' AND pageKeyword IS NULL');
			}

			$requete1 = $bdd->query('SELECT * FROM sujet WHERE sujetid='.$sujetid.'');
			$donnee = $requete->fetch();
			$donnee1 = $requete1->fetch();
			if (!isset($_SESSION['id'])) {
				echo 'vous devez être connecté';
				die();
			}
			if ($donnee1['authorid']!=$_SESSION['id']) {
				echo "Vous devez être l'auteur du sujet pour modifier la page";
			}
			else{
				if (isset($_POST['valide'])) {   // j'ai cliqué sur « valider »
				 	$visible=$_POST['visible'];
				 	
		 		
					$content = $_POST['content'];
					$mysqldate = date("Y-m-d H:i:s",time());
					if (isset($_SESSION['id'])) {
						$authorid = $_SESSION['id'];
					}
					else{
						echo "Vous devez être membres pour créer un wiki!";
						die();

					}
				
					$sujet = $_POST['sujet'];
					$description = $_POST['description'];








					$bdd->exec('UPDATE sujet SET sujetTitle="'.$sujet.'",sujetDesc="'.$description.'",sujetDateModif="'.$mysqldate.'", visibilityAuthorChoice="'.$visible.'" WHERE sujetid='.$sujetid.'');

					if (isset($_GET ['pageid'])) {
						$requete=$bdd->prepare('UPDATE page SET pageContent=?, pageLastModif="'.$mysqldate.'"  WHERE sujetid='.$sujetid.' AND pageid='.$pageid.'');

						$requete->execute(array($content));

					}
					else{
						$requete=$bdd->prepare('UPDATE page SET pageContent=?, pageLastModif="'.$mysqldate.'"  WHERE sujetid='.$sujetid.' AND pageKeyword IS NULL');

						$requete->execute(array($content));
					}

					

///////////////////////////////////////////////////:

			/*		$bdd->exec('insert into sujet (authorid,sujetTitle,sujetDesc,sujetDateCrea,sujetDateModif) values 
					("'.$authorid.'","'.$sujet.'","'.$description.'",
						"'.$mysqldate.'",
						"'.$mysqldate.'");');

					$requete=$bdd->prepare('insert into page (sujetid,pageContent,pageDateCrea,pageLastModif) values ("'.$sujetid.'",?,"'.$mysqldate.'","'.$mysqldate.'");');

					$requete->execute(array($content));*/





				    echo'	Wiki validé </br></br></br>

				    		<a href="wikis.php?action=consulter">Retour aux wikis</a>';
				 
				} 
				elseif (isset($_POST['Translate'])) {   // j'ai cliqué sur « Translate »
					if (!isset($_SESSION['id'])) {
						echo "</br></br>Vous devez être membres pour créer un wiki!";
						die();

					}
					
			 		echo"</br>
						<form method='post' action='wikis.php?action=modifier&sujetid=".$sujetid."&pageid=".$pageid."'>
					    </br>
					   		<label for='sujet'> Sujet :</label>"; 

					 afficheFormWiki();
				 	$text=charSpe($_POST['content']);
				    $text=baliseBase($text,$bdd);
			
					echo "</br>Traduction = </br>";
					echo '<div class="text_trad"></br>'.$text.'</br></br></div>';
				 
				}
				else {
					formModif($donnee,$donnee1,$sujetid,$pageid);

					$text=charSpe($donnee['pageContent']);
				    $text=baliseBase($text,$bdd);
			
					echo "Traduction = ";
					echo '<div class="text_trad"></br>'.$text.'</br></br></div>';

				}
			}
			break;

		case 'newPage1':
			$sujetid = $_GET['sujetid'];
			echo '<div class="instruction">Choisissez un "Mot Clé" : </div></br> ';
			echo'<form method="post" action="wikis.php?action=newPage&sujetid='.$sujetid.'">

    			 <div class="modif">
				 <label for="keyword">Mot clé :</label> 
				 <input type="text" name="keyword" id="keyword" size="30" maxlength"15"  required />
				 </br></div>
				 <input type="submit" value="Valider"/>';
			break;

		case 'newPage':
			$sujetid = $_GET['sujetid'];
			if (isset( $_POST['keyword'])) {
				
				$keyword = $_POST['keyword'];
			}
			else{
				$keyword = $_GET['keyword'];
			}
			//$keyword = $_GET['keyword'];
			if (isset($_SESSION['id'])) {
				$requete1 = $bdd->query('SELECT * FROM sujet WHERE sujetid='.$sujetid.'');
				$donnee1 = $requete1->fetch();
				if ($donnee1['authorid']==$_SESSION['id']) {
					//echo '<a href="wikis.php?action=modifier&sujetid='.$sujetid.'">Modifier</a>';
				}
				else{
					echo "</br></br>Seul l'auteur peut modifier la page";
					die();
				}
			}
			else{
				echo "</br></br>Vous n'êtes pas connecté";
			}
			
			if (isset($_POST['valide'])) {   // j'ai cliqué sur « valider »
			 	
				$content = $_POST['content'];
				$mysqldate = date("Y-m-d H:i:s",time());
				if (isset($_SESSION['id'])) {
					$authorid = $_SESSION['id'];
				}
				else{
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}

				$bdd->exec('UPDATE sujet SET sujetDateModif="'.$mysqldate.'" WHERE sujetid='.$sujetid.'');
				$requete=$bdd->prepare('insert into page (sujetid,pageContent, pageKeyword,pageDateCrea,pageLastModif) values ("'.$sujetid.'",?,"'.$keyword.'","'.$mysqldate.'","'.$mysqldate.'");');

				$requete->execute(array($content));




			    echo'	Wiki validé </br></br></br>

			    		<a href="wikis.php?action=consulter">Retour aux wikis</a>';
			 
			} elseif (isset($_POST['Translate'])) {   // j'ai cliqué sur « Translate »
				if (!isset($_SESSION['id'])) {
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}

				afficheFormPage($sujetid,$keyword);
			 	$text=charSpe($_POST['content']);
			    $text=baliseBase($text,$bdd);
		
				echo "Traduction = ";
				echo '<div class="text_trad"></br>'.$text.'</br></br></div>';
			 
			} else {
				if (!isset($_SESSION['id'])) {
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}
								
				afficheFormPage($sujetid,$keyword);
			}
			break;

		case 'creer':
			if (isset($_POST['valide'])) {   // j'ai cliqué sur « valider »
			 	$visible=$_POST['visible'];

				$content = $_POST['content'];
				$mysqldate = date("Y-m-d H:i:s",time());
				if (isset($_SESSION['id'])) {
					$authorid = $_SESSION['id'];
				}
				else{
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}
				//$authorid = $_SESSION['id'];
				$sujet = $_POST['sujet'];
				$description = $_POST['description'];
//$sql2 ="INSERT INTO `1415he201151`.`page` (`pageContent`,`pageDateCrea`)
//INNER JOIN `1415he201151`.`sujet` (`authorid`,`sujetTitle`,`sujetDesc`,`sujetDateCrea`,`sujetDateModif`,`modoId`,`visibilityAuthorChoice`,`visibilityModoChoice`,`visibilityAdminChoice`)

				//$requete = $bdd->query('INSERT INTO * FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id ');

				
				$bdd->exec('insert into sujet (authorid,sujetTitle,sujetDesc,sujetDateCrea,sujetDateModif,visibilityAuthorChoice) values 
					("'.$authorid.'","'.$sujet.'","'.$description.'",
						"'.$mysqldate.'",
						"'.$mysqldate.'","'.$visible.'");');


				

				$requete = $bdd->query('SELECT sujetid FROM sujet WHERE sujetTitle ="'.$sujet.'"');
				$donnee = $requete->fetch();
				$sujetid = $donnee['sujetid'];

				$requete=$bdd->prepare('insert into page (sujetid,pageContent,pageDateCrea,pageLastModif) values ("'.$sujetid.'",?,"'.$mysqldate.'","'.$mysqldate.'");');

				$requete->execute(array($content));




			    echo'	Wiki validé </br></br></br>

			    		<a href="wikis.php?action=consulter">Retour aux wikis</a>';
			 
			} elseif (isset($_POST['Translate'])) {   // j'ai cliqué sur « Translate »
				if (!isset($_SESSION['id'])) {
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}
				echo"<form method='post' action='wikis.php?action=creer'>
				    </br>
   					<label for='sujet'> Sujet :</label>"; 
		 		afficheFormWiki();
			 	$text=charSpe($_POST['content']);
			    $text=baliseBase($text,$bdd);
		
				echo "Traduction = ";
				echo '<div class="text_trad"></br>'.$text.'</br></br></div>';
			 
			} else {
				if (!isset($_SESSION['id'])) {
					echo "Vous devez être membres pour créer un wiki!";
					die();

				}
				echo"<form method='post' action='wikis.php?action=creer'>
				    </br>
   					<label for='sujet'> Sujet :</label>"; 
				afficheFormWiki();
		 		
			}





			//echo'<a href="wikis.php?action=valide">Valider</a>';
			break;
	}

	


	
	
	verifProfil();
?>

</div>
</div>

<footer>
<?php
	creeFooter();
?>
</footer>


</html>
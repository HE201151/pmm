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
	

<div id="Validation">


<?php
$bdd = coBdd();
	// si demande de nouveau mdp
	if (!empty($_POST['newpass'])){
		// si les deux pass sont égaux
		if ($_POST['newpass'] == $_POST['confpass']) {
			
			$mdp = hash('sha256', $_POST['newpass']);
			//$mdp = sha1($_POST['newpass']);
			//$mdp = $_POST['newpass'];
			$id = $_SESSION['id'];
			$cle = $_GET['cle'];
			//echo $cle;
			$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `userpwd`='$mdp' WHERE `userid`='$id' ");
			//$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='3' WHERE `user_id`='$id' AND  `profil_id`='6' ");
			$requete=  $bdd->query("DELETE FROM `1415he201151`.`user_profil` WHERE `user_id`='$id'AND `profil_id` =6");
			//$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `etat`='1' WHERE `userid`='$id' ");
			//$requete=  $bdd->query("UPDATE `1415he201151`.`activations` SET `activationCode`='0' WHERE `userid`='$id' ");
			$requete=  $bdd->query("DELETE FROM `1415he201151`.`activations` WHERE `userid`='$id' AND `activationCode`='$cle'");


			//on vérifie si l'user n'avait pas une demande de nouveau mail
			//$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='3' WHERE `user_id`='$id' AND  `profil_id`='5' ");
			$requete1 = $bdd->query("SELECT profil_id FROM user_profil WHERE user_id='$id' AND `profil_id`=5 " );
			$donnee1 = $requete1->fetch();
			if($donnee1['profil_id']=='5'){
				$requete=  $bdd->query("DELETE FROM `1415he201151`.`activations` WHERE `userid`='$id'");
				$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`=3 WHERE `user_id`='$id' ");
			}

			
			$_SESSION['login']='ok';
			$_SESSION['profilComplet']='ok';
			echo '<div class="wrong_log"> Mot de passe modifié </div> ';
			header("Refresh:3; url=connexion.php");

		}
		//deux mdpass diffèrent
		else{
			echo '<div class="wrong_log">  Erreur lors du changement de mot de passe: les deux mots de passe ne correspondent pas. </div>';
			afficheNewPass($bdd);
		}
	}
	//Si question secrète
	else{
		if (isset($_POST['reponse'])) {
			
			$cle = $_GET['cle'];

			$requete1 = $bdd->query('SELECT userid, activationCode FROM activations WHERE (activationCode)= \'' . $cle . '\'');
			$donnee1 = $requete1->fetch();

			$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail, question, reponse FROM tbuser WHERE (userid)= \'' . $donnee1['userid'] . '\'');
			$donnee = $requete->fetch();

			$donnePseudo = strtolower($donnee['pseudo']);
			$postPseudo = strtolower($_POST['pseudo']);
			$_SESSION['id']=$donnee['userid'];
			$reponse = hash('sha256', $_POST['reponse']);
			//$reponse = sha1($_POST['reponse']);
			//si pseudo correcte
			if ($donnePseudo==$postPseudo) {
				//si réponse correcte
				if ($donnee['reponse']==$reponse) {
					afficheNewPass();
				}
				else{
					echo"<div class='wrong_log'>Réponse inccorecte</div></br>";
					initMdp($bdd);
				}
			}
			else{
				echo "<div class='wrong_log'>Pseudo inccorect</div></br>";
				initMdp($bdd);
			}
		}
		else{
			// si demande d'activation de compte ou newmail
			if (isset($_GET['log'])) {
			 	if (isset($_GET['cle'])) {
			 		activation($bdd);
			 	} 
			 	else{
			 		echo "<div class='wrong_log'>Erreur : clé manquante</div>";
			 	}
			 }
			 else{
			 	initMdp($bdd);
			 } 
		}
	}
	
?>

</div>
</div>

<footer>
<?php
	creeFooter();
?>
</footer>


</html>
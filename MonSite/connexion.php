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
	$check=99999;
	if((!empty($_POST['pseudo'])) and (!empty($_POST['pass']))){
		$check=login();
		if($check==1){
			$_SESSION['login']='ok';
			
		}
		if($check==3){
			$_SESSION['login']='ok';
			$_SESSION['isadmin']='ok';
		}
		if($check==33){
			$_SESSION['login']='ok';
			$_SESSION['isadmin']='ok';
		}

		if($check==11){
			$_SESSION['login']='ok';
		}
		if($check==6){
			$_SESSION['login']='ok';
		}
		if($check==5){
			$_SESSION['login']='ok';
			$_SESSION['profilComplet'] = 'nok';
		}
	}
	
	
	$resultat=creeMenu();

?>


</div>


<div class="sous_menu">
	

<div id="Connexion">


<h2>Connexion</h2>
<div class="wrong_log">
<?php
	if($check==0){
		echo 'Login ou mot de passe incorrect';
	}
	if($check==2){
		echo 'Compte inactif, veuillez consulter votre boîte mail pour activer votre compte.';
	}



	if(isset($_POST['question'])){
		completeProfil();
		echo '</br><div class="instruction">Profil complété avec succès</div> </br>';
		$_SESSION['profilComplet'] = 'ok';
	}
?>
</div>
<?php



if(isset($_POST['email'])){
	$bdd=coBdd();
	//$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, u.profil_id FROM tbuser p, user_profil u WHERE (p.pseudo)= \'' . $_POST['pseudo'] . '\' AND u.user_id= p.userid');
	//$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.question, u.profil_id FROM tbuser p, user_profil u WHERE (usermail)= \'' . $_POST['email'] . '\' AND u.user_id=p.userid');

	$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.question, u.profil_id 
							FROM tbuser p JOIN user_profil u 
							     ON  u.user_id=p.userid
							WHERE (usermail)= \'' . $_POST['email'] . '\'');
	$donnee = $requete->fetch();
	if ($donnee['usermail'] == $_POST['email']){
		if (!isset($donnee['question'])){
			echo "<div class='wrong_log'>Votre profil n'est pas complet, veuillez contacter l'admin</div>";
		}
		else{
			if(($donnee['profil_id']==3) || ($donnee['profil_id']==5)){
				$id=$donnee['userid'];
				$cle = md5(microtime(TRUE)*100000);
				echo "<div class='instruction'>Demande traitée, consultez votre boîte mail pour réinitialiser votre mot de passe</div>";
				//On crée un deuxième profil qu'il faudra supprimé
				// (ANNULE) Si le user est déjà en demande d'un nouveau mail (il faut créer un deuxième profil)
				//if ($donnee['profil_id']==5){
					$sql2 ="INSERT INTO `1415he201151`.`user_profil` (`user_id`, `profil_id`) 
											VALUES('$id', '6')";
					$bdd->exec($sql2);
				/*}
				else{ 
					$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='6' WHERE `user_id`='$id' ");
				}*/
				
				//$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `etat`='11' WHERE `userid`='$id' ");
				//$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `cle`='$cle' WHERE `userid`='$id' ");

				// On rajoute la clé
				$sql1 ="INSERT INTO `1415he201151`.`activations` (`userid`, `activationCode`,`profil_id`) 
											VALUES('$id', '$cle',6)";
				$bdd->exec($sql1);
				$pseudo = $donnee['pseudo'];
				$destinataire = $donnee['usermail'];
				$sujet = "Réinitialiser le mot de passe" ;
				$entete = "From: mdp@MonSite.com" ;
				$message = 'Bonjour ' .$pseudo.',

				Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci dessous
				ou copier/coller dans votre navigateur internet.
				 
				http://193.190.65.94/he201151/ESSAIS/MonSite/validation.php?cle='.urlencode($cle).'
				 
				 
				---------------
				Ceci est un mail automatique, Merci de ne pas y répondre.';
				 
				
				mail($destinataire, $sujet, $message, $entete) ;
			}
			else{
				echo "<div class='wrong_log'>Votre statut ne vous permet pas de changer votre mot de passe</div>";
			}
		}	
	}
	else{
		echo "<div class='wrong_log'> Cette adresse email n'est pas valide</div></br>";
		$_GET['action'] = 'oubli';
	}
}
if(!isset($_SESSION['login'])){
	$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'connexion';
	switch($action){
	    case "connexion":
			echo '<form method="post" action="connexion.php">
			     <p>
			     <label for="pseudo">Votre pseudo :</label>';
			        
			     if (!empty($_POST['pseudo'])){
			        $pseudo=$_POST['pseudo'];
			        echo "<input type='text' name='pseudo' id='pseudo' value='$pseudo' size='30' maxlength='15'  required />";
			     }
			     else{
			        echo '<input type="text" name="pseudo" id="pseudo" placeholder="Ex : Zozor" size="30" maxlength="15"  required />';
			     }
			        
				echo '<br/>
				<label for="pass">Votre mot de passe :</label> 
				<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
				</br>
				<span id="mdp_perdu"><a href="http://193.190.65.94/he201151/ESSAIS/MonSite/connexion.php?action=oubli"> mot de passe oublié </a> </span>
				<br/>
				<input type="submit" value="Connexion" />
			    </p>
			</form>';
			break;
		case "oubli":
			echo "<form method='post' action='connexion.php'>
				<p>  <div id='oubli'>
				     <label for='email'>Insérez votre adresse email : </label></div>
				     <input type='email' name='email' id='email' placeholder='Ex. : exemple@mail.be' size='50' maxlength='50'  required />

				     <input type='submit' value='Valider' />
				</p>
			";
			break;
	}
}
else{
	//$pseudo=$_POST['pseudo'];
	$bdd=coBdd();
	$id =  $_SESSION['id'];
	$requete = $bdd->query("SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.userdateinscription, p.question, p.reponse, u.profil_id FROM tbuser p, user_profil u WHERE (userid)= '$id' AND u.user_id=p.userid");
	$donnee = $requete->fetch();
	if($donnee['profil_id'] == 1){
		$statut='Admin';
	}
	if($donnee['profil_id'] == 3){
		$statut='User';
	}
	if($donnee['profil_id'] == 5){
		$statut='En cours de réactivation';

		$_SESSION['newmail']='nok';
	}
	if($donnee['profil_id'] == 2){
		$statut='Sous-admin';
	}
	if($donnee['profil_id'] == 4){
		$statut='';
	}
	if($check==33){
		echo "<div class='wrong_log'>Votre nouvelle adresse mail n'a pas été validée, veuillez consulter votre boîte mail pour activer votre nouvelle adresse mail.</div>";
		$statut='Admin';
		$_SESSION['newmail']='nok';
	}
	if($check==6){
		echo '<div class="wrong_log"> Votre demande de changement de mot de passe a été annulée. </div>';
	}
	echo '<p>
			<div class="wrong_log">
			Connecté
			</div>
		  </p>
		  <p>
		  	<div class="instruction">Bienvenue sur le site ' .$donnee['pseudo'].' </div></br>
		  	<div class="modif">Votre adresse mail : '.$donnee['usermail'].'</br>
		  	Statut : '.$statut.'</div>
		  </p>';
		  //echo $donnee['etat'];
	if ($donnee['profil_id'] != 1) {
		if ($donnee['question']==NULL) {
			echo '</br> <div class="instruction">

	        			<p> Afin de compléter votre profil, veuillez entrer un question secrète ainsi que la réponse : </p></div>
	        			<div class="modif">
	        			<form method="post" action="connexion.php">
	        			<label for="question"> Question secrète :</label> 
						<input type="text" name="question" id="question" placeholder="Ex. : Quelle est la marque de ma première voiture?" size="50" maxlength"50" required /> </br>

						<label for="reponse"> Réponse :</label> 
						<input type="text" name="reponse" id="reponse" placeholder="Ex. : Mitsubishi" size="50" maxlength"50" required/> </br>
						</br>
						<input type="submit" value="Valider"/>
						</div>';
		}
		else{
			$_SESSION['profilComplet'] = 'ok';
		}
	}
	//}
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
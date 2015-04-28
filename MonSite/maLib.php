<html>



<?php



function creeMenu(){
	echo '<ul><li><a href="Index.php">Index</a></li>';
	echo '<li><a href="normale.php">Normale</a></li>';
	
	if(isset($_SESSION['login'])){		
		echo '<li><a href="User.php">Profil</a></li>';
		echo 'Connecté';
		echo '<li><a href="Deco.php">Déconnexion</a></li>';
	}
	else{
		
		echo '<li><a href="connexion.php">connexion</a></li>';
		echo '<li><a href="inscription.php">inscription</a></li>';
	}	
	if(isset($_SESSION['isadmin'])){
		echo '<li><a href="Administration.php">Administration</a></li>';
	}
	echo '<li><a href="contact.php">Contact</a></li></ul>';
}

function creeFooter(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);
	$nom = $ini['Gestionnaire']['Nom'];
	$prenom = $ini['Gestionnaire']['Prenom'];
	$tel = $ini['Gestionnaire']['tel'];
	$mail = $ini['Gestionnaire']['mail'];

	echo ''.$prenom.' '.$nom.' </br>';
	
	echo ''.$tel.'</br>';
	echo '<a href="mailto:'.$mail.'"> '.$mail.'</a>';
}


function connexion(){

	if((!empty($_POST['pseudo'])) and (!empty($_POST['pass']))){
		session_start();
		
	}
	else{
		echo 'erreur <br/>';
		if(empty($_POST['pseudo'])){
			echo 'Pseudo vide';
		}
		else{
			echo 'Mot de passe vide';
		}
	}
}


function login(){
if (!isset($_POST['pseudo']) || !isset($_POST['pass'])) {
	//echo "<script>alert('pseudo ou MDP incomplet')</script>";
	//header ('location: index.php?userChoice=CreateAcc');
	

die;
}
/*
SELECT tbuser.userid, tbuser.pseudo, tbuser.userpwd, tbuser.usermail, tbuser.question, user_profil.profil_id
FROM tbuser
INNER JOIN user_profil
ON tbuser.userid=user_profil.userid

SELECT id, pseudo, mdp,email, lvl, userDateInscription, userLastConnect FROM Personne WHERE id in (SELECT user_id from user_profil where profil_id =".$_POST['statut']."


	SELECT p.id p.pseudo, p.mdp, p.email, p.secretQuestion, p.secretAnswer, u.profil_id  FROM Personne p, user_profil u WHERE p.id = :requete AND u.user_id = :requete")
*/
else{
	//echo "<script>alert('pseudo et mdp rempli ok')</script>";
	$pseudo=$_POST['pseudo'];
	$password=$_POST['pass'];
	$bdd=coBdd();

	//$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail, etat FROM tbuser WHERE (pseudo)= \'' . $_POST['pseudo'] . '\'');
	$peudo=$_POST['pseudo'];
	$requete = $bdd->query("SELECT p.userid, p.pseudo, p.userpwd, p.usermail, u.profil_id FROM tbuser p, user_profil u WHERE (p.pseudo)= '$pseudo' AND u.user_id= p.userid");
	//$requete1 = $bdd->query('SELECT userid FROM tbuser WHERE (pseudo)= \'' . $_POST['pseudo'] . '\'');
	//$donnee1 = $requete1->fetch();
	//$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail FROM tbuser WHERE userid in (SELECT profil_id from  user_profil where user_id =  \'' . $donnee1['userid'] . '\'' ));


			$donnee = $requete->fetch();
			//echo '<pre>'.print_r($donnee['password'],1).'</pre>';
				$password=sha1($password);
				if($donnee['userpwd'] == $password){
					if($donnee['profil_id'] == 4){
						return 2;
					}
			
					$_SESSION['mail']=$donnee['usermail'];
					$_SESSION['id']=$donnee['userid'];
					$id = $_SESSION['id'];
					$mysqldate = date("Y-m-d H:i:s",time()); 					
					$sql ="UPDATE `1415he201151`.`tbuser` SET `userlastconnect` = '$mysqldate' WHERE `userid` = '$id'";											
					$bdd->exec($sql);
					//header ('location: http://193.190.65.94/he201151/ESSAIS/MonSite/Index.php');
					if($donnee['profil_id'] == 10){
						return 33;
					}


					if($donnee['profil_id'] == 1){
						return 3;
					}
					$requete1 = $bdd->query("SELECT p.userid, p.pseudo, p.userpwd, p.usermail, u.profil_id FROM tbuser p, user_profil u WHERE (p.pseudo)= '$pseudo' AND u.user_id= p.userid AND u.profil_id=6");
					$donnee1 = $requete1->fetch();
					if($donnee1['profil_id'] == 6){
						$_SESSION['id']=$donnee1['userid'];
						$id = $_SESSION['id'];
						//$requete = $bdd->query("UPDATE `1415he201151`.`tbuser` SET `cle` = '0' WHERE `userid` = '$id'");
						//$requete = $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id` = '3' WHERE `user_id` = '$id' AND `profil_id` = 6");
						$requete=  $bdd->query("DELETE FROM `1415he201151`.`activations` WHERE `userid`='$id' AND `profil_id` = 6");
						$requete=  $bdd->query("DELETE FROM `1415he201151`.`user_profil` WHERE `user_id`='$id'AND `profil_id` = 6");

						
						return 6;
						//echo '<div class="wrong_log"> Votre demande de changement de mot de passe a été annulée. </div>';
					}	
					$requete = $bdd->query("SELECT p.userid, p.pseudo, p.userpwd, p.usermail, u.profil_id FROM tbuser p, user_profil u WHERE (p.pseudo)= '$pseudo' AND u.user_id= p.userid AND u.profil_id=5");	
					$donnee = $requete->fetch();
					if($donnee['profil_id'] == 5){

						return 5;
					}
					if($donnee['profil_id'] == 2){
						$_SESSION['id']=$donnee1['userid'];
						$id = $_SESSION['id'];

						return 222;
					}						
					//echo "<script>alert('Vous êtes connecté')</script>";
					return 1;
				}
				else{
					//echo 'myPrint('.$donnee.')';
					//header ('location: index.php?userChoice=Login');
					//echo "<script>alert('Login ou mot de pass incorrect')</script>";
					return 0;
				}
		$requete->closeCursor();
	}
}


function validationChamp(){
		return !(empty($_POST)||empty($_POST['pseudo']) || empty($_POST['pass']) || empty($_POST['confirm'])||empty($_POST['mail']) || empty($_POST['mail']));
}

function msgChampVide(){
if(!empty($_POST)){
	if(empty($_POST['pseudo'])){
		echo 'Pseudo manquant </br>';
	}
	
	if(empty($_POST['pass'])){
		echo 'mot de passe manquant </br>';
	} 
	
	if(empty($_POST['confirm'])){
		echo 'confirmation du mot de passe manquant </br>';
	} 
	
	if(empty($_POST['mail'])){
		echo 'e-mail manquant </br>';
	}	
	
	if(empty($_POST['confMail'])){
		echo 'confirmation e-mail manquant </br>';
	}	
}
}

function signup(){
	$wrong=false;
	$champsValide=validationChamp();
	msgChampVide();
	if ($champsValide){
		$ini_path = 'conf.ini.php';
		$ini = parse_ini_file($ini_path, true);
		$minimum = $ini['Login']['minimum'];
		$pseudo=$_POST['pseudo'];
		$password=$_POST['pass'];
		$confirm=$_POST['confirm'];
		$mail=$_POST['mail'];
		$confMail = $_POST['confMail'];	
		if (strlen($pseudo)<$minimum) {
			echo 'Login trop court (minimum 5 caractères)</br>';
			$wrong=true;
		}
		if($password != $confirm){
			echo 'Les deux mots de passe ne correspondent pas </br>';
			$wrong=true;
		}
		if($mail != $confMail){
			echo 'Les deux mail ne correspondent pas </br>';
			$wrong=true;
		}
		if(!$wrong){
			$ini_path = 'conf.ini.php';
			$ini = parse_ini_file($ini_path, true);
			$dbName= $ini['BaseDeDonnee']['DBName'];
			$userName = $ini['BaseDeDonnee']['LoginBDD'];
			$passName = $ini['BaseDeDonnee']['MotDePassBDD'];   
			try{
				$bdd = new PDO('mysql:host='.$ini['BaseDeDonnee']['hostNameLocal'].';
								dbname='.$dbName.';', $userName, $passName);
				$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$requete = $bdd->query('SELECT pseudo, userpwd, usermail FROM tbuser WHERE (pseudo)= \'' . $_POST['pseudo'] . '\'');
				$donnee = $requete->fetch();
				$pseudo=strtolower($pseudo);
				$donneePseudo=strtolower($donnee['pseudo']);
				if ($donneePseudo == $pseudo){
					echo "Pseudo existant </br>";		
					$wrong=true;
				}
				$requete = $bdd->query('SELECT pseudo, userpwd, usermail FROM tbuser WHERE (usermail)= \'' . $_POST['mail'] . '\'');
				$donnee = $requete->fetch();
				if ($donnee['usermail'] == $mail){
					echo "Adresse email existante </br>";		
					$wrong=true;
				}
				if(!$wrong){
					//$_SERVER;
					/*$date = date("d-m-Y");
					$heure = date("H:i");
					$phpdate = strtotime( $mysqldate );
					$mysqldate = date( 'Y-m-d H:i:s', $phpdate ); */
					$mysqldate = date("Y-m-d H:i:s",time()); 
					$cle = md5(microtime(TRUE)*100000);
					$password=sha1($password);
					$sql ="INSERT INTO `1415he201151`.`tbuser` (`pseudo`, `userpwd`, `usermail` , `userdateinscription`) 
											VALUES('$pseudo', '$password', '$mail', '$mysqldate')";
											$bdd->exec($sql);
					
					$requete1 = $bdd->query('SELECT userid FROM tbuser  WHERE pseudo= "' . $pseudo . '";');
					$donnee1 = $requete1->fetch();
					
					$id = $donnee1['userid'];
					
					
					$sql1 ="INSERT INTO `1415he201151`.`user_profil` (`user_id`, `profil_id`) 
											VALUES('$id', '4')";
					$bdd->exec($sql1);
					$sql2 ="INSERT INTO `1415he201151`.`activations` (`userid`, `activationCode`) 
											VALUES('$id', '$cle')";
					$bdd->exec($sql2);
					$destinataire = $mail;
					$sujet = "Activer votre compte" ;
					$entete = "From: inscription@MonSite.com" ;
					$message = 'Bienvenue ' .$pseudo.',
 
					Pour activer votre compte, veuillez cliquer sur le lien ci dessous
					ou copier/coller dans votre navigateur internet.


					 
					http';
					if ($_SERVER['HTTPS']=='on') {
						$message=$message.'s';
					}
					else {
						//$message=$message.'no';
					}

					
					$serverName=$_SERVER['SERVER_NAME'];
					$path=$_SERVER['PHP_SELF'];
					$nb=substr_count($path, '/');
					$list=explode('/', $path);
					$message=$message.'://';
					$chemin=$list[0].'/';
					for ($i=1; $i <$nb ; $i++) { 
						$chemin=$chemin.$list[$i].'/';
					}
					$message=$message.$serverName.$chemin.'/validation.php?log='.urlencode($pseudo).'&cle='.urlencode($cle).'
					 
					 
					---------------
					Ceci est un mail automatique, Merci de ne pas y répondre.';
					 
					
					mail($destinataire, $sujet, $message, $entete) ;
					//header ('location: http://193.190.65.94/he201151/ESSAIS/MonSite/Index.php');
					//echo "<script>alert('Inscription réussie')</script>";
					return 1;
					}
			}
			catch (PDOException $e){
				//echo "<script>alert('Erreur de connexion ')</script>";
				die('Erreur : '. $e->getMessage());
			}
			$requete->closeCursor();
			$bdd = null;
		}	
	}
	return 0;
}

function activation(){
	$bdd = coBdd();
	// Récupération des variables nécessaires à l'activation
	$login = $_GET['log'];
	$cle = $_GET['cle'];
	 // Récupération de la clé correspondant au $login dans la base de données
	$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.question, p.reponse, u.profil_id FROM tbuser p, user_profil u WHERE (p.pseudo)= \'' . $login . '\' AND u.user_id= p.userid');
	//$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail, etat, cle, question, reponse FROM tbuser WHERE (pseudo)= \'' . $login . '\'');
	$donnee = $requete->fetch();
	$requete1 = $bdd->query('SELECT activationCode FROM activations WHERE userid=\''.$donnee['userid'].'\'');
	$donnee1 = $requete1->fetch();
		if($donnee1['activationCode'] == $cle){
			$clebdd = $donnee1['activationCode'];
			$actif = $donnee['profil_id']; // $actif contiendra alors 0, 1, 11 ou 33
		}
		else{
			echo "<div class='wrong_log'>Erreur ! Votre compte ne peut être activé...</div>";
			die();
		}
	if($actif == '3'){
		 echo "Votre compte est déjà actif !";
	}
	else{
		if($cle == $clebdd){
			if($actif == '4'){
				echo "Votre compte a bien été activé !";
				$requete=  $bdd->query('UPDATE `1415he201151`.`user_profil` SET `profil_id`="3" WHERE `user_id`=\'' . $donnee['userid'] . '\'');
				//$requete=  $bdd->query('UPDATE `1415he201151`.`tbuser` SET `cle`="0" WHERE `pseudo`=\'' . $login . '\'');
				$requete=  $bdd->query('DELETE FROM `1415he201151`.`activations` WHERE `userid`=\'' . $donnee['userid'] . '\'');
			}
			//si new pass admin
			if($actif == '10'){
				echo "Votre nouvelle adresse mail a bien été validée !";
				$_SESSION = array();
				$_SESSION['login']='ok';
				$_SESSION['isadmin']='ok';
				$_SESSION['mail']=$donnee['usermail'];
				$_SESSION['id']=$donnee['userid'];
				$requete=  $bdd->query('UPDATE `1415he201151`.`user_profil` SET `profil_id`="1" WHERE `user_id`=\'' . $donnee['userid'] . '\'');
				//$requete=  $bdd->query('UPDATE `1415he201151`.`tbuser` SET `cle`="0" WHERE `pseudo`=\'' . $login . '\'');
				$requete=  $bdd->query('DELETE FROM `1415he201151`.`activations` WHERE `userid`=\'' . $donnee['userid'] . '\'');
				header("Refresh:3; url=connexion.php");
			}
			//si new pass user
			if($actif == '5'){
				echo "Votre nouvelle adresse mail a bien été validée !";
				$_SESSION = array();
				$_SESSION['login']='ok';
				$_SESSION['mail']=$donnee['usermail'];
				$_SESSION['id']=$donnee['userid'];
				$requete=  $bdd->query('UPDATE `1415he201151`.`user_profil` SET `profil_id`="3" WHERE `user_id`=\'' . $donnee['userid'] . '\'');
				//$requete=  $bdd->query('UPDATE `1415he201151`.`tbuser` SET `cle`="0" WHERE `pseudo`=\'' . $login . '\'');
				$requete=  $bdd->query('DELETE FROM `1415he201151`.`activations` WHERE `userid`=\'' . $donnee['userid'] . '\'');
				header("Refresh:3; url=connexion.php");
			}


		}
		else{
			echo "Erreur ! Votre compte ne peut être activé...";
		}
	}
	$requete->closeCursor();	
}

function initMdp(){
	$bdd = coBdd();
	$cle = $_GET['cle'];
	/*
	$requete = $bdd->query('SELECT pseudo, userpwd, usermail, question, reponse FROM tbuser WHERE (cle)= \'' . $cle . '\'');
	$donnee = $requete->fetch(); */

	$requete1 = $bdd->query('SELECT userid, activationCode FROM activations WHERE (activationCode)= \'' . $cle . '\'');
	$donnee1 = $requete1->fetch();

	$requete = $bdd->query('SELECT pseudo, userpwd, usermail, question, reponse FROM tbuser WHERE (userid)= \'' . $donnee1['userid'] . '\'');
	$donnee = $requete->fetch();

	if($cle == $donnee1['activationCode']){
		echo '	<form method="post" action="MonSite/validation.php?cle='.urlencode($cle).'">
				<label for="pseudo"> Pseudo :</label>';
		if (!empty($_POST['pseudo'])){
	        $pseudo=$_POST['pseudo'];
	        echo "<input type='text' name='pseudo' id='pseudo' value='$pseudo' size='30' maxlength='15'  required /></br></br>";
	    }
	    else{
	    	echo '<input type="text" name="pseudo" id="pseudo"  size="15" maxlength"30" required/> </br></br>';
	    }
				
		echo $donnee['question'];
		echo '	</br>
				

				<label for="reponse"> Réponse :</label> 
				<input type="text" name="reponse" id="reponse"  size="50" maxlength"50" required/> </br>
				</br>
				<input type="submit" value="Valider"/>';
	}
	else{
		echo "Erreur !";
	}
	$requete->closeCursor();
}

function newPass(){
	if ($_POST['newpass'] == $_POST['confpass']) {
		$bdd = coBdd();
		$mdp = sha1($_POST['newpass']);
		$id = $_SESSION['id'];
		$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `userpwd`='$mdp' WHERE `userid`='$id' ");
		echo '<div class="wrong_log"> Mot de passe modifié </div> ';
	}
	else{
		echo '<div class="wrong_log">  Erreur lors du changement de mot de passe: les deux mots de passe ne correspondent pas. </div>';
		$_GET['action'] = 'modifier';
	}
	$requete->closeCursor();
}

function afficheNewPass(){
	$cle = $_GET['cle'];
	echo '<h2>Réinitialiser le mot de passe</h2>
		<div class="instruction">
		<p> Veuillez entrer un nouveau mot de passe : </p></div>
		<div class="modif">
		<form method="post" action="validation.php?cle='.urlencode($cle).'">
		<label for="pass">Nouveau mot de passe :</label> 
		<input type="password" name="newpass" id="newpass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15" required/> </br>';
	echo '<label for="pass">Confirmer :</label> 
		<input type="password" name="confpass" id="confpass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15" required/> </br>';
	echo '<input type="submit" value="Valider"/> </div>';
}

function newMail(){
	if (!empty($_POST['mail'])){
		if ($_POST['mail']==$_POST['confmail']) {
			$bdd = coBdd();
			$mail = $_POST['mail'];
			$requete = $bdd->query('SELECT pseudo, userpwd, usermail FROM tbuser WHERE (usermail)= \'' . $_POST['mail'] . '\'');
			$donnee = $requete->fetch();
			if ($donnee['usermail'] == $mail){
				echo '<div class="wrong_log"> Adresse email existante </div></br>';		
				$_GET['action'] = 'modifier';
			}
			else{
				$id = $_SESSION['id'];
				$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `usermail`='$mail' WHERE `userid`='$id' ");
				if (!isset($_SESSION['isadmin'])){
					$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='5' WHERE `user_id`='$id' ");
					//echo '<div class="wrong_log"> E-mail modifié, votre profil est en cours de réactivation </div> ';

				}
				else{
					$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='10' WHERE `user_id`='$id' ");
				}
				//else{
					echo '<div class="wrong_log"> E-mail modifié, veuillez consulter votre boîte mail pour le valider</div> ';
					$requete = $bdd->query("SELECT pseudo, userpwd, usermail FROM tbuser WHERE (usermail)= '$mail'");
					$_SESSION['newmail']='nok';
					$donnee = $requete->fetch();
					$pseudo = $donnee['pseudo'];
					$cle = md5(microtime(TRUE)*100000);
					$destinataire = $mail;
					$sujet = "Changement d'adresse mail" ;
					$entete = "From: changement_Mail@MonSite.com" ;
					$message = 'Bonjour ' .$pseudo.',
 
					Pour activer votre nouvelle adresse mail, veuillez cliquer sur le lien ci dessous
					ou copier/coller dans votre navigateur internet.
					 
					http://193.190.65.94/he201151/TRAV/5_site_core/validation.php?log='.urlencode($pseudo).'&cle='.urlencode($cle).'
					 
					 
					---------------
					Ceci est un mail automatique, Merci de ne pas y répondre.';
					 
					
					mail($destinataire, $sujet, $message, $entete) ;
					//$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='10' WHERE `user_id`='$id' ");
					//$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `etat`='33' WHERE `userid`='$id' ");
					//$requete=  $bdd->query("INSERT INTO `1415he201151`.`activations` SET `activationCode`='$cle' WHERE `userid`='$id' ");
					$sql2 ="INSERT INTO `1415he201151`.`activations` (`userid`, `activationCode`) 
											VALUES('$id', '$cle')";
					$bdd->exec($sql2);
				//}
			}
		}
		else{
			echo "<div class='wrong_log'>Erreur lors de la modification de l'adresse mail: les deux adresses mail ne correspondent pas.</div> ";
			$_GET['action'] = 'modifier';
		}
	}
}

function completeProfil(){
	$bdd = coBdd();
	$id = $_SESSION['id'];
	$reponse = sha1($_POST['reponse']);
	$question =  $_POST['question'];
	$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `question`='$question' WHERE `userid`='$id' ");
	$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `reponse`='$reponse' WHERE `userid`='$id' ");
}

function verifProfil(){
	if (isset($_SESSION['login'])) {
		if(!isset($_SESSION['isadmin'])){
			if(!isset($_SESSION['profilComplet'])) {
				echo "<p>
						<div class ='instruction'>Votre profil n'est pas complet, vous pouvez le compléter <a href='connexion.php'>ici</a>.</div></p>";
			}
		}
	}
}

function menuAdmin(){
	echo '<ul><li><a href="Administration.php?action=Users">Gérer Users</a></li> ';
	echo '<li><a href="Administration.php?action=Messages">Messages</a></li>';
	echo '<li><a href="Administration.php?action=Config">Configuration</a></li>';
	echo '</ul>';
}

function afficheTbUser(){
	$bdd = coBdd();
	$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id ');
	echo '<table>';
		echo '<tr>';
			echo '<td>Id : </td> <td>Pseudo : </td> <td>mail : </td> <td>Statut : </td> '; 
		echo '</tr>';
		while ($donnees = $requete->fetch()){
			echo '<tr>';
			echo '<td> '.$donnees['userid'].' </td> <td> '.$donnees['pseudo'].' </td> 
				<td> '.$donnees['usermail'].' </td> <td> '.$donnees['profil_id'].' </td>';
				if ($donnees['profil_id']==1) {
					//echo 'Admin';
				}
				else{
					echo '<td> <a href="Administration.php?action=Modifier&id='.$donnees['userid'].'&profil_id='.$donnees['profil_id'].'">Modifier</a> </td> ';
					echo '<td> <a href="Administration.php?action=messageUser&id='.$donnees['userid'].'">Voir les messages</a></td> ';
				}
				


			echo '</tr>';
		}
	echo '</table>';
	$requete->closeCursor();
}

function formRecherche(){
	echo ' </br><h4> Recherche : </h4>';
	echo '<form method="post" action="Administration.php?action=Users">';
	echo "

		<label for='rechLogin'>Login : </label>
		<input type='text' name='rechLogin' id='rechLogin' size='30' maxlength='15' />
		<br/>
		<label for='rechMail'>Mail : </label>
		<input type='email' name='rechMail' id='rechMail' size='30' maxlength='55' />
		<br/>
		<label for='rechStatut'>Statut : </label>
		<input type='text' name='rechStatut' id='rechStatut' size='30' maxlength='15' />
		<br/>";

		echo '<p> <input type="submit" value="Valider"/> ';
}

function recherche(){
	$bdd = coBdd();
	/*
	if (!empty($_POST['rechLogin'])) {
		if (!empty($_POST['rechMail'])) {
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'
										AND tbuser.usermail =\''.$_POST['rechMail'].'\'
										AND user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'
										AND tbuser.usermail =\''.$_POST['rechMail'].'\'');
			}
		}
		else{
			$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
									ON tbuser.userid=user_profil.user_id 
									AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'');
		}
	}
	else{
		if (!empty($_POST['rechMail'])) {
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND tbuser.usermail =\''.$_POST['rechMail'].'\'
										AND  user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND tbuser.usermail =\''.$_POST['rechMail'].'\'');
			}
		}
		else{
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND  user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id ');
			}
		}		
	}*/
	if (!empty($_POST['rechLogin'])) {
		if (!empty($_POST['rechMail'])) {
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
									ON tbuser.userid=user_profil.user_id 
									AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'
									AND tbuser.usermail =\''.$_POST['rechMail'].'\'
									AND  user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
									ON tbuser.userid=user_profil.user_id 
									AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'
									AND tbuser.usermail =\''.$_POST['rechMail'].'\'');
			}
		}
		else{
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
									ON tbuser.userid=user_profil.user_id 
									AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'
									AND user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
											ON tbuser.userid=user_profil.user_id 
											AND tbuser.pseudo =\''.$_POST['rechLogin'].'\'');
			}
		}
	}
	else{
		if (!empty($_POST['rechMail'])) {
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
									ON tbuser.userid=user_profil.user_id 
									AND user_profil.profil_id =\''.$_POST['rechStatut'].'\'
									AND tbuser.usermail =\''.$_POST['rechMail'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
											ON tbuser.userid=user_profil.user_id 
											AND tbuser.usermail =\''.$_POST['rechMail'].'\'');
			}
		}
		else{
			if (!empty($_POST['rechStatut'])) {
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
										ON tbuser.userid=user_profil.user_id 
										AND  user_profil.profil_id =\''.$_POST['rechStatut'].'\'');
			}
			else{
				$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id ');				
			}			
		}
	}
	

	
	echo '<table>';
		echo '<tr>';
			echo '<td>Id : </td> <td>Pseudo : </td> <td>mail : </td> <td>Statut : </td> '; 
		echo '</tr>';
		while ($donnees = $requete->fetch()){
			echo '<tr>';
			echo '<td> '.$donnees['userid'].' </td> <td> '.$donnees['pseudo'].' </td> 
				<td> '.$donnees['usermail'].' </td> <td> '.$donnees['profil_id'].' </td> ';
				if ($donnees['profil_id']==1) {
					//echo 'Admin';
				}
				else{
					echo '<td> <a href="Administration.php?action=Modifier&id='.$donnees['userid'].'&profil_id='.$donnees['profil_id'].'">Modifier</a> </td> ';
					echo '<td> <a href="Administration.php?action=messageUser&id='.$donnees['userid'].'">Voir les messages</a></td> ';
				}
			echo '</tr>';
		}
	echo '</table>';
	$requete->closeCursor();
}


function modifUSer(){
	$bdd=coBdd();
	$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
								ON tbuser.userid=user_profil.user_id 
								AND tbuser.userid =\''.$_GET['id'].'\'
								AND user_profil.profil_id=\''.$_GET['profil_id'].'\'');

	echo '<table>';
		echo '<tr>';
			echo '<td>Id : </td> <td>Pseudo : </td> <td>mail : </td> <td>Statut : </td> '; 
		echo '</tr>';
		while ($donnees = $requete->fetch()){
			echo '<tr>';
			echo '<td> '.$donnees['userid'].' </td> <td> '.$donnees['pseudo'].' </td> 
				<td> '.$donnees['usermail'].' </td> 

				<td> 
				<form method="post" action="Administration.php?action=Users&id='.$donnees['userid'].'&profil_id='.$donnees['profil_id'].'">
				<input type="text" name="profil_id" id="profil_id" value="'.$donnees['profil_id'].'"" size="2" maxlength="2"  required /> 
				

				</td> 
		
				
		

				 ';
			echo '</tr>';
		}
	echo '</table>
			<label for="pass">Votre mot de passe :</label> 
				<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
				</br>';

	echo '<input type="submit" value="Valider"/>';
	$requete->closeCursor();

}

function sendMail(){
	$bdd=coBdd();
	$id=$_GET['id'];
	$requete = $bdd->query('SELECT * FROM tbuser INNER JOIN user_profil 
								ON tbuser.userid=user_profil.user_id 
								AND tbuser.userid =\''.$_GET['id'].'\'
								AND user_profil.profil_id=\''.$_POST['profil_id'].'\'');
	//$requete = $bdd->query("SELECT * FROM tbuser WHERE userid='$id'");
	$donnee = $requete->fetch();
	$statut = checkStatut($donnee['profil_id']);
	$pseudo = $donnee['pseudo']; 
	$destinataire = $donnee['usermail'];
	$sujet = "Changement de statut" ;
	$entete = "From: statut@MonSite.com" ;
	$message = 'Bonjour ' .$pseudo.',

	Votre statut a été modifié.

	Votre statut est dorénavant : '.$statut.'.
	 
	 
	---------------
	Ceci est un mail automatique, Merci de ne pas y répondre.';
	 

	mail($destinataire, $sujet, $message, $entete) ;
	$requete->closeCursor();
	
}

function voirMsgUser(){
	$bdd=coBdd();
	$id=$_GET['id'];
	$requete = $bdd->query("SELECT * FROM tbmessages WHERE userid='$id'");
	echo'</br>';
	echo '<table>';
		echo '<tr>';
			echo '<td>Id message : </td> <td>Sujet : </td> <td>Mail : </td> <td>Message : </td> <td>Id parent : </td> '; 
		echo '</tr>';
		while ($donnees = $requete->fetch()){
			echo '<tr>';
			echo '<td> '.$donnees['mesid'].' </td> <td> '.$donnees['messujet'].' </td> <td> '.$donnees['mail'].' </td> 
				<td> '.$donnees['mestextes'].' </td> <td> '.$donnees['mesparentid'].' </td> 

				<td> <a href="Administration.php?action=repondre&id='.$_GET['id'].'&msgid='.$donnees['mesid'].'">Répondre</a> </td> ';

			echo '</tr>';
		}
	echo '</table>';
	$requete->closeCursor();
}

function repondreMsg(){
	$bdd=coBdd();
	$msgid = $_GET['msgid'];
	$requete = $bdd->query("SELECT * FROM tbmessages WHERE mesid='$msgid'");
	$donnee = $requete->fetch();
	echo'
		<form method="post" action="Administration.php?mail='.$donnee['mail'].'&id='.$msgid.'">
		<p>
			<label for="sujet">Sujet : </label>
			<input type="text" name="sujet" id="sujet" value="Re '.$donnee['messujet'].'"  size="30" maxlength="50" required />
			<br/>';

	echo'			
			</br>
			<label for="reponse">Content : </label>
			<textarea name="reponse" rows="5" cols="40" required></textarea>
			</br>
			<span class="marge"><input type="submit" value="Répondre"/></span>
		</p>
		</form>';
}

function afficherTbMsg($param){
	$bdd = coBdd();
	if ($param==0) {
		$requete = $bdd->query('SELECT * FROM tbmessages');
	}
	if ($param==1) {
		$requete = $bdd->query('SELECT * FROM tbmessages ORDER BY mesid DESC');
	}
	if ($param==2) {
		$requete = $bdd->query('SELECT * FROM tbmessages ORDER BY repondu ASC');
	} 
	if ($param==3) {
		$requete = $bdd->query('SELECT * FROM tbmessages ORDER BY userid DESC');
	} 
	
	echo '</br>';
	echo '<table>';
		echo '<tr>';
			echo '<td>
					<a href="Administration.php?action=Messages&tri=recent">Id message : </a> </td>
				 <td>Sujet : </td> <td>Mail : </td> <td>Message :
				 </td> 

				 <td>
				 <a href="Administration.php?action=Messages&tri=anonyme">User Id : </a> </td>

				 <td>Id parent : </td> 
				 <td>
				 <a href="Administration.php?action=Messages&tri=reponse">Répondu : </a> </td>
				 '; 
		echo '</tr>';
		while ($donnees = $requete->fetch()){
			echo '<tr>';
			echo '<td> '.$donnees['mesid'].' </td> <td> '.$donnees['messujet'].' </td> <td> '.$donnees['mail'].' </td> 
				<td> '.$donnees['mestextes'].' </td> <td> '.$donnees['userid'].' </td> <td> '.$donnees['mesparentid'].' </td>  ';

			if ($donnees['repondu']==1) {
				echo '<td>oui </td>';
			}
			else{
				echo '<td>non </td>
					<td> <a href="Administration.php?action=repondre&id='.$donnees['userid'].'&msgid='.$donnees['mesid'].'">Répondre</a> </td>';
			}
				//<td> <a href="Administration.php?action=repondre&id='.$_GET['id'].'&msgid='.$donnees['mesid'].'">Répondre</a> </td> ';

			echo '</tr>';
		}
	echo '</table>';
	$requete->closeCursor();
}

function modifConfig(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);


	echo '</br>';

	echo'<form method="post" action="Administration.php?action=modifConfig" enctype="multipart/form-data">';
	echo'<div id="formConf">';

	$titre = $ini['TitreSite']['titre']; 

	echo '<label for="titr">Titre : </label>';
	echo '<input type="text" name="titr" id="titr" size="30" maxlength="15" required value="'.$titre.'" /> </br>';
	echo '</br>';

	$hostName = $ini['BaseDeDonnee']['hostNameLocal'];
	$dbName= $ini['BaseDeDonnee']['DBName'];
	$userName = $ini['BaseDeDonnee']['LoginBDD'];
	$passName = $ini['BaseDeDonnee']['MotDePassBDD'];  
/*

	echo '<label for="hostName">Hôte local : </label>';
	echo '<input type="text" name="hostName" id="hostName" size="30" maxlength="15" required value="'.$hostName.'" /></br>';
	echo '<label for="dbName">Nom bdd : </label>';
	echo '<input type="text" name="dbName" id="dbName" size="30" maxlength="15" required value="'.$dbName.'" /></br>';
	echo '<label for="userName">Login : </label>';
	echo '<input type="text" name="userName" id="userName" size="30" maxlength="15" required value="'.$userName.'" /></br>';
	echo '<label for="passName">Mot de passe : </label>';
	echo '<input type="text" name="passName" id="passName" size="30" maxlength="15" required value="'.$passName.'" /></br>';
	echo '</br>'; */

	$source = $ini['Logo']['source'];
	$width = $ini['Logo']['width'];
	$height = $ini['Logo']['height'];

	/*echo '<label for="source">Source du logo : </label>';
	echo '<input type="text" name="source" id="source" size="30" maxlength="15" required value="'.$source.'" /></br>';*/

	echo '<label for="logo">Logo : </label>';
	echo '<input type="file" name="logo" /></br>';

	echo '<label for="width">Largeur du logo : </label>';
	echo '<input type="text" name="width" id="width" size="30" maxlength="15" required value="'.$width.'" /></br>';
	echo '<label for="height">Hauteur du logo : </label>';
	echo '<input type="text" name="height" id="height" size="30" maxlength="15" required value="'.$height.'" /></br>';	
	echo '</br>';

	$title = $ini['Title']['title'];
	echo '<label for="title">Titre onglet : </label>';	
	echo '<input type="text" name="title" id="title" size="30" maxlength="15" required value="'.$title.'" /></br>';
	echo '</br>';

	$nom = $ini['Gestionnaire']['Nom'];
	$prenom = $ini['Gestionnaire']['Prenom'];
	$tel = $ini['Gestionnaire']['tel'];
	$mail = $ini['Gestionnaire']['mail'];

	echo '<label for="nom">Nom du Gestionnaire : </label>';
	echo '<input type="text" name="nom" id="nom" size="30" maxlength="15" required value="'.$nom.'" /></br>';
	echo '<label for="prenom">Prénom Gestionnaire : </label>';
	echo '<input type="text" name="prenom" id="prenom" size="30" maxlength="15" required value="'.$prenom.'" /></br>';
	echo '<label for="telephone">Telephone : </label>';
	echo '<input type="text" name="tel" id="tel" size="30" maxlength="15" required value="'.$tel.'" /></br>';
	echo '<label for="mail">Mail : </label>';
	echo '<input type="text" name="mail" id="mail" size="30" maxlength="15" required value="'.$mail.'" /></br>';
	echo '</br>';

	$width = $ini['Avatar']['width'];
	$height = $ini['Avatar']['height'];

	echo '<label for="widthA">Largeur Avatar : </label>';
	echo '<input type="text" name="widthA" id="widthA" size="30" maxlength="15" required value="'.$width.'" /></br>';
	echo '<label for="heightA">Hauteur Avatar : </label>';	
	echo '<input type="text" name="heightA" id="heightA" size="30" maxlength="15" required value="'.$height.'" /></br>';
	echo '</br>';

	$minimum = $ini['Login']['minimum'];
	echo '<label for="minimum">Car. max pour pseudo : </label>';	
	echo '<input type="text" name="minimum" id="minimum" size="30" maxlength="15" required value="'.$minimum.'" /></br>';	
	echo '</br>';

	echo '	<label for="pass">Votre mot de passe :</label> 
				<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
				</br>';
	echo '</div>';

	echo '<input type="submit" value="Valider"/>';
	echo '</form>';


}

function valideConfig(){
	$ini_path = 'conf.ini.php';
	$ini1 = parse_ini_file($ini_path, true);

	if (!empty($_FILES['logo']['name'])) {
		$dossier = 'img/';
		$fichier1 = basename($_FILES['logo']['name']);
		$taille_maxi = 100000;
		$taille = filesize($_FILES['logo']['tmp_name']);
		$extensions = array('.png', '.gif', '.jpg', '.jpeg');
		$extension = strrchr($_FILES['logo']['name'], '.'); 
		//Début des vérifications de sécurité...
		if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
		{
		     $erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg, txt ou doc...';
		}
		if($taille>$taille_maxi)
		{
		     $erreur = 'Le fichier est trop gros...';
		}
		if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
		{
		     //On formate le nom du fichier ici...
		     $fichier1 = strtr($fichier1, 
		          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
		          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		     $fichier1 = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier1);
		     //$fichier = $_SESSION['id'].$extension;
		     if(move_uploaded_file($_FILES['logo']['tmp_name'], $dossier . $fichier1)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
		     {
		          echo 'Upload effectué avec succès !';
		          $ini['Logo']['source'] =$dossier . $fichier1;
		     }
		     else //Sinon (la fonction renvoie FALSE).
		     {
		          echo 'Echec de l\'upload !';
		     }
		}
		else
		{
		      echo 'Echec de l\'upload !';
		}
	}
	else{
	     $ini['Logo']['source'] =$ini1['Logo']['source'] ;
	}

	$fichier = fopen('conf.ini.php', 'w');




	$ini['TitreSite']['titre'] = $_POST['titr'];

	$ini['BaseDeDonnee']['hostNameLocal']=$ini1['BaseDeDonnee']['hostNameLocal'];
	$ini['BaseDeDonnee']['DBName']=$ini1['BaseDeDonnee']['DBName'];
	$ini['BaseDeDonnee']['LoginBDD']=$ini1['BaseDeDonnee']['LoginBDD'];
	$ini['BaseDeDonnee']['MotDePassBDD']=$ini1['BaseDeDonnee']['MotDePassBDD'];  

	
	$ini['Logo']['width'] = $_POST['width'];
	$ini['Logo']['height'] = $_POST['height'];

	$ini['Title']['title'] = $_POST['title'];

	$ini['Gestionnaire']['Nom'] = $_POST['nom'];
	$ini['Gestionnaire']['Prenom'] = $_POST['prenom'];
	$ini['Gestionnaire']['tel'] = $_POST['tel'];
	$ini['Gestionnaire']['mail'] = $_POST['mail'];

	$ini['Avatar']['width'] = $_POST['widthA'];
	$ini['Avatar']['height'] = $_POST['heightA'];

	$ini['Login']['minimum'] = $_POST['minimum'];




	$newConfig = ';<?php echo "vous n\'êtes pas autorisé à voir ce contenu"; exit;?>'."\n";
	foreach($ini as $key => $value){
	        $newConfig .= '['.$key.']'."\n";
	        foreach($value as $nom =>$valeur){
	                $newConfig .= "$nom = $valeur"."\n";
	        }
	}
	fputs($fichier, $newConfig);

	fclose($fichier);
	echo 'Modification réussie';
	header("Refresh:2; url=Administration.php?action=Config");

}

function afficheConfig(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);


	echo '</br>';

	$titre = $ini['TitreSite']['titre']; 
	echo "Titre du site :  ".$titre."</br>";
	echo '</br>';

	$hostName = $ini['BaseDeDonnee']['hostNameLocal'];
	$dbName= $ini['BaseDeDonnee']['DBName'];
	$userName = $ini['BaseDeDonnee']['LoginBDD'];
	$passName = $ini['BaseDeDonnee']['MotDePassBDD'];  

	echo 'Hôte local : '.$hostName.' </br>';
	echo 'Nom de la base de données : '.$dbName.' </br>';
	echo 'Login : '.$userName.' </br>';
	echo 'Mot de passe : '.$passName.' </br>';
	echo '</br>';

	$source = $ini['Logo']['source'];
	$width = $ini['Logo']['width'];
	$height = $ini['Logo']['height'];

	echo 'Source du logo : '.$source.' </br>';
	echo 'Largeur du logo : '.$width.' </br>';
	echo 'Hauteur du logo : '.$height.' </br>';	
	echo '</br>';

	$title = $ini['Title']['title'];
	echo 'Titre onglet: '.$title.' </br>';	
	echo '</br>';

	$nom = $ini['Gestionnaire']['Nom'];
	$prenom = $ini['Gestionnaire']['Prenom'];
	$tel = $ini['Gestionnaire']['tel'];
	$mail = $ini['Gestionnaire']['mail'];
	echo 'Nom du Gestionnaire : '.$nom.' </br>';
	echo 'Prénom du Gestionnaire : '.$prenom.' </br>';
	echo 'Telephone : '.$tel.' </br>';
	echo 'Mail : '.$mail.' </br>';
	echo '</br>';

	$width = $ini['Avatar']['width'];
	$height = $ini['Avatar']['height'];
	echo 'Largeur Avatar : '.$width.' </br>';
	echo 'Hauteur Avatar : '.$height.' </br>';	
	echo '</br>';

	$minimum = $ini['Login']['minimum'];
	echo 'Nombre de caractères minimum pour le login: '.$minimum.' </br>';	
	echo '</br>';


	echo '<a href="Administration.php?action=modifConfig">Modifier</a>';
}

function afficheFormContact(){
	echo'
	<form method="post" action="contact.php">
    <p>
		<label for="sujet">Sujet : </label>
		<input type="text" name="sujet" id="sujet" placeholder="" size="30" maxlength="30" required />
		<br/>';
		
		if(!isset($_SESSION['login'])){	
			echo '<label for="mail">E-mail : </label>';
			echo '<input type="email" name="mail" id="mail" placeholder="exemple@mail.com" size="30" maxlength="50" required />';
			echo '<br/>';
			}
		echo '

		</br>
		<label for="content">Content : </label>
		<textarea name="content" rows="5" cols="40" required></textarea>
		</br>
		<span class="marge"><input type="submit" value="Envoyer"/></span>
    </p>
	</form>';
}


function checkStatut($profil_id){
	if ($profil_id==1) {
		return "Admin";
	}
	if ($profil_id==2) {
		return "sous-dmin";
	}
	if ($profil_id==3) {
		return "user normal";
	}
	if ($profil_id==4) {
		return "en cours d'activation";
	}
	if ($profil_id==5) {
		return "en cours de reactivation";
	}
	if ($profil_id==6) {
		return "en demande de mot de passe";
	}
	if ($profil_id==7) {
		return "user gelé";
	}
	if ($profil_id==8) {
		return "user désinscrit";
	}
	if ($profil_id==9) {
		return "user banni";
	}
	if ($profil_id==10) {
		return "admin change de mail";
	}

}



function coBdd(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);
	$dbName= $ini['BaseDeDonnee']['DBName'];
	$userName = $ini['BaseDeDonnee']['LoginBDD'];
	$passName = $ini['BaseDeDonnee']['MotDePassBDD'];   
	try{
		//echo "<script>alert('Avant essai de connexion')</script>";
		$bdd = new PDO('mysql:host='.$ini['BaseDeDonnee']['hostNameLocal'].';
		dbname='.$dbName.';', $userName, $passName);
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $bdd;

	}
	catch (Exception $e){
		//echo "<script>alert('Erreur de connexion')</script>";
		die('Erreur : '. $e->getMessage());
	}
}



function afficheLogo(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);
	$source = $ini['Logo']['source'];
	$width = $ini['Logo']['width'];
	$height = $ini['Logo']['height'];
	echo '<img src="'.$source.'" width="'.$width.'" height="'.$height.'"/> ';
}

function afficheTitre(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);
	$titre = $ini['TitreSite']['titre'];
	echo' <h1> '.$titre.' </h1>';
}

function afficheTitle(){
	$ini_path = 'conf.ini.php';
	$ini = parse_ini_file($ini_path, true);
	$title = $ini['Title']['title'];

	echo' <title> '.$title.' </title>';
}


function setSession(){
	session_name("HE201151");
	session_start();
}

function myPrint($param){
	return '<pre>'.print_r($param,1).'<pre>';
}

?>
</html>
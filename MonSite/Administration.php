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
	

<div id="Administration">
		<h2>Administration</h2>

<?php
	$bdd=coBdd();

	if(isset($_SESSION['isadmin'])){
		echo "Bienvenue sur l'administration </br>";

		menuAdmin();
		
		if (!empty($_POST['reponse'])) {
			$msgid=$_GET['id'];
			$ini_path = 'conf.ini.php';
			$ini = parse_ini_file($ini_path, true);
			$mail= $ini['Gestionnaire']['mail'];
			$sujet = $_POST['sujet'];
			$message = $_POST['reponse'];
			$entete = 'From: '.$mail;
			$destinataire = $_GET['mail'];
			$mysqldate = date("Y-m-d H:i:s",time()); 
			mail($destinataire, $sujet, $message, $entete) ;
			$requete=  $bdd->query("UPDATE `1415he201151`.`tbmessages` SET `repondu`=1 WHERE `mesid`='$msgid' ");
			$requete=  $bdd->query("SELECT * FROM tbmessages WHERE `mesid`='$msgid' ");
			$donnee = $requete->fetch();
			$id=$_SESSION['id'];
			$destId=$donnee['userid'];
			if ($donnee['userid']!=0) {
				$requete=$bdd->prepare('INSERT INTO tbmessages SET messujet=?, mail=?, mestextes=? , userid=?, mesparentid=?, repondu=0, destId=?, msgDateCrea=? ');

				$requete->execute(array($sujet,$mail,$message,$id,$msgid,$destId,$mysqldate));
			}
			echo "Message envoyé";
			header("Refresh:2; url=Administration.php?action=Messages");
		}

		$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'';
		switch($action){
			case "Users":
				if (!empty($_GET['id'])) {
					$requete=  $bdd->query("SELECT userpwd FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id AND user_profil.profil_id=1 ");
					$donnee = $requete->fetch();
					$password = hash('sha256', $_POST['pass']);
					if ($donnee['userpwd']==$password) {
						$pid = $_POST['profil_id'];
						$id = $_GET['id'];
						$ppid = $_GET['profil_id'];
						if (($pid>1)&&($pid<10)) {
							$requete=  $bdd->query("UPDATE `1415he201151`.`user_profil` SET `profil_id`='$pid' WHERE `user_id`='$id' AND `profil_id`='$ppid' ");
							echo '</br>profil modifié';
							sendMail($bdd);
						}
						else{
							echo '<div class="wrong_log">Vous ne pouvez pas élever un autre utilisateur au rang "Admin", veuillez choisir un rang entre 2 et 9 compris</div>';
						}
					}
					else{
						echo '<div class="wrong_log">Mot de passe inccorrect</div>';
					}
					$requete->closeCursor();
				}
				formRecherche();
				
				if (!empty($_POST)) {
					recherche($bdd);
				}
				else{
					afficheTbUser($bdd);
				}
				break;

			case 'Modifier':
				modifUser($bdd);
				break;

			case 'messageUser':
				voirMsgUser($bdd);
				break;

			case 'repondre':
				repondreMsg($bdd);
				break;

			case "Messages":
				$tri = isset($_GET['tri'])?htmlspecialchars($_GET['tri']):'';
				if ($tri=="recent") {
					afficherTbMsg(1,$bdd);
				}
				if ($tri=="reponse") {
					afficherTbMsg(2,$bdd);
				}							
				if ($tri=="anonyme") {
					afficherTbMsg(3,$bdd);
				}							
				if ($tri=="") {
					afficherTbMsg(0,$bdd);
				}			
				break;

			case "Config":
				afficheConfig();
				break;
			case "modifConfig":
				if (empty($_POST)) {
					modifConfig();
				}
				else{
					
					$requete=  $bdd->query("SELECT userpwd FROM tbuser INNER JOIN user_profil ON tbuser.userid=user_profil.user_id AND user_profil.profil_id=1 ");
					$donnee = $requete->fetch();
					if ($donnee['userpwd']==sha1($_POST['pass'])) {
						valideConfig($bdd);
					}
					else{
						echo '<div class="wrong_log">Mot de passe inccorrect</div>';
						modifConfig();
					}
					$requete->closeCursor();
				}
				
				break;

		}
	}
	else{
		echo "<div class=wrong_log>Vous n'êtes pas autorisé à accéder à cette page, seul l'Admin peut voir le contenu de cette page</div>";
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
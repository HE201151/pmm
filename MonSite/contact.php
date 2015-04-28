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

<?php

if($_POST) {
	if(!isset($_SESSION['login'])){	
		$to = $_POST['mail'];
		$userid = 0;
	}
	else{
		$to = $_SESSION['mail'];
		$userid = $_SESSION['id'];
	}
$sujet = 'Formulaire de contact envoyé';
$content = $_POST['content'];
$message = 'votre mail avec comme sujet \''.$_POST['sujet'].'\' a bien été envoyé, 
			
			Message:

			' .$content.'  ';	


mail($to ,$sujet, $message, 'From: Romain Blan <romain.blan@hotmail.fr>') ;

$psujet = $_POST['sujet'];
$bdd=coBdd();
	
	
	/*$sql ="INSERT INTO `1415he201151`.`tbmessages` (`messujet`, `mail`, `mestextes`, `userid`, `mesparentid`) 
								VALUES('$psujet', '$to', '$content', '$userid', '0')";
	$bdd->exec($sql);*/


	$bdd->exec('insert into tbmessages (messujet,mail,mestextes,userid) values ("'.$psujet.'","'.$to.'","'.$content.'",'.$userid.');');



	echo 'Message envoyé';
}

?>
	

<div id="Contact">


<h2>Contact</h2>


<?php
	afficheFormContact();
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
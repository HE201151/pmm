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
	

<div id="Inscription">


<h2>Inscription</h2>

<div class="wrong_log">
<?php
//$check=999999;
$check=signup();

?>
</div>
<?php 
if (!isset($_SESSION['login'])) {
	if($check == 0 ){
		if (!empty($_POST)) {
			$pseudo = $_POST['pseudo'];
			$mail = $_POST['mail'];
			$confmail = $_POST['confMail'];
		}
		echo" <form method='post' action='inscription.php'>
			    <p>
					<label for='pseudo'>Pseudo* : </label>"; 
					if(isset($_POST['pseudo'])){
						echo '<input type="text" name="pseudo" id="pseudo" size="30" maxlength="15" required value="'.$pseudo.'" />';
					}
					else {
						echo "<input type='text' name='pseudo' id='pseudo' size='30' maxlength='15' required  placeholder='Minimum 5 carcatères' />";
					}
					//echo "<div class='champOblig'>Minimum 5 caractères</div>";


			echo "<br/>
					<label for='pass'>Mot de passe* : </label>
					<input type='password' name='pass' id='pass' placeholder='&#9679;&#9679;&#9679;&#9679;&#9679;'' size='30' maxlength='15' required />
					<br/>
					<label for='confirm'>Confirmer* : </label>
					<input type='password' name='confirm' id='confirm' placeholder='&#9679;&#9679;&#9679;&#9679;&#9679;' size='30' maxlength='15' required />
					<br/>

					<label for='mail'>Adresse e-mail* : </label>";
					if(isset($_POST['mail'])){
						echo '<input type="email" name="mail" id="mail" size="30" maxlength="50" required  value="'.$mail.'" />';
					}
					else{
						echo "<input type='email' name='mail' id='mail' placeholder='exemple@mail.com' size='30' maxlength='50' required /> ";
					}

			echo "<br/>
					<label for='mail'>Confirmer mail* : </label>";
					if(isset($_POST['confMail'])){
						echo '<input type="email" name="confMail" id="confMail" placeholder="exemple@mail.com" size="30" maxlength="50" required value="'.$confmail.'" />';
					}
					else{
						echo "<input type='email' name='confMail' id='confMail' placeholder='exemple@mail.com' size='30' maxlength='50' required />";
					}
			echo "
					<div class='champOblig'> * Champs obligatoires </div>
					<br/>
					<input type='submit' value='Inscription'/>
			    </p>
			</form>";
	}
	if ($check==1) {
		echo "Inscription réussie, consultez votre boîte mail pour activer votre compte";
	}
}
else{
	echo "Vous êtes connecté, il n'est pas nécessaire de vous inscrire";

	verifProfil();
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
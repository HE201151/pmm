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
	$_SESSION = array();
	$resultat=creeMenu();

?>


</div>


<div class="sous_menu">
	

<div id="Connexion">


<h2>Déconnexion</h2>

Vous êtes déconnecté.



</div>
</div>
<footer>
<?php
	creeFooter();
?>
</footer>

</html>
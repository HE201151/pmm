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
	//include "maLib.php";
	setSession();
	$resultat=creeMenu();

?>


</div>
<div class="sous_menu">


<h2>Index</h2>


<?php
	echo 'Bienvenue sur le site ';
	//echo myPrint($_SESSION);
	verifProfil();
	//echo myPrint($_SERVER);
	//echo $_SERVER['SERVER_NAME'];
?>





</div>
<footer>
<?php
	creeFooter();
?>
</footer>
</body>

</html>
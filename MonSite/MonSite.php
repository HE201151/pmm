<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Mon site</title>
	<link rel="stylesheet" type="text/css" href="MonSite.css" media="all" />
	<script type="text/javascript" src="afficherCacher.js" .js></script>

</head>

<body>
<div id="titre">
	<h1> Mon Site </h1>
</div>

<div id="menu">


<span class="bouton" id="bouton_Accueil" onclick="javascript:afficherCacher('Accueil');">Accueil</span>
<span class="bouton" id="bouton_Connexion" onclick="javascript:afficherCacher('Connexion');">Connexion</span>
<span class="bouton" id="bouton_Inscription" onclick="javascript:afficherCacher('Inscription');">Inscription</span>


</div>


<div id="sous_menu">

<div id="Accueil">
<h2>Accueil</h2>
<p> Bienvenue sur mon site.</p>
</div>

<div id="Connexion">
<h2>Connexion</h2>
<form method="post" action="traitement.php">
    <p>
        <label for="pseudo">Votre pseudo :</label>
        <input type="text" name="pseudo" id="pseudo" placeholder="Ex : Zozor" size="30" maxlength="10"  />
		<br/>
		<label for="pass">Votre mot de passe :</label> 
		<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"10"  />
        <br/>
		<input type="submit" value="Envoyer" />
    </p>
</form>

</div>

<div id="Inscription">
<h2>Inscription</h2>
<form method="post" action="traitement.php">
    <p>
		<label for="pseudo">Pseudo : </label>
		<input type="text" name="pseudo" id="pseudo" placeholder="Ex : Zozor" size="30" maxlength="10"  />
		<br/>
		<label for="pass">Mot de passe : </label>
		<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"10"  />
		<br/>
		<label for="confirm">Confirmation du mot de passe: </label>
		<input type="password" name="confirm" id="confirm" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"10"  />
		<br/>
		<label for="mail">Adresse e-mail: </label>
		<input type="email" name="mail" id="mail" placeholder="exemple@mail.com" size"50" maxlength"50" />
		<br/>
		<input type="submit" value="M'inscrire"/>
    </p>
</form>
</div>
</div>

</body>

</html>
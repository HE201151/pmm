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
	//$_GET['action'] = 'consulter';

?>


</div>
<div class="sous_menu">

<?php
$bdd=coBdd();
	if(isset($_SESSION['login'])){
	//Connexion BDD
	
	$id = $_SESSION['id'];

	//Si on envoie un formulaire
	if (!empty($_POST['pseudo'])){
		//echo 'myPrint('.$_POST['mail'].')';
		//echo 'ça marche';
		$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.userdateinscription, u.profil_id FROM tbuser p, user_profil u WHERE (userid)= \'' . $_SESSION['id'] . '\' AND u.user_id=p.userid');

		//$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail, etat, userdateinscription FROM tbuser WHERE (userid)= \'' . $_SESSION['id'] . '\'');
		$donnee = $requete->fetch(); 
		//On vérifie le mdp
		$password = hash('sha256', $_POST['pass']);
		//$password=sha1($_POST['pass']);
		if ($password == $donnee['userpwd']) {
			$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail FROM tbuser WHERE (pseudo)= \'' . $_POST['pseudo'] . '\'');
			$donnee = $requete->fetch();
			//On vérifie que le pseudo n'est pas déjà utilisé
			$donnePseudo = strtolower($donnee['pseudo']);
			$postPseudo = strtolower($_POST['pseudo']);
			if ($donnePseudo == $postPseudo){
				//Si ce n'est pas le même identifiant (le pseudo est utilisé par un autre utilisateur)
				if ($donnee['userid'] != $_SESSION['id']){
					echo '<div class="wrong_log"> Pseudo existant </div> ';		
					$_GET['action'] = 'modifier';
				}
			}
			//Si le pseudo est libre
			else{
				$pseudo = $_POST['pseudo'];
				$requete=  $bdd->query("UPDATE `1415he201151`.`tbuser` SET `pseudo`='$pseudo' WHERE `userid`='$id'  ");
				echo '<div class="wrong_log"> Pseudo modifié </div> ';	
			}
			// Si l'utilisateur souhaite changer de mdp
			if (!empty($_POST['newpass'])) {
				newPass($bdd);
			}
			// Si l'utilisateur veut changer de mail
			if(($_POST['mail'])!= ($donnee['usermail'])){
				newMail($bdd);
			}
			// Si l'utilisateur envoie un avatar
			if (!empty($_FILES['avatar']['name'])) {
				$dossier = 'upload/';
				$fichier = basename($_FILES['avatar']['name']);
				$taille_maxi = 100000;
				$taille = filesize($_FILES['avatar']['tmp_name']);
				$extensions = array('.png', '.gif', '.jpg', '.jpeg');
				$extension = strrchr($_FILES['avatar']['name'], '.'); 
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
				     $fichier = strtr($fichier, 
				          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
				          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
				     $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
				     $fichier = $_SESSION['id'].$extension;
				     if(move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				     {
				          echo 'Upload effectué avec succès !';
				     }
				     else //Sinon (la fonction renvoie FALSE).
				     {
				          echo 'Echec de l\'upload !';
				     }
				}
				else
				{
				     echo $erreur;
				}
			}

		}
		else{
			echo '<div class="wrong_log"> Mot de passe inccorect </div>';
			$_GET['action'] = 'modifier';
		}
		
	}
	$requete = $bdd->query('SELECT p.userid, p.pseudo, p.userpwd, p.usermail, p.userdateinscription, u.profil_id FROM tbuser p, user_profil u WHERE (userid)= \'' . $_SESSION['id'] . '\' AND u.user_id=p.userid');
	//$requete = $bdd->query('SELECT userid, pseudo, userpwd, usermail, etat, userdateinscription FROM tbuser WHERE (userid)= \'' . $_SESSION['id'] . '\'');
	$donnee = $requete->fetch();  

	$action = isset($_GET['action'])?htmlspecialchars($_GET['action']):'consulter';
	switch($action){
	    //Si c'est "consulter"
	    case "consulter":

			echo '<h2>Profil de '.$donnee['pseudo'].'</h2>';

			$filename = 'upload/'.$_SESSION['id'].'.jpg';
			if (file_exists($filename)) {
				$ini_path = 'conf.ini.php';
				$ini = parse_ini_file($ini_path, true);
				$width = $ini['Avatar']['width'];
				$height = $ini['Avatar']['height'];
				echo 'Avatar : <img src="'.$filename.'" width="'.$width.'" height="'.$height.'"/></br>';
			}
			else{
				echo "Avatar : Vous n'avez pas d'avatar</br>";
			}
			echo 'Pseudo : '.$donnee['pseudo'].'</br>';   
			echo 'Email : '.$donnee['usermail'].'</br>';
			verifProfil();
			if(isset($_SESSION['isadmin'])){
				echo '<a href="Administration.php">Administration</a> </br>';
			}
			echo'<p> <a href="User.php?action=modifier">Modifier</a> </p>'; 
			if(isset($_SESSION['newmail'])){
				echo "<div class='wrong_log'>Votre nouvelle adresse mail n'a pas été validée, veuillez consulter votre boîte mail pour activer votre nouvelle adresse mail.</div> </br>";
			} 
			break;
			

		//Si on choisit de modifier son profil
    	case "modifier":
    	if (empty($_POST['sent'])){ // Si on la variable est vide, on peut considérer qu'on est sur la page de formulaire 

    		echo '<h2>Modifier profil: </h2>';

    		echo '<form method="post" action="User.php" enctype="multipart/form-data">';

    		echo '<div class="modif"><label for="pseudo">Votre pseudo :</label>
        		  <input type="text" name="pseudo" id="pseudo" value='.$donnee['pseudo'].' size="30" maxlength="15"  required /> </br></div>';

     		echo '</br> <div class="instruction">
    				<p> Avatar : </p> </div>
    				<div class="modif">
    				</br>';

			echo '<input type="file" name="avatar" /> </br></div>';

    		echo '</br> <div class="instruction">
    				<p> Si vous voulez changer votre adresse mail, veuillez entrer une nouvelle adresse mail : </p> </div>
    				<div class="modif">
    				Votre adresse mail actuelle : 
    			  	'.$donnee['usermail'].' </br></br>
    				
        			
        			<label for="mail">Adresse mail* :</label>
        		  	<input type="email" name="mail" id="mail" placeholder="exemple@mail.com" size="30" maxlength="55"   /> </br>
        		  	<label for="mail">Confirmer* :</label>
        		  	<input type="email" name="confmail" id="confmail" placeholder="exemple@mail.com" size="30" maxlength="55"   /> </br></div>';

        	echo '</br> <div class="instruction">
        			<p> Si vous voulez changer votre mot de passe, veuillez entrer un nouveau mot de passe : </p></div>
        			<div class="modif">
        			<label for="pass">Nouveau mot de passe :</label> 
					<input type="password" name="newpass" id="newpass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15" /> </br>';
			echo '<label for="pass">Confirmer :</label> 
					<input type="password" name="confpass" id="confpass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15" /> </br></div>';

        	echo' </br> <div class="instruction">
        			<p> Pour valider les modifications, veuillez entrer votre (ancien) mot de passe : </p></div>
        			<div class="modif">
					<label for="pass">Mot de passe :</label> 
					<input type="password" name="pass" id="pass" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;" size="30" maxlength"15"  required />
					</br></div>';
        	echo '<p> <input type="submit" value="Valider"/> ';
    		echo ' <a href="User.php?action=consulter">Annuler</a> ';

    		echo "<div class='infos'>*Si vous changez votre adresse mail, votre profil devra être réactivé, vous pourrez encore vous connecter,
        			modifier votre profil et envoyer un message mais vous n'aurez plus les droits d'édition.</div></p>" ; 	
    		break;
    	}
    		
	}
}
else
{
	echo "<div class='wrong_log'> Bonjour, vous n'êtes pas autorisé à voir cette page sans être connecté à un compte actif.</div>";
}

?>



</div>
<footer>
<?php
	creeFooter();
?>
</footer>
</body>

</html>
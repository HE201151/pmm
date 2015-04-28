
<?php
include "maLib.php";

echo "<script>alert('Il se passe qqch!')</script>";
/*connexion base de donnée ephec*/

//session_start(); 

if (!isset($_POST['pseudo']) || !isset($_POST['pass'])) 
{
	echo "<script>alert('pseudo ou MDP incomplet')</script>";
	//header ('location: index.php?userChoice=CreateAcc');
	
myPrint($_POST);
die;
}
else
{
	echo "<script>alert('pseudo et mdp rempli ok')</script>";
	$pseudo=$_POST['pseudo'];
	$password=$_POST['pass'];


	try
	{
		echo "<script>alert('Avant essai de connexion')</script>";
		$bdd = new PDO('mysql:host=localhost:3306;
		dbname=1415he201151;', 'BLAN', 'Romain');
	}
	catch (Exception $e)
	{
		echo "<script>alert('Erreur de connexion')</script>";
		die('Erreur : '. $e->getMessage());
	}

	$requete = $bdd->query('SELECT pseudo, password, email FROM users WHERE (pseudo)= \'' . $_POST['pseudo'] . '\'');


			$donnee = $requete->fetch();
			//echo '<pre>'.print_r($donnee['password'],1).'</pre>';
			{
				if ($donnee['password'] == $password)
				{
					//$_SESSION['pseudo'] = $pseudo;
					
					//header ('location: index.php?userChoice=Home');
					echo "<script>alert('Vous êtes connecté')</script>";
				}
				else
				{
					//header ('location: index.php?userChoice=Login');
					echo "<script>alert('Login ou mot de pass incorrect')</script>";

				}
			}



		$requete->closeCursor();
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style/style.css" />
		<script type="text/javascript" src="script/script.js"></script>
	</head>

	<body>
	
		<div class="boiteInformation">

<?php
	try
	{
		$bdd = new PDO('mysql:host=localhost;dbname=stage;charset=utf8','root','', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}	

	catch (Exception $e)
	{
		die('Erreur: ' .$e -> getMessage());
	}

	$dossier = 'image/avatar/';
	$fichier = basename($_FILES['avatar']['name']);
	$taille = filesize($_FILES['avatar']['tmp_name']);
	$extensions = array('.png', '.gif', '.jpg', '.jpeg');
	$extension = strrchr($_FILES['avatar']['name'], '.'); 
	if($fichier != "empty.png")
	{
		$nomFichier = $_SESSION['id'];
	}
	else
	{
		$nomFichier = "empty";
	}
	$dimensionsAvatar = getimagesize($_FILES['avatar']['tmp_name']);
	$largeur = $dimensionsAvatar[0];
	$hauteur = $dimensionsAvatar[1];
 
	
	//Début des vérifications de sécurité...
	
	if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	{
		$erreur = 'Vous devez uploader un fichier de type png, gif, jpg, jpeg...';
	}
	
	if($taille>1000000)
	{
		$erreur = 'Le fichier est trop gros...';
	}
	
	if($largeur != 100 || $hauteur != 100)
	{
		$erreur = 'L\'image n\'est pas au bon format. Veuillez envoyer une image 100x100.';
	}
	
	if(isset($erreur)) //S'il y a une erreur: echec
	{		
		echo $erreur;
	}
	
	else
	{
		//formatage du nom (suppression des accents, remplacements des espaces par "-")

		
		if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $dossier . $nomFichier . $extension)) //renvoie FALSE si l'upload ne marche pas
		{
			echo 'Echec de l\'upload !';
		}
			
		else 
		{	
			$chemin = $dossier . $nomFichier . $extension;  
			echo 'Upload effectué avec succès !';
		
			$reqSuppr = $bdd -> prepare('SELECT avatar FROM membres WHERE id = :id');
			$reqSuppr -> execute(array(
				'id' => $_SESSION['id']
			));
			
			$avatar = $reqSuppr -> fetch();
			
			if($avatar['avatar'] && $avatar['avatar'] != "image/avatar/empty.png")
			{
				unlink($avatar['avatar']);
			}
		
			$reqCorrect = $bdd -> prepare('UPDATE membres SET avatar = :avatar WHERE id = :id');
			$reqCorrect -> execute(array(
				'avatar' => $chemin,
				'id' => $_SESSION['id']
			));
		}
	}
		
?>
		</div>
	</body>
</html>
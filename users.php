<?php
	include_once("maLibSQL.pdo.php");
	include_once("maLibUtils.php");
	$request_method = $_SERVER["REQUEST_METHOD"];


	function getUsers()
	{
		$query="SELECT * FROM user";
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}
	
	function getUser($id=0)
	{
		$query = "SELECT * FROM user";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}

    //TODO car actuellement renvoie tous les users
	function connexion()
    	{
    	    $login = valider("login");
            $passwd = valider("passwd");
    		$query = "SELECT * FROM user WHERE login LIKE '".$login."' AND passwd LIKE '".$passwd."' ";

    		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
    	}
	
    //TODO
	function getEspacesAssociatedToUser($idUser)
	{
		$query = "SELECT * FROM espace";
		if($idUser != 0)
		{
			$query .= " WHERE idUser=".$idUser;
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}

	function AddUser()
	{
		$nom = valider("nom");
		$prenom = valider("prenom");
		$login = valider("login");
		$passwd = valider("passwd");
		$query="INSERT INTO user( nom, prenom , login , passwd ) VALUES('".$nom."', '".$prenom."', '".$login."', '".$passwd."')";
		if($nom != null && $prenom!= null  && $login!= null && $passwd!= null ){
		$success = SQLInsert($query);
		if($success > 0)
			echo "User ajoute";
		}
	
	}
	
	
	function deleteUser($id)
	{
		$query = "DELETE FROM user WHERE id=".$id.";";
		$success = SQLDelete($query);
		if($success > 0)
			echo "User supprime";
	}
	
	switch($request_method)
	{
		
		case 'GET':
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getUser($id);
			}
			else if(!empty($_GET["login"]) && !empty($_GET["passwd"]))
            {
                $login=intval($_GET["login"]);
                $passwd=intval($_GET["passwd"]);
                connexion($login,$passwd);
            }
			else
			{
				if(!empty($_GET["idUser"]))
				{
					$idUser=intval($_GET["idUser"]);
					getEspacesAssociatedToUser($idUser);
				}
				else{
					getUsers();
				}
			}
			break;
		default:
			// Invalid Request Method
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			AddUser();
			break;
			
		case 'DELETE':
			// Supprimer un produit
			$id = intval($_GET["id"]);
			deleteUser($id);
			break;

	}
?>

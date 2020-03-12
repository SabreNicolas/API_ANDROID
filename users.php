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
		
		return parcoursRs(SQLSelect($query));
	}

	function connexion()
    	{
    	    $login = valider("login");
            $passwd = valider("passwd");
    		$query = "SELECT * FROM user WHERE login LIKE '".$login."' AND passwd LIKE '".$passwd."' ";

    		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
    	}


	function getEspacesAssociatedToUser($idUser)
	{
		$query = "SELECT * FROM espace WHERE idUser=".$idUser;

        $data["espaces"] = parcoursRs(SQLSelect($query));
        $data["success"] = true;
        $data["status"] = 201;
        echo json_encode($data, JSON_PRETTY_PRINT);
	}

    //VERSION NICO
	function getIndicateursAssociatedToUser($idUserIndicateurs)
    {
    	$query = "SELECT * FROM indicateur WHERE idUser=".$idUserIndicateurs;

    	$data["indicateurs"] = parcoursRs(SQLSelect($query));
        $data["success"] = true;
        $data["status"] = 201;
        echo json_encode($data, JSON_PRETTY_PRINT);
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
			$data["user"] = getUser($success);
			$data["success"] = true;
			$data["status"] = 201;
			echo json_encode($data, JSON_PRETTY_PRINT);
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
		    if(!empty($_GET["idUser"]))
            {
                $idUser=intval($_GET["idUser"]);
                getEspacesAssociatedToUser($idUser);
            }
            //VERSION NICO
            else if(!empty($_GET["idUserIndicateurs"]))
            {
                $idUserIndicateurs=intval($_GET["idUserIndicateurs"]);
                getIndicateursAssociatedToUser($idUserIndicateurs);
            }
			else if(!empty($_GET["id"]))
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
				getUsers();
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

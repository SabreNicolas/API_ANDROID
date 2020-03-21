<?php
	include_once("maLibSQL.pdo.php");
	include_once("maLibUtils.php");
	$request_method = $_SERVER["REQUEST_METHOD"];


	function getEspaces()
	{
		$query="SELECT * FROM espace";
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}
	
	function getEspace($id=0)
	{
		$query = "SELECT * FROM espace";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
		
	}

    //VERSION MAX
	/* function getIndicateursAssociatedToEspace($idEspace)
	{

		$query = "SELECT * FROM indicateur";
		if($idEspace != 0)
		{
			$query .= " WHERE idEspace=".$idEspace;
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	} */
	
	function AddEspace()
	{
		$idUser = valider("idUser");
		$nomEspace = valider("nomEspace");
		$query="INSERT INTO espace(idUser, nomEspace) VALUES('".$idUser."', '".$nomEspace."')";
		if($idUser!= null && $nomEspace!= null){
		$success = SQLInsert($query);
		if($success > 0)
        		getEspace($success);
        }
	}
	
	function updateEspace($id)
	{
		header('Content-Type: application/json');
		$_PUT =file_get_contents('php://input');
		$obj = json_decode($_PUT);
		$nomEspace=proteger($obj->nomEspace);
		if( $nomEspace!= null && $id != null){
		$query="UPDATE espace SET nomEspace='".$nomEspace."' WHERE id=".$id;
		$success = SQLUpdate($query);
		if($success > 0)
			echo "espace mis a jour";
		}
		else{
		echo "Problèmes de parametres.";
		}
	}
	
	function deleteEspace($id)
	{
		$query = "DELETE FROM espace WHERE id=".$id.";";
		$success = SQLDelete($query);
		if($success > 0)
			echo "espace supprime";
	}

	switch($request_method)
	{
		
		case 'GET':
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getEspace($id);
			}
			// VERSION MAX
			/* else
			{
				if(!empty($_GET["idEspace"]))
				{
					$idEspace=intval($_GET["idEspace"]);
					getIndicateursAssociatedToEspace($idEspace);
				}
				else{
					getEspaces();
				}
			} */
			//VERSION NICO
			 else{
                getEspaces();
             }
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			AddEspace();
			break;
			
		case 'PUT':
			$id = intval($_GET["id"]);
			updateEspace($id);
			break;
			
		case 'DELETE':
			$id = intval($_GET["id"]);
			deleteEspace($id);
			break;

	}
?>

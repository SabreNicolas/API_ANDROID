<?php
	include_once("maLibSQL.pdo.php");
	include_once("maLibUtils.php");
	$request_method = $_SERVER["REQUEST_METHOD"];


	function getIndicateurs()
	{
		$query="SELECT * FROM indicateur";
		$data["indicateurs"] = parcoursRs(SQLSelect($query));
        $data["success"] = true;
        $data["status"] = 201;
        echo json_encode($data, JSON_PRETTY_PRINT);
	}
	
	function getIndicateur($id=0)
	{
		$query = "SELECT * FROM indicateur";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
		
	}

	//VERSION MAX
	/* function AddIndicateur()
	{
		$idEspace = valider("idEspace");
		$type = valider("type");
		$valeurInit = valider("valeurInit");
		$nomIndicateur = valider("nomIndicateur");
		$query="INSERT INTO indicateur(idEspace, type,valeurInit,nomIndicateur) VALUES('".$idEspace."', '".$type."', '".$valeurInit."', '".$nomIndicateur."')";
		if( $idEspace != null && $type  && $valeurInit!= null && $nomIndicateur!= null){
		$success = SQLInsert($query);
		if($success > 0)
			echo "indicateur ajouté";
		}
		else
			echo "erreur";
	}

	function updateIndicateur($id)
	{
		header('Content-Type: application/json');
		$_PUT =file_get_contents('php://input');
		$obj = json_decode($_PUT);
		$idEspace = proteger($obj->idEspace);
		$type = proteger($obj->type);
		$valeurInit =  proteger($obj->valeurInit);
		$nomIndicateur =  proteger($obj->nomIndicateur);
		if( $idEspace!= null && $id!= null && $type!= null && $type!= null && $valeurInit!= null && $nomIndicateur!= null){
		$query="UPDATE indicateur SET idEspace='".$idEspace."',type= '".$type."',valeurInit= '".$valeurInit."',nomIndicateur= '".$nomIndicateur."' WHERE id=".$id;
		$success = SQLUpdate($query);
		if($success > 0)
			echo "indicateur mis a jour";
		}
		else{
		echo "Problèmes de parametres.";
		}
	} */

	//VERSION NICO
	function AddIndicateur()
    	{
    	    $idUser = valider("idUser");
    		$type = valider("type");
    		$valeurInit = valider("valeurInit");
    		$nomIndicateur = valider("nomIndicateur");
    		$query="INSERT INTO indicateur(type,valeurInit,nomIndicateur,idUser) VALUES('".$type."', '".$valeurInit."', '".$nomIndicateur."', '".$idUser."')";
    		if($type  && $valeurInit!= null && $nomIndicateur!= null){
    		$success = SQLInsert($query);
    		if($success > 0)
                getIndicateur($success);
            }
    	}


    	function updateIndicateur($id)
    	{
    		header('Content-Type: application/json');
    		$_PUT =file_get_contents('php://input');
    		$obj = json_decode($_PUT);
    		$type = proteger($obj->type);
    		$idUser = proteger($obj->idUser);
    		$valeurInit =  proteger($obj->valeurInit);
    		$nomIndicateur =  proteger($obj->nomIndicateur);
    		if($id!= null && $type!= null && $idUser!= null && $valeurInit!= null && $nomIndicateur!= null){
    		$query="UPDATE indicateur SET idUser= '".$idUser."', type= '".$type."',valeurInit= '".$valeurInit."',nomIndicateur= '".$nomIndicateur."' WHERE id=".$id;
    		$success = SQLUpdate($query);
    		if($success > 0)
    			$data["success"] = true;
                $data["status"] = 201;
                echo json_encode($data, JSON_PRETTY_PRINT);
    		}
    		else{
                $data["success"] = false;
                $data["status"] = 400;
                echo json_encode($data, JSON_PRETTY_PRINT);
            }
    	}
	
	function deleteIndicateur($id)
	{
		$query = "DELETE FROM indicateur WHERE id=".$id.";";
		$success = SQLDelete($query);
		if($success > 0)
			 $data["success"] = true;
             $data["status"] = 201;
             echo json_encode($data, JSON_PRETTY_PRINT);
	}
	
	switch($request_method)
	{
		case 'GET':
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getIndicateur($id);
			}
			else
			{
				getIndicateurs();
			}
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			AddIndicateur();
			break;
			
		case 'PUT':
			$id = intval($_GET["id"]);
			updateIndicateur($id);
			break;
			
		case 'DELETE':
			$id = intval($_GET["id"]);
			deleteIndicateur($id);
			break;

	}
?>

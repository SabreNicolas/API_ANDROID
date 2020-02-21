<?php
	include_once("maLibSQL.pdo.php");
	include_once("maLibUtils.php");
	$request_method = $_SERVER["REQUEST_METHOD"];


	function getActivites()
	{
		$query="SELECT * FROM activite";
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}
	
	function getActivite($id=0)
	{
		$query = "SELECT * FROM activite";
		if($id != 0)
		{
			$query .= " WHERE id=".$id." LIMIT 1";
		}
		
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
		
	}
	//PAS ENCORE FAIS si dessous
	function AddActivite()
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
	
	function updateActivite($id)
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
	}
	
	function deleteActivite($id)
	{
		$query = "DELETE FROM indicateur WHERE id=".$id.";";
		$success = SQLDelete($query);
		if($success > 0)
			echo "indicateur supprime";
	}
	
	switch($request_method)
	{
		case 'GET':
			if(!empty($_GET["id"]))
			{
				$id=intval($_GET["id"]);
				getActivite($id);
			}
			else
			{
				getActivites();
			}
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;
			
		case 'POST':
			AddActivite();
			break;
			
		case 'PUT':
			$id = intval($_GET["id"]);
			updateActivite($id);
			break;
			
		case 'DELETE':
			$id = intval($_GET["id"]);
			deleteActivite($id);
			break;

	}
?>
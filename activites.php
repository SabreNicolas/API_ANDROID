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
		
		$data["indicateur"] = parcoursRs(SQLSelect($query));
        $data["success"] = true;
        $data["status"] = 201;
        echo json_encode($data, JSON_PRETTY_PRINT);
	}

    // VERSION NICO
	function getIndicateursAssociatedToEspace($idEspace)
    {
        $query = "SELECT DISTINCT i.id, i.nomIndicateur, i.type, i.valeurInit, i.idUser
                  FROM indicateur as i
                  INNER JOIN activite as a
                  ON i.id = a.idIndicateur
                  WHERE a.idEspace =".$idEspace;

    	$data["indicateursEspace"] = parcoursRs(SQLSelect($query));
        $data["success"] = true;
        $data["status"] = 201;
        echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // VERSION NICO
    function getIndicateursAssociatedToEspaceAndDate($idEspace,$date)
    {
            $date = valider("date");
            $query = "SELECT DISTINCT i.id, i.nomIndicateur, i.type, i.valeurInit, i.idUser, a.valeur, a.date, a.id as idActivite
                      FROM indicateur as i
                      INNER JOIN activite as a
                      ON i.id = a.idIndicateur
                      WHERE a.idEspace =".$idEspace." and a.date LIKE '".$date."'";

        	$data["indicateursDate"] = parcoursRs(SQLSelect($query));
            $data["success"] = true;
            $data["status"] = 201;
            echo json_encode($data, JSON_PRETTY_PRINT);
    }

    // VERSION NICO
    function getEspaceHistorique($dateHistoric,$idUser)
    {
            $date = valider("dateHistorique");
            $query = "SELECT DISTINCT e.id, e.nomEspace, e.idUser
                      FROM espace as e
                      INNER JOIN activite as a
                      ON e.id = a.idEspace
                      WHERE e.idUSer =".$idUser." and a.date LIKE '".$date."'";

        	$data["espacesHistorique"] = parcoursRs(SQLSelect($query));
            $data["success"] = true;
            $data["status"] = 201;
            echo json_encode($data, JSON_PRETTY_PRINT);
    }


	function AddActivite()
	{
		$idEspace = valider("idEspace");
		$idIndicateur = valider("idIndicateur");
		$valeur = valider("valeur");
		$date = valider("date");
		$query="INSERT INTO activite(valeur,idEspace,idIndicateur,date) VALUES('".$valeur."', '".$idEspace."', '".$idIndicateur."', '".$date."')";
		if( $idEspace != null && $valeur!=null  && $date!= null && $idIndicateur!= null){
		$success = SQLInsert($query);
		if($success > 0)
            getActivite($success);
        }
	}
	
	function updateActivite($id)
	{
		header('Content-Type: application/json');
		$_PUT =file_get_contents('php://input');
		$obj = json_decode($_PUT);
		$idEspace = proteger($obj->idEspace);
		$date = proteger($obj->date);
		$valeur =  proteger($obj->valeur);
		$idIndicateur =  proteger($obj->idIndicateur);
		if( $idEspace!= null && $date!= null && $valeur!= null && $idIndicateur!= null){
		$query="UPDATE activite SET idEspace='".$idEspace."',date= '".$date."',valeur= '".$valeur."',idIndicateur= '".$idIndicateur."' WHERE id=".$id;
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
	
	function deleteActivite($id)
	{
		$query = "DELETE FROM activite WHERE id=".$id.";";
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
			// VERSION MAX
			/* else
			{
				getActivites();
			} */
			// VERSION NICO
			else
            {
            if(!empty($_GET["idEspace"]))
            {
                $idEspace=intval($_GET["idEspace"]);
                if(!empty($_GET["date"])) {
                    $date=intval($_GET["date"]);
                    getIndicateursAssociatedToEspaceAndDate($idEspace,$date);
                }
            	else getIndicateursAssociatedToEspace($idEspace);
            }
            else if(!empty($_GET["dateHistorique"]))
            {
                $dateHistorique=intval($_GET["dateHistorique"]);
                $idUser=intval($_GET["idUser"]);
                getEspaceHistorique($dateHistorique,$idUser);
            }
            else{
                getActivites();
            }
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

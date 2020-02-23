<?php
	include_once("maLibSQL.pdo.php");
	include_once("maLibUtils.php");
	$request_method = $_SERVER["REQUEST_METHOD"];


	function getActivites()
	{
		$query="SELECT * FROM myview";
		echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
	}

    //on récuperera d'abord les espaces d'un user pour ensuite récupèrer les views de ses espaces
    // utile pour le remplissage d'infos pour un espace
    // car on pourra récupérer le type et la valeur d'init de chaque indicateurs de l'espace
	function getIndicateursAssociatedToEspace($idEspace)
    {
        $query = "SELECT * FROM myview";
    	if($idEspace != 0)
    	{
    	    $query .= " WHERE idEspace=".$idEspace;
    	}

    	echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
    }

    //permet de récupérer les valeurs des indicateurs d'un espace pour une date => historique
    function getIndicateursAssociatedToEspaceByDate($idEspace,$date)
    {
        $date=valider("date");
        $query = "SELECT * FROM myview";
        if($idEspace != 0 && $date != null)
        {
            $query .= " WHERE date LIKE '".$date."' AND idEspace=".$idEspace;
        }

        echo json_encode(parcoursRs(SQLSelect($query)), JSON_PRETTY_PRINT);
    }

	switch($request_method)
	{
		case 'GET':
			if(!empty($_GET["idEspace"]) && empty($_GET["date"]))
            {
                $idEspace=intval($_GET["idEspace"]);
            	getIndicateursAssociatedToEspace($idEspace);
            }
            else if(!empty($_GET["idEspace"]) && !empty($_GET["date"]))
            {
                $idEspace=intval($_GET["idEspace"]);
                $date=intval($_GET["date"]);
                getIndicateursAssociatedToEspaceByDate($idEspace,$date);
            }
            else{
                getActivites();
            }
			break;
		default:
			header("HTTP/1.0 405 Method Not Allowed");
			break;


	}
?>

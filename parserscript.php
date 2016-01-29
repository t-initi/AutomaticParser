<?php
	require_once("./ParserTester.class.php");

	/*** DEBUT ***/
	//$_GET['siteUrl'] = 'google.com';
	$url = null ;
	$parser = null;


	/*if(!isset($_GET['siteUrl']) || empty($_GET['siteUrl'])){
		echo "Veuillez renseigner l'URL";
	}else{
		 $url = $_GET['siteUrl'];
		 $parser = new ParserTester($url);
		 	$parser->initialiseParser();

	}*/

	if(isset($_GET['func']) && $_GET['siteUrl']){
		//echo $_GET['func'];
		$url = $_GET['siteUrl'];
		$func = $_GET['func'];
		$parser = new ParserTester($url);

		if($func=='show_content'){
				$parser->showContent();
		}else if($func=='parse_id'){
			$id = $_GET['eltID'];
			$parser->parseElementByID($id);
		}else if($func=='get_node'){
			$id = $_GET['eltID'];
			$parser->parseElementByID($id);
			$parser->getNodeValue();
		}else if($func=='check_site'){
				$parser->initialiseParser();
		}else if($func=='parse_tag'){
			$tag = $_GET['tag'];
			$parser->parseElementsByTagName($tag);
		}
		

	}else{
		echo "Veuillez renseigner l'URL";
	}



?>

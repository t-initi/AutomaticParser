<?php
require_once '../generalFiles/php/define.php';
//require_once('../generalFiles/php/config.php');
require_once('../generalFiles/php/fonctionsGenerales.inc.php');
require_once('../generalFiles/adodb5/adodb.inc.php');
error_reporting(0);
class ParserTester{
	
	private $ch;
	private $timeout = 10;
	private $cookies_file;
	private $domObject;
	private $mainUrl;
	private $tmpNode;
	
	function __construct($url){
		libxml_use_internal_errors(true);
		$this->ch = curl_init();
		$this->mainUrl=$url;
		//$this->offreNonExtraite=0;
		curl_setopt($this->ch, CURLOPT_FRESH_CONNECT, true); // force à utiliser une connexion au lieu de celle en cache
		curl_setopt($this->ch, CURLOPT_TIMEOUT, $this->timeout); // temps maximal de la fonction d'exécution curl
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $this->timeout); // nombre de secondes à attendre durant la tentative de connexion
		//curl_setopt($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0'); // Le contenu de l'en-tête "User-Agent: " à utiliser dans une requête HTTP
		/**
		 * TRUE pour suivre toutes les en-têtes "Location:
		 * que le serveur envoie dans les en-têtes HTTP (notez que cette fonction est récursive et que PHP suivra
		 		* toutes les en-têtes "Location: " qu'il trouvera à moins que CURLOPT_MAXREDIRS ne soit définie)
		 */
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		/* TRUE retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec au lieu de l'afficher directement. */
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.17 (KHTML, like Gecko) Chrome/24.0.1312.52 Safari/537.17');
		curl_setopt($this->ch, CURLOPT_AUTOREFERER, true);
		curl_setopt($this->ch, CURLOPT_VERBOSE, 1);
		// Forcer cURL à utiliser un nouveau cookie de session
		curl_setopt($this->ch, CURLOPT_COOKIESESSION, true);
		//curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookies_file);
		// Fichier dans lequel cURL va lire les cookies
		//curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookies_file);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		//On supprime le fichier temporaire
		$this->deleteTmpFile();
		//On execute cette fonction pour créer les 2 nouveaux fichier
		$this->executeCurl();
	}
	

  //Initialise un Parser à partir de l'url
	function initialiseParser( ){
		$filename= dirname(__FILE__).'\tmp.html';
		
		$url = $this->mainUrl;
		curl_setopt($this->ch,CURLOPT_URL,$url);
		$content2 =	$this->executeCurl();							//execution de curl et écriture du fichier localfile.html
		$content = $filename;
		//$pageContent= $this->page_content =curl_exec($this->ch);
		
		$doc = new DOMDocument();
		/*if($pageContent == ""){
			exit("<b>Contenu de  la page est vide !!</b>");
		}*/
		if($doc->loadHTMLFile($content) && $doc->loadHTML($content2)){
			echo "<b style='color:green;'>Page Succesffuly loaded</b>";
			return true;
		} else{
			echo "<b style='color:red;'>Page invalide</b>";
		}
	}

	function showContent(){
		$filename= dirname(__FILE__).'./tmp.html';
		if ( ! file_exists($filename) ) exit("File n'existe pas") ;//Verifie que localfile.html exist
		$domObject = new DOMDocument();
		if($domObject->loadHTMLFile($filename)){
			$page = $domObject->saveHTML();
			echo htmlentities($page,ENT_IGNORE);
		}else{
			echo "Erreur de chargement";
		}
	}
	/**
	 * Execution d'une requête curl
	 * @return mixed
	 */
	function executeCurl(){
		$url = $this->mainUrl; //Initialise url
		set_time_limit(0);
		$fp = fopen (dirname(__FILE__) . '/localfile.html', 'w+');		//This is the file where we save the information
		curl_setopt($this->ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($this->ch, CURLOPT_FILE, $fp);  					//parametrage curl pour l'ecriture dans le fichiet
		curl_setopt($this->ch,CURLOPT_URL,$url);
		$pageContent = $this->page_content =curl_exec($this->ch);		// Recuperation du contenu de la page
		fclose($fp);													//Fermeture du fichier
		$this->createTmpFile();											//Creation d'une copie temporaire du fichier
		return $pageContent;
	}
	/**
	 * Recupère un DOMELlement par son identifiant
	 * @param unknown $id
	 * @return boolean
	 */
	function parseElementByID($id){
		$filename= dirname(__FILE__).'\tmp.html';
		
		if ( ! file_exists($filename) ){
			echo("Fichier "+$filename+" n'existe pas");
			$content =	$this->executeCurl();							//execution de curl et écriture du fichier localfile.html
		} 
		$content = $filename;
		$dom = new DOMDocument();
		if($dom->loadHTML($content)){
			$dom->loadHTMLFile($content);
			if($idVal =$dom->getElementById($id)){
				echo "<h3 class='subTitle'> ElementByID Value </h3>";
				echo "<p><b>Element By ID </b>= ".$id." <b style='color:green;'> succesffuly loaded</b></p>";
				echo "<div>".$idVal->nodeValue."<div />";
				$this->writeToTmpFile($dom->saveHTML($idVal));
			}else{
					echo "<p>ElementBy ID = <strong>".$id."</strong> <b style='color:red;'>not found</b></p>";
				}
			return true;
			} else{
				echo "<p>Erreur d'ouverture du fichier</p>";
			}

	}

	function getNodeValue(){
		if(isset($this->tmpNode)){
		echo "<h2>Node Value</h2>";
		echo $this->tmpNode->nodeValue;
		}else{
			echo "Trying to get property of non-object";
		}
	}
	
	/**
	 * Recupère un DOMELlement par le nom de sa balise
	 * @param unknown $tag
	 * @return boolean
	 */

	function parseElementsByTagName($tag){
	 $filename= dirname(__FILE__).'\tmp.html';
	if ( ! file_exists($filename) ){
			echo("Fichier "+$filename+" n'existe pas");
			$content =	$this->executeCurl();							//execution de curl et écriture du fichier localfile.html
		} //execution de curl et écriture du fichier localfile.html
	$content = $filename;
	$dom = new DOMDocument();
	$elts ="";
		if($dom->loadHTML($content)){
			$dom->loadHTMLFile($content);
			if($tagVal =$dom->getElementsByTagName($tag)){
				echo "<div class='resClass'><h3 class='subTitle'> ElementByTagName Value </h3>";
				echo "<p>ElementBy Tag Name = ".$tag." <b style='color:green;'> succesffuly loaded</b></p>";
				foreach ($tagVal as $key => $value) {
					echo "<p>".$key." => ".$value->nodeValue."</p>";
					$elts .= $dom->saveHTML($value);
				}
				echo "</div>";
				$this->writeToTmpFile($elts);
				}else{
					echo "<p>ElementBy ID = ".$tag." not found</p>";
				}
			return true;
			} else{
				echo "<p>Erreur d'ouverture du fichier</p>";
			}

	}
	function parseElementsByClassName(){

	}
	function parseElementsByAttribute(){

	}

	function createTmpFile(){
		$filename= dirname(__FILE__).'\localfile.html';
		$fileTowriteTo = dirname(__FILE__).'\tmp.html';
		$fp1 = file_get_contents($filename);
		//$content = htmlentities($fp1,ENT_IGNORE);
		$content =htmlspecialchars_decode($fp1);
		$fp = fopen($fileTowriteTo,"w+");
		fwrite($fp, $content);
		fclose($fp);
	}

	function writeToTmpFile($toWrite){
		$filename = dirname(__FILE__).'\tmp.html';
		$fp = fopen($filename,"w+");
		fwrite($fp, $toWrite);
		fclose($fp);
	}
	
	function deleteTmpFile(){
		$filename= dirname(__FILE__).'\tmp.html';
		if (file_exists($filename)){
			unlink($filename);
		}
	}

	
}



//$test = new ParserTester("www.spifftv.com");
//$test->parseElementsByTagName('li');
//$test->parseElementsByTagName('a');
//$test->createTmpFile();
//$test->writeToTmpFile("Hello");
//$test->initialiseParser();
//$test->parseElementByID("div1");
//$test->getNodeValue();
//$test->showContent();

<!DOCTYPE html>
<html lang="fr">

<head>
	<title>ParserAutomatique</title>
		<meta charset='utf-8' />
		<?php 
		require_once('../generalFiles/php/config.php');
		require_once('../generalFiles/php/fonctionsGenerales.inc.php');
		require_once('../generalFiles/adodb5/adodb.inc.php');
		require_once('../generalFiles/php/define.php');
		?>
		<script src='../generalFiles/javascript/jquery2.js'></script>
		<script src='./parser.js'></script>

		<style type="text/css">
		body{ width :100%;}
		#testPane {float: left;}
		#responsePane{width: 60%;}
		#testPane{width:38%;}
		#testPane , #responsePane{border: 1px solid black; display: inline-block; margin: 1px; padding: 2px; min-height: 120px; }
		#contentResPane{overflow: scroll; max-height: 400px; width: 100%; border: 1px solid grey; display:none;}
		.otherOpt button, .otherOpt input {display:block; width:100%; height: 25px;}
		#urlResPane, #res{font-weight: bold; width:42%;}
		#res{text-align:right;float: right}
		.subTitle{background: grey; text-align: center;}
		.resClass{border: 1px solid grey; overflow: scroll; max-height: 420px;}
		
		#alert{text-align:center; color:red;}
		#loader{display:none;}
		table {width:100%;}
		
		
		</style>
</head>
	<body>
		<div >
		
				<section id='testPane'><h2>Parser Automatique Test</h2>
					<div>
						<label for='siteUrl'>URL du Site : </label> 
						<input size='36' type ='text' id='siteUrl' name='siteUrl'><button id='siteBt'>OK</button>
						<hr />
					</div>
					
				<p class='eltOpt'><input type ='text' id='elementID' name='elementID'><button  id='elementIDBt'>GetElementByID</button></p>
				<p class='eltOpt'><input type ='text' id='elementTag' name='elementTag'><button id='elementTagBt'>GetElementsByTagName</button></p>
				<!--p class='eltOpt'><input type ='text' id='elementClass' name='elementClass'><button id='elementClassBt'>GetElementsByClassName</button></p-->
				<p class='eltOpt'><input type ='text' id='elementAtt' name='elementAtt'><button id='elementAttBt'>GetElementsByAttributes</button></p>
				<hr />
				
				<div class='otherOpt'>
					<button id='show_html_content' >Show HTML Content</button>
					<!--button id='get_node'>Get Node Value</button-->
					<button id='preg_rep'>Preg_Replace</button>
					<button id='preg_rep_all'>Preg_Replace_All</button>
					<input type='text' placeholder='Pattern' id='pattern' name='pattern'/>
					<input type='text' placeholder='Expression' id='expression' name='expression'/>
						
				</div>
				<p><h4 id='alert'></h4></p>
				
				</section>
		

			<section id='responsePane'><h2>Résultats</h2>
			<table>
					<tr>
							<td id='urlResPane'></td>
							<td id='loader'><img class='modal' src='http://i.stack.imgur.com/FhHRx.gif' alt='load'/>  Loading ...</td>
							<td id='res'></td>
					</tr>
			</table>
				<!--div id='urlResPane'> </div>
				<div id='loader'>
						<img class='modal' src='http://i.stack.imgur.com/FhHRx.gif' alt='load'/>
							
				</div >
				<div id='res'></div-->
				<div id='msgField'> </div>
				<div id='contentResPane'> </div>
			</section>
		</div>
		
	
		
	</body>
</html>
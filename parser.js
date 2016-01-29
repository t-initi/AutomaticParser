
			$(document).ready(function(){
				$('#responsePane, .eltOpt, #contentResPane, #expression, #pattern').hide(); //
				$loader = $("#loader");
				$('#siteUrl').on('mousedown', function (){
					$('.eltOpt, #responsePane, #res').slideDown(300);
				});
				$('#preg_rep, #preg_rep_all').on('mousedown', function (){
					$('#expression, #pattern').slideDown(300);
				});
				
				//Lorsqu'on click sur le boutton de l' URL
				$('#siteBt').on('click', function(){
					$('.eltOpt, #responsePane, #res').slideDown(300);
					$('#msgField, #urlResPane ').html("")
					var siteUrl= $('#siteUrl').val();
					if(siteUrl != ''){
						$('#urlResPane').text("URL : "+siteUrl);
						$.ajax({
							type: 'GET',
							url : 'parserscript.php',
							data : 'func=check_site&siteUrl='+siteUrl,
							beforeSend: function() { $loader.css("display","inline-block");},
					        complete: function() { $loader.css("display","none"); },
							success : function(data){
								//console.log('success',data);
								$('#msgField').html("");
								$('#res').html(data);
								//$('#urlResPane').text() = "<h3>"+data+"</h3>";
							},
							error: function(){
								$('#res').html("Erreur de location du fichier php");
							}
						});
					}
				});


				//Affichage du contenu
				$('#elementIDBt').on('click', function(){
					var id = $("#elementID").val();
					var siteUrl= $('#siteUrl').val();
					if(id != ""){
							$.ajax({
							url : 'parserscript.php',
							data : 'siteUrl='+siteUrl+'&func=parse_id&eltID='+id,
							beforeSend: function() { $loader.css("display","inline-block");},
					        complete: function() { $loader.css("display","none"); },
							success : function(data){
								//console.log('success',data);
								$('#contentResPane').html("");
								$('#msgField').html("");
								$('#msgField').html("<div>"+data+"</div>");
							}
						});

					}else{
							$('#contentResPane').append("<p>Veuillez entrer un identifiant</p>");
							$('#alert').html("Veuillez entrer un ID !");
					}
				});

				$('#elementTagBt').on('click', function(){
					var tag = $("#elementTag").val();
					var siteUrl= $('#siteUrl').val();
					if(tag != ""){
							$.ajax({
							url : 'parserscript.php',
							data : 'siteUrl='+siteUrl+'&func=parse_tag&tag='+tag,
							beforeSend: function() { $loader.css("display","inline-block");},
					        complete: function() { $loader.css("display","none"); },
							success : function(data){
								$('#msgField').html("");
								$('#msgField').html(data);
							}
						});

					}else{
							$('#contentResPane').append("<p>Veuillez entrer un tagName</p>");
							$('#alert').html("<p>Veuillez entrer un tagName</p>");
					}

				});

				$('#show_html_content').on('click', function(){
					var siteUrl= $('#siteUrl').val();
					if(siteUrl != ''){
						$.ajax({
							url : 'parserscript.php',
							data : 'func=show_content&siteUrl='+siteUrl,
							beforeSend: function() { $loader.css("display","inline-block");},
					        complete: function() { $loader.css("display","none"); },
							success : function(data){
								console.log('success',data);
								//alert(data);
								$('#contentResPane').html("");
								$('#contentResPane').slideDown(500);
								$('#contentResPane').html("<div><h2 class='subTitle'>Contenu HTML</h2>"+data+"</div>");
							}
						});
					}
				});

					$('#get_node').on('click', function(){
					var siteUrl= $('#siteUrl').val();
					 var id = $("#elementID").val();
					if(siteUrl != ''){
						//$('#responsePane').append("<p>URL : "+siteUrl+"</p>");
						$.ajax({
							url : 'parserscript.php',
							data : 'func=get_node&siteUrl='+siteUrl,
							beforeSend: function() { $loader.css("display","inline-block");},
					        complete: function() { $loader.css("display","none"); },
							success : function(data){
								//console.log('success',data);
								//alert(data);
								$('#contentResPane').append("<div>"+data+"</div>");
							}
						});
					}

				});
				
			
	
				

			}); //Fin de document ready
$('document').ready(function(){
 
	$("#btn-login").click(function(){
		var data = $("#login-form").serialize();
			
		$.ajax({
			type : 'POST',
			url  : 'login.php',
			data : data,
			dataType: 'json',
			beforeSend: function()
			{	
				$("#btn-login").html('Validando ...');
			},
			success :  function(response){						
				if(response.codigo == "1"){	
					$("#btn-login").html('Entrar');
					
					window.location.href = "inicial.php";
				}
				else{			
					$("#btn-login").html('Entrar');
					
					html('<strong>Erro! </strong>' + response.mensagem);
					window.location.href = "login.php";

					
					//$("#mensagem").html('<strong>Erro! </strong>' + response.mensagem);
				}
		    }
		});
	});
 
});
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
      <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
	<link href="<?php echo $css_url;?>bootstrap.css" rel="stylesheet" />
    <link href="<?php echo $css_url;?>font-awesome.css" rel="stylesheet" />
    <link href="<?php echo $css_url;?>login.css" rel="stylesheet" />    
</head>
<body class='login'>
	<div class="wrapper">
		<h1><img src="<?php echo $base_url;?>img/logo.png" alt="" class='retina-ready' width="150"></h1>
		<div class="login-body">
			<h2 align="center">LOGIN</h2> 
    		<form id="login_check" action="<?php echo $site_url?>login/login_check" method="post">					
				<div class="control-group">
					<div class="email controls">
						<input type="text" name='username' placeholder="Username" class='form-control'/>
					</div>
				</div>
				<div class="control-group">
					<div class="pw controls">
						<input type="password" name="passwd" placeholder="Password" class='form-control'/>
					</div>
				</div>
				<div class="submit">					
					<input type="submit" style="width:100%;" value="Login" class='btn btn-primary'>										
				</div>				
				<div class="login_result"></div>
			</form>
			<div class="forget">
				<span>N-T-S</span>
			</div>
		</div>
	</div>
	<script src="<?php echo $js_url;?>jquery-1.10.2.js"></script>
	<script>
	$(document).ready(function(){		
		$(document).on("submit","#login_check",function() {
			$('.login_result').html('<i class="fa fa-refresh fa-spin"></i> Login ...');
			form = $("#login_check").serialize();
			var formURL = $(this).attr("action");
			$.ajax({
				type: "POST",
				url: formURL,
				data: form,
				success: function(data){
					if(data==1)
					{
						$('.login_result').html('<div class="alert alert-success"><i class="fa fa-check-circle"></i> Login Successful.</div>');
						window.location = "<?php echo $site_url;?>";
					}else{
						$('.login_result').html('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Invalid username or password.</div>');
					}					
				}
			});
			return false;
		});	
	});
	</script>
</body>
</html>
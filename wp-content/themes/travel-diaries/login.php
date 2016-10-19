<?php
/*
Template Name: Login
*/
get_header();
 ?>
	
	<div class="container-fluid">
	
		<div class="row">
			   
		 	<div class="col-xs-12 col-md-12">
				<div class="login-page">
					<div id="login_form">
						<form id="login-form" method="post" onsubmit="return validateForm()" action="<?php echo get_template_directory_uri() . '/authenticate.php'; ?>">
							<input id="username" type="text" placeholder="username" name="username" autofocus />
							<input id="password" type="password" placeholder="password" name="password" />
							<!--<button type="submit" value="Submit" id="login_button">LOGIN</button>-->
							<input  type="submit" value="LOGIN"/>
						</form>
					</div>
				</div>
			</div>				
			
		</div>
		
    </div>

	
<?php get_footer(); ?>	
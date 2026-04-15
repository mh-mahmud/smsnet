<div class="header">
<table>
	<tr>
		<td width="40%" align="right"><img src="<?php echo $base_url; ?>img/login.png" width="50"/></td>	
		<td width="50%" align="center">Admin Login</td>	
	</tr>
</table>
</div>
<form action="<?php echo $site_url?>login/index" method="post">	
	<div class="body bg-gray">
		<span class="error">
		<?php echo $this->session->flashdata('message'); ?>
		<?php echo form_error('captcha'); ?>
		</span>
		<div class="form-group left-inner-addon">
        	<i class="fa fa-user"></i>		
			<input type="text" name="username" class="form-control login_field" placeholder="Username"/>
		</div>
		<div class="form-group left-inner-addon">
        	<i class="fa fa-unlock-alt"></i>		
			<input type="password" name="passwd" class="form-control login_field" placeholder="Password"/>
		</div>
		<div class="form-group">
			<table>
				<tr>
					<td><?php echo $cap_img; ?></td>	
					<td><input type="text" style="border:none;height:50px;font-size:20px;text-align:center;" name="captcha" class="form-control" placeholder="Security Code"/></td>	
				</tr>
			</table>			
		</div>		
	</div>
	<div class="footer">                                                               
		<button type="submit" style="font-size:20px;" class="btn bg-olive btn-block">Login</button> 
	</div>
</form>

            
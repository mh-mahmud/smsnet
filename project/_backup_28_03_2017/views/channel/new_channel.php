<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
				<div class="box-tools pull-right">
					<a class="ajax_link" href="<?=$site_url.$link_action;?>">
						<button class="btn btn-primary btn-xs" type="button"><i class="fa fa-table"></i> <?php echo $link_title; ?></button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<form role="form" action="<?=$site_url.$active_controller;?>/add" method="post">
				<div class="form-group">
					<text>Operator :</text>
					<div class="field">
						<select class="form-control" name="operator_id">
							<option value="" >---- Select Operator ----</option>
							<?php echo html_options($operator_options,set_value('operator_id')) ;?>
						</select>
						<span class='error'>* </span><?php echo form_error('operator_id'); ?> 
					</div>
				</div>					
				<div class="form-group">
					<text>Channel Name :</text>
					<div class="field">
						<input type="text" class="form-control" name="name" value="<?=set_value('name'); ?>"/>
						<span class="error">* </span><?php echo form_error('name'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Channel Type :</text>
					<div class="field">
						<select class="form-control" name="channel_type" id="channel_type">
							<option value="" >---- Select Channel Type ----</option>
							<?php echo html_options($channel_type_options,set_value('channel_type')) ;?>
						</select>
						<span class='error'>* </span><?php echo form_error('channel_type'); ?> 
					</div>
				</div>					
				
				<div class="form-group" id="url">
					<text>Url :</text>
					<div class="field">
						<input type="text" class="form-control" name="url" value="<?=set_value('url'); ?>"/>
						<span class="error">* </span><?php echo form_error('url'); ?>
					</div>
				</div>
				<div class="form-group" id="username">
					<text>Username :</text>
					<div class="field">
						<input type="text" class="form-control" name="username" value="<?=set_value('username'); ?>"/>
						<span class="error">* </span><?php echo form_error('username'); ?>
					</div>
				</div>
				<div class="form-group" id="password">
					<text>Password :</text>
					<div class="field">
						<input type="text" class="form-control" name="password" value="<?=set_value('password'); ?>"/>
						<span class="error">* </span><?php echo form_error('password'); ?>
					</div>
				</div>
				<div class="form-group" id="ip">
					<text>IP :</text>
					<div class="field">
						<input type="text" class="form-control" name="ip" value="<?=set_value('ip'); ?>"/>
						<span class="error">* </span><?php echo form_error('ip'); ?>
					</div>
				</div>
				<div class="form-group" id="port">
					<text>Port :</text>
					<div class="field">
						<input type="text" class="form-control" name="port" value="<?=set_value('port'); ?>"/>
						<span class="error">* </span><?php echo form_error('port'); ?>
					</div>
				</div>
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Submit</button>
					<button type="reset" class="btn btn-warning btn-sm">Reset</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div> 

<script>
//functin to toggle recipients
$(document).ready(function() {
			$("#url").hide();
			$("#username").hide();
			$("#password").hide();	
			$("#ip").hide();	
			$("#port").hide();
	$('#channel_type').change(function() {
			
		var channel_type = ($(this).val());
		if(channel_type=='HTTP'){
		
			$("#url").show();
			$("#username").show();
			$("#password").show();	
			$("#ip").hide();	
			$("#port").hide();	
		}
		if(channel_type=='SMPP'){
			$("#url").hide();
			$("#username").show();
			$("#password").show();	
			$("#ip").hide();	
			$("#port").hide();	
		}
		if(channel_type=='MAP'){
			$("#url").hide();
			$("#username").hide();
			$("#password").hide();	
			$("#ip").show();	
			$("#port").show();	
		}
	});
	var channel_type = '<?=$channel_type;?>';
		if(channel_type=='HTTP'){
		
			$("#url").show();
			$("#username").show();
			$("#password").show();	
			$("#ip").hide();	
			$("#port").hide();	
		}
		if(channel_type=='SMPP'){
			$("#url").hide();
			$("#username").show();
			$("#password").show();	
			$("#ip").hide();	
			$("#port").hide();	
		}
		if(channel_type=='MAP'){
			$("#url").hide();
			$("#username").hide();
			$("#password").hide();	
			$("#ip").show();	
			$("#port").show();	
		}
 });

	
</script>


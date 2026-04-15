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
					<text>Full Name :</text>
					<div class="field">
						<input type="text" class="form-control" name="full_name" value="<?=set_value('full_name'); ?>"/>
						<span class="error">* </span><?php echo form_error('full_name'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Short Name :</text>
					<div class="field">
						<input type="text" class="form-control" name="short_name" value="<?=set_value('short_name'); ?>"/>
						<span class="error">* </span><?php echo form_error('short_name'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Prefix :</text>
					<div class="field">
						<input type="text" class="form-control" name="prefix" value="<?=set_value('prefix'); ?>"/>
						<span class="error">* </span><?php echo form_error('prefix'); ?>
					</div>
				</div>	
				<div class="form-group">
					<text>Country :</text>
					<div class="field">
						<select class="chosen form-control" name="country_id">
							<option value="" >---- Select Country ----</option>
							<?php echo html_options($country_options,set_value('country_id')) ;?>
						</select>
						<span class='error'>* </span><?php echo form_error('country_id'); ?> 
					</div>
				</div>
				<div class="form-group">
					<text>Ton :</text>
					<div class="field">
						<input type="text" class="form-control" name="ton" value="<?=set_value('ton'); ?>"/>
						<span class="error"></span><?php echo form_error('ton'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Npi :</text>
					<div class="field">
						<input type="text" class="form-control" name="npi" value="<?=set_value('npi'); ?>"/>
						<span class="error"></span><?php echo form_error('npi'); ?>
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
$(document).ready(function(){

	jQuery(".chosen").chosen();
});
</script>



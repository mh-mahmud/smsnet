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
				<form role="form" action="<?=$site_url.$active_controller;?>/edit/<?=$id;?>" method="post">
				<div class="form-group">
					<text>User :</text>
					<div class="field">
						<select class="chosen form-control" name="user_id">
							<option value="" >---- Select User ----</option>
							<?php echo html_options($user_options,set_value('user_id',$user_id)) ;?>
						</select>
						<span class='error'></span><?php echo form_error('user_id'); ?> 
					</div>
				</div>
				<div class="form-group">
					<text>Operator :</text>
					<div class="field">
						<select class="chosen form-control" name="operator_id">
							<option value="" >---- Select Operator ----</option>
							<?php echo html_options($operator_options,set_value('operator_id',$operator_id)) ;?>
						</select>
						<span class='error'></span><?php echo form_error('operator_id'); ?> 
					</div>
				</div>
				<div class="form-group">
					<text>Channel :</text>
					<div class="field">
						<select class="chosen form-control" name="channel_id">
							<option value="" >---- Select Channel ----</option>
							<?php echo html_options($channel_options,set_value('channel_id',$channel_id)) ;?>
						</select>
						<span class='error'></span><?php echo form_error('channel_id'); ?> 
					</div>
				</div>
				<div class="form-group">
					<text>Cost :</text>
					<div class="field">
						<input type="text" class="form-control" name="cost" value="<?=set_value('cost',$cost); ?>"/>
						<span class="error"></span><?php echo form_error('cost'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Default Mask :</text>
					<div class="field">
						<input type="text" class="form-control" name="default_mask" value="<?=set_value('default_mask',$default_mask); ?>"/>
						<span class="error">*</span><?php echo form_error('default_mask'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Masking Option :</text>
					<div class="field">
						<select class="chosen form-control" name="has_mask">
							<option value="" >---- Select Mask Option ----</option>
							<?php echo html_options($mask_options,set_value('has_mask',$has_mask)) ;?>
						</select>
						<span class='error'>*</span><?php echo form_error('has_mask'); ?> 
					</div>
				</div>
				<div class="form-group">
					<text>Success Rate :</text>
					<div class="field">
						<input type="text" class="form-control" name="success_rate" value="<?=set_value('success_rate',$success_rate); ?>"/>
						%<span class="error"></span><?php echo form_error('success_rate'); ?>
					</div>
				</div>					
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Update</button>
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



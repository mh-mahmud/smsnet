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
				<form role="form" action="<?=$site_url.$active_controller;?>/memberEdit/<?=$id;?>" method="post">
				<div class="form-group">
					<text>Group :</text>
					<div class="field">
						<select class="form-control" name="group_id">
							<option value="" >---- Select Group ----</option>
							<?php echo html_options($group_options,set_value('group_id',$group_id)) ;?>
						</select>
						<span class='error'>*</span><?php echo form_error('group_id'); ?> 
					</div>
				</div>
				<!--div class="form-group">
					<text>File :</text>
					<div class="field">
						<input type="file" class='uploadForm' id='td_id' class="form-control" name="numberfile" value=""/>
					</div>
				</div-->
								
				<div class="form-group" id="name">
					<text>Name :</text>
					<div class="field">
						<input type="text" class="form-control" name="name" value="<?=set_value('name',$name); ?>"/>
						<span class="error">* </span><?php echo form_error('name'); ?>
					</div>
				</div>
				<div class="form-group" id="phone">
					<text>Phone :</text>
					<div class="field">
						<input type="text" class="form-control" name="phone" value="<?=set_value('phone',$phone); ?>"/>
						<span class="error">* </span><?php echo form_error('phone'); ?>
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
//functin to toggle recipients
$(document).ready(function() {
	
	$(".uploadForm").change((function(e) {
	var hasClass = $("#td_id").hasClass("uploadForm");
	e.preventDefault();
	
	if(hasClass==true){ 
		$("#name").hide();	
		$("#phone").hide();	
	}
	   
	}));
	var numberfile = '<?=$file;?>';	
	var editfile = '<?=$editfile;?>';	
	if(numberfile || editfile){
		$("#name").hide();	
		$("#phone").hide();
	}
	
 });
	
</script>



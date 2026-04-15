<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i>Create Menu
			</div>
			<div class="panel-body">
				<form role="form" action="<?=$site_url.$active_controller;?>/add/<?php echo $parent_id;?>" method="post">
				<div class="form-group">
					<text>Title :</text>
					<div class="field">
						<input type="text" class="form-control" name="title" value="<?=set_value('title'); ?>"/>
						<span class="error">* </span><?php echo form_error('title'); ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Admin Group Permission :</text>
					<div class="field">
						<?php 
						if(!empty($user_group_options)){
							foreach($user_group_options as $val=>$lb){ 					
						?>
						<div class="checkbox checkbox-inline checkbox-primary">
							<input type="checkbox" id="checkbox<?=$val;?>" class="styled" name="user_group_id[]" value="<?=$val;?>" <?=set_checkbox('user_group_id[]',$val); ?>/> <label for="checkbox<?=$val;?>"><?php echo $lb;?></label>
						</div>	                   
						<?php 
							}
						}	
						?>		
						<span class="error">*</span> <?php echo form_error('user_group_id[]'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Order :</text>
					<div class="field">
						<input type="text" class="form-control" name="order" value="<?=set_value('order'); ?>"/>
						<span class="error">* <?php echo form_error('order'); ?></span>
					</div>
				</div>
				<div class="form-group">
					<text>Status :</text>
					<div class="field">
						<select name='status' class='form-control'>
						<?php echo html_options($status_option,set_value('status')); ?>
						</select>
						<span class='error'>* <?php echo form_error('status'); ?> </span>
					</div>
				</div>	
				<div class="form-group">
					<text>Module link :</text>
					<div class="field">
						<input name="module_link" class='form-control' type="text"  value="<?=set_value('module_link'); ?>"/>
						<span class='error'>* <?php echo form_error('module_link'); ?></span> 
					</div>
				</div>	
				<div class="form-group">
					<text>Menu Icon :</text>
					<div class="field">
						<input name="icon" class='form-control' type="text" value="<?=set_value('icon'); ?>"/>
						<span class="display_icon"></span>
						<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#myModal">Select Icon Class</button>
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">      
			<div class="modal-body">
				<?php echo $this->load->element('icon_list');?>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>


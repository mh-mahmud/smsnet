<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i>Update Menu Permission
			</div>
			<div class="panel-body">
				<?php echo $this->session->flashdata('message'); ?>
				<form id="ajax_submit12" role="form" action="<?=$site_url.$active_controller;?>/update_permission/" method="post">
				<div class="form-group">
					<text style="text-align:left !important;">Menu List</text>
					<div class="field"><b>Admin Group</b></div>
				</div>				
				<?php foreach($menu_list as $mm){ ?>
				<div class="form-group">
					<text style="text-align:left !important;"><i class="fa <?php echo $mm['icon'];?>"></i>&nbsp;&nbsp; <?php echo $mm['menu_title'];?></text>
					<div class="field">
						<?php 
						$user_group = explode(',',$mm['user_group_id']);
						foreach($user_group_options as $val=>$lb){ 
						if(in_array($val,$user_group))
						{
							$check = 'checked';
						}else{
							$check = '';
						}	
						?>
							<div class="checkbox checkbox-inline checkbox-primary">
								<input type="checkbox" id="checkbox<?=$mm['id'].$val;?>" class="styled" name="<?php echo $mm['id'];?>[]" value="<?=$val;?>" <?php echo $check; ?>/> <label for="checkbox<?=$mm['id'].$val;?>"><?php echo $lb;?></label>
							</div>							
						<?php 
						}						
						?>	
					</div>
				</div>
				<?php 				
				if(count($mm['child'])>0)
				{	
					foreach($mm['child'] as $cm){
				?>	
				<div class="form-group">
					<text style="text-align:left !important;padding-left:30px;"><i class="fa <?php echo $cm['icon'];?>"></i>&nbsp;&nbsp; <?php echo $cm['menu_title'];?></text>
					<div class="field">
						<?php 
						$user_group_child = explode(',',$cm['user_group_id']);
						foreach($user_group_options as $cval=>$clb){ 
						if(in_array($cval,$user_group_child))
						{
							$check_child = 'checked';
						}else{
							$check_child = '';
						}	
						?>
							<div class="checkbox checkbox-inline checkbox-primary">
								<input type="checkbox" id="checkbox<?=$cm['id'].$cval;?>" class="styled" name="<?php echo $cm['id'];?>[]" value="<?=$cval;?>" <?php echo $check_child; ?>/> <label for="checkbox<?=$cm['id'].$cval;?>"><?php echo $clb;?></label>
							</div>							
						<?php 
						}						
						?>	
					</div>
				</div>
				<?php	
						}
					}
				} 			
				?>
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Update</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
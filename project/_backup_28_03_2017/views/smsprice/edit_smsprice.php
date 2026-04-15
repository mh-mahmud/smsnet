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
					<text>Operator :</text>
					<div class="field">
						<select class="chosen form-control" name="operator_id">
							<option value="" >---- Select Operator ----</option>
							<?php echo html_options($operator_options,set_value('operator_id',$operator_id)) ;?>
						</select>
						<span class='error'>* </span><?php echo form_error('operator_id'); ?> 
					</div>
				</div>
				<?php if($this->session->userdata('user_group_id')==1){?>
				<div class="form-group">
					<text>Reseller Rate :</text>
					<div class="field">
						<input type="text" class="form-control" name="reseller_cost_per_unit" value="<?=set_value('reseller_cost_per_unit',$reseller_cost_per_unit); ?>"/>
						<span class="error">* </span><?php echo form_error('reseller_cost_per_unit'); ?>
					</div>
				</div>
				<?php }?>
				<div class="form-group">
					<text>Customer Rate :</text>
					<div class="field">
						<input type="text" class="form-control" name="customer_cost_per_unit" value="<?=set_value('customer_cost_per_unit',$customer_cost_per_unit); ?>"/>
						<span class="error">* </span><?php echo form_error('customer_cost_per_unit'); ?>
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
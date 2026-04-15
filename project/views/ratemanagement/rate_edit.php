<?php $per_sms_rate=0;?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
			</div>
			<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>
			<div class="panel-body">
				<form role="form" action="<?=$site_url.$active_controller;?>/usersmsprice/<?=$id?>" method="post">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="30" align="center">#</th>
								<th class="short">Operator</th>	
								<th class="short">Unit Cost</th>
							</tr>			
						</thead>						
						<tbody>
						<?php foreach($operator_options as $key=>$val){?>
							<tr>
								<td>#</td>
								<td  align="center">
									<text><?=$val['full_name'];?> </text>
									<input type="hidden" class="form-control" name="name[]; ?>" value=""/>
									<input type="hidden" class="form-control" name="operator_id<?=$key; ?>" value="<?=$val['id']; ?>"/>
								</td>
								<td  align="center">
									<input type="text" id="rate" class="form-control" name="unit_cost<?=$key; ?>" value="<?=set_value('unit_cost',$val['unit_cost']); ?>"/>
									<span class="error">*</span><?php echo form_error('unit_cost'); ?>
								</td>

							</tr>
						<?php }?>
						</tbody>
					</table>
				</div>
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Update</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div>
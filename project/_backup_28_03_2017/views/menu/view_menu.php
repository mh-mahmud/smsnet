<div class="grid_board">
<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller;?>" method="post">
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-list-alt"></i>Menu Details				
			</div>
			<div class="panel-body">
				<div class="form-group">
					<text>Title :</text>
					<div class="field"><?php echo $title;?></div>
				</div>
				<div class="form-group">
					<text>Admin Group Permission :</text>
					<div class="field">
						<?php 
							if(!empty($user_group_options)){
								foreach($user_group_options as $val=>$lb){ 	
									foreach($user_group as $cval=>$clb){
											if($val==$clb){
												echo $lb.',';
											}
										}
									}
							}	
						?>
					</div>
				</div>
				<div class="form-group">
					<text>Order :</text>
					<div class="field"><?php echo $order;?></div>
				</div>
				<div class="form-group">
					<text>Status :</text>
					<div class="field"><?php echo $status;?></div>
				</div>
				<div class="form-group">
					<text>Module Link :</text>
					<div class="field"><?php echo $module_link;?></div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-table"></i><?php echo $page_title; ?>
				<div class="box-tools pull-right">
					<a class="ajax_link" href="<?=$site_url.$link_action;?>">
						<button class="btn btn-primary btn-xs" type="button"><i class='fa fa-plus'></i> Add Submenu</button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">
						<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
													
							<?php $this->load->element('menu_grid_board');?>
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>	
</form>
</div>
<script type='text/javascript'>
$(document).ready(function() {
	var menuItems=[
					{title:'<i class="fa fa-check-circle text-success"> Publish</i>',value:'publish'},
					{title:'<i class="fa fa-times-circle text-danger"> Unpublish</i>',value:'unpublish'}
				  ];
	$("td.stat_menu a").statusMenu({items:menuItems}); 
});
</script>
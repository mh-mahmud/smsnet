<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller;?>" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
					<div class="box-tools pull-right">
						<a class="ajax_link" href="<?=$site_url.$link_action;?>">
							<button class="btn btn-primary btn-xs" type="button"><i class='fa fa-plus'></i> <?php echo $link_title; ?></button>
						</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
							<div class="grid_board">							
								<?php $this->load->element('grid_board');?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>


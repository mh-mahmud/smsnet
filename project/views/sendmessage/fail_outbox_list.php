<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller.'/failedMessageList';?>" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
				</div>
				<div class="box-tools pull-right">
					<a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
					<a href="<?= $site_url . $active_controller; ?>/word/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
					<a href="<?= $site_url . $active_controller; ?>/failMessageExcel/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
					<a href="<?= $site_url . $active_controller; ?>/pdf/<?=$active_function;?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
				</div>
				
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
						
								<?php $this->load->element('sendmessage_grid_board');?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>

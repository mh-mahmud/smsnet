<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller;?>/routeindex" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
					<div class="box-tools pull-right">
						<a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
						<a href="<?= $site_url . $active_controller; ?>/word/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
						<a href="<?= $site_url . $active_controller; ?>/Excel/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
						<a href="<?= $site_url . $active_controller; ?>/pdf/<?=$active_function;?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
						<a href="<?= $site_url . $link_action; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class='fa fa-plus'></i> New Route</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
							<table class="table table-bordered">
									<tr>
									<td>
									<select class="chosen form-control lg" name="user_id" id="user_id">
										<option value="">---- Select User -----</option>
										<?php echo html_options($user_options,htmlspecialchars($_POST['user_id'])); ?>	
									</select>									
									</td>
									<td>
									<select class="chosen form-control lg" name="operator_id" id="operator_id">
										<option value="">---- Select operator -----</option>
										<?php echo html_options($operator_options,htmlspecialchars($_POST['operator_id'])); ?>	
									</select>									
									</td>
									<td>
									<select class="chosen form-control lg" name="channel_id" id="channel_id">
										<option value="">---- Select channel -----</option>
										<?php echo html_options($channel_options,htmlspecialchars($_POST['channel_id'])); ?>	
									</select>									
									</td>
									<td width="100" align="center">
										<button type="submit" name="submit" class="btn btn-primary btn-sm"><i class='fa fa-search'></i></button>
										<button type="submit" name="reset" class="btn btn-warning btn-sm"><i class='fa fa-refresh'></i></button>
									</td>
								</tr>
							</table>							
								<?php $this->load->element('administration_grid_board');?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>
<script>
$(document).ready(function(){

	jQuery(".chosen").chosen();
});
</script>
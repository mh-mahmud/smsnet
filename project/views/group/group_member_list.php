<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller.'/memberList';?>" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
					<div class="box-tools pull-right">
						<a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
						<a href="<?= $site_url . $active_controller; ?>/word/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
						<a href="<?= $site_url . $active_controller; ?>/excel/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
						<a href="<?= $site_url . $active_controller; ?>/pdf/<?=$active_function;?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
						<a href="<?= $site_url . $link_action; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class='fa fa-plus'></i> New Member</a>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
							<table class="table table-bordered">
								<tr>
									<td><input type="text" class="form-control lg" name="name" placeholder="Name" value="<?php echo htmlspecialchars($_POST['name']); ?>"/></td>
									<td><input type="text" class="form-control lg" name="phone" placeholder="Phone" value="<?php echo htmlspecialchars($_POST['phone']); ?>"/></td>
									<td width="100" align="center">
										<button type="submit" name="submit" class="btn btn-primary btn-sm"><i class='fa fa-search'></i></button>
										<button type="submit" name="reset" class="btn btn-warning btn-sm"><i class='fa fa-refresh'></i></button>
									</td>
								</tr>
							</table>							
								<?php $this->load->element('group_grid_board');?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>
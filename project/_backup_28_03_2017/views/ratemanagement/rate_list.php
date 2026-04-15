<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller;?>" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
							<table class="table table-bordered">
								<tr>
									<td width=""><input type="text" class="form-control lg" name="name" placeholder="Rate name" value="<?php echo htmlspecialchars($_POST['name']); ?>"/></td>
									<td width="100" align="center">
										<button type="submit" name="submit" class="btn btn-primary btn-sm"><i class='fa fa-search'></i></button>
										<button type="submit" name="reset" class="btn btn-warning btn-sm"><i class='fa fa-refresh'></i></button>
									</td>
								</tr>
							</table>							
								<?php $this->load->element('rate_grid_board');?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</form>

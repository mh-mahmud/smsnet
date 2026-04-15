<div id="page-inner">	
	<div class="row">		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span>User SMS Status </span>
		</span>
				</div> 
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>S No.</th>
									<th>User</th>
									<th>Sent</th>
									<th>Deliverd</th>
									<th>Processing</th>
									<th>Queue</th>
									<th>Failed</th>
								</tr>
							</thead>
							<tbody>
							<?php if(count($statuswiseSmsReport)>0){ foreach($statuswiseSmsReport as $key=>$val){?>
								<tr>
									<td align="center"><?=$key+1;?></td>
									<td align="center"><?=$val['username'];?></td>
									<td align="center"><?=$val['total_Sent'][0]['total'];?></td>
									<td align="center"><?=$val['total_Deliverd'][0]['total'];?></td>
									<td align="center"><?=$val['total_Processing'][0]['total'];?></td>
									<td align="center"><?=$val['total_Queue'][0]['total'];?></td>
									<td align="center"><?=$val['total_Failed'][0]['total'];?></td>
								</tr>
							<?php }}else{?> 
								<tr><td colspan="7">No Data</td></tr>	
							<?php }?> 
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
	<div class="row">
		<footer><p>All right reserved. App by: IAMDigital</p></footer>
	</div>
</div>
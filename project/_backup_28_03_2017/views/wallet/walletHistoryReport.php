<div id="page-inner">	
	<div class="row">		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span>User Wallet Status </span>
		</span>
				</div> 
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>S No.</th>
									<th>User</th>
									<th>TotalCredite</th>
									<th>TotalDeposit</th>
									<th>TotalTransfer</th>
								</tr>
							</thead>
							<tbody>
							<?php if(count($walletHistoryReport)>0){ foreach($walletHistoryReport as $key=>$val){?>
								<tr>
									<td align="center"><?=$key+1;?></td>
									<td align="center"><?=$val['username'];?></td>
									<td align="center"><?=$val['TotalCredite'];?></td>
									<td align="center"><?=$val['TotalDeposit'];?></td>
									<td align="center"><?=$val['TotalTransfer'];?></td>
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
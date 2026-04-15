<div id="page-inner">
	<div class="row">
		<div class="col-md-12">
			<h1 class="page-header">
				Dashboard 
				<span style="color:#5CB85C;" id=tick2></span>
			</h1>
		</div>
	</div>
	<div class="row">
		<?php if($this->session->userdata('user_group_id')!=1){?>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-green">
				<div class="panel-body">
					<i class="fa fa-bar-chart-o fa-5x"></i>
					<h3><?php if($creditBalance['credite_balance']){echo $creditBalance['credite_balance'];}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-green">
					Total Credit Balance

				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-green">
				<div class="panel-body">
					<i class="fa fa-bar-chart-o fa-5x"></i>
					<h3><?php if($creditBalance['credite_balance']){echo $creditBalance['credite_balance'];}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-green">
					Total SMS Balance

				</div>
			</div>
		</div>
		<?php }?><?php if($this->session->userdata('user_group_id')==1){?>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-green">
				<div class="panel-body">
					<i class="fa fa-bar-chart-o fa-5x"></i>
					<h3><?php if($reseller){echo $reseller;}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-green">
					Total Reseller

				</div>
			</div>
		</div>
		<?php } if($this->session->userdata('user_group_id')==1 || $this->session->userdata('user_group_id')==2){?>           
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-brown">
				<div class="panel-body">
					<i class="fa fa-users fa-5x"></i>
					<h3><?php if($customer){echo $customer;}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-brown">
					Total Customer

				</div>
			</div>
		</div>
		<?php }?>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-blue">
				<div class="panel-body">
					<i class="fa fa-comments fa-5x"></i>
					<h3><?php if($sentMessage){echo $sentMessage;}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-blue">
					Total sent SMS

				</div>
			</div>
		</div>
		<div class="col-md-3 col-sm-12 col-xs-12">
			<div class="panel panel-primary text-center no-boder bg-color-red">
				<div class="panel-body">
					<i class="fa fa fa-comments fa-5x"></i>
					<h3><?php if($failedMessage){echo $failedMessage;}else{echo 0;}?></h3>
				</div>
				<div class="panel-footer back-footer-red">
					Total Failed SMS

				</div>
			</div>
		</div>
	</div>
    <?php if($this->session->userdata('user_group_id')==1 || $this->session->userdata('user_group_id')==2){?>           
	<div class="row">		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span>Last 10 due bill list </span><span style="color:green;"><?=$this->session->flashdata('message');
		?></span>
				</div> 
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>S No.</th>
									<th>User</th>
									<th>Amount</th>
									<th>Curency</th>
									<th>Date</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($recentBill as $key=>$val){?>
								<tr>
									<td align="center"><?=$key+1;?></td>
									<td align="center"><?=$val['username'];?></td>
									<td align="right"><?=$val['total'];?></td>
									<td align="center"><?=$val['curency'];?></td>
									<td align="center"><?=$val['created'];?></td>
									<td width="5%" align="center">
										<a href="<?=$site_url.$active_controller.'/sendbillbyemail/'.$val['email'] ?>" title="Click here for email"><span class="action_btn btn-success"><i class="fa fa-envelope"></i></span></a>
									</td>
								</tr>
							<?php }?> 
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
	          
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
	<?php }?>
	<?php if($this->session->userdata('user_group_id')==3){?>           
	<div class="row">		
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<span>Last 10 sent sms list </span>
		
				</div> 
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>S No.</th>
									<th>Source</th>
									<th>Message</th>
									<th>Date</th>
								</tr>
							</thead>
							<tbody>
							<?php foreach($recentSmsRequest as $key=>$val){?>
								<tr>
									<td align="center"><?=$key+1;?></td>
									<td align="center"><?=$val['source'];?></td>
									<td align="left"><?=$val['message'];?></td>
									<td align="center"><?=$val['date'];?></td>
								</tr>
							<?php }?> 
							</tbody>
						</table>
					</div>
				</div>
			</div>

		</div>
	</div>
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
	<?php }?>
	<div class="row">
		<footer><p>All right reserved. App by: IAMDigital</p></footer>
	</div>
</div>
<script>
function show2(){
	if (!document.all&&!document.getElementById)
	return
	thelement=document.getElementById? document.getElementById("tick2"): document.all.tick2
	var Digital=new Date()
	var hours=Digital.getHours()
	var minutes=Digital.getMinutes()
	var seconds=Digital.getSeconds()
	var dn="PM"
	if (hours<12)
	dn="AM"
	if (hours>12)
	hours=hours-12
	if (hours==0)
	hours=12
	if (minutes<=9)
	minutes="0"+minutes
	if (seconds<=9)
	seconds="0"+seconds
	var ctime=hours+":"+minutes+":"+seconds+" "+dn
	thelement.innerHTML="<b style='font-size:10;color:#5CB85C; float:right;'>"+ctime+"</b>"
	setTimeout("show2()",1000)
}
window.onload=show2
</script>
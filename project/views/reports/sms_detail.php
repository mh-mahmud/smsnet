<form id="ajax_submit" role="form" action="<?=$site_url.$active_controller.'/sms_detail';?>" method="post">
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-table"></i><?php echo $page_title; ?>
				</div>
				<div class="box-tools pull-right">
					<a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
					<a href="<?= $site_url . $active_controller; ?>/word/<?=$active_function;?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
					<a href="#" id="btnExport" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
					<a href="<?= $site_url . $active_controller; ?>/pdf/<?=$active_function;?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
							<table class="table table-bordered">
								<tr>
									</td>
									<td width="15%">
									<select class="chosen form-control lg" name="user_id" id="user_id">
										<option value="">---- Select User -----</option>
										<?php echo html_options($user_options,htmlspecialchars($_POST['user_id'])); ?>	
									</select>									
									</td>
									<td><input type="text" class="form-control lg datepicker" name="from_date" placeholder="From Date" value="<?php echo htmlspecialchars($_POST['from_date']); ?>"/></td>
									<td><input type="text" class="form-control lg datepicker" name="to_date" placeholder="To Date" value="<?php echo htmlspecialchars($_POST['to_date']); ?>"/></td>
									<td width="100" align="center">
										<button type="submit" name="submit" class="btn btn-primary btn-sm"><i class='fa fa-search'></i></button>
										<button type="submit" name="reset" class="btn btn-warning btn-sm"><i class='fa fa-refresh'></i></button>
									</td>
								</tr>
							</table>
								<div id="sms_detail">							
									<?php $this->load->element('sms_detail_board');?>
								</div>
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
	$(".datepicker").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
});

$(document).ready(function() {
  $("#btnExport").click(function(e) {
    e.preventDefault();
	//getting data from our table
	
	var filename = 'sms_bill_for_'+ "<?= $billingReport[0]['username'].' '.$billingReport[0]['date_from'] . ' to ' . $billingReport[0]['date_to']; ?>";
    var data_type = 'data:application/vnd.ms-excel';
    var table_div = document.getElementById('sms_detail');
    var table_html = table_div.outerHTML.replace(/ /g, '%20');

    var a = document.createElement('a');
    a.href = data_type + ', ' + table_html;
    a.download = filename + '.xls';
    a.click();
	//location.reload();
  });
});


</script>
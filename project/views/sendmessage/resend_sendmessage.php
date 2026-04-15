<style>
	#countBox, #countBox2, #rate, #amount {
	width: 50%; 
	font-weight: bold; 
	height: 15px;
	border: 1px solid #fff; 
	background-color: #fff;	
}
</style>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
				<div class="box-tools pull-right">
					<a class="ajax_link" href="<?=$site_url.$link_action;?>">
						<button class="btn btn-primary btn-xs" type="button"><i class="fa fa-table"></i> <?php echo $link_title; ?></button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<form role="form" action="<?=$site_url.$active_controller;?>/resend/<?=$id;?>" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<text>Masking :</text>
					<div class="field">
						<select class="form-control" name="senderID">
							<option value="" >---- Select Mask ----</option>
							<?php echo html_options($mask_options,set_value('senderID',$senderID)) ;?>
						</select>
						<span class="error"></span><?php echo form_error('senderID'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Recipients :</text>
					<div class="field">
						<a class="btn btn-primary btn-xs" href="javascript:()" title="number" id="numberButton">Type Numbers</a>
						<a class="btn btn-primary btn-xs" href="javascript:()" title="group" id="groupButton">Use Group</a>
						<a class="btn btn-primary btn-xs" href="javascript:()" title="file" id="uploadButton">Upload File</a>	
						<input type="hidden" id="receiver" name="receiver" value="" />
					</div>
				</div>
				
				<div class="form-group" id="numberBox">
					<text>Seprate Numbers by Comma. (include country code for international destinations)	:</text>
					<div class="field">
						<textarea type="text" rows="7" class="form-control" onBlur="count(this,this.form.countBox);" onKeyUp="count(this,this.form.countBox);" id="recipientList" name="recipient"><?=set_value('recipient',$recipient) ; ?></textarea></br>
						<input type="hidden" id="temp_count" name="temp_count" value="" />
						<span style="font-size: 12px; font-weight: bold;" id="display_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span> 
						<span class="error"></span><?php echo form_error('recipient'); ?>
					</div>  
				</div>
				
				<div class="form-group" id="groupBox">
					<text>Group	:</text>
					<div class="field">
					<?php foreach($group_options as $val){?>
						<?php if($val['total_member']>0){?>
						<input name="group_id[]" class="group_id" type="checkbox" value="<?=$val['group_id'];?>" <?php echo 'checked'; ?>> <?=$val['name'].'('.$val['total_member'].')';?> &nbsp;&nbsp;
					<?php }}?>
					<span class="error"></span><?php echo form_error('group_id'); ?>
					</div>  
				</div>
				
				<div class="form-group" id="fileupload">
					<text>File	:</text>
					<div class="field">
						<input name="file" id="upload" type="file" value=""> 
						<input type="hidden" id="temp_count" name="temp_count" value="" />
						<input type="hidden" id="" name="file1" value="<?=$file;?>" />
						<span style="font-size: 12px; font-weight: bold;" id="display_File_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span> 
					</div>  
				</div>
				
				<div class="form-group">
					<text>Message: 160 Characters = 1 SMS	:</text>
					<div class="field">
						<textarea type="text" rows="7" class="form-control" name="message" onBlur="count2(this,this.form.countBox2,1000);" onKeyUp="count2(this,this.form.countBox2,1000);" id="message"><?=set_value('message',$message); ?></textarea></br>
						<input readonly onFocus="this.blur();" name="countBox2" value = "0 Characters Used" id="countBox2" /> 
						<input type="hidden" name="page" value = "" id="page" /> 
						<span class="error"></span><?php echo form_error('message'); ?>
					</div>
				</div>					
				<div class="form-group">
					<text>Schedule :</text>
					<div class="field">
						<input name="sms_type" type="checkbox" id="smstype" value="ScheduleMessage <?php if($sms_type=='ScheduleMessage'){echo 'checked';}?>" /> Schedule &nbsp;&nbsp;
						<input name="scheduleDateTime" class="form-control datepicker" size="2" type="text" id="datepicker" value="<?=set_value('scheduleDateTime',$scheduleDateTime);?>" placeholder="Date Time" />
						<span class="error"></span><?php echo form_error('scheduleDateTime'); ?>
					</div>
				</div>					
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Resend Message</button>
					
				</div>
				</form>
			</div>
		</div>
	</div>
</div> 
<?=validation_errors(); ?>
<script>
	$(function() {
	$('#upload').change( function(event) {
		var f = event.target.files[0];
		if (f) {
			var r = new FileReader();
	
			r.onload = function(e) { 
				var contents = e.target.result;
				var res = contents.split(","); 
				$("#display_File_count").text(res.length);
				$('#temp_count').value = res.length;
				//$(".group_id" ).prop( "checked", false );
			}
			r.readAsText(f);
		}
	});
});
	
	
$(document).ready(function() {
  $("#recipientList").on('keyup', function() {
	  this.value = $.map(this.value.split(","), $.trim).join(", ");
      var words = this.value.match(/\S+/g).length;

      $('#display_count').text(words);
	  $('#temp_count').value = words;
	  $("#temp_count").val(words);
  });
}); 
</script>
<script>
			
//functin to toggle recipients
$(document).ready(function() {
	$("#numberBox").show();
	$("#groupBox").hide();
	$("#fileupload").hide();
	$("#datepicker").hide();
	$("#hour").hide();
	$("#min").hide();
	$("#receiver").val('number');
	$("#numberButton").click(function(){
		$("#numberBox").show();
		$("#groupBox").hide();
		$("#fileupload").hide();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		//$(".group_id" ).prop( "checked", false );
	});
	
	var recipient = '<?=$recipient;?>';
	var group = '<?=$group_id;?>';
	var file = '<?=$file;?>';
	var smsType = '<?=$sms_type;?>';
	if(recipient!=null){
		$("#numberBox").show();
		$("#groupBox").hide();
		$("#fileupload").hide();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		$(".group_id" ).prop( "checked", false );
	}
	$("#groupButton").click(function(){
		$("#numberBox").hide();
		$("#groupBox").show();
		$("#fileupload").hide();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		//$(".group_id" ).prop( "checked", true );
	});
	if(group!=''){
		$("#numberBox").hide();
		$("#groupBox").show();
		$("#fileupload").hide();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		$(".group_id" ).prop( "checked", true );
	}
	$("#uploadButton").click(function(){
		$("#numberBox").hide();
		$("#groupBox").hide();
		$("#fileupload").show();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		$(".group_id" ).prop( "checked", false );
	});
	if(file!=''){
		$("#numberBox").hide();
		$("#groupBox").hide();
		$("#fileupload").show();
		var title = $(this).attr('title');
		$("#receiver").val(title);
		$(".group_id" ).prop( "checked", false );
	}
	
	$("#smstype").click(function(){
		var schedule = $("#smstype").is(':checked') ? 1 : 0;
		if(schedule==1){$("#datepicker").show();}else{$("#datepicker").hide();}
		if(schedule==1){$("#hour").show();}else{$("#hour").hide();}
		if(schedule==1){$("#min").show();}else{$("#min").hide();}
	});
	
	if(smsType=='ScheduleMessage'){
		var schedule = $("#smstype").is(':checked') ? 1 : 0;
		if(schedule==1){$("#datepicker").show();}else{$("#datepicker").hide();}
		if(schedule==1){$("#hour").show();}else{$("#hour").hide();}
		if(schedule==1){$("#min").show();}else{$("#min").hide();}
	}
 });

	
</script>

<script>
//count message lenght 
	function count2(field,countfield,maxlimit) {
		var draftBox = document.getElementById('draftBox');
		var draftRecipient = document.getElementById('draftRecipient');
		var field2 = document.getElementById('recipientList');
	if (field.value.lenght > 1000) {
		field.value = field.value.substring(0,1000);
		field.blur();
		return false;
	} else {
		var smslenght = 160;
		if(message.length > 160){
			var smslenght = 145;
		}
		var pages = field.value.length /smslenght;
			if(pages < 1) { var page = '1';	}
			if(pages == 1) { var page = '1';}
			if(pages > 1) { var page = '2';	}
			if(pages > 2) {	var page = '3';	}
			if(pages > 3) {	var page = '4';	}
			if(pages > 4) {	var page = '5';	}
			if(pages > 5) {	var page = '6';	}
			if(pages > 6) {	var page = '7';	}
															
		countfield.value = field.value.length + " of 1000 Characters Used ("+page+" SMS)";
		document.getElementById('page').value=page;
		}
		draftBox.value = field.value;
		draftRecipient.value = field2.value;
	}
	
</script>

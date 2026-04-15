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
				<form role="form" action="<?=$site_url.$active_controller;?>/addressBookAdd" method="post">
				<div class="form-group">
					<text>Masking :</text>
					<div class="field">
						<select class="form-control" name="masking"/>
							<option value="">ssdsd</option>
							<option value="">ssdsd</option>
							<option value="">ssdsd</option>
						</select>
						<span class="error"></span><?php echo form_error('masking'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Recipients :</text>
					<div class="field">
						<a class="btn btn-primary btn-xs" href="javascript:()" id="numberButton">Type Numbers</a>
						<a class="btn btn-primary btn-xs" href="javascript:()" id="groupButton">Use Group</a>
						<a class="btn btn-primary btn-xs" href="javascript:()" id="uploadButton">Upload File</a>			
					</div>
				</div>
				
				<div class="form-group" id="numberBox">
					<text>Seprate Numbers by Comma. (include country code for international destinations)	:</text>
					<div class="field">
						<textarea type="text" rows="7" class="form-control" onBlur="count(this,this.form.countBox);" onKeyUp="count(this,this.form.countBox);" id="recipientList" name="recipientList"  ><?php //echo $recipientList; ?></textarea></br>
						<span style="font-size: 12px; font-weight: bold;" id="display_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span> 
						<input type="hidden" id="temp_count" name="temp_count" value="" />	
					</div>  
				</div>
				
				<div class="form-group" id="groupBox">
					<text>Group	:</text>
					<div class="field">
						<input name="schedule" type="checkbox" value="Yes"> asdas(20) &nbsp;&nbsp;
						<input name="schedule" type="checkbox" value="Yes"> asdas(400) &nbsp;&nbsp;
						<input name="schedule" type="checkbox" value="Yes"> asdas(1000) &nbsp;&nbsp;
						<input name="schedule" type="checkbox" value="Yes"> asdas(490) </br>
						
					</div>  
				</div>
				
				<div class="form-group" id="fileupload">
					<text>File	:</text>
					<div class="field">
						<input name="schedule" type="file" value=""> 
						<span style="font-size: 12px; font-weight: bold;" id="display_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span> 
						
					</div>  
				</div>
				
				<div class="form-group">
					<text>Message: 70 Characters = 1 SMS	:</text>
					<div class="field">
						<textarea type="text" rows="7" class="form-control" name="description" onBlur="count2(this,this.form.countBox2,1000);" onKeyUp="count2(this,this.form.countBox2,1000);" id="message"><?=set_value('description'); ?></textarea></br>
						<input readonly onFocus="this.blur();" name="countBox2" value = "0 Characters Used" id="countBox2" /> 
					</div>
				</div>					
				<div class="form-group">
					<text>Schedule :</text>
					<div class="field">
						<input name="smstype" type="checkbox" id="smstype" value="schedule" /> Schedule &nbsp;&nbsp;
						<input name="schedule" class="form-control datepicker" size="5" type="text" id="datepicker" value="" placeholder="Date" />
					</div>
				</div>					
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Send Message</button>
					<button type="reset" class="btn btn-warning btn-sm">Cencel Message</button>
					
				</div>
				</form>
			</div>
		</div>
	</div>
</div> 

<script>
$(document).ready(function() {
  $("#recipientList").on('keyup', function() {
	  this.value = $.map(this.value.split(","), $.trim).join(", ");
      var words = this.value.match(/\S+/g).length;

      $('#display_count').text(words);
	  $('#temp_count').value = words;
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
	$("#numberButton").click(function(){
		$("#numberBox").show();
		$("#groupBox").hide();
		$("#fileupload").hide();
	});
	$("#groupButton").click(function(){
		$("#numberBox").hide();
		$("#groupBox").show();
		$("#fileupload").hide();
	});
	$("#uploadButton").click(function(){
		$("#numberBox").hide();
		$("#groupBox").hide();
		$("#fileupload").show();
	});
	
	$("#smstype").click(function(){
		var schedule = $("#smstype").is(':checked') ? 1 : 0;
		if(schedule==1){$("#datepicker").show();}else{$("#datepicker").hide();}
	});
 });

	
</script>

<script>
//count message lenght 
	function count2(field,countfield,maxlimit) {
		var draftBox = document.getElementById('draftBox');
		var draftRecipient = document.getElementById('draftRecipient');
		var field2 = document.getElementById('recipientList');
	if (field.value.lenght > 500) {
		field.value = field.value.substring(0,500);
		field.blur();
		return false;
	} else {
		var smslenght = 70;
		if(message.length > 70){
			var smslenght = 60;
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
															
		countfield.value = field.value.length + " of 500 Characters Used ("+page+" SMS)";
		}
		draftBox.value = field.value;
		draftRecipient.value = field2.value;
	}
</script>

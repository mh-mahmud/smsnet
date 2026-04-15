<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i><?php echo 'Sent Message view'; ?>

			</div>
			<div class="panel-body">
				<div class="form-group">
					<text>Sender ID :</text>
					<div class="field">
						<?php echo $data['senderID']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Sent From :</text>
					<div class="field">
						<?php echo $data['sentFrom']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>SMS Type :</text>
					<div class="field">
						<?php echo $data['sms_type']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>IP :</text>
					<div class="field">
						<?php echo $data['IP']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Status :</text>
					<div class="field">
						<?php echo $data['status']; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Message :</text>
					<div class="field">
						<?php echo $data['message']; ?>
					</div>
				</div>
				<div class="form-group">
				<text>Recipients :</text>
					<div class="field">
						<?php if(is_array($data['recipient'])){foreach($data['recipient'] as $val){?>
						<?php if(is_array($val)){echo $val['phone'].',';}else{echo $val.',';}  ?>	
						
						<?php }}?>
						<?php if(!is_array($data['recipient'])){?>
						
						<?php echo $data['recipient'].','; ?>	
						
						<?php }?>
						
					</div>
				</div>				
			</div>
		</div>
	</div>
</div> 




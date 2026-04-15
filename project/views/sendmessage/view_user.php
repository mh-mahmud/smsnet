<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
				<div class="box-tools pull-right">
					<a class="ajax_link" href="<?=$site_url.'user/edit/'.$this->session->userdata('user_userid').'/profile';?>">
						<button class="btn btn-primary btn-xs" type="button"><i class="fa fa-pencil"></i> <?php echo 'Edit Profile'; ?></button>
					</a>
					<a class="ajax_link" href="<?=$site_url.$link_action;?>">
						<button class="btn btn-primary btn-xs" type="button"><i class="fa fa-table"></i> <?php echo $link_title; ?></button>
					</a>
				</div>
			</div>
			<div class="panel-body">
				<div class="form-group">
					<text>senderID :</text>
					<div class="field">
						<?php echo $data[0]['senderID']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>sentFrom :</text>
					<div class="field">
						<?php echo $data[0]['sentFrom']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>sms_type :</text>
					<div class="field">
						<?php echo $data[0]['sms_type']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>IP :</text>
					<div class="field">
						<?php echo $data[0]['IP']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>status :</text>
					<div class="field">
						<?php echo $data[0]['status']; ?>
					</div>
				</div>
				<div class="form-group">
					<text>message :</text>
					<div class="field">
						<?php echo $data[0]['message']; ?>
					</div>
				</div>
				<div class="form-group">
				<text>recipient :</text>
					<div class="field">
						<table class="table table-striped table-bordered table-hover">
						<?php //foreach($data['recipient'] as $val){?>
						<tr>
						<td><?php ///echo $val; ?></td>	
						</tr>
						<?php //}?>
						</table>
					</div>
				</div>				
			</div>
		</div>
	</div>
</div> 




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
					<text>Username :</text>
					<div class="field">
						<?php echo $username; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Mobile Number :</text>
					<div class="field">
						<?php echo $mobile; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Email :</text>
					<div class="field">
						<?php echo $email; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Address :</text>
					<div class="field">
						<?php echo $address; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Admin Group :</text>
					<div class="field">
						<?php echo $user_type; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Status :</text>
					<div class="field">
						<?php echo $status; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 




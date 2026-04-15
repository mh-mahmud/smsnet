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
				<div class="form-group">
					<text>Name :</text>
		
					<div class="field">
						<?php echo $name; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Operator Name :</text>
					<div class="field">
						<?php echo $operator_name; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Channel Type :</text>
					<div class="field">
						<?php echo $channel_type; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Url :</text>
					<div class="field">
						<?php echo $url; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Username :</text>
					<div class="field">
						<?php echo $username; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Password :</text>
					<div class="field">
						<?php echo $password; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Ip :</text>
					<div class="field">
						<?php echo $ip; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Port :</text>
					<div class="field">
						<?php echo $port; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Created :</text>
					<div class="field">
						<?php echo $created; ?>
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




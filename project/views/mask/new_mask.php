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
				<form role="form" action="<?=$site_url.$active_controller;?>/add" method="post">
				<div class="form-group">
					<text>Mask :</text>
					<div class="field">
						<input type="text" class="form-control" name="senderID" value="<?=set_value('senderID'); ?>"/>
						<span class="error">* </span><?php echo form_error('senderID'); ?>
					</div>
				</div>
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Submit</button>
					<button type="reset" class="btn btn-warning btn-sm">Reset</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div> 




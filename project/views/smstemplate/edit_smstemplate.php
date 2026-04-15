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
				<form role="form" action="<?=$site_url.$active_controller;?>/edit/<?=$id;?>" method="post">
				<div class="form-group">
					<text>Title :</text>
					<div class="field">
						<input type="text" class="form-control" name="title" value="<?=set_value('title',$title); ?>"/>
						<span class="error">* </span><?php echo form_error('title'); ?>
					</div>
				</div>
				<div class="form-group">
					<text>Description :</text>
					<div class="field">
						<textarea type="text" rows="7" class="form-control"  name="description"><?= set_value('description',$description); ?></textarea></br>
                        <span class="error">* </span><?php echo form_error('description'); ?>
					</div>
				</div>
				<div class="form-group action">
					<button type="submit" class="btn btn-primary btn-sm">Update</button>
				</div>
				</form>
			</div>
		</div>
	</div>
</div> 
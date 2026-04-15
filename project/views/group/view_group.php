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
					<text>Group Name :</text>
					<div class="field">
						<?php echo $group[0]['groupName']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Created By :</text>
					<div class="field">
						<?php echo $group[0]['user_id']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Created :</text>
					<div class="field">
						<?php echo $group[0]['created']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Description :</text>
					<div class="field">
						<?php echo $group[0]['description']; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Members :</text>
					<div class="field">
						<table class="table table-striped table-bordered table-hover">
						<?php foreach($group as $val){?>
						<tr>
						<td><?php echo $val['name']; ?></td><td><?php echo $val['phone']; ?></td>	
						</tr>
						<?php }?>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div> 




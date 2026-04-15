<div class="loader">
	<div class="loader_icon">
		<i class="fa fa-spinner fa-spin fa-3x"></i>
	</div>	
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th width="30" align="center">#</th>
				<?php foreach($labels as $field=>$lbl):?>
				<th class="short">
					<?php if($sort_on==$field):?>
					 <a href="<?=sprintf($sort_url,$field)?>" class='active'>
						<?php echo $lbl;?>
						<?php if($sort_type=='asc'):?>
							<i class='fa fa-caret-down'></i>
						<?php else:?>
							<i class='fa fa-caret-up'></i>
						<?php endif?>
					</a>
					<?php else: ?>
					<a href="<?=sprintf($sort_url,$field)?>"><?php echo $lbl;?></a>
					<?php endif?>
				</th>									
				<?php endforeach ?>
				<?php if(!isset($grid_action)||(is_array($grid_action) && !empty($grid_action))):?>
				<th width="100" align="center"><a href="#">Action</a></th>
				<?php endif?>	
			</tr>			
		</thead>						
		<tbody>
			
		<?php foreach($records as  $row):?>
		<tr class="<?php echo cycle(array('bg1','bg'));?>">
			<td><?php echo sprintf('%02d',++$offset);?></td>
			<?php foreach($labels as  $field=>$label):?>
			<?php 
			if($field=='status'):
			if(strtolower($row[$field])=='publish' OR strtolower($row[$field])=='active' OR strtolower($row[$field])=='approved')
			{
				$status_title = '<span class="label label-success">'.ucfirst(strtolower($row[$field])).'</span>';
			}else if(strtolower($row[$field])=='unpublish' OR strtolower($row[$field])=='inactive'){
				$status_title = '<span class="label label-danger">'.ucfirst(strtolower($row[$field])).'</span>';
			}else if(strtolower($row[$field])=='pending'){
				$status_title = '<span class="label label-warning">'.ucfirst(strtolower($row[$field])).'</span>';
			}
			else{
				$status_title = ucfirst(strtolower($row[$field]));
			}
			?>
			<td width="100" align="center" class='stat_menu stat_menu_id_<?php echo $row['id']?>'>
			<a href="<?php echo $site_url.$active_controller;?>/set_status/<?php echo $row['id']?>" class="<?=strtolower($row[$field])?>"><?php echo $status_title; ?> </a>
			</td>	 
			<?php 
			elseif($field=='send_status'):
			if(strtolower($row[$field])=='success')
			{
				$mg_status_title = '<span class="label label-success">'.ucfirst(strtolower($row[$field])).'</span>';
			}else if(strtolower($row[$field])=='failed'){
				$mg_status_title = '<span class="label label-danger">'.ucfirst(strtolower($row[$field])).'</span>';
			}else if(strtolower($row[$field])=='pending'){
				$mg_status_title = '<span class="label label-warning">'.ucfirst(strtolower($row[$field])).'</span>';
			}
			else{
				$mg_status_title = ucfirst(strtolower($row[$field]));
			}
			?>
			<td width="100" align="center">
			<?php echo $mg_status_title; ?>
			</td>  
		   <?php elseif($field=='link'): ?>
			<td class='lnk'><a href="<?php echo $row[$field] ?>" title='visit now'><?php echo $row[$field]; ?> </a></td>
			<?php elseif(in_array($field,array('comment','description'))):?>
			<td class='view' align='justify'><?php echo word_limiter($row[$field],10); ?></td>
			<?php elseif($field=='order44'): ?>
			<td><input type='text' class='txt-order' value='<?= $row[$field] ?>' style="width:40px;" />
			&nbsp;<a href='' title='save' class='order-save'>save</a>
			</td>
			<?php else:?>
			<td><?php echo $row[$field] ?></td>
			<?php endif?>
			<?php endforeach ?>
			<?php if(!isset($grid_action)):?>		   					
			<td class='actn-btn'>
			<a href="<?=$site_url.$active_controller;?>/edit/<?= $row['id'] ?>" title="Click here for edit this item" class='edit ajax_link'><span class="action_btn btn-primary"><i class="fa fa-pencil"></i></span></a>
			<a href="<?=$site_url.$active_controller;?>/del/<?= $row['id'] ?>" title="Click here for delete this item" class='del ajax_link'> <span class="action_btn btn-danger"><i class="fa fa-times"></i></span></a>
			</td>
			<?php elseif(is_array($grid_action) && !empty($grid_action)):?>	
			<td class='actn-btn' align="center" width="15%">
			<?php foreach($grid_action as $gatype=>$gactn):?>
			<?php if($gatype === 'edit'):?> 
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'].'/'.$row['invoice_id'] ?>" class="ajax_link" title="Click here for edit this item"><span class="action_btn btn-primary"><i class="fa fa-pencil"></i></span></a>
			<?php elseif($gatype === 'view'):?>
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'] ?>" class="ajax_link" title="Click here for view details"><span class="action_btn btn-success"><i class="fa fa-search-plus"></i></span></a>
			<?php elseif($gatype === 'processview'):?> <!-- only for letter process -->
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['process_id'] ?>" title="Click here for view details"><img src="<?=$image_url?>a_view.gif" width="16" height="13" border="0" alt="View" /></a>
			<?php elseif($gatype === 'barcode'):?> <!-- only for letter process -->
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['product_code'].'/'.$row['quantity'].'/'.$row['mrp'].'/'.$row['product_name'] ?>" class="ajax_link" title="Click here for barcode of this item"><span class="action_btn btn-primary"><i class="fa fa-barcode"></i></span></a>
			<?php elseif($gatype === 'del' || $gatype ==='delete' ):?>
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'] ?>" title="Click here for delete this item" class='del' ><span class="action_btn btn-danger"><i class="fa fa-times"></i></span></a>
			<?php else:?>
			<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'] ?>" title="Click here for <?php echo $gactn ?> this item" class='<?php echo $gactn;?>'><img src="<?=$image_url?>a_<?php echo $gactn;?>.gif" width="16" height="13" border="0" alt="<?php echo $gactn;?>" /></a>
			<?php endif ?>
			<?php endforeach ?>
			</td>	
			<?php endif ?>
		</tr>
		<?php endforeach ?>

		<?php if(empty($records)):?>
		<tr>
			<td colspan="<?=count($labels)+2;?>" class="no-record">
				No record is found.
			</td>
		</tr>
		<?php endif ?>
		</tbody>
	</table>
									

</div>

<script type='text/javascript'>
$(document).ready(function() {

	var status_type = '<?php echo $status_type; ?>';
	if(status_type==1)
	{
		var menuItems=[
			{title:'<i class="fa fa-check-circle text-success"> Active</i>',value:'Active'},
			{title:'<i class="fa fa-times-circle text-danger"> Inactive</i>',value:'Inactive'}				  
		];
	}else if(status_type==2)
	{
		var menuItems=[
			{title:'<i class="fa fa-check-circle text-success"> Approved</i>',value:'Approved'},
			{title:'<i class="fa fa-times-circle text-danger"> Pending</i>',value:'Pending'}				  
		];
	}else{
		var menuItems=[
			{title:'<i class="fa fa-check-circle text-success"> Publish</i>',value:'publish'},
			{title:'<i class="fa fa-times-circle text-danger"> Unpublish</i>',value:'unpublish'}
		];
	}

	$("td.stat_menu a").statusMenu({items:menuItems}); 
});
</script>
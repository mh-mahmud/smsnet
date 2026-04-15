<table id="example2" class="table table-bordered table-hover">
	<thead>
		<tr>
			<th width="30" align="center">#</th>
			<?php foreach($labels as $field=>$lbl):?>
			<th class="short">
				<?php if($sort_on==$field):?>
				 <a href="<?=sprintf($sort_url,$field)?>" class='active'>
					<?php echo $lbl;?>
					<?php if($sort_type=='asc'):?>
						<img src="<?=$image_url;?>sort_asc.png"/>
					<?php else:?>
						<img src="<?=$image_url;?>sort_desc.png"/>
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
		
	<?php 
	if(!empty($records))
	{
	foreach($records as  $row):	
	?>
	<tr class="<?php echo cycle(array('bg1','bg'));?>">
		<td style="vertical-align: middle;"><?php echo sprintf('%02d',++$offset);?></td>
		<?php foreach($labels as  $field=>$label):?>
		<?php 
		if($field=='status'):
		if(strtolower($row[$field])=='publish' OR strtolower($row[$field])=='active')
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
		<td style="vertical-align: middle;" width="100" align="center" class='stat_menu stat_menu_id_<?php echo $row['id']?>'>
		<a href="<?php echo $site_url.$active_controller;?>/set_status/<?php echo $row['id']?>" class="<?=strtolower($row[$field])?>"><?php echo $status_title; ?> </a>
		</td>	 
		<?php elseif($field=='image'): ?>
		<td style="vertical-align: middle;" align="center">
		<?php 
		if(!empty($row[$field]))
		{
		foreach($row[$field] as $img)
		{
		?>
		<a class="group1" href="<?php echo $base_url;?>upload_images/<?php echo $img['image']; ?>">
			<img class="image" src="<?php echo $base_url;?>upload_images/<?php echo $img['image']; ?>" width="50" height="50"/>&nbsp;&nbsp;
	    </a>		
		<?php	
		}
		}
		?>			
		</td>
		<?php elseif($field=='lat_long'): ?>
		<td style="vertical-align: middle;" align="center">
		<a href="#mapmodals" class="openmodal" data-toggle="modal" data-id="<?php echo $row[$field];?>" title="<?php echo $row[$field]; ?>"><b> 
        <?php		
			echo $row[$field];
		?>
        </b></a>		
		</td>
		<?php elseif($field=='link'): ?>
		<td style="vertical-align: middle;" class='lnk'><a href="<?php echo $row[$field] ?>" title='visit now'><?php echo $row[$field]; ?> </a></td>
		<?php elseif(in_array($field,array('comment','description'))):?>
		<td style="vertical-align: middle;" class='view' align='justify'><?php echo word_limiter($row[$field],10); ?></td>
		<?php elseif($field=='order44'): ?>
		<td style="vertical-align: middle;"><input type='text' class='txt-order' value='<?= $row[$field] ?>' style="width:40px;" />
		&nbsp;<a href='' title='save' class='order-save'>save</a>
		</td>
		<?php else:?>
		<td style="vertical-align: middle;"><?php echo $row[$field] ?></td>
		<?php endif?>
		<?php endforeach ?>
		<?php if(!isset($grid_action)):?>		   					
		<td style="vertical-align: middle;" class='actn-btn'>
		<a href="<?=$site_url.$active_controller;?>/edit/<?= $row['id'] ?>" title="Click here for edit this item" class='edit ajax_link'><span class="action_btn btn-primary"><i class="fa fa-pencil"></i></span></a>
		<a href="<?=$site_url.$active_controller;?>/del/<?= $row['id'] ?>" title="Click here for delete this item" class='del ajax_link'> <span class="action_btn btn-danger"><i class="fa fa-times"></i></span></a>
		</td>
		<?php elseif(is_array($grid_action) && !empty($grid_action)):?>	
		<td style="vertical-align: middle;" class='actn-btn' align="center">
		<?php foreach($grid_action as $gatype=>$gactn):?>
		<?php if($gatype === 'edit'):?> 
		<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'] ?>" class="ajax_link" title="Click here for edit this item"><span class="action_btn btn-primary"><i class="fa fa-pencil"></i></span></a>
		<?php elseif($gatype === 'view'):?>
		<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['id'] ?>" class="ajax_link" title="Click here for view details"><span class="action_btn btn-success"><i class="fa fa-search-plus"></i></span></a>
		<?php elseif($gatype === 'processview'):?> <!-- only for letter process -->
		<a href="<?=$site_url.$active_controller.'/'.$gactn.'/'.$row['process_id'] ?>" title="Click here for view details"><img src="<?=$image_url?>a_view.gif" width="16" height="13" border="0" alt="View" /></a>
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

	<?php 
	}
	if(empty($records)):?>
	<tr>
		<td colspan="<?=count($labels)+2;?>" class="no-record">
			No record is found.
		</td>
	</tr>
	<?php endif ?>
	</tbody>
</table>
								
<div class="row">
	<div class="col-xs-6">
		<div id="example2_info" class="dataTables_info">
			&nbsp;&nbsp;&nbsp;&nbsp;<span>Page <strong><?=$cur_page ?>/<?=$total_page ?></strong></span> | <span>View 
			<select name="rec_per_page" id='rec_per_page'>
				<?php echo html_options(array(5=>5,10=>10,30=>30,50=>50,100=>100,200=>200),$rec_per_page); ?>
			</select>		
			per page | Total <strong><?=$total_record;?> </strong> records found.</span>
		</div>
	</div>
	<div class="col-xs-6">						
		<div class="dataTables_paginate paging_bootstrap">
			<ul class="pagination">
				<?php $this->tpl->pagination(); ?>
			</ul>
		</div>							
	</div> 			    
</div>
<div id="lightbox" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <button type="button" class="close hidden" data-dismiss="modal" aria-hidden="true">×</button>
        <div class="modal-content">
            <div class="modal-body">
                <img src="" alt="" />
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="mapmodals">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myCity"></h4>
			</div>
			<div class="modal-body">
				<div id="map_canvas" style="width:560px; height:380px"></div>
			</div>
			<div class="modal-footer">
				<button type="button" class="close" data-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
	$(".group1").colorbox({rel:'group1'});
	var map;
	var myLatlng;
	function mapsDisplay(longs,latts){
		myLatlng = new google.maps.LatLng(longs,latts);
		var map_options = {
		  zoom: 13,
		  mapTypeControl: false,
		  center:myLatlng,
		  panControl:false,
		  rotateControl:false,
		  streetViewControl: false,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		var map = new google.maps.Map(document.getElementById("map_canvas"), map_options);
		var marker = new google.maps.Marker({
			position: myLatlng,
			map: map
		});
    		
		$('#mapmodals').on('shown.bs.modal', function () {
			google.maps.event.trigger(map, "resize");
			map.setCenter(myLatlng);
		});
	}

	$(document).on("click", ".openmodal", function (){
		var myMapId = $(this).data('id');
		var aa = myMapId.split(',');
		mapsDisplay(aa[0],aa[1]);
    	$(".modal-header #myCity").html( myMapId );
		$('#mapmodals').modal('show');
	});
</script>
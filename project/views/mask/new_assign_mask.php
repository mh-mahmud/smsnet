<?php $per_sms_rate = 0; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
            </div>
            <div class="panel-body">
                <form role="form" action="<?= $site_url . $active_controller; ?>/assign_mask_add" method="post">
					<div class="form-group">
						<text>Mask :</text>
						<div class="field">
							<select class="form-control chosen" name="mask_id">
								<option value="" >---- Select Mask ----</option>
								<?php echo html_options($mask_options, set_value('mask_id')); ?>
							</select>
							<span class='error'>* </span><?php echo form_error('mask_id'); ?> 
						</div>
					</div>
                    
                    
                    <div class="form-group">
                        <text>User :</text>
                        <div class="field">
                            <button type="button" class="btn btn-primary btn-xs"><a href='#' id='select-all'>select all</a></button>
                            <button type="button" class="btn btn-primary btn-xs"><a href='#' id='deselect-all'>deselect all</a></button>
                            </br></br>
                            <select id='public-methods'  multiple='multiple' name="user_id[]">
                                <?php echo html_options($user_options, set_value('user_id',$user_id)); ?>
                            </select>
                            <span class='error'></span><?php echo form_error('user_id'); ?> 
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


<script>
    $(document).ready(function () {

        jQuery(".chosen").chosen();

    });
</script>
<script>
    //$('#pre-selected-options').multiSelect();
    $('#public-methods').multiSelect();
    $('#select-all').click(function () {
        $('#public-methods').multiSelect('select_all');
        return false;
    });
    $('#deselect-all').click(function () {
        $('#public-methods').multiSelect('deselect_all');
        return false;
    });
</script>
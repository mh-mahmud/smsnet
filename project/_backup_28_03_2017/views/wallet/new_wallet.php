<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
                <div class="box-tools pull-right">
                    <a class="ajax_link" href="<?= $site_url . $link_action; ?>">
                        <button class="btn btn-primary btn-xs" type="button"><i class="fa fa-table"></i> <?php echo $link_title; ?></button>
                    </a>
                </div>
            </div>

            <div class="panel-body">
                <form role="form" action="<?= $site_url . $active_controller; ?>/walletAdd" method="post">
                    <div class="form-group">
                        <text>User :</text>
                        <div class="field">
                            <select class="chosen form-control" name="user_id">
                                <option value="" >---- Select User ----</option>
                                <?php echo html_options($user_options, set_value('user_id')); ?>
                            </select>
                            <span class='error'>* </span><?php echo form_error('user_id'); ?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Deposit Amount :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="deposit_amount" value="<?= set_value('deposit_amount'); ?>"/>
                            <span class="error">* </span><?php echo form_error('deposit_amount'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Curency :</text>
                        <div class="field">
                            <select class="chosen form-control" name="curency">
                                <option value="" >---- Select Curency ----</option>
                                <?php echo html_options($curency_options, set_value('curency')); ?>
                            </select>
                            <span class='error'>* </span><?php echo form_error('curency'); ?> 
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

<script>
    $(document).ready(function () {

        jQuery(".chosen").chosen();
    });
</script>
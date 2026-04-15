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
                <form role="form" action="<?= $site_url . $active_controller; ?>/add" method="post">
                    <div class="form-group">
                        <text>Username :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="username" value="<?= set_value('username'); ?>"/>
                            <span class="error">* </span><?php echo form_error('username'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Password :</text>
                        <div class="field">
                            <input type="password" class="form-control" name="passwd" value="<?= set_value('passwd'); ?>"/>
                            <span class="error">* </span><?php echo form_error('passwd'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Retype Password :</text>
                        <div class="field">
                            <input type="password" class="form-control" name="confirm_password" value="<?= set_value('confirm_password'); ?>"/>
                            <span class="error">* </span><?php echo form_error('confirm_password'); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <text>Mobile Number :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="mobile" value="<?= set_value('mobile'); ?>"/>
                            <span class="error">* </span><?php echo form_error('mobile'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Email :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="email" value="<?= set_value('email'); ?>"/>
                            <span class="error"> </span><?php echo form_error('email'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Address :</text>
                        <div class="field">
                            <textarea type="text" class="form-control" name="address"><?= set_value('address'); ?></textarea>

                        </div>
                    </div>
                    <div class="form-group">
                        <text>Admin Group :</text>
                        <div class="field">
                            <select class="form-control" name="id_user_group">
                                <option value="" >---- Select Group ----</option>
                                <?php echo html_options($user_group_options, set_value('id_user_group')); ?>
                            </select>
                            <span class='error'>* </span><?php echo form_error('id_user_group'); ?> 
                        </div>
                    </div>

                    <div class="form-group">
                        <text>Rate Config</text>
                        <div class="field">
                            <select class="form-control" name="rate_id">
                                <option value="" >---- Select Rate ----</option>
                                <?php echo html_options($rate_options, set_value('rate_id')); ?>
                            </select>
                            <span class='error'>* </span><?php echo form_error('rate_id'); ?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Billing Type :</text>
                        <div class="field">
                            <select class="form-control" name="billing_type">
                                <option value="" >---- Select Type ----</option>
                                <?php echo html_options($user_type_options, set_value('billing_type')); ?>
                            </select>
                            <span class="error"> </span><?php echo form_error('billing_type'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <text>MRC/OTC</text>
                        <div class="field">
                            <input type="text" class="form-control" name="mrc_otc" value="<?= set_value('mrc_otc'); ?>"/>
                            <span class="error"> </span><?php echo form_error('mrc_otc'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <text>Validity( Duration )</text>
                        <div class="field">
                            <input type="text" class="form-control" name="duration_validity" value="<?= set_value('duration_validity'); ?>"/>
                            <span class="error"> </span><?php echo form_error('duration_validity'); ?>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <text>Bill Cycle Start</text>
                        <div class="field">
                            <input type="text" class="form-control" name="bill_start" value="<?= set_value('bill_start'); ?>"/>
                            <span class="error"> </span><?php echo form_error('bill_start'); ?>
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




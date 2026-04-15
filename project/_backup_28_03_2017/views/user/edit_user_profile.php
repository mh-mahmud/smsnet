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
                <form role="form" action="<?= $site_url . $active_controller; ?>/edit/<?= $id . '/' . $profile; ?>" method="post">
                    <div class="form-group">
                        <text>Password :</text>
                        <div class="field">
                            <input type="password" class="form-control" name="passwd" value="<?= set_value('passwd'); ?>"/>
                            <span class="error"> </span><?php echo form_error('passwd'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Retype Password :</text>
                        <div class="field">
                            <input type="password" class="form-control" name="confirm_password" value="<?= set_value('confirm_password'); ?>"/>
                            <span class="error"> </span><?php echo form_error('confirm_password'); ?>
                        </div>
                    </div>				
                    <div class="form-group">
                        <text>Mobile Number :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="mobile" value="<?= set_value('mobile', $mobile); ?>"/>
                            <span class="error">* </span><?php echo form_error('mobile'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Email :</text>
                        <div class="field">
                            <input type="text" class="form-control" name="email" value="<?= set_value('email', $email); ?>"/>
                            <span class="error"> </span><?php echo form_error('email'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Address :</text>
                        <div class="field">
                            <textarea type="text" class="form-control" name="address"><?= set_value('address', $address); ?></textarea>

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

<?//=validation_errors(); ?> 


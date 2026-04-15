<form id="ajax_submit" role="form" action="<?= $site_url . $active_controller; ?>" method="post">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-table"></i><?php echo $page_title; ?>
                    <div class="box-tools pull-right">
                        <div class="box-tools pull-right">
                                <!--a class="ajax_link" href="<?//=$site_url.$link_action;?>">
                                        <button class="btn btn-primary btn-xs" type="button"><i class='fa fa-plus'></i> <?php echo $link_title; ?></button>
                                </a-->
                            <a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
                            <a href="<?= $site_url . $active_controller; ?>/word/<?= $active_function; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
                            <a href="<?= $site_url . $active_controller; ?>/excel"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
                            <a href="<?= $site_url . $active_controller; ?>/pdf/<?= $active_function; ?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
                            <a href="<?= $site_url . $link_action; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class='fa fa-plus'></i> New User</a>
                        </div>				
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
                            <table class="table table-bordered">
                                <tr>
                                    <!--td><input type="text" class="form-control lg" name="username" placeholder="Username" value="<?php //echo htmlspecialchars($_POST['username']); ?>"/></td-->
									<td>
                                        <input type="text" class="form-control lg" name="mobile" placeholder="mobile" value="<?php echo htmlspecialchars($_POST['mobile']); ?>"/>										
                                    </td>
                                    <td width="20%">
                                        <select class="form-control lg chosen" name="user_id" id="user_id">
                                            <option value="">---- Select user -----</option>
                                            <?php //echo html_options($type_option,set_value('type')); ?>
                                            <?php echo html_options($user_options,htmlspecialchars($_POST['user_id'])) ;?>
                                        </select>									
                                    </td>
                                    <td width="20%">
                                        <select class="form-control lg chosen" name="id_user_group" id="id_user_group">
                                            <option value="">---- Select group -----</option>
                                            <?php //echo html_options($type_option,set_value('type')); ?>
                                            <?php echo html_options($user_group_options, htmlspecialchars($_POST['id_user_group'])); ?>	
                                        </select>									
                                    </td>
                                    <td width="20%">
                                        <select class="form-control lg chosen" name="rate_id" id="rate_id">
                                            <option value="">---- Select rate -----</option>
                                            <?php //echo html_options($type_option,set_value('type')); ?>
                                            <?php echo html_options($rate_options, htmlspecialchars($_POST['rate_id'])); ?>	
                                        </select>									
                                    </td>
                                    <td width="20%">
                                        <select class="form-control lg chosen" name="status">
                                            <option value="">---- Select Status -----</option>
                                            <?php //echo html_options($type_option,set_value('type')); ?>
                                            <?php echo html_options($status_options, htmlspecialchars($_POST['status'])); ?>	
                                        </select>									
                                    </td>									
                                    <td width="100" align="center">
                                        <button type="submit" name="submit" class="btn btn-primary btn-sm"><i class='fa fa-search'></i></button>
                                        <button type="submit" name="reset" class="btn btn-warning btn-sm"><i class='fa fa-refresh'></i></button>
                                    </td>
                                </tr>
                            </table>							
                            <?php $this->load->element('user_grid_board'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	
</form>
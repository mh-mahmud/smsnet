<?php $per_sms_rate = 0; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
            </div>
            <span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>
            <div class="panel-body">
                <form role="form" action="<?= $site_url . $active_controller; ?>/smsrate" method="post">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="30" align="center">#</th>
                                    <th class="short">Operator</th>									
                                    <th class="short">Buying Rate</th>
                                    <th class="short">Selling Rate</th>
                                </tr>			
                            </thead>						
                            <tbody>
                                <?php foreach ($operator_options as $key => $val) { ?>
                                    <tr>
                                        <td>#</td>
                                        <td  align="center">
                                            <text><?= $val['full_name']; ?> </text>
                                            <input type="hidden" class="form-control" name="name[]; ?>" value=""/>
                                            <input type="hidden" class="form-control" name="operator_id<?= $key; ?>" value="<?= $val['id']; ?>"/>
                                        </td>
                                        <td  align="center">
                                            <?php if ($this->session->userdata('user_group_id') == 2) { ?>
                                                <input readonly type="text" id="rate" class="form-control" name="buying_rate<?= $key; ?>" value="<?= set_value('buying_rate', $val['buying_rate']); ?>"/>
                                            <?php }if ($this->session->userdata('user_group_id') == 1) { ?>
                                                <input type="text" id="rate" class="form-control" name="buying_rate<?= $key; ?>" value="<?= set_value('buying_rate', $val['buying_rate']); ?>"/>
                                            <?php } ?>
                                            <span class="error">*</span><?php echo form_error('buying_rate'); ?>
                                        </td>

                                        <td  align="center">
                                            <input type="text" id="rate" class="form-control" name="selling_rate<?= $key; ?>" value="<?= set_value('selling_rate', $val['selling_rate']); ?>"/>
                                            <span class="error">*</span><?php echo form_error('selling_rate'); ?>
                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group action">
                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<?php

function get_buying_rate($opid) {
    $CI = & get_instance();
    $CI->load->model('operatormodel');
    $result = $CI->operatormodel->get_buying_rate($opid);
    return $result[0]['selling_rate'];
}
?>
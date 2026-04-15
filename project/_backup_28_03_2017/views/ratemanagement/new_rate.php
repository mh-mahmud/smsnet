<?php $per_sms_rate = 0; ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil-square-o"></i><?php echo $page_title; ?>
            </div>
            <div class="panel-body">
                <form role="form" action="<?= $site_url . $active_controller; ?>/new_rate" method="post">
                    <div class="form-group">
                        <text>Rate Name:</text>
                        <div class="field">
                            <input type="text" class="form-control" name="name"; value=" "/>
                            <span class='error'></span><?php echo form_error('name'); ?> 
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Operatorwise Rate:</text>
                        <div class="field">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th class="short">Operator</th>									
                                        <th class="short">Selling Rate</th>
                                    </tr>			
                                </thead>						
                                <tbody>
                                    <?php foreach ($operator_options as $key => $val) { ?>
                                        <tr>
                                            <td  align="center">
                                                <text><?= $val['full_name']; ?> </text>
                                                <input type="hidden" class="form-control" name="operator_id<?= $val['id']; ?>" value="<?= $val['id']; ?>"/>
                                            </td>
                                            <td  align="center">
                                                <input type="text" id="rate" class="form-control" name="selling_rate<?= $val['id']; ?>" value="<?= set_value('selling_rate', $val['selling_rate']); ?>"/>
                                                <span class="error">*</span><?php echo form_error('selling_rate'); ?>
                                            </td>

                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table> 
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <text>User :</text>
                        <div class="field">
                            <button type="button" class="btn btn-primary btn-xs"><a href='#' id='select-all'>select all</a></button>
                            <button type="button" class="btn btn-primary btn-xs"><a href='#' id='deselect-all'>deselect all</a></button>
                            </br></br>
                            <select id='public-methods' multiple='multiple' name="user_id[]">
                                <?php echo html_options($user_options, set_value('user_id')); ?>
                            </select>
                            <span class='error'></span><?php echo form_error('user_id'); ?> 
                        </div>
                    </div>
                    -->
                    <div id="ajaxreturn"></div>

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
<!-------------------------------- Start Ajax ---------------------------------->
<script src="<?php echo $js_url; ?>jquery-1.9.1.min.js"></script>

<script>
    $(document).ready(function () {
        $('#user_id').on('change', function () {
            var user_id = $(this).val();
            var Result = $('#ajaxreturn');
            var dataPass = 'user_id=' + user_id;
            $.ajax({
                type: 'POST',
                data: dataPass,
                url: '<?= site_url('ratemanagement/user_rate'); ?>',
                success: function (responseText) {
                    //alert(responseText);
                    Result.html(responseText);
                }
            });
        });
    });
</script>

<!-------------------------------- End Ajax ------------------------------------>

<?php

function get_buying_rate($opid) {
    $CI = & get_instance();
    $CI->load->model('operatormodel');
    $result = $CI->operatormodel->get_buying_rate($opid);
    return $result[0]['selling_rate'];
}
?>
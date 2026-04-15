<form id="ajax_submit" role="form" action="<?= $site_url . $active_controller; ?>/depositIndex" method="post">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-table"></i><?php echo $page_title; ?>
                    <div class="box-tools pull-right">
                        <div class="box-tools pull-right">
                                <!--a class="ajax_link" href="<?//=$site_url.$link_action;?>">
                                        <button class="btn btn-primary btn-xs" type="buutton"><i class='fa fa-plus'></i> <?php echo $link_title; ?></button>
                                </a-->
                            <a href="#" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-print"></i> Print</a>
                            <a href="<?= $site_url . $active_controller; ?>/word/<?= $active_function; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Word</a>
                            <a href="<?= $site_url . $active_controller; ?>/Excel/<?= $active_function; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  Exel</a>
                            <a href="<?= $site_url . $active_controller; ?>/pdf/<?= $active_function; ?>" target="_blank" class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class="fa fa-download"></i>  PDF</a>
                            <a href="<?= $site_url . $link_action; ?>"  class="btn btn-primary btn-xs pull-right" style="margin-right: 5px; margin-top: 5px;"><i class='fa fa-plus'></i> Deposit History</a>
                        </div>				
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <span class="delete_message"><?php echo $this->session->flashdata('message'); ?></span>	
                            <table class="table table-bordered">
                                <tr>
                                    <td>Total Received Amount : <?= $depositSummery[0]['total_received_amount']; ?></td>
                                    <td>From : <?= $depositSummery[0]['fromdate']; ?></td>
                                    <td>To : <?= $depositSummery[0]['todate']; ?></td>
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

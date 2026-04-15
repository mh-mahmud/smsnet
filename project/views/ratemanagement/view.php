<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <i class="fa fa-pencil-square-o"></i><?php echo 'Rate view'; ?>

            </div>
            <div class="panel-body">
                <div class="form-group">
                    <text>Name:</text>
                    <div class="field">
                        <?php
                        echo $rate->name;
                        ?>
                    </div>
                </div>
                <?php foreach ($operator_rates as $key => $val) { ?>
                    <div class="form-group">
                        <text><?php echo $val['full_name']; ?> :</text>
                        <div class="field">
                            <?php echo $val['buying_rate']; ?>
                        </div>
                    </div>				
                    <?php $i++;
                } ?>
                <!--
                <div class="form-group">

                    <text>Users :</text>
                    <div class="field">
                        <?php
                        echo $rate->users;
                        ?>

                    </div>
                </div>
                -->
            </div>
        </div>
    </div>
</div> 




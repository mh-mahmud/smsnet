<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th width="30" align="center">#</th>
                <th class="short">Operator</th>	
                <th class="short">Unit Cost</th>
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
                        <input type="text" id="rate" class="form-control" name="unit_cost<?= $key; ?>" value="<?= $val['unit_cost']; ?>"/>

                    </td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
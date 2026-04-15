<style>
    .invoice-title h2, .invoice-title h3 {
        display: inline-block;
    }

    .table > tbody > tr > .no-line {
        border-top: none;
    }

    .table > thead > tr > .no-line {
        border-bottom: none;
    }

    .table > tbody > tr > .thick-line {
        border-top: 2px solid;
    }
    p{margin-left:90px;}
    .custom{margin-left:10px;}
    .customright{margin-right:10px;}
    .customaddress{
        margin-left:170px;
        margin-top:10px;
        text-align:center;
    }


</style>

<!-- user view portion -->


<div class="container">
    <div class="row">

        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="col-xs-12">
                    <div class="invoice-title">
                        <div class="row">
                            <div class="col-xs-1">

                            </div>
                            <div class="col-xs-8">
                                <address class="customaddress">
                                    <strong>Metronet Bangladesh LTD</strong><br>
                                    SMS Details Recored ( Last Month Generated Bill )<br>
                                    CLient Name : <?= $billingReport[0]['username']; ?><br>
                                    <strong>Period : <?= $billingReport[0]['date_from'] . ' to ' . $billingReport[0]['date_to']; ?></strong>
                                </address>
                            </div>
                            <div class="col-xs-3 text-right">
                                <div align="right">
                                    <img onclick="printDiv('containerid')" src="<?= base_url('img/print_32.png') ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>


                <div class="row custom"" >
                    <div class="panel-body">
                        <div>
                            <table class="table table-condensed">
                                <thead>
                                    <tr>
                                        <td class="text-center"><strong>SL#</strong></td>
                                        <td class="text-center"><strong>Operator</strong></td>
                                        <td class="text-center"><strong>Rate/SMS</strong></td>
                                        <td class="text-center"><strong>Total SMS</strong></td>
                                        <td class="text-center"><strong>Total SMS Count</strong></td>
                                        <td class="text-center"><strong>Total Bill by Operator</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- foreach ($order->lineItems as $line) or some such thing here -->
                                    <?php $totasms = 0;
                                    $totasmscount = 0;
                                    $totalBill = 0;
                                    $i = 1;
                                    if (count($billingReport) > 0) {
                                        foreach ($billingReport as $val) { ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td class="text-center"><?php echo $val['operator']; ?></td>
                                                <td class="text-center"><?php echo number_format((float) $val['buying_rate'], 2, '.', ''); ?></td>
                                                <td class="text-center"><?php echo $val['totalSmsOperatorwise'][0]->totalsms; ?></td>
                                                <td class="text-center"><?php echo $val['totalSmsCountOperatorwise'][0]->totalsmscount; ?></td>
                                                <td class="text-center"><?php echo number_format((float) ($val['buying_rate'] * $val['totalSmsCountOperatorwise'][0]->totalsmscount), 2, '.', ''); ?></td>
                                            </tr>
                                            <?php
                                            $i++;
                                            $totasms+=$val['totalSmsOperatorwise'][0]->totalsms;
                                            $totasmscount+=$val['totalSmsCountOperatorwise'][0]->totalsmscount;
                                            $totalBill+=number_format((float) ($val['buying_rate'] * $val['totalSmsCountOperatorwise'][0]->totalsmscount), 2, '.', '');
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-left" colspan="3">Sub Total: </td>
                                        <td class="text-center"><?php echo intval($totasms); ?></td>
                                        <td class="text-center"><?php echo intval($totasmscount); ?></td>
                                        <td class="text-center"><strong><?php echo number_format((float) $totalBill, 2, '.', ''); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="3">MRC/OTC: </td>
                                        <td class="text-center"><?php $billingReport[0]['mrc_otc']; ?></td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><strong><?php echo $mrc_otc = number_format($billingReport[0]['mrc_otc'], 2, '.', ''); ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="3">SD(5%): </td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><strong><?php echo $sd = number_format((float) ($totalBill * 5) / 100, 2, '.', '');
; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="3">SC(1%): </td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><strong><?php echo $sc = number_format((float) ($totalBill * 1) / 100, 2, '.', '');
; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="3">VAT(15%): </td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><strong><?php echo $vat = number_format((float) ($totalBill * 15) / 100, 2, '.', '');
; ?></strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-left" colspan="3">Total: </td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><?php echo 0; ?></td>
                                        <td class="text-center"><strong><?php echo $subtotal = number_format((float) ($totalBill +$mrc_otc + $sd + $sc + $vat), 2, '.', '');
; ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row custom" >
                    <div class="col-xs-12">
                        <address>
                            <strong>In Words (in BDT):</strong>
<?php echo ucfirst(convert_number_to_words($subtotal)) . ' .'; ?><br>

                        </address>
                    </div>					
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print();

        document.body.innerHTML = originalContents;
    }
</script>
<?php

function convert_number_to_words($number) {

    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
?>
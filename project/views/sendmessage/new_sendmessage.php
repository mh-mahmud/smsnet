<style>
    #countBox, #countBox2, #rate, #amount {
        width: 20%; 
        font-weight: bold; 
        height: 15px;
        border: 1px solid #fff; 

    }
</style>
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
                <form role="form" action="<?= $site_url . $active_controller; ?>/composemessage" method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <text>Masking :</text>
                        <div class="field">
                            <select class="form-control chosen" name="senderID">
                                <option value="" >---- Select Mask ----</option>
                                <?php echo html_options($mask_options, set_value('senderID')); ?>
                            </select>
                            <span class="error"></span><?php echo form_error('senderID'); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <text>Recipients :</text>
                        <div class="field">
                            <a class="btn btn-primary btn-xs" href="javascript:()" title="number" id="numberButton">Type Numbers</a>
                            <a class="btn btn-primary btn-xs" href="javascript:()" title="group" id="groupButton">Use Group</a>
                            <a class="btn btn-primary btn-xs" href="javascript:()" title="file" id="uploadButton">Upload File</a>	
                            <input type="hidden" id="receiver" name="receiver" value="" />
                        </div>
                    </div>

                    <div class="form-group" id="numberBox">
                        <text>Seprate Numbers by Comma. (include country code for international destinations)	:</text>
                        <div class="field">
                            <textarea type="text" rows="7" class="form-control" onBlur="count(this, this.form.countBox);" onKeyUp="count(this, this.form.countBox);" id="recipientList" name="recipient"><?= set_value('recipient'); ?></textarea></br>
                            <input type="hidden" id="temp_count" name="temp_count" value="" />
                            <span style="font-size: 12px; font-weight: bold;" id="display_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span> 
                            <span class="error"></span><?php echo form_error('recipient'); ?>
                        </div>  
                    </div>

                    <div class="form-group" id="groupBox">
                        <text>Group	:</text>
                        <div class="field">
                            <?php foreach ($group_options as $val) { ?>
                                <?php if ($val['total_member'] > 0) { ?>
                                    <input name="group_id[]" type="checkbox" value="<?= $val['group_id']; ?>"> <?= $val['name'] . '(' . $val['total_member'] . ')'; ?> &nbsp;&nbsp;
                                <?php }
                            } ?>
                            <span class="error"></span><?php echo form_error('group_id'); ?>
                        </div>  
                    </div>

                    <div class="form-group" id="fileupload">
                        <text>File	:</text>
                        <div class="field">
                            <input name="file" id="upload" type="file" value="" > 
                            <input type="hidden" id="temp_count" name="temp_count" value="" />
                            <!--span style="font-size: 12px; font-weight: bold;" id="display_File_count">0</span> <span style="font-size: 12px; font-weight: bold;">Total Recipients</span--> 
                        </div>  
                    </div>

                    <div class="form-group">
                        <text>Message: <br><br><br>
                            * Maximum 500 characters are allowed, but in case of Unicode/Bangla the length must be under 256 characters. 
                            <br><br>
                            * One simple text message is of 160 characters long.
                            <br><br>
                            * One simple text message containing extended GSM character set (~^{}[]\|€≪FF≫...) is of 140 characters long.
                            <br><br>
                            * One Unicode message is of 70 characters long.
                        </text>
                        <div class="field">
                            <textarea type="text" rows="7" class="form-control" name="message" id="message"><?= set_value('message'); ?></textarea></br>
                            <span>Entered Char : <span  name="countBox2" id="countBox2"></span>, Number of char per SMS : <span  name="actualSMSLength" id="actualSMSLength"></span> ,Total SMS :<span  name="usrSMSCnt" id="usrSMSCnt"></span></span> 
                            <input type="hidden" name="page" value = "" id="page" /> 
                            <span class="error"></span><?php echo form_error('message'); ?>
                        </div>
                    </div>					
                    <div class="form-group">
                        <text>Schedule :</text>
                        <div class="field">
                            <input name="sms_type" type="checkbox" id="smstype" value="ScheduleMessage" /> Schedule &nbsp;&nbsp;
                            <input name="scheduleDateTime" class="form-control datepicker" size="2" type="text" id="datepicker" value="" placeholder="Date Time" />
                            <span class="error"></span><?php echo form_error('scheduleDateTime'); ?>
                        </div>
                    </div>					
                    <div class="form-group action">
                        <button type="submit" class="btn btn-primary btn-sm">Send Message</button>
                        <button type="reset" class="btn btn-warning btn-sm">Cencel Message</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 
<?//=validation_errors(); ?> 
<script>
    $(function () {
        $('#upload').change(function (event) {
            var f = event.target.files[0];
            if (f) {
                var r = new FileReader();

                r.onload = function (e) {
                    var contents = e.target.result;
                    var res = contents.split(",");
                    $("#display_File_count").text(res.length);
                    $('#temp_count').value = res.length;
                }
                r.readAsText(f);
            }
        });
    });


    $(document).ready(function () {
        $("#recipientList").on('keyup', function () {
            this.value = $.map(this.value.split(","), $.trim).join(", ");
            var words = this.value.match(/\S+/g).length;
            $('#display_count').text(words);
            $('#temp_count').value = words;
            $("#temp_count").val(words);
            if (words > 500) {
                alert('Phone number must be smpller than 500 (number <= 500)');
                var exacnumber = this.value.slice(0, 6499);
                $('#display_count').text(500);
                $("#recipientList").val(exacnumber);
                $('#temp_count').value = 500;
                $("#temp_count").val(500);

            }
        });
    });
</script>
<script>

//functin to toggle recipients
    $(document).ready(function () {
        $("#numberBox").show();
        $("#groupBox").hide();
        $("#fileupload").hide();
        $("#datepicker").hide();
        $("#hour").hide();
        $("#min").hide();
        $("#receiver").val('number');
        $("#numberButton").click(function () {
            $("#numberBox").show();
            $("#groupBox").hide();
            $("#fileupload").hide();
            var title = $(this).attr('title');
            $("#receiver").val(title);
        });
        $("#groupButton").click(function () {
            $("#numberBox").hide();
            $("#groupBox").show();
            $("#fileupload").hide();
            var title = $(this).attr('title');
            $("#receiver").val(title);
        });
        $("#uploadButton").click(function () {
            $("#numberBox").hide();
            $("#groupBox").hide();
            $("#fileupload").show();
            var title = $(this).attr('title');
            $("#receiver").val(title);
        });

        $("#smstype").click(function () {
            var schedule = $("#smstype").is(':checked') ? 1 : 0;
            if (schedule == 1) {
                $("#datepicker").show();
            } else {
                $("#datepicker").hide();
            }
            if (schedule == 1) {
                $("#hour").show();} else {
                $("#hour").hide();}
            if (schedule == 1) {
                $("#min").show();
            } else {
                $("#min").hide();
            }
        });
    });


</script>

<script>
//count message lenght 
     $("#countBox2").html(0);
     $("#actualSMSLength").html(0);
     $("#usrSMSCnt").html(0);



    function getSMSType(usrSms) {
        var smsType;
        if (jQuery.trim(usrSms).match(/[^\x00-\x7F]+/) !== null) {
            smsType = "unicode";
        } else {
            var newSMS = usrSms.match(/(\u000C|\u005e|\u007B|\u007D|\u005c|\u005c|\u005B|\u007E|\u005D|\u007C|\u20ac)/g);
            if (newSMS !== null) {
                smsType = "gsmextended";
            } else {
                smsType = "plaintext";
            }
        }
        return smsType;
    }

    function calculateSMSs() {
        var content = $('#message').val();
        var newLines = content.match(/(\r\n|\n|\r)/g);
        var addition = 0;
        if (newLines != null) {
            addition = newLines.length;
        }
        usrSMSCharLength = content.length + addition;
        $("#countBox2").html(usrSMSCharLength);
        //alert(getSMSType(content));
        if (getSMSType(content) === 'plaintext') {
            if (usrSMSCharLength <= 160) {
                actualSMSLength = 160;
                usrSMSCnt = 1;
            } else {
                actualSMSLength = 160 - 7;
                usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
            }
        } else if (getSMSType(content) === 'gsmextended') {
            if (usrSMSCharLength <= 140) {
                actualSMSLength = 140;
                usrSMSCnt = 1;
            } else {
                actualSMSLength = 140 - 6;
                usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
            }
        } else if (getSMSType(content) === 'unicode') {
            if (usrSMSCharLength <= 70) {
                actualSMSLength = 70;
                usrSMSCnt = 1;
            } else {
                actualSMSLength = 70 - 3;
                usrSMSCnt = Math.ceil(usrSMSCharLength / actualSMSLength);
            }
        }
        $("#countBox2").html(usrSMSCharLength);
        $("#actualSMSLength").html(actualSMSLength);
        $("#usrSMSCnt").html(usrSMSCnt);

    }
    ;

    $("#message").on('keyup', function () {
        calculateSMSs();
    });


</script>

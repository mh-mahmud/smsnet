<?php
function indent($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;

        // If this character is the end of an element,
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }

        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element,
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }

        $prevChar = $char;
    }

    return $result;
}						


?>

 <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>jQuery UI Tabs - Default functionality</title>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">


<div id="tabs">
  <ul>
    <li><a href="#tabs-1">JSON</a></li>
    <li><a href="#tabs-2">XML</a></li>
  </ul>
  <div id="tabs-1">
    <div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">

			<div class="panel-body">
				<div class="form-group">
					<text>Url :</text>
					<div class="field">
						<?php echo $url; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Method :</text>
					<div class="field">
						<?php echo "POST"; ?>
					</div>
				</div>
				
				<div class="form-group">
					<text>Format :</text>
					<div class="field">
						<?php echo "JSON"; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Authentication Information :</text>
					<div class="field">
						<?php echo 'api_key : '. $auth['APIKEY'].'</br>'; ?>
						<?php echo 'api_secret : '. $auth['SECRETKEY']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Json Format :</text>
					<div class="field">
						<?php 
						
						
						$sample = array("auth"=>array("username"=>"test",
																"api_key"=>"xxxxxxxxxx",
																"api_secret"=>"xxxxxxxxxx"
															  ),
											 "sms_data"=>array(
													array("recipient"=>"01711xxxxxx", "mask"=>"test-mask", "message"=>"Test Message 1"),										 
											 		array("recipient"=>"01911xxxxxx", "mask"=>"Metro", "message"=>"Test Message 2"),
											 		array("recipient"=>"01811xxxxxx", "mask"=>"BD-Info", "message"=>"Test Message 3"),
											 )
								);
						
						$message = 	json_encode($sample);
						
						echo "<pre>";
						echo indent($message);//print_r($sample);						
						echo "</pre>";
						?>
						
					</div>
				</div>
				
				<div class="form-group">
					<text>Successful Response :</text>
					<div class="field">		
				
					<?php
						$success = '{"result":[{"messageid":"58026daf44542","status":"success","message":"Request has been accepted successfully"}]}';
						echo "<pre>";
						echo indent($success);//print_r($sample);						
						echo "</pre>";						
						
					?>		
				
				</div>
				</div>
				
				<div class="form-group">
					<text>Failed Response :</text>
					<div class="field">		
				
					<?php
						$success = '{"result":[{"messageid":"","status":"failed","message":"Please check your input data"}]}';
						echo "<pre>";
						echo indent($success);//print_r($sample);						
						echo "</pre>";						
						
					?>		
				
				</div>
				</div>

			</div>
		</div>
	</div>
</div> 
  </div>
  <div id="tabs-2">
    <div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">

			<div class="panel-body">
				<div class="form-group">
					<text>Url :</text>
					<div class="field">
						<?php echo $url; ?>
					</div>
				</div>
				<div class="form-group">
					<text>Method :</text>
					<div class="field">
						<?php echo "POST"; ?>
					</div>
				</div>
				
				<div class="form-group">
					<text>Format :</text>
					<div class="field">
						<?php echo "XML"; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Authentication Information :</text>
					<div class="field">
						<?php echo 'api_key : '. $auth['APIKEY'].'</br>'; ?>
						<?php echo 'api_secret : '. $auth['SECRETKEY']; ?>
					</div>
				</div>				
				<div class="form-group">
					<text>Xml Format :</text>
					<div class="field">
						<?php 
						
header('content: text/xml');
$string =  '<?xml version="1.0" encoding="UTF-8"?>
<sms_data>
	<auth>
		<username>admin</username>
		<api_key>71728fd93a2f1642d20adb54c5e947d81</api_key>
		<api_secret>90917a2a7c075d9ecfe9e6cf229a5fb51</api_secret>
	</auth>
	<bulk_sms>
		<sms>
			<recipient>01917658629</recipient>
			<mask>samsung bd</mask>
			<message>This is text</message>
		</sms>
		<sms>
			<recipient>01734183130</recipient>
			<mask>samsung bd</mask>
			<message>This is text2</message>
		</sms>
	</bulk_sms>
</sms_data>';
echo '<pre>', htmlentities($string), '</pre>'; ?>
					</div>
				</div>
				
				<div class="form-group">
					<text>Successful Response :</text>
					<div class="field">		
				
					<?php
header('content: text/xml');
$string = '
<?xml version="1.0" encoding="UTF-8" ?>
<bulksms>
    <messageid>356356564654</messageid>
    <status>success</status>
    <message>Request has been accepted successfully</message>
</bulksms>';
 echo '<pre>', htmlentities($string), '</pre>'; ?>		
				
		
				
				</div>
				</div>
				
				<div class="form-group">
					<text>Failed Response :</text>
					<div class="field">		
				
<?php
header('content: text/xml');
$string = '
<?xml version="1.0" encoding="UTF-8" ?>
<bulksms>
    <messageid></messageid>
    <status>failed</status>
    <message>Invalid data ! Please check your request data format!</message>
</bulksms>';
 echo '<pre>', htmlentities($string), '</pre>'; ?>		
				
				</div>
				</div>

			</div>
		</div>
	</div>
</div> 

  </div>

</div>
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
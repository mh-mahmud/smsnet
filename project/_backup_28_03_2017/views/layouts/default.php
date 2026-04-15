<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $page_title;?></title>
	<script type="text/javascript">
		var site_url = '<?php echo $site_url;?>';
	</script>
	<link href="<?php echo $css_url;?>bootstrap.css" rel="stylesheet"/>
    <link href="<?php echo $css_url;?>font-awesome.css" rel="stylesheet"/>
    <link href="<?php echo $css_url;?>styles.css" rel="stylesheet"/>	
    <link href="<?php echo $css_url;?>multi-select.css" rel="stylesheet"/>	
    <link href="<?php echo $css_url;?>bootstrap-datetimepicker.min.css" rel="stylesheet"/>	
    <script src="<?php echo $js_url;?>jquery-2.0.2.js"></script>
	<script src="<?php echo $js_url;?>bootstrap.min.js"></script>
	<script src="<?php echo $js_url;?>jquery.metisMenu.js"></script>
	<script src="<?php echo $js_url;?>custom-scripts.js"></script>
	<script src="<?php echo $js_url;?>jquery.statusmenu.js"></script>
	<script src="<?php echo $js_url;?>jquery.multi-select.js"></script>
	<script src="<?php echo $js_url;?>bootstrap-datetimepicker.min.js"></script>
	<link rel="icon"type="image/png" href="<?php echo $base_url;?>img/favicon.png">
	<?php echo $this->tpl->custom_head(); ?>
	<script>
	$(document).ready(function(){			
		$('.loader').hide();
	});
	</script>
</head>
<body>	
	<div id="wrapper">
        <nav class="navbar navbar-default top-navbar" role="navigation">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".sidebar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?php echo $site_url;?>"><img src="<?php echo $base_url;?>img/logo.png" alt="" class='retina-ready' width="110" height="50px;"></a>
            </div>
            <ul class="nav navbar-top-links navbar-right">                
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#" aria-expanded="false">
                        <i class="fa fa-user fa-fw"></i> <b><?php echo $this->session->userdata('user_username'); ?> </b>&nbsp; <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo $site_url;?>user/view/<?php echo $this->session->userdata('user_userid');?>"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo $site_url;?>login/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>                   
                </li>                
            </ul>
        </nav>		
		<nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">                
				<?php echo $top_menu_html; ?>
            </div>
        </nav>		
		<div id="page-wrapper">
			<div class="grid_board">
				<?php echo $content_for_layout; ?>
			</div>	
		</div>
	</div>		
</body>

<script>
$(document).ready(function(){		
	
	$('.loader').hide();	

	var btnName;
	$(document).on("click",".btn",function() {		
		btnName = $(this).attr('name');				
	});

	$(document).on("submit","#ajax_submit",function() {
		$('.loader').show();
		form = $("#ajax_submit").serialize();
		form += '&submit='+btnName; 
		var formURL = $(this).attr("action");
		
		$.ajax({
			type: "POST",
			url: formURL,
			data: form,
			success: function(data){	
				$('.grid_board').html(data);
				$('.loader').hide();			
				
				//datepicker();				
			}
		});
		return false;
	});	
	
	$(document).on("change","#rec_per_page",function() {
		$('#ajax_submit').submit();
	});
	
	$(document).on("click",".pagination a",function() {		
		var url=$(this).attr('href');		
		$('#ajax_submit').attr('action',url).submit();
		return false;
	});

	$(document).on("click","#grid-board th a",function() {
		var url=$(this).attr('href');
		$('#ajax_submit').attr('action',url).submit();
		return false;
	});  
	
	$(document).on("click",".short a",function() {
		var url=$(this).attr('href');
		$('#ajax_submit').attr('action',url).submit();
		return false;
	});
	
	$(document).on("click",".actn-btn a.del",function() {
		var message ="Are you sure you want to remove this item?\n Note: item can not be restored after removal!";
		if (confirm(message))
        {
			var page_url = $(this).attr('href');
			var parent = $(this).parent().parent();
			$.ajax(
            {
				type: "POST",
				url: page_url,
				data: '',
				cache: false, 
				success: function(data){
					var obj = jQuery.parseJSON(data);
					if(obj.status==1)
					{
						parent.fadeOut('slow', function() {$(this).remove();});
						$('.delete_message').html(obj.message);
					}else{
						$('.delete_message').html(obj.message);
					}
				}
            });
			return false; 
		}	
		return false;	
	}); 
	
	
	$(document).on("click",".change_status_menu",function() {
		var page_url = $(this).attr('href');
		var parent = $(this).parent().parent();
		$.ajax(
		{
			type: "POST",
			url: page_url,
			data: '',
			cache: false, 
			success: function(data){
				var obj = jQuery.parseJSON(data);
				$('.dropdown-menu').hide();
				$('.stat_menu_id_'+obj.id+' a').html(obj.data);
			}
		}); 
		return false; 			 
	});	

/* 	$(document).on("focusin",".datepicker",function() {
		$(this).datepicker({
			format: 'yyyy-mm-dd'
		}).on('changeDate', function(e){
		    $(this).datepicker('hide');
		});	 
	}); */
	
	jQuery(".chosen").chosen();
	
	//$(".datetime").datetimepicker({format: 'yyyy-mm-dd hh:ii'});
	$(".datepicker").datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'});
	
});
</script>
</html>
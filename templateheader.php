<?php 
	//Check for User's Session else redirect to login page
	require_once(dirname(__FILE__)."/scripts/classes/users.php");

	$objUser = new User();
	$loggedinUserDetails = $objUser->fnGetLoginUserDetails();

	if($loggedinUserDetails['login_status']) {
		$userAccountDetails = $loggedinUserDetails;
	} else {
		header("Location: index.php");
	}
?>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Cloads | Summary & Logs MIS</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.5 -->
	<link rel="stylesheet" href="media/css/bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="media/css/font-awesome-4.6.1/css/font-awesome.min.css">
	<link rel="stylesheet" href="media/css/bootstrap/css/jquery-jvectormap-1.2.2.css">
	<link rel="stylesheet" href="media/css/dist/css/AdminLTE.min.css">
	<!-- AdminLTE Skins. Choose a skin from the css/skins, folder instead of downloading all of them to reduce the load. -->
	<link rel="stylesheet" href="media/css/dist/css/skins/_all-skins.min.css">
	<!-- iCheck -->
	<!--<link rel="stylesheet" href="media/css/plugins/iCheck/flat/blue.css">-->
	<!-- Morris chart -->
	<!--<link rel="stylesheet" href="media/css/plugins/morris/morris.css">-->
	<!-- jvectormap -->
	<!--<link rel="stylesheet" href="media/css/plugins/jvectormap/jquery-jvectormap-1.2.2.css">-->
	<!-- Date Picker -->
	<link rel="stylesheet" href="media/css/plugins/datepicker/datepicker3.css">
	<!-- bootstrap wysihtml5 - text editor -->
	<!--<link rel="stylesheet" href="media/css/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">-->


	<script src="media/js/plugins/jQuery/jQuery-2.1.4.min.js"></script>
	

	<!-- Bootstrap 3.3.5 -->

	<script src="media/js/plugins/jQuery/bootstrap.js"></script>
	<script src="media/js/plugins/jQuery/bootbox.min.js"></script>

	<!--<script src="media/js/plugins/daterangepicker/daterangepicker.js"></script>-->

	<!-- datepicker -->
	<script src="media/js/plugins/datepicker/bootstrap-datepicker.js"></script>

	<script src="media/js/dist/js/app.min.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->

	<!-- AdminLTE for demo purposes -->
	<script src="media/js/dist/js/demo.js"></script>
	<script src="media/js/dist/js/jquery-loading-overlay-master/src/loadingoverlay.min.js"></script>

	<!-- Data Table-->
	<!--<script src="media/js/dist/js/jquery.dataTables.min.js"></script>
	<script src="media/js/dist/js/dataTables.bootstrap.min.js"></script>
	<script type="text/javascript" src="media/DataTables/fnReloadAjax.js"></script>-->


	<!-- Table Pagination Plugin-->
	<link rel="stylesheet" type="text/css" href="media/datatables.min.css"/>
	<script type="text/javascript" src="media/datatables.min.js"></script>
	<script type="text/javascript" src="media/DataTables/fnReloadAjax.js"></script>


	<style>
		.sidebar-menu > li.active > a
		{
		   background-color:#63686B !important;
		   color:white !important;
		   font-weight:bold;
		}
		.active
		{
		}

		.accordion {
			background-color: #222d32;
			color: #444;
			cursor: pointer;
			padding: 18px;
			width: 100%;
			text-align: left;
			border: none;
			outline: none;
			transition: 0.4s;
		}

		/* Add a background color to the button if it is clicked on (add the .active class with JS), and when you move the mouse over it (hover) */
		.active, .accordion:hover {
		}

		/* Style the accordion panel. Note: hidden by default */
		.accordionpanel {
			display: none;
			overflow: hidden;
			padding: 5px;
		}

	</style>

</head>

<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<header class="main-header">
			<a href="index.php" class="logo">
				<!-- mini logo for sidebar mini 50x50 pixels -->
				<span class="logo-mini"><b><font size=3px></font></b></span>
				<!-- logo for regular state and mobile devices -->
				<span class="logo-lg">  SMS MIS </span>
			</a>

			<!-- Header Navbar: style can be found in header.less -->
			<nav class="navbar navbar-static-top" role="navigation">
				<!-- Sidebar toggle button-->
				<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
					<span class="sr-only">Toggle navigation</span>
				</a>
				<!-- Navbar Right Menu -->
				<div class="navbar-custom-menu">
					<h5><font color=white>Username:<b> <?php echo $loggedinUserDetails['username'];?> &nbsp;&nbsp;&nbsp;</font> </b>|&nbsp;&nbsp;&nbsp;<a href="logout.php" style="color:white">Logout</a>&nbsp;&nbsp;&nbsp;</h5>
				</div>
			</nav>
		</header>

		<!-- Left side column. contains the logo and sidebar -->
		<aside class="main-sidebar">
			<!-- sidebar: style can be found in sidebar.less -->
			<section class="sidebar">
				<!-- sidebar menu: : style can be found in sidebar.less -->
				<ul class="sidebar-menu">
					<li class="treeview"><br></li>
					<li><br></li>
					<li class="treeview" id="summary_li" >
                                                <form action="summary.php" method="post" id='summary'>
                                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                        <a href="javascript:{}" onclick="document.getElementById('summary').submit();"><i class="fa fa-feed" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Summary</font></a>
                                                </form>
                                        </li>
					
					<!--<li><br></li>
					<li class="treeview" id="campaignwise_li" >
						<form action="campaignwiselogs.php" method="post" id='campaignwise'>
							
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:{}" onclick="document.getElementById('campaignwise').submit();"><i class="fa fa-line-chart" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Campaignwise</font></a>
						</form>
					</li>-->


					<li><br></li>
					<li class="treeview" id="search_li" >
						<form action="search.php" method="post" id='search'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:{}" onclick="document.getElementById('search').submit();"><i class="fa fa-search" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Search</font></a>
						</form>
					</li>
					<li><br></li>
					<li id="campaignwise" class="accordion">
						<a href="#"><i class="fa fa-plus" style="color: #faca25  ;margin-left:-7px;"></i>&nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Campaignwise</font></a>
						<ul class="accordionpanel sidebar-menu">
							<li><br></li>
							<li class="treeview" id="campaingwiselogs_li">
								<form action="campaignwiselogs.php" method="post" id='campaingwiselogs'>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:{}" onclick="document.getElementById('campaingwiselogs').submit();"><i class="fa fa-bars" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Current Month</font></a>
								</form>
								
							</li>
							<li><br></li>
							<li class="treeview" id="oldercampaingwiselogs_li">
								<form action="oldercampaignwiselogs.php" method="post" id='oldercampaingwiselogs'>
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="javascript:{}" onclick="document.getElementById('oldercampaingwiselogs').submit();"><i class="fa fa-bars" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Previous Month</font></a>
								</form>
							</li>
						</ul>
					</li>


					<li><br></li>
					<li class="treeview" id="detailedlogs_li" >
						<form action="detailedlogs.php" method="post" id='detailedlogs'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:{}" onclick="document.getElementById('detailedlogs').submit();"><i class="fa fa-download" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Detailed logs</font></a>
						</form>
					</li>

					<li><br></li>
					<li class="treeview" id="requestlogs_li" >
						<form action="logsrequests.php" method="post" id='requestlogs'>
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<a href="javascript:{}" onclick="document.getElementById('requestlogs').submit();"><i class="fa fa-download" style="color: #faca25  ;margin-left:-7px;"></i> &nbsp;&nbsp;&nbsp;&nbsp;<font color='white' style='font-weight:normal'>Requested logs</font></a>
						</form>
					</li>

				</ul>           
			</section>
			<!-- /.sidebar -->
		</aside>

		<script>
			var acc = document.getElementsByClassName("accordion");
			var i;

			for (i = 0; i < acc.length; i++) {
				acc[i].addEventListener("click", function() {
					this.classList.toggle("active");
						//var panel = this.nextElementSibling;
					
						if ($("ul.accordionpanel").css('display') != 'none') {
							$("ul.accordionpanel").hide();
						} else {
							$("ul.accordionpanel").show();
							//panel.style.display = "block";
						}
					});
			}
		</script>

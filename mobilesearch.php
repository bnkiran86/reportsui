<?php
	$usernames = array('ABOFsms','adabofsms','admin','Ajeenkya','alertsms','allensollyotp','amitdaga','Amitsms','amwaytrans','Aniruddha','arvindintern','ashirvad','Ashwasurya','AX120719SMSKB','AX240619SMSKB','AXCT14052019KB','AXCT26042019KB','AXIEVA24042019KB','bajajsms','batranjali','Benetton','birlawhite','BishopCotton','bluehyundai','bluestone','boxeightpromo','Bsnlalertsms','bulkpromo','bygbrewski','cadskpsms','celioapi','Celiopromo','Celiotrans','centraldnd','Centrldnd','Chaayosclm','Chaayosclm1','charutarhealth','clmorepromo','clmoresms','cmbpro','collectiveotp','D2HSASPROMO','dcthlnp','demo1','demo6','demoac','demoacc','demonew1','dhanwantry','digitalindia','dishtvsms','dncc','dndicspipe','dndsmspipe','dndxmlpipe','easyrewardlylty','easyrewardpromo','easyrewardtrans','easyrewardzauto','encrcl','encrcldnd','ffreshsms','forever21','forevr','fortismulund','forumpromo','healthianstrans','hnglowbulk','hnglowtrans','housejoysms','icsalertotp','icstest','insuremilepromo','Intellispace','inurturets','janalk','jindalsms','jsshospital','jssmch','jssunisms','Kalingahos','kayaec','kodvme','krishnam','labournet','lakmetrans','lendingkart','lendingkartpromo','levisotp','lmgotp','louisphillipeotp','mangoindia','MDBaug','medlabsms','metrodnd1','metrodnd2','metronondnd1','metronondnd2','mgundnd','mitsjadan','mitsjd','modex','ModiInfraPromo','ModiRealPromo','Morecrm','mtildemo','myntra','myntracart','newuidnd','Nirvaasms','npschool','numericcups','opendoors','opendoorsdnd','Paadashaala','pantaloons','peopleotp','peterengland','peterenglandotp','planetfashionotp','pwads','quikrPromo','Rapipay','ravindu','Raymond','rdmotors','rubanb','rymondsms','saturamotp','sbdsms','shellindnew','SKRMsms','smetmys','smlcamp','smsvalert','SnapDealOTP','SnapDealTrans','SnapdealTransSX','southformnewtrans','SouthformPromo','SouthformTrans','spencerics','spencersecom','spencerspost','spencersstr2','spencersstr4','spencersstr6','spencersstr7','spencersstr8','spencersyvm','ssnmchospital','ssyllpsms','stingdemo','StyleBaazar','sugal','tatatel','testmtnlt','TIC','tosohnew','touristsms','trans','Trellsms','trident','tridentnew','Ughaisms','ulccserp','ULIIT','ultcfdback','ultratech','Unipromo','unlmtdclm','urbantouchdemo','UrbanTouchPromo','urbantouchtrans','Utclcboa','utclcsc','utclcz','utcldcw','utclgj','Utclhwc','utcljcw','Utcljhra','utclkl','Utclkr','Utclkr1','Utcllog','utclodis','utclpcw','Utclrwn','utcltasc','utcltn','Utclts','vaidyasms1','valentinoshoes','vanheusenotp','vedantutrans','vfirstnew','Vrukksha','wigzopromo','wslyec','zivapromosms');

?>

<!DOCTYPE html>

<html>
<?php include ('templateheader.php'); ?>

  
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->       

        <!-- Main content -->
        <section class="content">
        	<center>
		<h3>Download Campaignwise SMS report</h3>	     
		</h6><font color=red>**Only current month's reports will be available here**</font></h6>
              
               <div class="well">
			<form action="queries/downloadquery.php" method="post" target="_blank">
			 <table class="table table-bordered table-hover table-striped col-xs-12 col-md-6 col-lg-6">
				
				<tr>
					<td style="text-align:right">Date Option:</td>
					<td><input type="radio"  name="selectiontype" checked value="specificdate" onclick="fnToggleSelectionType('specificdate')" />&nbsp;Specific Month</td>

					<td><input type="radio"  name="selectiontype"  value="daterange" onclick="fnToggleSelectionType('daterange')" />&nbsp;Date Range</td>
					<td></td>
				</tr>

				<tr id="specificdate" >
					<td>Date:</td>
					<td colspan="2"><input class='form-control datepicker' placeholder='Select Date' type="text" name="searchDate"  id="searchDate" /></td>
				</tr>

				<tr id="daterange" style="display:none">
			
					<td>
						 Select Date:
					</td>

					<td style="color:black;">
						<input  class='form-control datepicker' id='fromdate' name='fromdate' placeholder='From Date' style="width:50% !important">
					</td>
					<td style="color:black;">
						
						<input  class='form-control datepicker' id='todate' name='todate' placeholder='To Date' style="width:50% !important">
					</td>
				</tr>

				<tr>
					<td> Mobile Number:</td>
					<td style="color:black;" colspan='2'>
						<input  class='form-control' id='mobileno' name='mobileno' placeholder='Mobileno'>
					</td>
				</tr>

				<tr>
					<td>Tag your report:</td>
					<td style="color:black;" colspan='2'>
						<input  class='form-control' id='reporttag' name='reporttag'>	
					</td>
				</tr>


				<tr>
					<td colspan='3' align="center">
						<input type='hidden' value="<?php echo $_POST['username'];?>" id="username" name="username">
						<input type="button" class="btn btn-warning" value='Download' onclick="fnProcessDownload()">
					</td>				
				</tr>
			 </table>
			</form>
			<table id="exportlink" style="display:none" class='col-xs-12 col-md-12 col-lg-12'>
				<tr id="progressbar" style="display:none">
					<td>
						<iframe name="downloadFrame" id="downloadFrame" width="600" height="57" frameBorder="0"></iframe>
					</td>
				</tr>
			</table>


		</div>
              </center>

          <!-- Small boxes (Stat box) -->
          <div class="row">
           
          </div><!-- /.row -->

          <!-- Main row -->
          <div class="row">
       
           
          </div><!-- /.row (main row) -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


<?php include ('templatefooter.php'); ?>

<script type="text/javascript">
	$(document).ready(function(){

		/*
		var startDate = new Date();
		startDate.setDate(startDate.getDate() - 30);
		*/

		var date = new Date();
		var startDate = new Date(date.getFullYear(), date.getMonth(), 1);

		var endDate = new Date();
		endDate.setDate(endDate.getDate() - 1);

		$('input.datepicker').datepicker({
			format: 'dd-mm-yyyy',
			maxViewMode: 0,
			changeMonth: false,
			autoclose: true,
			startDate: startDate,
			endDate: endDate
		});
	});

	$('.treeview').removeClass('active');
	$('#repordownload_li').addClass('active');


	function fnToggleSelectionType(type){
		if(type == "daterange"){
			$("#daterange").show();
			$("#specificdate").hide();
		} else {
			$("#daterange").hide();
			$("#specificdate").show();
		}
	}


	function fnProcessDownload() {
		var flag = true;
		var fromdate = $.trim($("#fromdate").val());
		var todate = $.trim($("#todate").val());
		var username = $.trim($("#username").val());
		var server = $.trim($("#server").val());
		var mobileno = $.trim($("#mobileno").val());
		var reporttag = $.trim($("#reporttag").val());
		var specificdate = $.trim($("#specificdate").val());
		var searchDate = $.trim($("#searchDate").val());
		var selectiontype = $("input[name='selectiontype']:checked").val();

		//alert(searchDate);

		
		if(username == "") {
			alert("Select User");
			flag = false;
		}

		if(server == "") {
			alert("Select Server");
			flag = false;
		}

		if(selectiontype == "" ) {
			alert("Choose Date option");
			flag = false;
		}

		
		if(selectiontype == 'daterange') { 
			if(fromdate == "") {
				alert("Select From Date");
				flag = false;
			}

			if(todate == "") {
				alert("Select To Date");
				flag = false;
			}
		} else {
			if(searchDate == "") {
				alert("Select Date");
				flag = false;
			}

		}

		if(mobileno == "") {
			alert("Enter Mobileno");
			flag = false;
		}

		if(flag == true) {
			$("table#exportlink").css("display","block");
			$("tr#progressbar").css("display","block");

			var $frame = $('iframe#downloadFrame');
			var doc = $frame[0].contentWindow.document;

			var site = "scripts/saverequest.php"+"?type=MOBILESEARCH&server="+server+"&selectiontype="+selectiontype+"&searchdate="+searchDate+"&fromdate="+fromdate+"&todate="+todate+"&username="+username+"&mobileno="+encodeURI(mobileno)+"&reporttag="+encodeURI(reporttag);

			var $body = $('body',doc);
			$body.html('<div id="progress" style="width:1%;background-color:#ddd;background-image:url(media/pbar-ani.gif);"></div><div id="information" style="width">Fetching Logs...</div>');

			document.getElementById('downloadFrame').src = site;
		}
	}

	
</script>

</html>

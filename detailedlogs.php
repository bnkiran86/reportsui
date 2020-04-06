
<!DOCTYPE html>

<html>
<?php include_once(dirname(__FILE__).'/templateheader.php'); ?>

  
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->       

        <!-- Main content -->
        <section class="content">
        	<center>
		<h3>Download Detailed SMS logs</h3>	     
		<div class="well">
			<form id="logsform" method="post" target="_blank">
			 <table class="table table-bordered table-hover table-striped col-xs-12 col-md-6 col-lg-6">
				
				<tr tyle="text-align:right">
					<td>Tag your request:</td>
					<td colspan="4"><input class='form-control' type="text" name="reporttag" placeholder='Tag' id="reporttag" style="width:30% !important" maxlength='50' /></td>
				</tr>

				<tr tyle="text-align:right">
					<td>Sender ID:</td>

					<td style="color:black;">
						<input  class='form-control' id='senderid' name='senderid' placeholder='Sender ID' style="width:50% !important">
					</td>
					<td>Campaign Tag:</td>
					<td style="color:black;">
						<input  class='form-control' id='campaigntag' name='campaigntag' placeholder='Campaign Tag' style="width:50% !important">
					</td>
				</tr>

				<tr>
					<td style="text-align:left">Date Option:</td>
					<td><input type="radio"  name="selectiontype" checked value="specificdate" onclick="fnToggleSelectionType('specificdate')" />&nbsp;Specific Date</td>

					<td><input type="radio"  name="selectiontype"  value="daterange" onclick="fnToggleSelectionType('daterange')" />&nbsp;Date Range</td>
				</tr>

				<tr id="specificdate">
					<td>Date:</td>
					<td colspan="4"><input class='form-control datepicker' type="text" name="searchDate" placeholder='Select Date' id="searchDate" style="width:30% !important" /></td>
				</tr>

				<tr id="daterange" style="display:none">
					<td>Select Date:</td>
					<td style="color:black;">
						<input  class='form-control datepicker' id='fromdate' name='fromdate' placeholder='From Date' style="width:50% !important">
					</td>
					<td style="color:black;">
						<input  class='form-control datepicker' id='todate' name='todate' placeholder='To Date' style="width:50% !important">
					</td>
				</tr>




				<tr>
					<td colspan='4' align="center">
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


<?php include_once(dirname(__FILE__).'/templatefooter.php'); ?>

<script type="text/javascript">
	$(document).ready(function(){
		/*
		var startDate = new Date();
		startDate.setDate(startDate.getDate() - 30);
		*/

		var date = new Date();
		var startDate = new Date(date.getFullYear(), date.getMonth()-3, 1);

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
		} else{
			$("#daterange").hide();
			$("#specificdate").show();
		}
	}



	function fnProcessDownload() {
		var flag = true;

		var reporttag = $.trim($("#reporttag").val());
		var senderid = $.trim($("#senderid").val());
		var campaigntag = $.trim($("#campaigntag").val());
		var fromdate = $.trim($("#fromdate").val());
		var todate = $.trim($("#todate").val());
		var specificdate = $.trim($("#specificdate").val());
		var selectiontype = $("input[name='selectiontype']:checked").val();
		var searchDate = $.trim($("#searchDate").val());
		
		
		if(reporttag == "" ) {
			alert("Tag your request");
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



		if(flag == true) {
			$("table#exportlink").css("display","block");
			$("tr#progressbar").css("display","block");

			var $frame = $('iframe#downloadFrame');
			var doc = $frame[0].contentWindow.document;
			var formData = $('form#logsform').serialize();

			
			//console.log(formData);

			$.ajax({
						url: 'scripts/detailedlogs.php', 
						type: 'POST',
						data: $('form#logsform').serialize(),
						success: function(data){
							var resp = $.trim(data);
							
							if(resp == 'LOGIN_SESSION_EXPIRED') {
								alert("Your login Session Expired");
								window.location = "login.html";
							} else {
								alert(resp); // show response from the php script.
								window.location = "logsrequests.php";
							}
						},
						error: function (data) {
							alert('An error occurred, please try again');
						}
			});

		}
	}

	
</script>

</html>

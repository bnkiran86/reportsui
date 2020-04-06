

<!DOCTYPE html>

<html>
<!--
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
-->
<?php include_once(dirname(__FILE__).'/templateheader.php'); ?>

 
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->       

        <!-- Main content -->
        <section class="content">
        
              <center>

		<h3>Requested Logs Details</h3>
                <div class="well">
			
				
			 <table class="table table-bordered table-hover table-striped col-xs-12 col-md-6 col-lg-6">
				<tr>
					<td style="color:black">
						<input  class='form-control datepicker' id='fromdate' name='fromdate' placeholder='From Date'>
					</td>


					<td style="color:black">
						<input class='form-control datepicker' id='todate' name='todate' placeholder='To Date'>
					</td>

					<td style="color:black;cursor:pointer" >
						<input type='text' value='' class='form-control' id='requesttag' name='requesttag' placeholder="Request Tag">
					</td>

					<td  align="center">
						<input type="button" class="btn btn-warning" value="Filter" onclick="fnFilter()">
					</td>

					<td  align="left">
                                                <input type="button" class="btn btn-warning" value="Clear Filter" onclick="fnClearFilter()">
                                        </td>
				</tr>
			
			

			 </table>
			
		</div>
		

              </center>

		

	 <table class="table table-bordered table-hover table-striped" id="requestsTable">
		<thead>
		    <tr class="info">	        
			  <th>Request Date</th>
			  <th>Request Tag</th>
		          <th>Status</th>
			  <th>Download Link</th>
		    </tr>
		</thead>
		<tbody id="grid_tab_tbody">
	     
		</tbody>
         </table>
	<input type="hidden" id="selected_fileids" name="selected_fileids" />
</div>



         
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


<?php include_once(dirname(__FILE__).'/templatefooter.php'); ?>

</html>


<script type="text/javascript">

	
	$(document).ready(function(){
		$("input#fromdate").val("");
		$("input#todate").val("");
		$("input#campaigntag").val("");

		//var startDate = new Date();
		//startDate.setDate(startDate.getDate() - 1);

		var date = new Date();
		var startDate = new Date(date.getFullYear(), date.getMonth(), 1);
		
	
		$('input.datepicker').datepicker({
			format: 'dd-mm-yyyy',
			maxViewMode: 0,
			changeMonth: false,
			autoclose: true,
			todayHighlight:'TRUE'			
			
		});


		//DataTable Initialization
		oTable = $('#requestsTable').dataTable( {
			"bJQueryUI": true,
			"bInfo" : true,
			"aaSorting": [[ 0, "desc" ]],
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"bServerSide": true,
			"searching" : false,
			"lengthChange": false,
			"ordering": false,
			"sAjaxSource": "scripts/requestedlogsdetails.php",
			"fnServerData": function(url, data, callback) {

				data.push( { "name": "fromDate", "value": $("#fromdate").val() } );
				data.push( { "name": "toDate", "value": $("#todate").val() } );
				data.push( { "name": "requesttag", "value": $.trim($("#requesttag").val()) } );

				$.ajax({
					"url": url,
					"data": data,
					"success": callback,
					//"contentType": "application/x-www-form-urlencoded; charset=utf-8",
					"dataType": "json",
					"type": "POST",
					"cache": false,
					"error": function() {
						alert("DataTables warning: JSON data from server failed to load or be parsed. " + "This is most likely to be caused by a JSON formatting error.");
					}
				});
			},
			"oLanguage": { "sInfoFiltered": "" }
		} );


	});



	
	function fnFilter() {
		var fromDate = $("#fromdate").val();
		var toDate = $("#todate").val();
		var requesttag = $.trim($("#requesttag").val());


		
		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&fromDate="+fromDate+"&toDate="+toDate+"&requesttag="+requesttag;
		oTable.fnReloadAjax('scripts/requestedlogsdetails.php?'+params);
		
	}

	function fnClearFilter() {
		$("#fromdate").val("");
		$("#todate").val("");
		$("#requesttag").val("");

		var fromDate = "";
		var toDate = "";
		var requesttag = "";

		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&fromDate="+fromDate+"&toDate="+toDate+"&requesttag="+requesttag;
		oTable.fnReloadAjax('scripts/requestedlogsdetails.php?'+params);
}



$('.treeview').removeClass('active');
$('#deliveryanalysis_li').addClass('active');

$("#month,#daterange").val('');

function fncirclewisequery()
{

	if($('#month').val()==null || $('#daterange').val()==null)
	{
		bootbox.alert('Please select valid Month/Daterange');
		return false;
	}
	$.LoadingOverlay("show");
	var form_data={'month':$('#month').val(),'daterange':$('#daterange').val(),'seqid':$('#seqid').val(),username:$('#username').val()};
	$.ajax({
		url : "queries/circlewisequery.php",	                 
		type: 'post',
		data:form_data,
		dataType : "JSON",
		async:false,
		success: function(data) {
			if(data.status=="success")
			{
				$('#grid_tab_tbody').html('');
				if(data.data.result=="No files")
				{
					$('#grid_tab_tbody').html('');     
					$('#grid_tab_tbody').append('<tr><td colspan="8" align="center"> No records found!</td></tr>');
					$('#rows_per_page').addClass('hidden');
					cur_page=0;
				}
				else
				{
					var arr_length=data.data.result.length;
					for(var i=0;i<arr_length;i++)
					{
						var row=data.data.result[i];
						fninsert_row(row);
				
					}
				}
			}
			else
			{
				bootbox.alert("please refresh page");
			}
		}
	});  
	$.LoadingOverlay("hide");
}  

function fninsert_row(row)
 {
        
	$('#grid_tab_tbody').append('<tr>'+
	'<td>'+row.circle+'</td>'+
	'<td>'+row.count_0_6A+'</td>'+
	'<td>'+row.count_6_10A+'</td>'+
	'<td>'+row.count_10_12A+'</td>'+
	'<td>'+row.count_12_2A+'</td>'+
	'<td>'+row.count_2_5A+'</td>'+
	'<td>'+row.count_5_8A+'</td>'+
	'<td>'+row.count_8_12A+'</td>'+
	'</tr>');
 }

</script>

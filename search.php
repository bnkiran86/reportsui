

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

		<h3>Mobileno Search</h3>
		</h6><font color=red>Current day's logs will be available here</font></h6>
                <div class="well">
			
				
			 <table class="table table-bordered table-hover table-striped col-xs-12 col-md-6 col-lg-6">
				<tr>
					<td style="color:black">
						<input  class='form-control' id='mobileno' name='mobileno' placeholder='Mobileno'>
					</td>

					<td  align="center">
						<input type="button" class="btn btn-warning" value="Filter" onclick="fnFilter()">
					</td>

					<td  align="left">
                            <input type="button" class="btn btn-warning" value="Clear Filter" onclick="fnClearFilter()">
                   </td>

					<!--<td align="center" id="exportBtn">
						<input type="button" class="btn btn-warning" value="Export Selected" onclick="fnExportSelectedFiles()">
					</td>-->
				</tr>
			
			

			 </table>
			
		</div>
		

              </center>

		

	 <table class="table table-bordered table-hover table-striped" id="campaignsTable">
		<thead>
		    <tr class="info">	        
			<th width='1%'>&nbsp;</th>
		          <th>Mobileno</th>
		          <th>Senderid</th>
			<th>SMS Text</th>
			  <th>Submit Time</th>
		          <th>Delivered Time</th>
			  <th>Status</th>
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
		$("input#mobileno").val("");



		//DataTable Initialization
		oTable = $('#campaignsTable').dataTable( {
			"bJQueryUI": true,
			"bInfo" : true,
			"aaSorting": [[ 0, "desc" ]],
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"bServerSide": true,
			"searching" : false,
			"lengthChange": false,
			"ordering": false,
			"sAjaxSource": "scripts/mobilenosearch.php",
			"fnServerData": function(url, data, callback) {

				data.push( { "name": "mobileno", "value": $.trim($("#mobileno").val()) } );

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
		var mobileno = $.trim($("#mobileno").val());
		
		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&mobileno="+mobileno;
		oTable.fnReloadAjax('scripts/mobilenosearch.php?'+params);
		
	}

	function fnClearFilter() {
		$("#mobileno").val("");

		var fromDate = "";
		var toDate = "";
		var mobileno = "";

		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&mobileno="+mobileno;
		oTable.fnReloadAjax('scripts/mobilenosearch.php?'+params);
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

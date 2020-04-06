

<!DOCTYPE html>

<html>
<?php include ('templateheader.php'); ?>

 
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->       

        <!-- Main content -->
        <section class="content">
        
              <center>

		<h3>Activity Logs</h3>
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
						<input type='text' value='' class='form-control' id='reporttag' name='reporttag' placeholder="Report Tag">
					</td>

					<td colspan="2" align="center">
						<input type='hidden' value="<?php echo $_POST['username'];?>" id="username" name="username">
						<input type="button" class="btn btn-warning" value="Filter" onclick="fnFilter()">
					</td>
				</tr>
			
			

			 </table>
			
		</div>
		

              </center>

	 <table class="table table-bordered table-hover table-striped" id="reportTable">
		<thead>
		    <tr class="info">	        
			  <th>Requested Time</th>
			  <th>Report Tag</th>
			  <th>Download Link</th>
		    </tr>
		</thead>
		<tbody id="grid_tab_tbody">
	     
		</tbody>
         </table>
</div>



         
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->


<?php include ('templatefooter.php'); ?>

</html>

<!-- Table Pagination Plugin-->
<link rel="stylesheet" type="text/css" href="media/datatables.min.css"/>
<script type="text/javascript" src="media/datatables.min.js"></script>
<script type="text/javascript" src="media/DataTables/fnReloadAjax.js"></script>

<script type="text/javascript">

	
	$(document).ready(function(){
		var startDate = new Date();
		startDate.setDate(startDate.getDate() - 45);

		
	
		$('input.datepicker').datepicker({
			format: 'dd-mm-yyyy',
			maxViewMode: 0,
			changeMonth: false,
			autoclose: true,
			todayHighlight:'TRUE',
			//startDate: startDate,
			//endDate: new Date()
			
			
		});

		//DataTable Initialization
		oTable = $('#reportTable').dataTable( {
			//"bSort": true,
			//"aoColumnDefs" : [ {'bSortable' : false,'aTargets' : [ 5,7,8,9,10,11,12,13,14,15,17]} ] ,
			"bJQueryUI": true,
			"aaSorting": [[ 0, "desc" ]],
			//"aaSorting": [[ 0, "desc" ], [1,"desc"]],
			"sPaginationType": "full_numbers",
			"bProcessing": true,
			"bServerSide": true,
			"searching" : false,
			"lengthChange": false,	
			"sAjaxSource": "scripts/fetchreportdetails.php",
			"fnServerData": function(url, data, callback) {
						/*
						var newDataObj = {};
						newDataObj['name'] = 'campaigntag';
						newDataObj['value'] = $.trim($("#campaigntag").val());
						

						//var newDataObj = {'fromDate':$("#fromdate").val(),'toDate':$("#todate").val(),'campaigntag':$.trim($("#campaigntag").val())};
						data.push(newDataObj);
						*/

						data.push( { "name": "fromDate", "value": $("#fromdate").val() } );
						data.push( { "name": "toDate", "value": $("#todate").val() } );
						data.push( { "name": "reporttag", "value": $.trim($("#reporttag").val()) } );
						$.ajax({
							"url": url,
							"data": data,
							"success": callback,
							//"contentType": "application/x-www-form-urlencoded; charset=utf-8",
							"dataType": "json",
							"type": "GET",
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
		var reporttag = $.trim($("#reporttag").val());

		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&fromDate="+fromDate+"&toDate="+toDate+"&reporttag="+reporttag;
		oTable.fnReloadAjax('scripts/fetchreportdetails.php?'+params);
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

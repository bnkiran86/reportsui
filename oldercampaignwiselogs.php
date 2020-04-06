

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

		<h3>Campaignwise Details</h3>
		</h6><font color=red>Previous month's reports will be available here</font></h6>
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
						<input type='text' value='' class='form-control' id='campaigntag' name='campaigntag' placeholder="Campaign Tag">
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
			  <th>Campagin Date</th>
		          <th>Campagin Type</th>
			  <th>Campaign Tag</th>
			  <th>Option</th>
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
		$("td#exportBtn").hide();
		$("input#fromdate").val("");
		$("input#todate").val("");
		$("input#campaigntag").val("");

		/*
		var date = new Date();
		var startDate = new Date(date.getFullYear(), date.getMonth(), 1);
		var endDate = new Date(date.getFullYear(), date.getMonth() + 1, 0);
		*/
		var now = new Date();
		var endDate = new Date(now.getFullYear(), now.getMonth(), 0);
		var startDate = new Date(now.getFullYear() - (now.getMonth() > 0 ? 0 : 1), (now.getMonth() - 1 + 12) % 12, 1);
		
	
		$('input.datepicker').datepicker({
			format: 'dd-mm-yyyy',
			maxViewMode: 0,
			changeMonth: false,
			autoclose: true,
			startDate: startDate,
			endDate: endDate			
		});



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
			"sAjaxSource": "scripts/oldercampaignwisedetails.php",
			"fnServerData": function(url, data, callback) {

				data.push( { "name": "fromDate", "value": $("#fromdate").val() } );
				data.push( { "name": "toDate", "value": $("#todate").val() } );
				data.push( { "name": "campaigntag", "value": $.trim($("#campaigntag").val()) } );

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


	function base64_encode (data) {
		// From: http://phpjs.org/functions
		// +   original by: Tyler Akins (http://rumkin.com)
		// +   improved by: Bayron Guevara
		// +   improved by: Thunder.m
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   bugfixed by: Pellentesque Malesuada
		// +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// +   improved by: Rafał Kukawski (http://kukawski.pl)
		// *     example 1: base64_encode('Kevin van Zonneveld');
		// *     returns 1: 'S2V2aW4gdmFuIFpvbm5ldmVsZA=='
		// mozilla has this native
		// - but breaks in 2.0.0.12!
		//if (typeof this.window['btoa'] === 'function') {
		//    return btoa(data);
		//}
		var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
		var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
		ac = 0,
		enc = "",
		tmp_arr = [];

		if (!data) {
			return data;
		}
		do { // pack three octets into four hexets
			o1 = data.charCodeAt(i++);
			o2 = data.charCodeAt(i++);
			o3 = data.charCodeAt(i++);

			bits = o1 << 16 | o2 << 8 | o3;

			h1 = bits >> 18 & 0x3f;
			h2 = bits >> 12 & 0x3f;
			h3 = bits >> 6 & 0x3f;
			h4 = bits & 0x3f;

			// use hexets to index into b64, and append result to encoded string
			tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
		} while (i < data.length);

		enc = tmp_arr.join('');

		var r = data.length % 3;

		return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
	}


	

	function fnSelectForExport(obj,id) {
		var value = $("input#selected_fileids").val();
		if($(obj).is(':checked')){
			//Add this value to hidden field comma seperated
			value += id+",";
			$("input#selected_fileids").val(value);
		} else {
			//Remove this value from the hidden field
			value = value.replace(id,'');
			value = value.replace(',,',',');
			$("input#selected_fileids").val(value);
		}

		var selectedFiles = $.trim($("input#selected_fileids").val());
		if(selectedFiles != "" || selectedFiles != ",") {
			$("td#exportBtn").show();
		} else {
			$("td#exportBtn").hide();
		}
		
		//console.log($("input#selected_fileids").val());
	}


	function fnExportSelectedFiles() {
		var values = $.trim($("#selected_fileids").val());
		if(values !="" || values != ",") {
			var params = base64_encode(values);
			/*
			//window.location.href="bulkexport.php?q="+params;
			var newForm = jQuery('<form id="tempform">', {
						'action': 'bulkexport.php',
						'target': '_top'
					}).append(jQuery('<input>', {
						'name': 'q',
						'value': params,
						'type': 'hidden'
					}));
			newForm.submit();

			//Remove form elem
			//$("form#tempform").remove();
			*/
			$('<form>', {
				"id": "tempform",
				"method":"post",
				"html": '<input type="hidden" id="q" name="q" value="' + params + '" />',
				"action": 'bulkolderexport.php'
			}).appendTo(document.body).submit();

		} else {
			alert("Please select the data you want to export");
		}
	}


	function fetch_data(is_date_search, start_date='', end_date='', campaigntag) {
		var dataTable = $('#campaignsTable').DataTable({
					"processing" : true,
					"serverSide" : true,
					"searching" : false,
					"lengthChange": false,
					"order" : [],
					"ajax" : {
						url:"scripts/oldercampaignwisedetails.php",
						type:"POST",
						data:{
							is_date_search:is_date_search, start_date:start_date, 
							end_date:end_date,campagintag:campaigntag
						}
					}
			});
	}

	
	function fnFilter() {
		var fromDate = $("#fromdate").val();
		var toDate = $("#todate").val();
		var campaigntag = $.trim($("#campaigntag").val());

		//fetch_data('yes', fromDate, toDate, campaigntag);

		
		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&fromDate="+fromDate+"&toDate="+toDate+"&campaigntag="+campaigntag;
		oTable.fnReloadAjax('scripts/oldercampaignwisedetails.php?'+params);
		
	}

	function fnClearFilter() {
		$("#fromdate").val("");
		$("#todate").val("");
		$("#campaigntag").val("");
		var fromDate = "";
		var toDate = "";
		var campaigntag = "";

		oTable.fnClearTable(0);
		var params = "ajaxCall=yes&fromDate="+fromDate+"&toDate="+toDate+"&campaigntag="+campaigntag;
		oTable.fnReloadAjax('scripts/oldercampaignwisedetails.php?'+params);
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

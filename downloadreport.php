

<!DOCTYPE html>

<html>
<?php include ('templateheader.php'); ?>

  
  
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->       

        <!-- Main content -->
        <section class="content">
        	<center>
		<h3>DownLoad SMS report</h3>	     
		</h6><font color=red>**Reports of Last Month and current Month till yesterday are available here**</font></h6>
              
               <div class="well">
			<form action="queries/downloadquery.php" method="post" target="_blank">
			 <table class="table table-bordered table-hover table-striped col-xs-12 col-md-6 col-lg-6">
				
				 <tr>
                                        <td>
                                                Select Month:
                                        </td>

                                        <td style="color:black;cursor:pointer">
                                                <select id='month' name='month' class='form-control'>
                                                        <option value='currentmonth'>Current Month</option>
                                                        <!--<option value='lastmonth'>Last Month</option>-->
                                                </select>
                                        </td>
                                </tr>

				<tr>
			
					<td>
						 Date Range:
					</td>

					<td style="color:black;cursor:pointer">
						<select id='daterange' name='daterange' class='form-control'>
							<option value='1to10'>1 to 10</option>
							<option value='11to20'>11 to 20</option>
							<option value='21to31'>21 to 31</option>
						</select>
					</td>
				</tr>

				<tr>
					<td>
						 Campaign Id(Optional):
					</td>


					<td style="color:black;cursor:pointer" >
						<input type='text' value='' class='form-control' id='seqid' name='seqid' placeholder="campaign ID">
					</td>
		
				</tr>


				<tr>
					<td>
						Custom Fields(MobNum and status will be mandatory)
					</td>
					<td>

						<input type="checkbox" value='Message' name="columnarray[]"/>Message &nbsp;&nbsp;&nbsp;
						<input type="checkbox" Value='Dlv time' name="columnarray[]"/>Delivery time &nbsp;&nbsp;&nbsp;
						<input type="checkbox" Value='Credits' name="columnarray[]"/>Credits &nbsp;&nbsp;&nbsp;
					</td>
				</tr>

				<tr>
					<td colspan='2' align="center">
						<input type='hidden' value="<?php echo $_POST['username'];?>" id="username" name="username">
						<input type="submit" class="btn btn-warning" value='Download'>
					</td>


					
				</tr>


			 </table>
			</form>

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

<script>
$('.treeview').removeClass('active');
$('#repordownload_li').addClass('active');
</script>

</html>

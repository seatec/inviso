<?php session_start(); 
 include "db_connect.php"; ?>
 
                  <?php if( isset($_SESSION['ID']) ) {
						 echo $_SESSION['username'] ;
					} ?>
              <?php
		if(isset($_POST['delete']) ) {
				$deleted = $_POST['checkbox'];
				$user_id  = $_POST['user_id'];
				$n = 0;
			foreach ($deleted as $index => $value) {
				$del_sql = "DELETE FROM sites WHERE user_id = '$value'";
				$result = mysql_query($del_sql) or die(mysql_error());

				$n++;
				}
		echo "<p>Deleted ". $n ."item(s)</p>";
		header("Location:user_listing.php");
		}

		$user_id = $_GET['id'];
		$where = "user_id = '$user_id' ";
		$sql = "SELECT id, description FROM sites WHERE ".$where." ORDER BY id";
		$result = mysql_query($sql) or die(mysql_error());
		$num = mysql_num_rows($result);
		if($num > 0) {
		echo "<h2>".$num ." row(s) returned.</h2>";
		echo "<a href='add_site.php?id=$user_id'>Add Site </a> | ";
		echo "<a href='user_listing.php'>Back to User Listing </a>";
		?>
     <!-- USER LISTING FORM
		 <form action="" name="listingForm" id="listingForm" method="post">
                <input type="hidden" name="user_id" value="<?php echo $_GET['user_id'] ?>" />
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr class="headerRow">
                        <th>
                          <input type="checkbox" onclick="toggleChecked(this.checked)" />
                        </th>
                        <th>Site</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
						while ($row = mysql_fetch_object($result)) {            
						?>
                      <tr class="gridRow">
                        <td>
                          <input name="checkbox[]" type="checkbox" class="checkbox" value="<?php echo $row->id; ?>" />
                        </td>
                        <td>
                          <?php echo $row->description ?>
                        </td>
                      </tr><?php   
								}
								?>
                      <tr>
                        <td colspan="20" bgcolor="#FFFFFF">
                          <input name="delete" type="submit" id="delete" value="Delete" style="background:red;" />
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </form>
			  -->
			  <?php
				}
				else {
						echo "No records found.";
				}
				?>

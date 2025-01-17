<?php
    session_start(); // session start
    ob_start();
    ini_set('display_errors', '0'); // errors reporting on
    error_reporting(E_ALL); // all errors
    require_once('../includes/functions.php');
    require_once('../includes/connection.inc.php');
    require_once('../includes/imageFunctions.inc.php');
    $link = connectTo();
       if(!isset($_SESSION['authenticated']) || $_SESSION['role'] != "RP")
       {
            header('Location: ../index.php');
            exit;
       }
       if($_SESSION['freeze'] == "TRUE")
       {
          // echo "Account Frozen";
           header('Location: accountEdit.php');
       }
       $group = $_SESSION['groupid'];
       $userID = $_SESSION['userId'];
       if(isset($_POST['submit']))
       {
          $cname = mysqli_real_escape_string($link, $_POST['cname']);
          $address1 = mysqli_real_escape_string($link, $_POST['address1']);
          $address2 = mysqli_real_escape_string($link, $_POST['address2']);
          $city = mysqli_real_escape_string($link, $_POST['city']);
          $state = mysqli_real_escape_string($link, $_POST['state']);
          $fname = mysqli_real_escape_string($link, $_POST['fname']);
          $mname = mysqli_real_escape_string($link, $_POST['mname']);
          $lname = mysqli_real_escape_string($link, $_POST['lname']);
          $email = mysqli_real_escape_string($link, $_POST['email']);
          $zip = mysqli_real_escape_string($link, $_POST['zip']);
          $phone = mysqli_real_escape_string($link, $_POST['phone']);
          $ext = mysqli_real_escape_string($link, $_POST['ext']);
          $dealerID = mysqli_real_escape_string($link, $_POST['memberid']);
          $tt = mysqli_real_escape_string($link, $_POST['title']);
	  $gd = mysqli_real_escape_string($link, $_POST['gender']);
          $type = "Business Associate";
          $conPhoto = $_FILES['uploaded_file']['tmp_name'];
	  $imageDirPath = $_SERVER['DOCUMENT_ROOT'].'/images/contacts/';
	  $conPicPath = "";
	  $groupID = mysqli_real_escape_string($link, $_POST['groupid']);
          
          function process_image($name, $id, $tmpPic, $baseDirPath){

		$cleanedPic = checkName($_FILES["$name"]["name"]);	
		if(!is_image($tmpPic)) {
    			// is not an image
    			$upload_msg .= $cleanedPic . " is not an image file. <br />";
    		} else {
    			if($_FILES['$name']['error'] > 0) {
				$upload_msg .= $_FILES['$name']['error'] . "<br />";
			} else {
				
				if (file_exists($baseDirPath.$id."/".$cleanedPic)){
					$imagePath = "images/contacts/".$id."/".$cleanedPic;
				} else {
					$picDirectory = $baseDirPath;
					
					
					if (!is_dir($picDirectory.$id)){
						mkdir($picDirectory.$id);
						
					}
					$picDirectory = $picDirectory.$id."/";
					move_uploaded_file($tmpPic, $picDirectory . $cleanedPic);
					$upload_msg .= "$cleanedPic uploaded.<br />";
					$imagePath = "images/contacts/".$id."/".$cleanedPic;
					
					
				}// end third inner else
				return $imagePath;
			} // end first inner else
		      } // end else
	     }// end process_image
	     
	     
	     if($conPhoto != '')
	     {
		    $personalPicPath = process_image('uploaded_file',$dealerID, $conPhoto, $imageDirPath);   
	     }
          
          $query = "INSERT INTO orgCustomers (first, last, companyname, relationship, gender, email, workPhone, ext,  address, apt, city, state, zip, fundMemberID,fundGroupID, image_path, repID, title)VALUES('$fname', '$lname', '$cname', '$type', '$gd', '$email', '$phone', '$ext',  '$address1', '$address2', '$city','$state', '$zip', '$dealerID', '$groupID', '$personalPicPath', '$userID', '$tt')";
          $result = mysqli_query($link, $query)or die("MySQL ERROR query 1: ".mysqli_error($link));
             
          $query2 = "INSERT INTO orgContacts(Title, orgFName, orgLName, orgEmail, orgPhone, fundraiserID, fund_owner_id, org_contact_created, repID)VALUES('$cname', '$fname', '$lname', '$email', '$phone', '$dealerID', '$groupID', 'now()','$userID')";
          $result2 = mysqli_query($link, $query2)or die("MYSQL ERROR query 2: ".mysqli_error($link));
          if($result && $result2 )
          {
            header( 'Location: contacts.php' );
          }
          
       }//end post submit
                                                     
   $userID = $_SESSION['userId'];
   $query = "SELECT * FROM user_info WHERE userInfoID='$userID'";
   $result = mysqli_query($link, $query)or die ("couldn't execute query.".mysql_error());
   $row = mysqli_fetch_assoc($result);
   $pic = $row['picPath'];
?>
<!DOCTYPE html>
<head>
	<title>Add Business Associate</title>
<style>
#border{
background-color:#f8f8f8;
box-shadow: 0px 0px 15px #888888;
padding:15px 35px 40px 35px; 
}
</style>
</head>

<body>
        <?php include 'header.inc.php' ; ?>
      <?php include 'sideLeftNav.php' ; ?>

    <div class="container" id="getStartedContent" >
        <div class="row-fluid">
 <div class="page-header">
          <h2 align="center">Add Business Associate</h2>
</div>
 <div class=" col-md-7 col-md-push-2" id="bizAssociate-col">
		<div class="table">
<br>
<div id="border">
			<form class="" action="addBusinessAssociate.php" method="post" id="myForm" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
			
					<div class="row" style="margin-left:15px">
					        <!--<span style="line-height:35px;" id="hd_fm4">Select Representative:</span>-->
						<!--<span style="line-height:35px;" id="hd_fm4">Select Account Group:</span>-->
						<!--<span style="line-height:35px;" id="hd_fm4">Select Member:</span>-->
					</div> <!-- end row -->
					
					<div class="row">
						<span id="hd_fm4">Select Account Group:</span>
						<span id="hd_fm4">Select Member:</span>
					</div> <!-- end row -->
					
					<div class="row">
					     <!--<span id="ga"></span>-->
						<select class="" name="groupid" id="groupid" onChange="fetch_select2(this.value);" required>
							<option value="">Select FR Account</option>
							<?php 
						$getAccount = "SELECT * FROM Dealers WHERE setuppersonid = '$userID' AND isMainGroup = 0 ORDER BY Dealer asc";
						$result = mysqli_query($link, $getAccount)
						or die("MySQL ERROR om query 1: ".mysqli_error($link));
						while($row = mysqli_fetch_assoc($result))
						{
						  $dealerName = $row['Dealer'];
						  echo '
						  <option value="'.$row['loginid'].'">'.$dealerName.' '.$row[DealerDir].'</option>
						  ';
					        }
						?>
						</select>
						<!-- <span id="ma"></span>-->
						<select class="" name="memberid" id="memberid" required>
						<option value="">Select Member</option>

						</select>
						<!--<select name="" class="role2">
							<option value="">Select Business</option>
							<option value=""></option>
							<option value=""></option>
							<option value=""></option>
						</select>-->
					</div> <!-- end row -->

				<div class="simpleTabs" style="margin-left:15px">
					<!--<ul class="simpleTabsNavigation">
						<li><a href="#">Information</a></li>
						<li><a href="#">Account Login</a></li>
						<li><a href="#">Profile Photo</a></li>
					</ul>-->
					<div class="interim-form">
						<div class="interim-header"><h2>Information</h2></div>
						<hr style="border-color:#b8b8b8;">
						
						<!--<span>Position Title:</span>
						<select name="">
							<option value="">Select Position Title</option>
							<option value="">-depends on business-</option>
							<option value=""></option>
							<option value=""></option>
							<option value=""></option>
						</select>-->
						
						<div class="row" style="margin-left:2px"> <!-- titles -->									
							<span style="line-height:35px;" id="hd_fname">First</span>
							<!--<span id="hd_mname">Middle</span>-->
							<span style="line-height:35px;margin-left:129px;" id="hd_lname">Last</span>
							<span style="line-height:35px;margin-left:128px;" id="hd_title">Title</span>
							<span style="line-height:35px;margin-left:28px;" id="hd_gender">Gender</span>
						</div> <!-- end row -->
						<div class="row" style="margin-left:2px"> <!-- inputs -->
							<input id="fname" type="text" name="fname" required>
							<!--<input id="mname" type="text" name="mname">-->
							<input id="lname" type="text" name="lname" required>
							<!--<input id="pname" type="text" name="">-->
							<select name="title">
								<option value="">---</option>
								<option value="Mr.">Mr.</option>
								<option value="Ms.">Ms.</option>
								<option value="Mrs.">Mrs.</option>
								<option value="Miss">Miss</option>
								<option value="Dr.">Dr.</option>
								<option value="Rev.">Rev.</option>
							</select>
							<select name="gender">
								<option value="">---</option>
								<option value="Male">Male</option>
								<option value="Female">Female</option>
							</select>
						</div> <!-- end row -->
						
						<br>
						
						<table>
							<tr>
								<td id="td_1">
									<!-- Physical Address -->
									<div class="row" style="margin-left:2px"> <!-- title -->
										<!--<span style="line-height:35px;" id="hd_address1">Address 1</span>-->
									</div> <!-- end row -->
									<div class="row" style="margin-left:2px"> <!-- input -->
										<!--<input id="address1" type="text" name="address1" required>-->
									</div> <!-- end row -->
									
									<div class="row" style="margin-left:2px"> <!-- title -->
										<!--<span style="line-height:35px;" id="hd_address2">Address 2</span>-->
									</div> <!-- end row -->
									<div class="row" style="margin-left:2px"> <!-- input -->
										<!--<input id="address2" type="text" name="address2">-->
									</div> <!-- end row -->
													
									<div class="row" style="margin-left:2px"> <!-- titles -->
										<!--<span style="line-height:35px;" id="hd_city">City</span>-->
										<!--<span style="line-height:35px;margin-left:130px;" id="hd_state">State</span>-->
										<!--<span style="line-height:35px;margin-left:17px;" id="hd_zip">Zip</span>-->
									</div> <!-- end row -->
									<div class="row" style="margin-left:2px"> <!-- inputs -->
										<!--<input id="city" type="text" name="city" required>-->
										<!--<select id="state" name="state" required>
											<option value="">--</option>
											<option value="AL">AL</option>
											<option value="AK">AK</option>
											<option value="AZ">AZ</option>
											<option value="AR">AR</option>
											<option value="CA">CA</option>
											<option value="CO">CO</option>
											<option value="CT">CT</option>
											<option value="DE">DE</option>
											<option value="DC">DC</option>
											<option value="FL">FL</option>
											<option value="GA">GA</option>
											<option value="HI">HI</option>
											<option value="ID">ID</option>
											<option value="IL">IL</option>
											<option value="IN">IN</option>
											<option value="IA">IA</option>
											<option value="KS">KS</option>
											<option value="KY">KY</option>
											<option value="LA">LA</option>
											<option value="ME">ME</option>
											<option value="MD">MD</option>
											<option value="MA">MA</option>
											<option value="MI">MI</option>
											<option value="MN">MN</option>
											<option value="MS">MS</option>
											<option value="MO">MO</option>
											<option value="MT">MT</option>
											<option value="NE">NE</option>
											<option value="NV">NV</option>
											<option value="NH">NH</option>
											<option value="NJ">NJ</option>
											<option value="NM">NM</option>
											<option value="NY">NY</option>
											<option value="NC">NC</option>
											<option value="ND">ND</option>
											<option value="OH">OH</option>
											<option value="OK">OK</option>
											<option value="OR">OR</option>
											<option value="PA">PA</option>
											<option value="RI">RI</option>
											<option value="SC">SC</option>
											<option value="SD">SD</option>
											<option value="TN">TN</option>
											<option value="TX">TX</option>
											<option value="UT">UT</option>
											<option value="VT">VT</option>
											<option value="VA">VA</option>
											<option value="WA">WA</option>
											<option value="WV">WV</option>
											<option value="WI">WI</option>
											<option value="WY">WY</option>
										</select>-->
										<!--<input id="zip" type="text" name="zip" maxlength="5" required>-->
									</div> <!-- end row -->
									<!-- Physical Address End -->
									<div class="row" style="margin-left:2px"> <!-- titles -->
										<span style="line-height:35px;" id="hd_wphone">Primary Phone</span>
										<span style="line-height:35px;margin-left:65px;" id="hd_ext">Ext</span>
									</div> <!-- end row -->
									<div class="row" style="margin-left:2px">
				                 <input id="phone" type="text" name="phone" maxlength="14">
							<input id="ext" type="text" name="ext" maxlength="5">
									</div> <!-- end row -->
								</td>
									
								<td id="td_2">
									<!--<div class="row"> <!-- titles -->
										<!--<span id="hd_mphone">Mobile Phone</span>
									</div> <!-- end row -->
									<!--<div class="row"> <!-- inputs -->
										<!--<input id="mphone1" type="text" name=""><input id="mphone2" type="text" name=""><input id="mphone3" type="text" name="">
										<select>
											<option>Select Carrier</option>
											<option>Verizon</option>
											<option>AT&T</option>
											<option>Sprint</option>
											<option>T-Mobile</option>
											<option>U.S. Cellular</option>
											<option>Other</option>
										</select>
									</div> <!-- end row -->
									<!--<div class="row">
										<span style="line-height:35px;" id="hd_hphone">Home Phone</span>
									</div> <!-- end row -->
									<!--<div class="row">
										<input id="hphone1" type="text" name=""><input id="hphone2" type="text" name=""><input id="hphone3" type="text" name="">
									</div> <!-- end row -->
									
								</td>
							</tr>
						</table>
												
						<!--<div class="row"> <!-- titles -->
							<!--<span id="hd_bday">Birthday</span>
							<span style="line-height:35px;" id="hd_gender">Gender</span>
						</div> <!-- end row -->
						<!--<div class="row"> <!-- inputs -->
							<!--<select id="month" name="">
								<option value="na">Month</option>
								<option value="1">January</option>
								<option value="2">February</option>
								<option value="3">March</option>
								<option value="4">April</option>
								<option value="5">May</option>
								<option value="6">June</option>
								<option value="7">July</option>
								<option value="8">August</option>
								<option value="9">September</option>
								<option value="10">October</option>
								<option value="11">November</option>
								<option value="12">December</option>
							</select>
							<select id="day" name="">
								<option value="na">Day</option>
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
								<option value="6">6</option>
								<option value="7">7</option>
								<option value="8">8</option>
								<option value="9">9</option>
								<option value="10">10</option>
								<option value="11">11</option>
								<option value="12">12</option>
								<option value="13">13</option>
								<option value="14">14</option>
								<option value="15">15</option>
								<option value="16">16</option>
								<option value="17">17</option>
								<option value="18">18</option>
								<option value="19">19</option>
								<option value="20">20</option>
								<option value="21">21</option>
								<option value="22">22</option>
								<option value="23">23</option>
								<option value="24">24</option>
								<option value="25">25</option>
								<option value="26">26</option>
								<option value="27">27</option>
								<option value="28">28</option>
								<option value="29">29</option>
								<option value="30">30</option>
								<option value="31">31</option>
							</select>
							<select id="year" name="">
								<option value="na">Year</option>
								<option value="2014">2014</option>
								<option value="2013">2013</option>
								<option value="2012">2012</option>
								<option value="2011">2011</option>
								<option value="2010">2010</option>
								<option value="2009">2009</option>
								<option value="2008">2008</option>
								<option value="2007">2007</option>
								<option value="2006">2006</option>
								<option value="2005">2005</option>
								<option value="2004">2004</option>
								<option value="2003">2003</option>
								<option value="2002">2002</option>
								<option value="2001">2001</option>
								<option value="2000">2000</option>
								<option value="1999">1999</option>
								<option value="1998">1998</option>
								<option value="1997">1997</option>
								<option value="1996">1996</option>
								<option value="1995">1995</option>
								<option value="1994">1994</option>
								<option value="1993">1993</option>
								<option value="1992">1992</option>
								<option value="1991">1991</option>
								<option value="1990">1990</option>
								<option value="1989">1989</option>
								<option value="1988">1988</option>
								<option value="1987">1987</option>
								<option value="1986">1986</option>
								<option value="1985">1985</option>
								<option value="1984">1984</option>
								<option value="1983">1983</option>
								<option value="1982">1982</option>
								<option value="1981">1981</option>
								<option value="1980">1980</option>
								<option value="1979">1979</option>
								<option value="1978">1978</option>
								<option value="1977">1977</option>
								<option value="1976">1976</option>
								<option value="1975">1975</option>
								<option value="1974">1974</option>
								<option value="1973">1973</option>
								<option value="1972">1972</option>
								<option value="1971">1971</option>
								<option value="1970">1970</option>
								<option value="1969">1969</option>
								<option value="1968">1968</option>
								<option value="1967">1967</option>
								<option value="1966">1966</option>
								<option value="1965">1965</option>
								<option value="1964">1964</option>
								<option value="1963">1963</option>
								<option value="1962">1962</option>
								<option value="1961">1961</option>
								<option value="1960">1960</option>
								<option value="1959">1959</option>
								<option value="1958">1958</option>
								<option value="1957">1957</option>
								<option value="1956">1956</option>
								<option value="1955">1955</option>
								<option value="1954">1954</option>
								<option value="1953">1953</option>
								<option value="1952">1952</option>
								<option value="1951">1951</option>
								<option value="1950">1950</option>
								<option value="1949">1949</option>
								<option value="1948">1948</option>
								<option value="1947">1947</option>
								<option value="1946">1946</option>
								<option value="1945">1945</option>
								<option value="1944">1944</option>
								<option value="1943">1943</option>
								<option value="1942">1942</option>
								<option value="1941">1941</option>
								<option value="1940">1940</option>
								<option value="1939">1939</option>
								<option value="1938">1938</option>
								<option value="1937">1937</option>
								<option value="1936">1936</option>
								<option value="1935">1935</option>
								<option value="1934">1934</option>
								<option value="1933">1933</option>
								<option value="1932">1932</option>
								<option value="1931">1931</option>
								<option value="1930">1930</option>
								<option value="1929">1929</option>
								<option value="1928">1928</option>
								<option value="1927">1927</option>
								<option value="1926">1926</option>
								<option value="1925">1925</option>
								<option value="1924">1924</option>
								<option value="1923">1923</option>
								<option value="1922">1922</option>
								<option value="1921">1921</option>
								<option value="1920">1920</option>
								<option value="1919">1919</option>
								<option value="1918">1918</option>
								<option value="1917">1917</option>
								<option value="1916">1916</option>
								<option value="1915">1915</option>
								<option value="1914">1914</option>
							</select>
							<select id="gender">
								<option value="na">Gender</option>
								<option value="female">Female</option>
								<option value="male">Male</option>
							</select>
						</div> <!-- end row -->	
					</div> <!-- end simple tabs content -->
					
					<div class="interim-form">
						<div class="interim-header"><h2>Contact Email</h2></div>
						<hr style="border-color:#b8b8b8;">
						<div id="row" style="margin-left:2px"> <!-- titles -->
							<span style="line-height:35px;" id="hd_loginemail">Email Address</span>
						</div> <!-- end row -->
						<div id="row" style="margin-left:2px"> <!-- inputs -->
							<input id="email" type="email" name="email" value="" required>
						</div> <!-- end row -->
						
						<!--<div id="row" style="margin-left:15px"> --><!-- titles -->
						<!--<span id="hd_password">Password</span>
						<span style="line-height:35px;" id="hd_cpassword">Confirm Password</span>
						</div> --><!-- end row -->
						<!--<div id="row" style="margin-left:2px"> --><!-- inputs -->
							<!--<input id="password" type="password" name="password" value="">
							<input id="cpassword" type="password" name="password" value="">
						</div>--> <!-- end row -->
					</div> <!-- end tab 2 -->
					
					<div class="interim-form"> <!-- profile pic tab3 -->
						<div class="interim-header"><h2>Profile Photo</h2></div>
						<hr style="border-color:#b8b8b8;">
						<div class="row" style="margin-left:2px"> 
							<span style="line-height:35px;" id="">Upload Profile Photo:</span>
							<input type="file" id="" name="uploaded_file">
							<!--<input type="submit" class="redbutton" value="Upload Photo">
							<br><br>
							<span style="line-height:35px;" id="">Preview Photo:</span>
							<img src="" alt="uploaded profile photo">-->
						</div> <!-- end row -->
					</div> <!-- end tab3 content (profile pic) -->
				</div> <!-- end simple tabs -->
				<br>
				<br>
				<input style="margin-left:15px" type="submit" class="redbutton" name="submit" value="Save Contact">
				<!--<input type="submit" class="redbutton" value="Save & Add Another">-->
			</form>
			
			<br>
</div>			
			<!--<form class="graybackground">
				<h3>--Option 2: Add Multiple Business Associates--</h3>
				<h2>How To Add Multiple Business Associates</h2><br>
				<ol>
					<li><a href="">Download</a> Our Business Associate Setup Spreadsheet</li>
					<li>Input the Data for Each Associate You want to Add</li>
					<li>Upload the Completed Spreadsheet...</li>
				</ol>
				<input type="file" name="">
				<input class="redbutton" type="submit" name="uploaded_file" value="Upload File">
			</form>-->
		</div> <!-- end table -->

  </div> <!--end content -->
	    </div>
    </div> 
</div>
</div> <!--end container-->	
<br>
      <?php include 'footer.php' ; ?>   

</body>
</html>

<?php
   ob_end_flush();
?>
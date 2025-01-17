<script type="text/javascript">
	function validate(form) {
		for(var i=0; i<11; i++) {
			var input = form[i].value;
			if(input == "" || input == null) {
				if(form[i].name != "address2") {
					alert("Please enter a value for " + form[i].name);
					form[i].focus();
					return false;
				}
			}

		}
		var email = form['email'].value;
		if(!isValidEmail(email)) {
			alert("please enter a valid email address");
			return false;
		}
		var pass1 = form['loginPass'].value;
		var pass2 = form['confirmPass'].value;
		if(pass1 == "" || pass1 == null) {
			alert("please enter a valid password");
			return false;
		}
		if(pass1 != pass2) {
			alert("passwords do not match");
			return false;
		}
		return true;
	}
	function isValidEmail(email) {
		if(email.indexOf("@") == -1 || email.indexOf(".") == -1) {
			return false;
		} else {
			return true;
		}
	}
</script>
<h1 class="setupEmail1">Representative Setup</h1>
                        <form action="handleRepresentativeSetup.php" method="POST" name="representative" enctype="multipart/form-data" id="representative1" onsubmit="return validate(this);">
                          <label for="FName">
                            <span id="f">First Name</span>
                            <input type="text" maxlength="30" id="FName" name="FName"/>
                          </label>
                          <label for="MName">
                            <span>Middle Name</span>
                            <input type="text" maxlength="30" id="MName" name="MName"/>
                          </label>
                         <label for="LName">
                            <span>Last Name</span>
                            <input type="text" maxlength="30" id="LName" name="LName"/>
                          </label>
                         <label for="ssn">
                            <span>SSN#</span>
                            <input type="text" maxlength="11" id="ssn" name="ssn"/>
                          </label>
                         <label id="address1" for="address1">
                            <span>Address1</span>
                            <input type="text" maxlength="40" id="address1" name="address1"/>
                         </label>
                         <label id="address2" for="address2" >
                            <span>Address2</span>
                            <input type="text" maxlength="40" id="address2" name="address2"/>
                          </label>
                         <label id="city" for="city">
                            <span>City</span>
                            <input type="text" maxlength="30" id="city" name="city"/>
                          </label>
                         <label id="state" for="state">
                            <span>State (e.g. MN)</span>
                            <input type="text" maxlength="2" id="state" name="state"/>
                         </label>
                         <label id="zip" for="zip">
                            <span>ZIP</span>
                            <input type="text" maxlength="10" id="zip" name="zip"/>
                          </label>
                          <label id="email" for="email">
                            <span>Email</span>
                            <input type="text" maxlength="30" name="email" id="email" />
                         </label>
                         <label id="homephone" for="homephone">
                            <span>Phone (Home)</span>
                            <input type="text" maxlength="12" id="homephone" name="homephone"/>
                          </label>
                         <label id="cellphone" for="cellphone">
                            <span>Phone (Cell)</span>
                            <input type="text" maxlength="12" id="cellphone" name="cellphone"/>
                          </label>
                          <label id="workphone" for="workphone">
                            <span>Phone (work)</span>
                            <input type="text" maxlength="12" id="workphone" name="workphone"/>
                          </label>
                          <label id="extphone" for="extphone">
                            <span>Phone (ext)</span>
                            <input type="text" maxlength="5" id="extphone" name="extphone"/>
                          </label>    
                         <label id="fbPage" for="fbPage">
                            <span>Facebook</span>
                            <input type="text" maxlength="40" id="fbPage" name="fbPage"/>
                          </label>
                          <label id="twitter" for="twitter">
                            <span>Twitter</span>
                            <input type="text" maxlength="40" id="twitter" name="twitter"/>
                         </label>
                         <label id="linkedin" for="linkedin" name="linkedin">
                            <span>LinkedIn</span>
                            <input type="text" maxlength="40" id="linkedin" name="linkedin"/>
                          </label>   
                        
                      <div class="hr">&nbsp;</div>
                      <br />
                      <br />
                      <label id="sales1" for="sales" name="sales">
                            <span>Rep(optional)</span>
                            <input type="text" id="linkedin" name="sales"/>
                          </label>     
                      <div class="hr">&nbsp;</div>
                          <h2 id="upload1">Upload Photos</h2>
                          <p> 
                                <label for="representativePic">Picture of Representative:</label>
                                <input type="file" name="representativePic" id="pic">
                          </p>
                          <p>
                          	<label for="bannerPic">Logo/Banner:</label>
                          	<input type="file" name="bannerUpload">
                            </p>
                                                   
                       <div class="hr">&nbsp;</div>    
                        
                        <h3>Your Email Address will be your login to your account on the website.</h3>
                        	<label for="loginPass">Enter a Login Password<input type="text" name="loginPass"></label>
                       		<label for="confirmPass">Reconfirm Password<input type="password" name="confirmPass"></label>
                       		<br />
                       		<input type="submit" value="Submit" />
                       		<input type="reset" value="Reset Form"/>
                       		
                        </form>
<?php 

	function validateRegisterForm()
	{
		//ONLY Mandatory fields will be checked for emptyness
		$formdata = array(	'username' 			=> $_POST['username'], 
							'password' 			=> $_POST['password'],
							'password_repeat' 	=> $_POST['password_repeat'],
							'firstname' 		=> $_POST['firstname'],
							'lastname' 			=> $_POST['lastname'],
							'email' 			=> $_POST['email'],
							'street' 			=> $_POST['street'],
							'zipCode'		 	=> $_POST['zipCode'],
							'phoneNumber' 		=> $_POST['phoneNumber'],
							'city'			 	=> $_POST['city'] );

		if($e = readyToValidate($formdata))
		{
			$errors .= $e;
			$errors .= "</ul>";
			return $errors;
		}else
		{
			$errors .= validateUsername($formdata['username']);
			$errors .= validatePassword($formdata['password'], $formdata['password_repeat']);
			$errors .= validateEmail($formdata['email']);
			$errors .= validateName($formdata['firstname'], $formdata['lastname']);
			$errors .= validateStreet($formdata['street']);
			$errors .= validateCity($formdata['city']);
			$errors .= validateZipcode($formdata['zipCode']);
			$errors .= validatePhoneNumber($formdata['phoneNumber']);
			return $errors;
		}

	
	}
	
	function validateUsername($username)
	{
		$username = strtolower($username);
		require 'db_connect.php';
	
		$query = 'SELECT username FROM user WHERE username = ?';

		if($stmt = $database->prepare($query))
    	{ 
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            $database->close();
        }

        if($result)
        {
   			return "<li>Sorry username is already taken</li>";
        }
	}

	function validatePassword($password, $password_repeat)
	{

		//if the two strings are equal strcmp returns 0 thats why NOT operater is added in the condition
		if(!strcmp($password, $password_repeat))
		{
			$pattern = "/^((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%]).{6,20})$/";
			if(!preg_match($pattern, $password))
			{
				return "<li> Password: 6 to 20 characters string with at least one digit, one upper case letter, one lower case letter and one special symbol (“@#$%”).</li>";
			}
		}else
		{
			return "<li>The given passwords do not match</li>";
		}

	}

	function validateName($firstname, $lastname)
	{
		/*Error 'LOLLOLOLOLOL How did you write you name wrong?'*/
		$pattern = "/^[\p{L}\s'.-]+$/";
		if(!preg_match($pattern, $firstname . " " . $lastname))
		{
			return "<li>LOLLOLOLOLOL How did you write you name wrong?</li>";
		}
	}

	function validateEmail($email)
	{
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return "<li>E-mail is not valid</li>";
		}
	}

	function validateZipcode($zipcode)
	{
		$pattern = "/^[1-9][0-9]{3}[\s]?(?!SS|SA|SD|[A-Z](F|I|O|Q|U|Y))[A-Z]{2}$/";
		$zipcode = strtoupper($zipcode);
		if(!preg_match($pattern, $zipcode))
		{
			return "<li>Please enter a valid zipcode</li>";
		}
	}

	function validateCity($city)
	{
		// /*Error '<li>Please enter a valid city name</li>' */
		// $pattern = "/^$/";
		// if(!preg_match($pattern, $city))
		// {
		// 	return "<li>Please enter a valid street</li>";
		// }
	}

	function validatePhoneNumber($phoneNumber)
	{
		$pattern = "/^(\+|00|0)(31\s?)?(6[\s-]?[1-9][0-9]{7}|[1-9][0-9][\s-]?[1-9][0-9]{6}|[1-9][0-9]{2}[\s-]?[1-9][0-9]{5})$/";
		if(!preg_match($pattern, $phoneNumber))
		{
			return "<li>Please enter a valid phone number</li>";
		}
	}


	function validateStreet($street)
	{
		$pattern = "/^([1-9][e][\s])*([a-zA-Z]+(([\.][\s])|([\s]))?)+[1-9][0-9]*(([-][1-9][0-9]*)|([\s]?[a-zA-Z]+))?$/";
		if(!preg_match($pattern, $street))
		{
			return "<li>Please enter a valid street</li>";
		}
	}

	function saveNewUser()
	{
		 require 'db_connect.php';

			$username = strtolower($_POST['username']);
			$password = md5($_POST['password']);
			$firstname = strtolower($_POST['firstname']);
			$lastname = strtolower($_POST['lastname']);
			$email = $_POST['email'];
			$street = strtolower($_POST['street']);
			$zipcode = strtoupper($_POST['zipCode']);
			$zipcode = str_replace(' ', '', $zipcode);
			$phoneNumber = $_POST['phoneNumber'];
			$city = strtolower($_POST['city']);
			$country = "Netherlands";
			$sex = "M";
			$rights = 1;
			$id = 4;
			echo $username,"<br>", $password, "<br>", $firstname, "<br>", $lastname, "<br>", $street. "<br>", $zipcode, "<br>", $country, "<br>", $sex, "<br>", $phoneNumber, "<br>", $rights;
			// print_r($_POST);

			// $query = 
			// "
			// 	INSERT INTO user VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
			// ";

			$query = "INSERT INTO user (username, password, firstname, lastname, address, zipcode, country, sex, phonenumber, rights, email, city) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
			echo $query;
			if($stmt = $database->prepare($query))
	        { 
	        	$stmt->bind_param("sssssssssiss", $username, $password, $firstname, $lastname, $street, $zipcode, $country, $sex, $phoneNumber, $rights, $email, $city);
	            $stmt->execute();
	            $stmt->close();
	            $database->close();
	        }
	}

	function readyToValidate($formdata)
	{
		foreach ($formdata as $key => $value)
		{
			if(!isset($value) || empty($value))
			{
				return "<li>Please fill in all form data.</li>";
			}
		}
	}
 ?>
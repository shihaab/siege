<?php
function login($Username, $password, $pdo)
{

	$parameter = array(':status'=>$Username);
	$sth = $pdo->prepare('SELECT * FROM klanten WHERE Inlognaam = :status');

	$sth->execute($parameter);


	if ($sth->rowCount() == 1) 
	{

		$row = $sth->fetch();
		

		$password = hash('sha512', $password . $row['Salt']);


		if ($row['Paswoord'] == $password)
		{
			$user_browser = $_SERVER['HTTP_USER_AGENT'];

			$_SESSION['user_id'] = $row['KlantID'];
			$_SESSION['username'] = $row['Inlognaam'];
			$_SESSION['level'] = $row['Level'];
			$_SESSION['login_string'] = hash('sha512',
					  $password . $user_browser);
			return true;
		 } 
		 else 
		 {
			return false;
		 }
	}
	else
	{
		return false;
	}
}

if(isset($_POST['Inloggen']))
{

	$gebruikersnaam = $_POST['Gebruikersnaam'];
	$wachtwoord = $_POST['Wachtwoord'];

	
	$check = login($gebruikersnaam,$wachtwoord,$pdo);
	if ($check){
		echo "U bent succesvol ingelogd";
		RedirectNaarPagina(1,2);
	}
	else{
		echo "De Inlognaam of het paswoord is onjuist.";
		RedirectNaarPagina(1,6);
	}


}
else
{	
	require('./Forms/InloggenForm.php');
}
?>






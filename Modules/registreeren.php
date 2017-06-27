<?php
//init fields
$Email = $Username = $Password = $RetypePassword = NULL;

//init error fields
$MailErr = $UserErr = $PassErr = $RePassErr = NULL;

if(isset($_POST['Registreren']))
{
	$CheckOnErrors = false; 
	
        $Email          = $_POST['Email'];
        $Username       = $_POST['Username'];
        $Password       = $_POST['Password'];
        $RetypePassword = $_POST['RetypePassword'];

		
    if (empty($Email)){
        $CheckOnErrors = true;
        $MailErr = 'Verplicht invullen';
    }
    elseif (is_email($Email) == false ){
        $CheckOnErrors = true;
        $MailErr = 'Geen geldig Email adres';
    }

    if (empty($Username)){
        $CheckOnErrors = true;
        $UserErr = 'Verplicht invullen';
    }
    elseif (is_Username_Unique($Username,$pdo) == false ){
        $CheckOnErrors = true;
        $UserErr = 'De gebruikers naam bestaat al';
    }
	
    if (empty($Password)){
        $CheckOnErrors = true;
        $PassErr = 'Verplicht invullen';
    }
    elseif (is_minlength($Password, 6) == false ){
        $CheckOnErrors = true;
        $PassErr = 'Moet minimaal 6 karakters lang zijn';
    }
	
   if ($Password != $RetypePassword){
       $CheckOnErrors = true;
       $RePassErr = 'Pasword komt niet overeen';
   }

	if($CheckOnErrors == true) 
	{
        require('./Forms/RegistrerenForm.php');
	}
	else
	{

		$Salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));

		$Password = hash('sha512', $Password . $Salt);

        $parameters = array(':Email'=>$Email,
                            ':Username'=>$Username,
                            ':Paswoord'=>$Password,
                            ':Salt'=>$Salt,
                            ':Level'=>1);
							
        $sth = $pdo->prepare('INSERT INTO klanten ( Email, Inlognaam, Paswoord, Salt, Level) 
                              VALUES (:Email, :Username, :Paswoord, :Salt, :Level)');

        $sth->execute($parameters);


        echo "U heeft zich succesvol geregistreerd en kunt vanaf nu inloggen op de website ";
        echo "<br />";
        echo "U wordt binnen 5 seconden doorgestuurd naar de hoofdpagina ";
        RedirectNaarPagina(3,2);

		
	}
}
else
{
	require('./Forms/RegistrerenForm.php');
}
?>
<?php

function ConnectDB()
{
    try{
        $pdo = new PDO("mysql:host=".HOST.";dbname=".DBNAME,USERNAME,PASSWORD);
    }
    catch(PDOException $e){
        echo $e->getMessage();
    }

    return $pdo;
}

function LoginCheck($pdo)
{
    // Controleren of Sessie variabelen bestaan
    if (isset($_SESSION['user_id'], $_SESSION['username'],$_SESSION['login_string']))
    {
        $KlantID = $_SESSION['user_id'];
        $Login_String = $_SESSION['login_string'];
        $Username = $_SESSION['username'];

        $user_browser = $_SERVER['HTTP_USER_AGENT'];

        $parameters = array(':KlantID'=>$KlantID);
        $sth = $pdo->prepare('SELECT Paswoord FROM klanten WHERE KlantID = :KlantID LIMIT 1');

        $sth->execute($parameters);


        if ($sth->rowCount() == 1)
        {

            $row = $sth->fetch();


            $Login_Check = hash('sha512', $row['Paswoord'] . $user_browser);

            if ($Login_Check == $Login_String)
                return true;
            else
                return false;
        } else
            return false;
    } else
        return false;
}
function is_email($Invoer)
{
    return (bool)(preg_match("^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$^",$Invoer));
}

function is_minlength($Invoer, $MinLengte)
{
    return (strlen($Invoer) >= (int)$MinLengte);
}
function is_Username_Unique($Invoer,$pdo)
{
    $parameters = array(':Username'=>$Invoer);
    $sth = $pdo->prepare('SELECT KlantID FROM klanten WHERE Inlognaam = :Username LIMIT 1');

    $sth->execute($parameters);

    // controleren of de username voorkomt in de DB
    if ($sth->rowCount() == 1)
        return false;//username komt voor
    else
        return true;//username komt niet voor
}

?>

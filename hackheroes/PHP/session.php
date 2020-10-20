<?php
    //wyzeruj logowanie jeżeli nikt nie jest zalogowany pod danym adresem sesji
	session_start();
    if(empty($_SESSION["user"]))$_SESSION["user"]=0;
    
    //Zabezpieczenie przed przejmowaniem sesji jakiegoś idioty
	if (!isset($_SESSION['init']))
	{
		session_regenerate_id();
		$_SESSION['init'] = true;
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	}
    
    //Zabezpieczenie przed przejmowaniem sesji z innego IP
	if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
	{
		$_SESSION['user'] = 0;
	}
?>
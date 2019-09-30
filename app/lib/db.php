<?php
     define('_HOST_NAME','10.1.10.13');
     define('_DATABASE_NAME','absensi_magang');
     define('_DATABASE_USER_NAME','root');
     define('_DATABASE_PASSWORD','qwery123');
	
     $MySQLiconn = new MySQLi(_HOST_NAME,_DATABASE_USER_NAME,_DATABASE_PASSWORD,_DATABASE_NAME);
	 
	 if($MySQLiconn->connect_errno)
	 {
		 die("ERROR : -> ".$MySQLiconn->connect_error);
	 }
	 

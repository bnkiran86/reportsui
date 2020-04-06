<?php

	include_once(dirname(__FILE__)."/Cryptor.php");

	$istring = "100";

	$encryption_key = "*ku7H]-!hRkQ4hYa]wa[+1K3DC|7;xaPGH)?pUUWKS2'RtFhaAs-v/).1W?F'T";
	$cryptor = new Cryptor($encryption_key);
	$crypted_token = $cryptor->encrypt($istring);
	unset($token);


	//$encryption_key = 'CKXH2U9RPY3EFD70TLS1ZG4N8WQBOVI6AMJ5';
	$cryptor = new Cryptor($encryption_key);
	$ostring = $cryptor->decrypt($crypted_token);

	echo "\nI/p :".$istring;
	echo "\nCrypted : ".$crypted_token;
	echo "\nO/p :".$ostring;


?>

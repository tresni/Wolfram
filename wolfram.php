<?php

/* -----------------------------------
	Script: 	Wolfram Alpha
	Author: 	David Ferguson
	Usage:		w <query>
	Desc:		Searches Wolfram Alpha for the passed search query
				and displays the plaintext result via Growl.
	Updated:	7/8/11
----------------------------------- */

	//Save user input
	$q=$argv[1];

	//Replace bad characters with encoded characters
	$q = urlencode($q);

	//Retrieve query results
	$url 		= "http://api.wolframalpha.com/v2/query?input=$q&appid=JXULYW-L3J2U64VTE";
	$crl 		= curl_init();
	$timeout 	= 10;
	curl_setopt ($crl, CURLOPT_URL, $url);
	curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
	$def 		= curl_exec($crl);
	curl_close  ($crl);

	//Parse it with SimpleXML
	$def = simplexml_load_string($def);
	
	//Return result
	if ($def->pod[1]->subpod->plaintext)
	{ echo $def->pod[1]->subpod->plaintext; }
	else
	{ echo "Unable to find suitable results"; }

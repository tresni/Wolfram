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
	
	$replace = array(
	"%25","%2B","+","%21","%22","%23","%24","%26","%27","%28","%29","%2A","%2C","%2D","%2E","%2F","%3A","%3B","%3C",
	"%3D","%3E","%3F","%40","%5B","%5C","%5D","%5E","%5F","%60","%7B","%7C","%7D","%7E","%80","%82","%83","%84",
	"%85","%86","%87","%88","%89","%8A","%8B","%8C","%8E","%91","%92","%93","%94","%95","%96","%97","%98","%99",
	"%9A","%9B","%9C","%9E","%9F","%A1","%A2","%A3","%A5","%A6","%A7","%A8","%A9","%AA","%AB","%AC","%AD","%AE",
	"%AF","%B0","%B1","%B2","%B3","%B4","%B5","%B6","%B7","%B8","%B9","%BA","%BB","%BC","%BD","%BE");

	$search = array(
	"%","+"," ","!","\"","#","$","&","'","(",")","*",",","-",".","/",":",";","<","=",">","?","@","[","\\","]",
	"^","_","`","{","|","}","~","€","‚","ƒ","„","…","†","‡","ˆ","‰","Š","‹","Œ","Ž","‘","’","“","”","•","–",
	"—","˜","™","š","›","œ","ž","Ÿ","¡","¢","£","¥","|","§","¨","©","ª","«","¬","¯","®","¯","°","±","²","³",
	"´","µ","¶","·","¸","¹","º","»","¼","½","¾");

	//Replace bad characters with encoded characters
	$q = str_replace($search, $replace, $q);

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
	{
		echo $def->pod[1]->subpod->plaintext;
	}
	else {
		$attributes = $def->attributes();
		if ($attributes->error == "true")
		{
			echo "Wolfram error: {$def->error->msg}";
		}
		else
		{
			echo "Unable to find suitable results";
		}
	}
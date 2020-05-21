<?php

	if(!isset($_GET['birim'])) {
		$arr = [
			'err' => true,
			'errmessage' => "Bir para birimi girmediniz.",
			'currency' => 0
		];
		header('Content-type: application/json');
		echo json_encode($arr);
		die;
	}

	function getCurrency($string = 'ABD DOLARI', $type = 1) {
		$sitecontent = file_get_contents('https://xn--dviz-5qa.com/');
		preg_match_all('/<td>(.*)<\/td>/', $sitecontent, $matches);
		$dovizKur = 0;
		for($i = 0; $i < sizeof($matches[0]); $i++) {
			if($matches[0][$i] == '<td>'.$string.'</td>') {
				preg_match('/<td>(.*)<\/td>/', $matches[0][$i+2], $matches);
				$dovizKur = floatval($matches[1]);
				break;
			}
		}
		
		if($type == 1)
			return (1/$dovizKur);
		else 
			return $dovizKur;
	}
	
	$currency = getCurrency($_GET['birim'], isset($_GET['tl']));
	
	if($currency == 0) {
		$arr = [
			'err' => true,
			'errmessage' => "Gerçersiz para birimi girdiniz.",
			'currency' => 0
		];
	} else {
		$arr = [
			'err' => false,
			'errmessage' => "",
			'currency' => $currency
		];
	}
	header('Content-type: application/json');
	echo json_encode($arr);
	
	
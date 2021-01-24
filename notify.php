<?php
    $app_key	  = '<<app_key>>';		// App Key
	$secret_key    = '<<app_secret>>';   // App Secret
	$data		= $_POST;

	file_put_contents('debug.log',print_r($data, true) . "\n", FILE_APPEND);
	if ($data) {
		$sign_content = 'amount=' . $data['amount'] . 
		  '&appKey=' . $data['appKey'] .
		  '&channel=' . $data['channel'] .
		  '&mrn=' . $data['mrn'] .
          '&originAmount=' . $data['originAmount'] .
		  '&key=' . $secret_key;
		$sign = md5($sign_content);
		if ($sign == $data['sign']) {
			$amount = floatval($data['amount']);
			$order_id = $data['mrn'];
			
            // TODO
            // 给会员上分
			exit('200');
		} else {
			file_put_contents('debug.log', "sign error: $sign vs ". $data['sign']. "\n", FILE_APPEND);
			exit('sign error');
		}
	} else {
		file_put_contents('debug.log', "no data" . "\n", FILE_APPEND);
		exit('no data');
    }
    
    echo 'unexpected';
?>
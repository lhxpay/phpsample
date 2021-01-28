<?php 
	// 联合
	
	$app_key	  = '<<app_key>>';		// App Key
	$secret_key    = '<<app_secret>>';   // App Secret
	$pay_url = '网关的post地址';
	if($app_key == '' || $secret_key == ''){
		exit('第三方支付接口未配置');
	}
	
	if ($pay_url == '') {
		exit('支付地址未配置');
	}

	$order_id = intval($_GET['orderid']);
	if(!$order_id){
		exit('参数错误');
	}
    
    // TODO
    /*
    商户逻辑，请在此确认订单有效性并获取订单金额
    */
    $order_amount = 100; // 从你们数据库中获取实际金额

	$err_msg	= '';
	$is_valid	= true;
	
	if ($_COOKIE['last_order_id'] == $order_id) {
		$err_msg = '订单重复，请勿重复提交！';
		$is_valid    = false;
	}


	if ($is_valid) {
		setcookie("last_order_id", $orderid);
		$post_data = array(
			"appKey" => $app_key,
			"amount" => number_format(floatval($order_amount), 2, '.', ''),
			"mrn" => $order_id,
			"channelId" => 3,
			"returnUrl" => "会员跳回来的地址",
			"callbackUrl" => '支付成功回调地址'
		);

		ksort($post_data);
		$sign_content = "";
		foreach ($post_data as $name => $value) {
			if ($value != null && $value != '' && $name != 'payer') {
				$sign_content .=  $name . '=' . $value.'&';
			}
		}

		$sign_content .= "key=".$secret_key;
		$sign = md5($sign_content);
		$post_data['sign'] = $sign;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $config['name']?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=no" />
		<style>
			body {
				font-size: 18px;
				border: 1px solid #ccc;
				font-family: "Microsoft YaHei";
			}
			
			.clearfix:after {
				visibility: hidden;
				display: block;
				font-size: 0;
				content: ".";
				clear: both;
				height: 0;
			}
			
			* html .clearfix {
				zoom: 1;
			}
			
			*:first-child+html .clearfix {
				zoom: 1;
			}
			
			.main {
				position: relative;
			}
			
			.title {
				background: #d3d3d3;
				font-size: 18px;
				padding: 10px 20px;
				border-bottom: 1px solid #ccc;
			}
			
			.content {
				font-size: 18px;
				padding: 10px 20px;
			}
			
			.tips {
				font-size: 18px;
				text-align: center;
				padding: 10px 0 10px 0;
			}

            .rebtn {
				padding: 10px 15px;
				font-size: 16px;
				border: 1px solid #ccc;
				border-radius: 3px;
				cursor: pointer;
				display: inline-block;
				background: #d3d3d3;
				text-decoration: none;
				color: #333;
				outline: none;
			}
			
		</style>
	</head>
<?php if($is_valid) { ?>
	<body onload="form1.submit();">
	<form name="form1" method="post" action="<?php echo $pay_url;?>">
		<?php foreach($post_data as $k => $v) { ?>
		<input type="hidden" name="<?php echo $k?>" value="<?php echo $v; ?>" />
		<?php }?>
	</form>
	</body>
<?php } else { ?>
	<body>
	<div class="main">
		<div class="title">订单支付信息：</div>
		<div class="content">
			<div>订单号：<?php echo $exchange_info['orderno']?></div><br/>
			<div style="white-space:nowrap; ">
				充值金额：<label style="font-size:24px;  color:#FF0000;" id="ismoney"><?php echo $money ?></label>
			</div>
			<div class="tips">
				<?php echo $err_msg; ?>
			</div>
			<div style="text-align:center; padding:15px 0;">
				<a class="rebtn" href="/">返回</a>
			</div>
		</div>
	</div>
	</body>
<?php } ?>
</html>


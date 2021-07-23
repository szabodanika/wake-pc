<?php
	// ini_set('display_errors', 1);
	// read config.ini
	if (($config = parse_ini_file("wake-pc-config.ini", true)) == false){
		// uh-oh, failed ot read ini file
		$status = "Missing or corrupt configuration file!";
	} else {
		// config.ini read successfully, let's break it up to sections
		$connection = $config['connection'];
		$customStrings = $config['customization'];
		// ping pc
		$waitTimeoutInSeconds = 1; 
		$online = fsockopen($connection['host'],$connection['port'],$errCode,$errStr,$waitTimeoutInSeconds);
		// check password
		if(isset($_GET['pwd'])) {
			$pwd = $_GET['pwd'];
			if($pwd == $connection['password']){
				// password ok, lets send WOL
				$status =  shell_exec('wakeonlan -i '.$connection['host'].' '.$connection['mac'].' 2>&1; echo $?');
			} else $status =  'Incorrect password';
		} else $status = 'Please enter the password';
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="content-type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wake <?=$customStrings['pcname']?></title>
</head>
<body >
<div class="card" style="max-width:240px; margin:auto; margin-top: 2em">
	<div class="card-body">
		<h5 class="card-title">Wake <?=$customStrings['pcname']?> </h5>
		<form name="input" action="" method="get">
		<div class="form-group">
			<label for="pwd">Password</label>
			<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
		</div>
		<div class="error"><?= $status ?></div>
		<br>
		<?php
			if ($online) echo '<p class="text-center" style="color:green">⬤ Online</p>';
			else echo '<p class="text-center" style="color:red">❌ Offline</p>';
		?> 
		<br>
		<input class="btn btn-primary btn-block" type="submit" value="⏰ Wake"/>
	</form>
	</div> 
	</div> 
</body>
</html>


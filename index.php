<?php
// ini_set('display_errors', 1);
// read config.ini
if (($config = parse_ini_file("config.ini", true)) == false) {
	// uh-oh, failed ot read ini file
	$status = "Missing or corrupt configuration file!";
} else {
	// config.ini read successfully, let's break it up to sections
	$globalConfig = $config['global'];
	unset($config['global']);
	$pcConfigs = $config;
	// get config of selected PC
	$pcSelected = isset($_GET['pc']) ? $_GET['pc'] : array_key_first($config);
	$pcConfig = $pcConfigs[$pcSelected];
	// ping PC
	$online = fsockopen($pcConfig['host'], $pcConfig['pingPort']);
	// check if we want to wake or just fetching page
	if (isset($_POST['wake'])) {
		// check password if needed
		if ((isset($_POST['pwd']) && $pcConfig['password'] == $_POST['pwd']) ||
			$pcConfig['password'] == ""
		) {
			// password ok/not needed, lets send WOL
			$result = shell_exec("echo -e $(echo $(printf 'f%.0s' {1..12}; printf \"$(echo " . $pcConfig['mac'] . " | sed 's/://g')%.0s\" {1..16}) | sed -e 's/../\\\\x&/g') | nc -w1 -u -b " . $pcConfig['broadcast'] . " " . $pcConfig['wolPort'] . ' 2>&1; echo $?');
			// check the status code of the above command, if 0 say OK otherwise print output
			$status = trim($result) == "0" ? 'Magic packet sent to ' . $pcConfig['pcName'] : 'Failed to send WOL packet: <pre>' . $result . '</pre>';
		} else $status =  'Incorrect password';
	}
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<!-- Bootstrap 5.0.2 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<!-- Bootstrap 5.0.2 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta charset="utf-8">
	<title>⏰ Wake PC</title>
</head>

<body>
	<div class="card" style="max-width:240px; margin:auto; margin-top: 2em">
		<div class="card-body">
			<h4 class="card-title mb-3">Wake PC</h4>
			<form action="" method="post">
				<select id="pc-select" class="form-select mb-3" name="pc" onchange="pcSelected()">
					<?php
					foreach ($pcConfigs as $key => $value) {
						echo '<option value="' . $key . '" ' . ($key === $pcSelected ? "selected" : "") . '>' . $value["pcName"] . '</option>';
					}
					?>
				</select>
				<?php
				if ($pcConfig['password'] != "") {
					echo '
						<div class="form-group mb-3" >
							<label for="pwd">Password</label>
							<input type="password" class="form-control" id="pwd" name="pwd" placeholder="Password">
						</div>
						';
				}
				?>
				<?php
				if ($online) {
					echo '<p class="text-center" style="color:green; cursor: pointer;" onclick="reload()">⬤ Online</p>';
				} else {
					echo '<p class="text-center" style="color:red; cursor: pointer;" onclick="reload()">❌ Offline</p>';
				}
				?>
				<div class="d-grid mb-3">
					<button class="btn btn-primary btn-block" type="submit" id="wake-btn" name="wake" value="wake"><?= $globalConfig['wakeButtonText'] ?></button>
				</div>
			</form>
			<div class="text-center"><?= $status ?></div>
		</div>
	</div>
</body>

</html>
<script>
	function pcSelected() {
		selected = document.getElementById("pc-select").value;
		var url = new URL(window.location);
		url.searchParams.set('pc', selected);
		window.open(url, "_self");
	}

	function reload() {
		window.location.reload();
	}
</script>
<?php
	ini_set("display_errors",0);
	session_start();
	$filename = $_SESSION["filename"];

	if (file_exists($filename)) {
		$FileVar = fopen($filename,"a");
		fwrite($FileVar,"<input type=\"submit\" value=\"Submit Quiz\" /></form></p>");
		fclose($FileVar);
	}

	session_destroy();
?>
<html>
<head>
	<?php
		ini_set("display_errors",0);
		$next = "\"$filename\"";
		$time = 3000;
		if ($filename=="") {
			$next = "\"login.html\"";
			$time = 15000;
		}
		print "<script>\n";
		print "function delay() {";
		print "window.setTimeout(function() { window.location = ".$next."; },".$time.");";
		print "}\n";
		print "</script>\n"
	?>
</head>
<body onLoad="delay();">
	<?php
		ini_set("display_errors",0);
		if ($filename=="") {
			print "<script>document.title = \"ERROR | Quiz Administration System\";</script>\n";
			print "<h2>ERROR</h2>\n";
			print "<p>Either you have found this page by mistake, or something went wrong when generating the quiz. Please <a href=\"login.html\">log in</a> as an instructor and repeat the process. We are sorry for the inconvenience.</p>";
			print "<p>(If this is a recurring issue, please contact the systems administrator ASAP.)</p><br/><br/>\n";
			print "<div>You should be redirected to the login page 15 seconds after pageload. (Link is above, just in case.)</div>";
	} else {
			print "<script>document.title = \"Quiz created. Redirecting...\";</script>\n";
			print "<h2>Quiz Successfully Created</h2>\n";
			print "<p>Redirecting you in a few seconds...</p>";
		}
	?>
</body>
</html>

<?php

ini_set("display_errors",0);
session_start();
$instructor = trim($_POST["name"]);
$course = trim($_POST["course"]);
$password = $_POST["password"];
$quiz = $_POST["quiz"];
$_SESSION["instructor"] = $instructor;
$_SESSION["course"] = $course;
$_SESSION["quiz"] = $quiz;

?>
<!DOCTYPE html>
<html>
<head>
	<script>
		function delay() {
			window.setTimeout(function() { window.location = "login.html"; },2000);
		}
	</script>
</head>
<?php
ini_set("display_errors",0);
if ($password != "xyzzy") {
	print "<body onLoad=\"delay();\">\n";
	print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
	print "<h2>ERROR</h2>";
	print "<p>$password is not the correct password. Sending you back...</p>";
	session_destroy();
} else {
	print "<body>\n";
	print "<script>document.title = \"Login successful. Loading quiz generator...\";</script>";
	$_SESSION["q_number"]=0;
	
	if(!file_exists(strtolower($instructor."/".$course))) { mkdir(strtolower("./".$instructor."/".$course),0755,true); }

	$filename = strtolower($instructor."/".$course)."/".$course."Quiz".$quiz.".html";
	$_SESSION["filename"] = $filename;
	$FileVar = fopen($filename,"w");
	if (!$FileVar) {
		print "<h2>FATAL ERROR</h2>";
		print "<p>Unable to create quiz file, \"$filename\".</p>";
		print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
		session_destroy();
	} else {
		print "<h2>Login successful.</h2>";
		fwrite($FileVar,"<!DOCTYPE html>\n<html>\n<head>\n<title>Taking Quiz ($course Quiz $quiz) | Quiz Administration System</title>\n</title>\n<body>\n");
		fwrite($FileVar,"<h2>$course Quiz #$quiz</h2>\n");
		fwrite($FileVar,"<p><span style=\"text-decoration:underline;\">Instructor:</span> $instructor</p>\n<br/>\n");
		fwrite($FileVar,"<form name=\"".substr($filename,0,-5)."Form\" method=\"post\" action=\"../../grader.php\" />\n");
		fclose($FileVar);
		
		if (file_exists(substr($filename,0,-5)."Key.txt")) { unlink(substr($filename,0,-5)."Key.txt"); }
		
		print "<script type=\"text/javascript\">";
		print "window.location = \"qentry.php\";";
		print "</script>";
	}
}

?>
</body>
</html>

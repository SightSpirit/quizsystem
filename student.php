<?php
ini_set("display_errors",0);

session_start();
$student = trim($_POST["name"]);
$instructor = trim($_POST["instructor"]);
$course = trim($_POST["course"]);
$quiz = $_POST["quiz"];
$_SESSION["student"] = $student;
$_SESSION["instructor"] = $instructor;
$_SESSION["quiz"] = $quiz;
$_SESSION["course"] = $course;
$q_directory = strtolower($instructor."/".$course."/");
$_SESSION["q_directory"] = $q_directory;
$quizfile = $q_directory.$course."Quiz".$quiz.".html";
$_SESSION["quizfile"] = $quizfile;
$lockfile = substr($quizfile,0,-5)."Students.txt";
$_SESSION["lockfile"] = $lockfile;

?>
<!DOCTYPE html>
<html>
<head>
	<script>
		function delay() {
			window.setTimeout(function() { window.location = "login.html"; },3000);
		}
	</script>
</head>
<?php
ini_set("display_errors",0);
if ($student=="") {
	print "<body onLoad=\"delay();\">\n";
	print "<h2>ERROR</h2>";
	print "<p>Please <a href=\"login.html\">log in</a> first. (Redirecting you...)</p>";
	session_destroy();
	print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
} else {
	print "<body>\n";
	if (!file_exists($quizfile)) {
		print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
		print "<h2>FATAL ERROR</h2>";
		print "<p>Unable to locate the quiz you specified ($quizfile). Please contact your instructor immediately.</p>";
		session_destroy();
	} else {
		$taken = false;
		if (file_exists($lockfile)) {
			$locks = file($lockfile,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($locks as $cur_student) {
				if (strtolower($student)==$cur_student) { $taken = true; break; }
			}
			if ($taken) {
				print "<h2>ERROR</h2>";
				print "<p>You have already taken this quiz. If you believe this to be an error, please contact your instructor ASAP.</p>";
				print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
				session_destroy(); }
		}
		if (!$taken) {
			print "<h2>Login successful.</h2>";
			print "<script type=\"text/javascript\">";
			print "document.title = \"Login successful. Loading quiz...\";";
			print "window.location = \"$quizfile\";";
			print "</script>";
		}
	}
}
?>
</body>
</html>

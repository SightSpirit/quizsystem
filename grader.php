<?php
	ini_set("display_errors",0);	

	session_start();
	$instructor = $_SESSION["instructor"];
	$quiz = $_SESSION["quiz"];
	$student = $_SESSION["student"];
	$course = $_SESSION["course"];
	$q_directory = $_SESSION["q_directory"];
	$quizfile = $_SESSION["quizfile"];
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
		if (is_null($student)) {
			print "<body onLoad=\"delay();\">\n";
			print "<h2>ERROR</h2>";
			print "<p>You are not allowed here. Please log in and submit a valid quiz.<br/>Redirecting you to <a href=\"login.html\">login page</a>...</p>";
			session_destroy();
			print "<script>";
			print "document.title = \"ERROR | Quiz Administration System\";";
			print "</script>";
		} else {
		print "<body>\n";
		print "<script>document.title = \"Your Score ($course Quiz $quiz) | Quiz Administration System\";</script>";

		$StudentLock = fopen($_SESSION["lockfile"],"a");
		fwrite($StudentLock,strtolower($student)."\n");
		fclose($StudentLock);

		$AnswerKey = file(substr($quizfile,0,-5)."Key.txt",FILE_IGNORE_NEW_LINES);
		$correct = 0;
		foreach ($AnswerKey as $q_num) {
			if ($_POST["ans_".substr($q_num,0,-1)]==$q_num) {
				$correct++;
			}
		}
		$percent = number_format(100*($correct / count($AnswerKey)),2,".","");

		$grades = $q_directory."Grades.txt";
		if (!file_exists($grades)) {
			$GradesFile = fopen($grades,"a");
			fwrite($GradesFile,"$course Quiz Grades\n\nQUIZ #\t\tSTUDENT NAME\t\t\tPERCENT CORRECT\n");
		} else { $GradesFile = fopen($grades,"a"); }
		fwrite($GradesFile,"$quiz\t\t$student\t\t\t$percent\n");
		fclose($GradesFile);
	
		print "<h2>Quiz Complete</h2>\n";
		print "<p>You have completed $course Quiz #$quiz, $student.<br/>You got <strong>$correct</strong> out of <strong>".count($AnswerKey)."</strong> (<strong>$percent%</strong>) correct. Your score has been recorded.</p>";
	
		session_destroy(); }
	?>
</body>
</html>

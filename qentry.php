<?php
	ini_set("display_errors",0);
	session_start();
	$instructor = $_SESSION["instructor"];
	$course = $_SESSION["course"];
	$quiz = $_SESSION["quiz"]; 
	$number = $_SESSION["q_number"];
?>

<!DOCTYPE html>
<html>
<head>
	<style>
		th { padding-right:10px; }
		span.par { text-decoration:underline; }
	</style>
	<script>
		function delay() {
			window.setTimeout(function() { window.location = "login.html"; },3000);
		}
	</script>
</head>
	<?php
		ini_set("display_errors",0);
		if ($instructor=="") {
			print "<body onLoad=\"delay();\">\n";
			print "<script>document.title = \"ERROR | Quiz Administration System\";</script>";
			print "<h2>ERROR</h2>";
			print "<p>You must be logged in as an instructor in order to access the quiz generator.<br/>Redirecting you to the <a href=\"login.html\">login page</a>...</p>";
			session_destroy();
		} else {
		print "<body>\n";
		print "<script>document.title = \"Quiz Generator | Quiz Administration System\";</script>";
		print "<p><span class=\"par\">Instructor:</span> $instructor<br/>";
		print "<span class=\"par\">Course:</span> $course<br/>";
		print "<span class=\"par\">Quiz number:</span> $quiz</p>";
		$filename = $_SESSION["filename"];
		
		if ($number>0) {
			$FileVar = fopen($filename,"a");
			if (!$FileVar) {
				session_destroy();
				print "<script>document.title = \"500 Internal Server Error\";</script>";
				print "<h1>INTERNAL SERVER ERROR.</h1><h2>Could not open quiz file.</h2>";
			}
			fwrite($FileVar,"<p>$number) ");
			fwrite($FileVar,$_POST["question"]."<br/>\n");
			fwrite($FileVar,"<ol type=\"A\">\n");
			fwrite($FileVar,"<li><input type=\"radio\" name=\"ans_$number\" value=\"$number"."A\" required /> ".$_POST["ans_A"]."</li>\n");
			fwrite($FileVar,"<li><input type=\"radio\" name=\"ans_$number\" value=\"$number"."B\" /> ".$_POST["ans_B"]."</li>\n");
			fwrite($FileVar,"<li><input type=\"radio\" name=\"ans_$number\" value=\"$number"."C\" /> ".$_POST["ans_C"]."</li>\n");
			fwrite($FileVar,"<li><input type=\"radio\" name=\"ans_$number\" value=\"$number"."D\" /> ".$_POST["ans_D"]."</li>\n");
			fwrite($FileVar,"<li><input type=\"radio\" name=\"ans_$number\" value=\"$number"."E\" /> ".$_POST["ans_E"]."</li>\n");
			fwrite($FileVar,"</ol>");
			fclose($FileVar);
			
			$FileVarKey = fopen(substr($filename,0,-5)."Key.txt","a");
			$correct = $_POST["correct"];
			fwrite($FileVarKey,$number.$correct."\n");
			fclose($FileVarKey);
		}
		$done = false;
		if (array_key_exists("done",$_POST)) { $done = $_POST["done"]; }
		if ($done=="Done") {
			$FileVar = fopen($filename,"a");
			fwrite($FileVar,"</body>\n</html>");
			fclose($FileVar);
		print "<script type=\"text/javascript\">";
		print "window.location = \"endsession.php\";";
		print "</script>";
		}
		}
	?>
	<h1 class="valid">Quiz Generator</h1>
	<form class="valid" name="question_entry" method="post" action="qentry.php">
		<table>
			<tr>
				<th>
				<?php
					$number++;
					print "$number";
					$_SESSION["q_number"] = $number;
				?>
				</th><td colspan="2"><textarea name="question" rows="5" cols="70"></textarea></td>
			</tr>
			<tr>
				<th>A.</th><td><input name="ans_A" type="text" size="60" /></td><td><input type="radio" name="correct" value="A" required /></td>
			</tr>
			<tr>
				<th>B.</th><td><input name="ans_B" type="text" size="60" /></td><td><input type="radio" name="correct" value="B" /></td>
			</tr>
			<tr>
				<th>C.</th><td><input name="ans_C" type="text" size="60" /></td><td><input type="radio" name="correct" value="C" /></td>
			</tr>
			<tr>
				<th>D.</th><td><input name="ans_D" type="text" size="60" /></td><td><input type="radio" name="correct" value="D" /></td>
			</tr>
			<tr>
				<th>E.</th><td><input name="ans_E" type="text" size="60" /></td><td><input type="radio" name="correct" value="E" /></td>
			</tr>
		</table>
		<p><input type="submit" value="Add Question" /></p>
		<p><input type="submit" value="Done" name="done" /></p>
	</form>
<?php
	if ($instructor=="") {
		for ($i=0; $i<2; $i++) {
			print "<script>document.getElementsByClassName(\"valid\")[".$i."].style.display = \"none\";</script>";
		}
	}
?>
</body>
</html>

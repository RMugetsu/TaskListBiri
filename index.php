<!DOCTYPE html>
<html>
<head>
	<title>Tasklist</title>
</head>
<body>
	<?
	//$db = new PDO('mysql:host=localhost;dbname=TaskList;charset=utf8mb4', 'Admin', 'Admin');
	$database = parse_url(getenv("DATABASE_URL"));
	$db = new PDO("pgsql:" . sprintf(
	    "host=%s;port=%s;user=%s;password=%s;dbname=%s",
	    $database["host"],
	    $database["port"],
	    $database["user"],
	    $database["pass"],
	    ltrim($database["path"], "/")
	));
	
	if(isset($_GET['Done'])){
		$query = $db->prepare("UPDATE TaskList SET state = 1 WHERE id = ".$_GET['Done'].";");
		$query->execute();
	}else if(isset($_GET['NotDone'])){
		$query = $db->prepare("UPDATE TaskList SET state = 0 WHERE id = ".$_GET['NotDone'].";");
		$query->execute();
	}else if(isset($_GET['delete'])){
		$query = $db->prepare("DELETE FROM TaskList WHERE id = ".$_GET['delete'].";");
		$query->execute();
	}else if(isset($_GET['newtask'])){
		$query = $db->prepare("INSERT INTO TaskList (task,state) VALUES ('".$_GET['newtask']."',0)");
		if (!$query) {
    		echo "\nPDO::errorInfo():\n";
    		print_r($dbh->errorInfo());
		}
		$query->execute();
	}
	$notdone = $db->query("SELECT * FROM tasklist WHERE state = 0");
	$done = $db->query("SELECT * FROM tasklist WHERE state = 1");
	?>
		<h2>TaskList</h2>
		<form action="#" method="GET">
			<input type="text" name="newtask"> <input type="submit" value="Insert New">
		</form>
		<br><br>
		<b>Tareas por hacer: <br></b>
		<ul>

		<?
		foreach($notdone as $row){
			echo "<li>" . $row['task']." <a href='?Done=".$row['id']."'>Done</a> <a href='?delete=".$row['id']."'>Delete</a></li><br>";
		}
?>
		</ul>

		<b>Tareas hechas: <br></b>
		<ul>

		<?
		foreach($done as $row){
			echo "<li>" . $row['task']." <a href='?NotDone=".$row['id']."'>Not Done</a> <a href='?delete=".$row['id']."'>Delete</a></li><br>";
		}
?>
		</ul>
		
</body>
</html>

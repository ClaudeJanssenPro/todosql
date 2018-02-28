<?php
  $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  if(isset($_POST['desc'])) {
    $desc = trim($_POST['desc']);
    if (!empty($desc)) {
      $add_task = $db->prepare('
        INSERT INTO task_table (task_desc)
        VALUES (:desc)
      ');
      $add_task->execute([
        'desc' => $desc
      ]);
    }
  }
  $request = $db->prepare('SELECT * FROM task_table');
  $request->execute();
  $task_todos = $request->rowCount() ? $request : [];
  $tasks_todo = $db->query('SELECT * FROM task_table WHERE done=0');
  $tasks_done = $db->query('SELECT * FROM task_table WHERE done=1');
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
		<link rel="stylesheet" href="style.css" charset="utf-8" />
		<title>TodoList SQL</title>
	</head>
	<body>
    <div class="list">
      <fieldset>
        <h1 class="header">À faire</h1>
        <!-- <?php if (!empty($task_todos)): ?> -->
        <ul class="items">
          <?php foreach ($tasks_todo as $task_todo): ?>
          <li>
            <span class="item<?php echo $task_todo['done'] ? ' done' : '' ?>"><?php echo $task_todo['task_desc'], '<br />'; ?></span>
            <?php if (!$task_todo['done']): ?>
              <a href="#" class="done-button">C'est fait</a>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <!-- <?php else: ?>
          <p>Rien de prévu!</p>
        <?php endif; ?> -->
        <h1 class="header">Archive</h1>
            <ul class="items">
              <?php foreach ($tasks_done as $task_done): ?>
              <li>
                <span class="item<?php echo $task_done['done'] ? ' done' : '' ?>"><?php echo $task_done['task_desc'], '<br />'; ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
      </fieldset>
    </div>
    <div class="list">
      <fieldset>
        <h1 class="header">Ajouter une nouvelle tâche</h1>
        <form class="item-add" action="formulaire.php" method="post">
          <label for="desc">Description</label>
          <input type="text" name="desc" placeholder="(ajoute ta tâche ici)" class="input" autocomplete="off" required>
          <input type="submit" value="Ajouter" class="submit">
        </form>
      </fieldset>
    </div>
	</body>
</html>

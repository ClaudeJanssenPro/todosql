<?php
  $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  $request = $db->prepare('SELECT * FROM task_table');
  $request->execute();
  $items = $request->rowCount() ? $request : [];
  if(isset($_POST['submit'])) {
    $desc = trim($_POST['desc']);
    if (!empty($desc)) {
      $add_task = $db->query('INSERT INTO task_table (task_desc) VALUES (:desc)');
      // $add_task->execute([ 'desc' => $desc ]);
    }
  }
  // header("Location: formulaire.php");
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- <link href="https://fonts.googleapis.com/css?family=Shadows+Into+Light+Two" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Athiti" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet"> -->
		<link rel="stylesheet" href="style.css" charset="utf-8" />
		<title>TodoList SQL</title>
	</head>
	<body>
    <div class="list">
      <fieldset>
        <h1 class="header">À faire</h1>
        <?php if (!empty($items)): ?>
        <ul class="items">
          <?php foreach ($items as $item): ?>
          <li>
            <span class="item<?php echo $item['done'] ? ' done' : '' ?>"><?php echo $item['task_desc'], '<br />'; ?></span>
            <?php if (!$item['done']): ?>
              <a href="#" class="done-button">C'est fait</a>
            <?php endif; ?>
          </li>
          <?php endforeach; ?>
        </ul>
        <?php else: ?>
          <p>Rien de prévu!</p>
        <?php endif; ?>
        <h1 class="header">Archive</h1>
      </fieldset>
    </div>
    <div class="list">
      <fieldset>
        <h1 class="header">Ajouter une nouvelle tâche</h1>
        <form class="item-add" action="formulaire.php" method="post">
          <label for="desc">Description</label>
          <input type="text" name="desc" placeholder="(ajoute ta tâche ici)" class="input" autocomplete="off" required>
          <input type="submit" value="Ajouter" name="submit">
        </form>
      </fieldset>
    </div>
	</body>
</html>

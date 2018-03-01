<?php
try
{
	  $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
    die('Erreur : '.$e->getMessage());
}

// @@@@@@@@@@@@@@@@@@@@@@@@@@ Task addition @@@@@@@@@@@@@@@@@@@@@@@@@@
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
// @@@@@@@@@@@@@@@@@@@@@@@@@@ Task' status change @@@@@@@@@@@@@@@@@@@@@@@@@@
if (isset($_POST['boutton'])){ //si j'enregistre ( je check la case.. )
    $choix=sanitize($_POST['tache']); // je récupère les valeurs checkée ("tache[]") des inputs ( qui sont alors dans un tableau )
    foreach ($choix as $key){ // pour chaque ligne ...
    $dbup = "UPDATE tache
            SET fin = 'True'
            WHERE nomtache='".$key."'";
            //Si nomtache est égale à la valeur checkée, remplacement de 'False' par 'True'
    $resultat = $bdd->exec($dbup); // Exécution... ( query )
    }
}

  // @@@@@@@@@@@@@@@@@@@@@@@@@@ Sani @@@@@@@@@@@@@@@@@@@@@@@@@@
  // function sanitize($key, $filter=FILTER_SANITIZE_STRING){
  //
  //     $sanitized_variable = null;
  //
  //     if(isset($_POST['desc'])OR isset($_POST['done-button'])){
  //
  //         if(is_array($key)){                 // si la valeur est un tableau...
  //         $sanitized_variable = filter_var_array($key, $filter);
  //         }
  //         else {                              // sinon ...
  //         $sanitized_variable = filter_var($key, $filter);
  //         }
  //     }
  //
  //     return $sanitized_variable;
  // }

  $request = $db->prepare('SELECT * FROM task_table');
  $request->execute();
  $task_todos = $request->rowCount() ? $request : [];
  $tasks_todo = $db->query('SELECT * FROM task_table WHERE done=0');
  $tasks_done = $db->query('SELECT * FROM task_table WHERE done=1');
?>

<a href="#" class="done-button" name="boutton" value="check">C'est fait</a>
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
              <a href="formulaire.php?as=done&task=<?php echo $task['id']; ?>" class="done-button" name="done-button" value="check">C'est fait</a>
              
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

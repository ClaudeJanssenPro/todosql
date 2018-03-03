
<?php
// °°°°°°°°°°°°°°°°°°°°°°°°°° DB HookUp °°°°°°°°°°°°°°°°°°°°°°°°°°
try
{
  $db = new PDO('mysql:host=localhost;dbname=todolist;charset=utf8', 'root', 'user', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
}
catch(Exception $e)
{
  die('Erreur : '.$e->getMessage());
}
// °°°°°°°°°°°°°°°°°°°°°°°°°° Sani °°°°°°°°°°°°°°°°°°°°°°°°°°
function sanitize($key, $filter=FILTER_SANITIZE_STRING){
    $sanitized_variable = null;
    if(isset($_POST['desc'])OR isset($_POST['done_button'])){
        if(is_array($key)){
        $sanitized_variable = filter_var_array($key, $filter);
        }
        else {
        $sanitized_variable = filter_var($key, $filter);
        }
    }
    return $sanitized_variable;
echo($sanitized_variable);
}
// °°°°°°°°°°°°°°°°°°°°°°°°°° Task addition °°°°°°°°°°°°°°°°°°°°°°°°°°
if(isset($_POST['desc'])) {
  $desc = sanitize($_POST['desc']);
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
// °°°°°°°°°°°°°°°°°°°°°°°°°° Task' status change °°°°°°°°°°°°°°°°°°°°°°°°°°
if (isset($_POST['check_task'])){
  $check = ($_POST['task']);
  foreach ($check as $key) {
  $dbup = '
    UPDATE task_table
    SET done = 1
    WHERE task_desc = "'.$key.'"
    ';
  $update = $db->exec($dbup);
  }
}
// °°°°°°°°°°°°°°°°°°°°°°°°°° Display tasks w/cond °°°°°°°°°°°°°°°°°°°°°°°°°°
$request = $db->prepare('SELECT * FROM task_table');
$request->execute();
$task_todos = $request->rowCount() ? $request : [];
$tasks_todo = $db->query('SELECT * FROM task_table WHERE done=0');
$tasks_done = $db->query('SELECT * FROM task_table WHERE done=1');
$title = 'TodoList (SQL)';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="style.css" charset="utf-8" />
		<title><?=$title?></title>
	</head>
	<body>
    <div class="text">
      <span><h1><?=$title?></h1></span>
    </div>
    <div class="postit">
      <fieldset>
        <legend><h1>À faire</h1></legend>
        <form class="task_mod" action="formulaire.php" method="post">
          <ul class="items">
            <?php foreach ($tasks_todo as $task_todo): ?>
            <li>
              <input type='checkbox' name='task[]' value="<?php echo $task_todo['task_desc']; ?>" />
							<label for='checkbox'><?php echo $task_todo['task_desc']; ?></label><br />
            </li>
            <?php endforeach; ?>
          </ul>
        	<input type="submit" name="check_task" value="Fait" class="submit">
        </form>
      </fieldset>
    </div>
    <div class="postit green">
      <fieldset>
        <legend><h1>Fait</h1></legend>
            <ul class="items">
              <?php foreach ($tasks_done as $task_done): ?>
              <li>
                <span class="item<?php echo $task_done['done'] ? ' done' : '' ?>"><?php echo $task_done['task_desc'], '<br />'; ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
        </form>
      </fieldset>
    </div>
    <div class="postit blue">
      <fieldset>
        <legend><h1>Nouvelle tâche</h1></legend>
        <form class="task_add" action="formulaire.php" method="post">
          <label for="desc">Description</label>
          <input type="text" name="desc" placeholder="(ajoute ta tâche ici)" class="input" autocomplete="off" required>
          <input type="submit" value="Ajouter" class="submit">
        </form>
      </fieldset>
    </div>
	</body>
</html>

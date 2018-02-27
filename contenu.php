<?php
    ini_set("dispay_errors",0);error_reporting(0);
    $filename="todo.json";
    $content_json = file_get_contents($filename);
    $receipt = json_decode($content_json, true);

    //Sanitize
    if (isset($_POST['submit'])) {
      $sanitize = filter_var($_POST['newtask'],FILTER_SANITIZE_STRING);
    }
    // bouton ajouter
    if (isset($_POST['submit']) AND end($receipt)['taskname'] != $_POST['newtask']){
        $add_task = $sanitize;
        $array_task = array("taskname" => $add_task,
                             "Terminer" => false);
        $receipt[] = $array_task;
        $json= json_encode($receipt, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);
        $receipt = json_decode($json, true);
    }

    // bouton enregistrer
    if (isset($_POST['save'])){
        $choice=$_POST['newtask'];
        for ($init = 0; $init < count($receipt); $init ++){
            if (in_array($receipt[$init]['taskname'], $choice)){
              $receipt[$init]['Terminer'] = true;
            }
        }
        $json= json_encode($receipt, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);
        $receipt = json_decode($json, true);
    }
    // bouton retirer
    if (isset($_POST['unsave'])){
        $choice=$_POST['removetask'];
        // var_dump($choice);
        for ($init = 0; $init < count($receipt); $init ++){
            if (!in_array($receipt[$init]['taskname'], $choice)){
              $receipt[$init]['Terminer'] = false;
            }
        }
        $json= json_encode($receipt, JSON_PRETTY_PRINT);
        file_put_contents($filename, $json);
        $receipt = json_decode($json, true);
    }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
        <script type="text/javascript">
        function ShowHideDiv(chkTask) {
            var dvTask = document.getElementById("dvTask");
            dvTask.style.display = chkTask.checked ? "block" : "none";
        }
        </script>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<link rel="stylesheet" href="style.css" type="text/css" charset="utf-8" />
		<title>To do</title>
	</head>
    <body>
            <div class="container">
            <fieldset>
                <form action="contenu.php" method="POST">

                        <h3>A faire</h3>
                        <ul id="incomplete-tasks">
                        <label for="chkTask">
                            <?php
                                foreach ($receipt as $key => $value) {
                                    if ($value["Terminer"] == false){

                                        echo "<li><input id='chkTask' onclick='ShowHideDiv(this)' type='checkbox' name='newtask[]' value='".$value["taskname"]."'/>
                                        <label for='choice'>".$value["taskname"]."</label></li><br />";
                                    }
                                }
                            ?>
                        </label>
                        <div id="dvTask" style="display: none">
                            <button class="save" name="save" type="submit">Enregistrer</button>
                        </div>
                </form>

                <form action="contenu.php" method="POST">

                        <h3>Archive</h3>
                        <ul id="completed-tasks">
                <?php
                    foreach ($receipt as $key => $value) {
                        if ($value["Terminer"] == true){
                            echo "<li><input type='checkbox' name='removetask[]' value='".$value["taskname"]."'checked/>
                                <label for='choice'>".$value["taskname"]."</label></li><br />";
                        }
                    }
                ?>
                        </ul>
                            <button class="unsave" name="unsave" type="submit">Retirer</button>
                </form>
            </fieldset>
            </div>

            <div class="container">
            <fieldset>
                <form method="post" action="contenu.php">
                        <p>
                            <h3>Ajouter une nouvelle tache</h3>
                        </p>
                        <p>
                            <textarea name="newtask" placeholder="(ajouter ta tâche ici)" class="expanding" autofocus required></textarea>
                        </p>
                        <p>
                            <button type="submit" name="submit">Ajouter</button>
                        </p>
                </form>
            </fieldset>
            </div>
        </fieldset>
    </body>
</html>

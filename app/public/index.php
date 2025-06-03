<?php

namespace Dices;

require_once '../DieSingle.php';

$num_dices = $_GET['num_dices'] ?? 1;
$dices = [];
$rolled = [];
$action = 'none';

if ($_GET['roll_dices'] !== null) {
	$action = 'roll_dices';
} else if ($_GET['add_die'] !== null) {
	$action = 'add_die';
} else if ($_GET['clear_dices'] !== null) {
	$action = 'clear_dices';
} else {
    for ($i = 0; $i < $num_dices; $i++) {
        $delete_die_action = $_GET['del_die_' . $i] ?? '';
        $roll_die_action = $_GET['roll_die_' . $i] ?? '';
        if($delete_die_action !== '')
            $action = 'del_die_' . $i;
        else if($roll_die_action !== '')
            $action = 'roll_die_' . $i;
    }
}

for ($i = 0; $i < $num_dices; $i++) {
	$die_type = (int) ($_GET['die_' . $i] ?? 6);
    $die_type = $die_type < 2 ? 2 : $die_type;
    $die = new DieSingle($die_type);
    $dices[] = $die;
    $rolled[] = ($_GET['rolled_' . $i] ?? '');
}

if ($action === 'add_die') {
	$dices[] = new DieSingle(6);
	$num_dices++;
}

if ($action === 'roll_dices') {
	$rolled = array_map(fn ($_die) => "{$_die->roll()}", $dices);
}

if ($action === 'clear_dices') {
	$rolled = array_map(fn ($_die) => '', $dices);
}

if (str_starts_with($action, 'del_die_')) {
    $to_delete = intval(substr($action, 8));
    if ($to_delete >= 0 && $to_delete < count($dices)) {
        array_splice($dices, $to_delete, 1);
        array_splice($rolled, $to_delete, 1);
        $num_dices--;
    }
}

if (str_starts_with($action, 'roll_die_')) {
    $to_roll = intval(substr($action, 9));
    if ($to_roll >= 0 && $to_roll < count($dices)) {
        $rolled[$to_roll] = "{$dices[$to_roll]->roll()}";
    }
}


// echo '<pre>';
// var_dump($action);
// var_dump($_GET);
// var_dump($dices);
// var_dump($rolled);
// echo '</pre>';

?>

<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <header>
      <h1>
		DICES
      </h1>
    </header>
  <body>
    <form action='' method='get'>
	  <main>
		<input type='hidden' name='num_dices' value='<?= $num_dices ?>'>
		<table>
		  <thead>
			<th>type</th>
			<th>rolled</th>
			<th>action</th>
		  </thead>
		  <tbody>
			<?php foreach($dices as $i => $_die): ?>
			  <tr>
				<td><input type='number' name='die_<?= $i ?>' value='<?= $_die->type() ?>'></td>
     			<input type='hidden' name='rolled_<?= $i ?>' value='<?= $rolled[$i] ?>'>
				<td><?= $rolled[$i] ?></td>
                <td> 
					 <input type='submit' name='del_die_<?= $i ?>' value='Delete'>
     				 <input type='submit' name='roll_die_<?= $i ?>' value='Roll'>
                </td>
			  </tr>
			<?php endforeach ?>
		  </tbody>
		</table>	
	  </main>
	  <footer>
		<input type='submit' name='add_die' value='Add die'>
		<input type='submit' name='roll_dices' value='Roll Dices'>
	    <input type='submit' name='clear_dices' value='Clear'>
	  </footer>
    </form>
  </body>
  <footer>
  </footer>
  </body>
</html>

<?php

declare(strict_types=1);

namespace Dices;

require_once '../view_model/ViewModel.php';

$vm = new ViewModel(messages: $_POST);

?>

<!DOCTYPE html>
<html>
  <head>
  </head>
  <body>
    <header>
      <h1>DICES</h1>
    </header>
    <form action='' method='post'>
	  <main>
		<input type='hidden'
               name='num_dices' value='<?= $vm->num_dices() ?>'>
		<table>
		  <thead>
			<th>type</th>
			<th>rolled</th>
			<th>action</th>
		  </thead>
		  <tbody>
            <?php foreach($vm->dices() as $i => $_die): ?>
			  <tr>
				<td><input type='number'
                           name='die_<?= $i ?>'
                           value='<?= $_die->type() ?>'></td>
     			<input type='hidden'
                       name='rolled_<?= $i ?>'
                       value='<?= $vm->rolled()[$i] ?>'>
                <td><?= $vm->rolled()[$i] ?></td>
                <td> 
					 <input type='submit'
                            name='del_die_<?= $i ?>' value='Delete'>
     				 <input type='submit'
                            name='roll_die_<?= $i ?>' value='Roll'>
     				 <input type='submit'
                            name='clear_die_<?= $i ?>' value='Clear'>     
                </td>
			  </tr>
			<?php endforeach ?>
		  </tbody>
          <tfoot>
              <tr>
                <th> SUM </th>
                <th> <?= $vm->sum() ?> </th>
              </tr>
          </tfoot>
		</table>	
	  </main>
	  <footer>         
		<input type='submit' name='add_die' value='Add die'>
		<input type='submit' name='roll_dices' value='Roll Dices'>
	    <input type='submit' name='clear_dices' value='Clear'>
	  </footer>
    </form>
    <footer>
    </footer>
  </body>
</html>

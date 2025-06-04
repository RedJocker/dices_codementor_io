<?php

namespace Dices;

class ViewModel {

    private int $num_dices;
    private array $dices;
    private array $rolled;
    private string $action;
    private string $sum;

    public function __construct(array $messages) {
        $this->num_dices = $this->num_dices_from_messages($messages);
        $this->action =
            $this->action_from_messages($messages, $this->num_dices);
        $this->dices =
            $this->dices_from_messages($messages, $this->num_dices);
        $this->rolled =
            $this->rolled_from_messages($messages, $this->num_dices);

        // echo '<pre>';
        // var_dump($this->action);
        // var_dump($this->num_dices);
        // var_dump($this->dices);
        // var_dump($this->rolled);
        //echo '</pre>';
        
        $this->perform_action($this->action);
        $this->sum = $this->sum_rolled_dices($this->rolled);
    }

    public function dices(): array {
        return $this->dices;
    }

    public function rolled(): array {
        return $this->rolled;
    }

    public function num_dices(): int {
        return $this->num_dices;
    }

    public function sum(): string {
        return $this->sum;
    }

    private function sum_rolled_dices(array $rolled): string {
        $sum = array_sum($rolled);
        return $sum === 0 ? '' : "$sum";
    }

    private function perform_action(string $action): void {
        if ($action === 'add_die') {
            $this->dices[] = new DieSingle(6);
            $this->num_dices++;
        } else if ($action === 'roll_dices') {
            $this->rolled =
                array_map(fn ($_die) => "{$_die->roll()}", $this->dices);
        } else if ($action === 'clear_dices') {
            $this->rolled = array_map(fn ($_die) => '', $this->dices);
        } else if (str_starts_with($action, 'del_die_')) {
            $to_delete = intval(substr($action, 8));
            if ($to_delete >= 0 && $to_delete < count($this->dices)) {
                array_splice($this->dices, $to_delete, 1);
                array_splice($this->rolled, $to_delete, 1);
                $this->num_dices--;
            }
        } else if (str_starts_with($action, 'roll_die_')) {
            $to_roll = intval(substr($action, 9));
            if ($to_roll >= 0 && $to_roll < count($this->dices)) {
                $this->rolled[$to_roll] =
                    "{$this->dices[$to_roll]->roll()}";
            }
        } else if (str_starts_with($action, 'clear_die_')) {
            $to_clear = intval(substr($action, 10));
            if ($to_clear >= 0 && $to_clear < count($this->dices)) {
                $this->rolled[$to_clear] = '';
            }
        }
    }

    private function num_dices_from_messages(array $messages) {
        return $messages['num_dices'] ?? 1;
    }

    private function action_from_messages(
        array $messages, int $num_dices
    ): string {

        if ($messages['roll_dices'] !== null) {
            return 'roll_dices';
        } else if ($messages['add_die'] !== null) {
            return 'add_die';
        } else if ($messages['clear_dices'] !== null) {
            return 'clear_dices';
        } else {
            for ($i = 0; $i < $num_dices; $i++) {
                $delete_die_action = $messages['del_die_' . $i] ?? '';
                $roll_die_action = $messages['roll_die_' . $i] ?? '';
                $clear_die_action = $messages['clear_die_' . $i] ?? '';
                if($delete_die_action !== '')
                    return 'del_die_' . $i;
                else if($roll_die_action !== '')
                    return 'roll_die_' . $i;
                else if($clear_die_action !== '')
                    return 'clear_die_' . $i;
            }
            return 'none';
        }
    }

    private function dices_from_messages(
        array $messages, int $num_dices
    ): array {
        $dices = [];
        for ($i = 0; $i < $num_dices; $i++) {
            $die_type = (int) ($messages['die_' . $i] ?? 6);
            $die_type = $die_type < 2 ? 2 : $die_type;
            $die = new DieSingle($die_type);
            $dices[] = $die;
        }
        return $dices;
    }

    private function rolled_from_messages(
        array $messages, int $num_dices
    ): array {
        $rolled = [];
        for ($i = 0; $i < $num_dices; $i++) {
            $rolled[] = ($messages['rolled_' . $i] ?? '');
        }
        return $rolled;
    }
}

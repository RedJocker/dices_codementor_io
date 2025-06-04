<?php

declare(strict_types=1);

namespace Dices;

class DieSingle {

    public function __construct(private int $faces){}

    public function roll() {
        return rand(1, $this->faces);
    }

	public function type() {
        return $this->faces;
    }
}

<?php

namespace CH\Testing;

use PHPUnit\Framework\TestCase;

final class Console extends TestCase{
     /**
     * @outputBuffering disabled
     */
    public function testList() {

      $console =exec('php antonella2 list');
      $this->assertSame('  theme:list       Gets a list of themes', $console);
       
    }   
}
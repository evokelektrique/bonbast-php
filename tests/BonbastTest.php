<?php

use PHPUnit\Framework\TestCase;
use Bonbast\Bonbast;

final class BonbastTest extends TestCase {
    public function testGetFormattedPrices(): void {
        $bonbast = new Bonbast();
        $result = $bonbast->get_formatted_price("usd");
        
        $this->assertIsArray($result);
    }
}

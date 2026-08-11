<?php

namespace Tests\Unit;

use App\Models\Property;
use PHPUnit\Framework\TestCase;

class MoneyFormattingTest extends TestCase
{
    public function test_price_formatted_from_cents(): void
    {
        $p = new Property;
        $p->setRawAttributes(['price_cents' => 1_234_567_00], true);
        $this->assertSame('€1,234,567', $p->priceFormatted);
    }

    public function test_zero_price(): void
    {
        $p = new Property;
        $p->setRawAttributes(['price_cents' => 0], true);
        $this->assertSame('€0', $p->priceFormatted);
    }
}

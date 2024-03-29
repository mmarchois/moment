<?php

declare(strict_types=1);

namespace App\Tests\Unit\Infrastructure\Adapter;

use App\Infrastructure\Adapter\DateUtils;
use PHPUnit\Framework\TestCase;

final class DateUtilsTest extends TestCase
{
    public function testNow(): void
    {
        $dateUtils = new DateUtils();

        $this->assertEquals((new \DateTimeImmutable('now'))->format('Y-m-d'), $dateUtils->getNow()->format('Y-m-d'));
        $this->assertEquals((new \DateTimeImmutable('now'))->format('Y'), $dateUtils->getCurrentYear());
    }
}

<?php declare(strict_types=1);

namespace Tests\nicoSWD\SecHeaderCheck\Application\Command;

use nicoSWD\SecHeaderCheck\Domain\HeaderService;
use PHPUnit\Framework\TestCase;

class HeaderServiceTest extends TestCase
{
    public function testOne()
    {
        $service = new HeaderService();

        $this->assertTrue($service->getHeaders());
    }
}
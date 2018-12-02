<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\Header\ReferrerPolicyHeader;
use PHPUnit\Framework\TestCase;

final class ReferrerPolicyHeaderTest extends TestCase
{
    /** @dataProvider nonLeakingReferrerSettings */
    public function testGivenAReferrerPolicyHeaderWithNonLeakingSettingsItShouldNotWarnAndPenalise($setting)
    {
        $header = new ReferrerPolicyHeader($setting);

        $this->assertSame(1., $header->getScore());
        $this->assertEmpty($header->getWarnings());
    }

    /** @dataProvider leakingReferrerSettings */
    public function testGivenAReferrerPolicyHeaderWithLeakingSettingsItShouldWarnAndPenalise($setting)
    {
        $header = new ReferrerPolicyHeader($setting);

        $this->assertSame(.5, $header->getScore());
        $this->assertCount(1, $header->getWarnings());
    }

    public function testGivenAReferrerPolicyHeaderWithAnUnknownOptionItShouldWarnAndPenalise()
    {
        $header = new ReferrerPolicyHeader('do-something');

        $this->assertSame(.0, $header->getScore());
        $this->assertCount(1, $header->getWarnings());
    }

    public function nonLeakingReferrerSettings(): array
    {
        return [
            [
                'no-referrer',
                'no-referrer-when-downgrade',
                'same-origin',
                'strict-origin',
            ]
        ];
    }

    public function leakingReferrerSettings(): array
    {
        return [
            [
                'origin',
                'origin-when-cross-origin',
                'strict-origin-when-cross-origin',
            ]
        ];
    }
}

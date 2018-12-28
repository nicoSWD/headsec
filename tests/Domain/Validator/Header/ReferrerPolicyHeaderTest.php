<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace Tests\nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Header\SecurityHeader;
use nicoSWD\SecHeaderCheck\Domain\Validator\Header\ReferrerPolicyHeader;
use PHPUnit\Framework\TestCase;

final class ReferrerPolicyHeaderTest extends TestCase
{
    /** @dataProvider nonLeakingReferrerSettings */
    public function testGivenAReferrerPolicyHeaderWithNonLeakingSettingsItShouldNotWarnAndPenalise($policy)
    {
        $header = new ReferrerPolicyHeader(new HttpHeader(SecurityHeader::REFERRER_POLICY, $policy));
        $result = $header->parse();

        $this->assertTrue($result->doesNotLeakReferrer());
        $this->assertFalse($result->mayLeakOrigin());
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

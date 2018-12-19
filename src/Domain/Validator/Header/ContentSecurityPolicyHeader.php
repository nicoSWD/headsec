<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\Result\ContentSecurityPolicyHeaderResult;
use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderParser;

final class ContentSecurityPolicyHeader extends AbstractHeaderParser
{
    private const DEFAULT_SRC = 'default-src';
    private const SCRIPT_SRC = 'script-src';
    private const IMG_SRC = 'img-src';
    private const FRAME_ANCESTORS = 'frame-ancestors';
    private const REPORT_URI = 'report-uri';
    private const CONNECT_SRC = 'connect-src';
    private const STYLE_SRC = 'style-src';
    private const SANDBOX = 'sandbox';

    private const EXPECTED_DIRECTIVES = [
        self::SCRIPT_SRC,
        self::IMG_SRC,
        self::FRAME_ANCESTORS,
        self::REPORT_URI,
        self::CONNECT_SRC,
        self::STYLE_SRC,
    ];

    private $foundDirectives = [];

    public function parse(): AbstractParsedHeader
    {
        foreach ($this->getDirectives($this->getValue()) as $directive) {
            [$directiveName, $policy] = $this->parseDirectiveNameAndPolicy($directive);

            switch ($directiveName) {
                case self::DEFAULT_SRC:
                case self::IMG_SRC:
                case self::SCRIPT_SRC:
                case self::FRAME_ANCESTORS:
                case self::REPORT_URI:
                case self::CONNECT_SRC:
                case self::STYLE_SRC:
                case self::SANDBOX:
                    if ($this->hasValidPolicy($policy)) {
                        $this->foundDirectives[] = $directiveName;
                    } else {
//                        $this->addWarning(new ContentSecurityPolicyInvalidWarning());
                    }
                    break;
                default:
//                    $this->addWarning(new ContentSecurityPolicyWithInvalidDirectiveWarning($directiveName));
                    break;
            }
        }

        $missingDirectives = $this->getMissingDirectives();

        if (count($missingDirectives) > 0) {
            if (!in_array(self::FRAME_ANCESTORS, $missingDirectives, true)) {
//                $this->addWarning(new ContentSecurityPolicyMissingFrameAncestorsDirective());
            } else {
//                $this->addWarning(new ContentSecurityPolicyWithMissingDirectiveWarning(implode(', ', $missingDirectives)));
            }
        }

        $contentSecurityPolicyResult = new ContentSecurityPolicyHeaderResult($this->getName(), $this->getValue());

        return $contentSecurityPolicyResult;

    }

    private function parseDirectiveNameAndPolicy(string $directiveAndValues): array
    {
        $directiveAndValues = $this->splitSkipEmpty('~[ ]~', $directiveAndValues);
        $directiveName = array_shift($directiveAndValues);

        return [strtolower($directiveName), $directiveAndValues];
    }

    private function hasValidPolicy(array $values): bool
    {
        return true;
    }

    private function getDirectives(string $header): array
    {
        return $this->splitSkipEmpty('~;~', $header);
    }

    private function splitSkipEmpty(string $regex, string $header): array
    {
        return array_map('trim', preg_split($regex, $header, -1, PREG_SPLIT_NO_EMPTY));
    }

    private function getMissingDirectives(): array
    {
        if (!in_array(self::DEFAULT_SRC, $this->foundDirectives, true)) {
            return array_diff(self::EXPECTED_DIRECTIVES, $this->foundDirectives);
        }

        return [];
    }
}

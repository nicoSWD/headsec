<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator\Header;

use nicoSWD\SecHeaderCheck\Domain\Validator\AbstractHeaderValidator;
use nicoSWD\SecHeaderCheck\Domain\Validator\ValidationError;

final class ContentSecurityPolicyHeader extends AbstractHeaderValidator
{
    private const SCRIPT_SRC = 'script-src';
    private const IMG_SRC = 'img-src';
    private const FRAME_ANCESTORS = 'frame-ancestors';
    private const REPORT_URI = 'report-uri';
    private const CONNECT_SRC = 'connect-src';
    private const STYLE_SRC = 'style-src';

    private const EXPECTED_DIRECTIVES = [
        self::SCRIPT_SRC,
        self::IMG_SRC,
        self::FRAME_ANCESTORS,
        self::REPORT_URI,
        self::CONNECT_SRC,
        self::STYLE_SRC,
    ];

    private $foundDirectives = [];

    public function getScore(): float
    {
        foreach ($this->getCSPHeaders() as $header) {
            foreach ($this->getDirectives($header) as $directive) {
                [$directiveName, $policy] = $this->parseDirectiveNameAndPolicy($directive);

                switch ($directiveName) {
                    case self::IMG_SRC:
                    case self::SCRIPT_SRC:
                    case self::FRAME_ANCESTORS:
                    case self::REPORT_URI:
                    case self::CONNECT_SRC:
                    case self::STYLE_SRC:
                        if ($this->hasValidPolicy($policy)) {
                            $this->foundDirectives[] = $directiveName;
                        } else {
                            $this->addWarning('Invalid policy');
                        }
                        break;
                    default:
                        $this->addWarning('Invalid CSP directive: ' . $directiveName);
                        break;
                }
            }
        }

        $missingDirectives = $this->getMissingDirectives();

        if (count($missingDirectives) > 0) {
            $this->addWarning(ValidationError::CSP_DIRECTIVE_MISSING . ': ' . implode(', ', $missingDirectives));
            return .5;
        }

        return 1;
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

    private function getCSPHeaders(): array
    {
        return (array) $this->getValue();
    }

    private function getMissingDirectives(): array
    {
        return array_diff(self::EXPECTED_DIRECTIVES, $this->foundDirectives);
    }
}

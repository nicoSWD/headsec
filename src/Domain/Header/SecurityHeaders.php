<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Header;

final class SecurityHeaders
{
    public const CONTENT_SECURITY_POLICY = 'content-security-policy';
    public const REFERRER_POLICY = 'referrer-policy';
    public const SET_COOKIE = 'set-cookie';
    public const SERVER = 'server';
    public const STRICT_TRANSPORT_SECURITY = 'strict-transport-security';
    public const X_FRAME_OPTIONS = 'x-frame-options';
    public const X_CONTENT_TYPE_OPTIONS = 'x-content-type-options';
    public const X_POWERED_BY = 'x-powered-by';
    public const X_XSS_PROTECTION = 'x-xss-protection';

    /** @var HeaderFactory */
    private $headerFactory;

    public function __construct(HeaderFactory $headerFactory)
    {
        $this->headerFactory = $headerFactory;
    }

    /** @return Header[] */
    public function getAll(): array
    {
        return [
            $this->headerFactory->createMandatory(self::STRICT_TRANSPORT_SECURITY),
            $this->headerFactory->createMandatory(self::X_FRAME_OPTIONS),
            $this->headerFactory->createMandatory(self::X_XSS_PROTECTION),
            $this->headerFactory->createMandatory(self::X_CONTENT_TYPE_OPTIONS),
            $this->headerFactory->createMandatory(self::REFERRER_POLICY),
            $this->headerFactory->createMandatory(self::CONTENT_SECURITY_POLICY),
            $this->headerFactory->createOptional(self::SET_COOKIE),
            $this->headerFactory->createOptional(self::SERVER),
            $this->headerFactory->createOptional(self::X_POWERED_BY),
        ];
    }
}

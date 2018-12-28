<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Domain\Validator;

use nicoSWD\SecHeaderCheck\Domain\Header\HttpHeader;
use nicoSWD\SecHeaderCheck\Domain\Result\AbstractParsedHeader;

abstract class AbstractHeaderParser
{
    private $name = '';
    private $value = '';

    public function __construct(HttpHeader $header)
    {
        $this->name = $header->name();
        $this->value = $header->value();
    }

    /** @return AbstractParsedHeader */
    abstract public function parse();

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}

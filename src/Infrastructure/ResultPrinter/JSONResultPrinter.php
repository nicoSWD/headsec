<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Infrastructure\ResultPrinter;

use nicoSWD\SecHeaderCheck\Domain\Result\ResultSet;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterInterface;

final class JSONResultPrinter implements ResultPrinterInterface
{
    public function getOutput(ResultSet $resultSet): string
    {
        $data = [
            'score'    => $resultSet->getScore(),
            'warnings' => $resultSet->getWarnings(),
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }
}

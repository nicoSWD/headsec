<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\Command;

use nicoSWD\SecHeaderCheck\Application\UseCase\ScanHeaders\ScanURLRequest;
use nicoSWD\SecHeaderCheck\Application\UseCase\ScanHeaders\ScanURLResponse;
use nicoSWD\SecHeaderCheck\Application\UseCase\ScanHeaders\ScanURLUseCase;
use nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders\ScanHeadersRequest;
use nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders\ScanHeadersResponse;
use nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders\ScanHeadersUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SecHeadersCheckCommand extends Command
{
    private const STATUS_ERROR = 1;
    private const STATUS_SUCCESS = 0;

    /** @var ScanURLUseCase */
    private $scanURLUseCase;
    /** @var ScanHeadersUseCase */
    private $scanHeadersUseCase;

    public function __construct(
        ScanURLUseCase $scanURLUseCase,
        ScanHeadersUseCase $scanHeadersUseCase
    ) {
        parent::__construct();

        $this->scanURLUseCase = $scanURLUseCase;
        $this->scanHeadersUseCase = $scanHeadersUseCase;
    }

    protected function configure()
    {
        $this->setName('nicoswd:security-header-check')
            ->setDescription('Check a site\'s security headers')
            ->addArgument('url', InputArgument::OPTIONAL, 'URL to check')
            ->addOption('ignore-redirects', 'r', InputOption::VALUE_OPTIONAL, 'Ignore redirects', false)
            ->addOption('output-format', 'o', InputOption::VALUE_OPTIONAL, 'Output format', 'console')
            ->addOption('target-score', 't', InputOption::VALUE_OPTIONAL, 'Target score', '5')
            ->addOption('show-all-headers', 'a', InputOption::VALUE_OPTIONAL, 'Show all headers', false)
            // Ideas...
            ->addOption('silent', 's', InputOption::VALUE_OPTIONAL, 'No output, just fail on error', false)
            ->addOption('config', 'c', InputOption::VALUE_OPTIONAL, 'Load config file', '')
            ->addOption('print-score', 'p', InputOption::VALUE_OPTIONAL, 'Only output the score', '')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $scanResult = $this->getScanResult($input);

        $output->writeln($scanResult->output);

        if ($scanResult->hitTargetScore) {
            return self::STATUS_SUCCESS;
        }

        return self::STATUS_ERROR;
    }

    protected function scanHeadersFromURL(InputInterface $input): ScanURLResponse
    {
        $scanRequest = new ScanURLRequest();
        $scanRequest->url = $input->getArgument('url');
        $scanRequest->outputFormat = $input->getOption('output-format');
        $scanRequest->targetScore = (float) $input->getOption('target-score');
        $scanRequest->followRedirects = $input->getOption('ignore-redirects') === false;
        $scanRequest->showAllHeaders = $input->getOption('show-all-headers') !== false;

        $scanResult = $this->scanURLUseCase->execute($scanRequest);

        return $scanResult;
    }

    private function scanHeadersFromStdIn(InputInterface $input): ScanHeadersResponse
    {
        $scanRequest = new ScanHeadersRequest();
        $scanRequest->headers = $this->getHeadersFromStdIn();
        $scanRequest->outputFormat = $input->getOption('output-format');
        $scanRequest->targetScore = (float) $input->getOption('target-score');
        $scanRequest->showAllHeaders = $input->getOption('show-all-headers') !== false;

        $scanResult = $this->scanHeadersUseCase->execute($scanRequest);

        return $scanResult;
    }

    private function getHeadersFromStdIn(): string
    {
        $headers = '';

        if (ftell(STDIN) === 0) {
            while (!feof(STDIN)) {
                $headers .= fread(STDIN, 1024);
            }
        }

        return $headers;
    }

    private function getScanResult(InputInterface $input)
    {
        $url = $input->getArgument('url');

        if ($url) {
            $scanResult = $this->scanHeadersFromURL($input);
        } else {
            $scanResult = $this->scanHeadersFromStdIn($input);
        }

        return $scanResult;
    }
}

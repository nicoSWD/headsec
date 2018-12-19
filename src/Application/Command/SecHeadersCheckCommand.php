<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\Command;

use nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders\ScanSecurityHeadersRequest;
use nicoSWD\SecHeaderCheck\Application\UseCase\SecurityHeaders\ScanSecurityHeadersUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SecHeadersCheckCommand extends Command
{
    private const STATUS_ERROR = 1;
    private const STATUS_SUCCESS = 0;

    /** @var ScanSecurityHeadersUseCase */
    private $scanSecurityHeadersUseCase;

    public function __construct(ScanSecurityHeadersUseCase $scanSecurityHeadersUseCase)
    {
        parent::__construct();

        $this->scanSecurityHeadersUseCase = $scanSecurityHeadersUseCase;
    }

    protected function configure()
    {
        $this->setName('nicoswd:security-header-check')
            ->setDescription('Check a site\'s security headers')
            ->addArgument('url', InputArgument::REQUIRED, 'URL to check')
            ->addOption('ignore-redirects', 'i', InputOption::VALUE_OPTIONAL, 'Follow redirects', false)
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
        $scanRequest = new ScanSecurityHeadersRequest();
        $scanRequest->url = $input->getArgument('url');
        $scanRequest->outputFormat = $input->getOption('output-format');
        $scanRequest->followRedirects = $input->getOption('ignore-redirects') === false;
        $scanRequest->targetScore = (float) $input->getOption('target-score');
        $scanRequest->showAllHeaders = $input->getOption('show-all-headers') !== false;

        $scanResult = $this->scanSecurityHeadersUseCase->execute($scanRequest);

        $output->writeln($scanResult->output);

        if ($scanResult->hitTargetScore) {
            return self::STATUS_SUCCESS;
        }

        return self::STATUS_ERROR;
    }
}

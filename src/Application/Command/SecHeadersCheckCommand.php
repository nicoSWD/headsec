<?php declare(strict_types=1);

namespace nicoSWD\SecHeaderCheck\Application\Command;

use nicoSWD\SecHeaderCheck\Domain\Headers\HeaderService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SecHeadersCheckCommand extends Command
{
    /** @var HeaderService */
    private $headerService;

    public function __construct(HeaderService $headerService)
    {
        parent::__construct();

        $this->headerService = $headerService;
    }

    protected function configure()
    {
        $this->setName('nicoswd:security-header-check')
            ->setDescription('Check a site\'s security headers')
            ->addArgument('url', InputArgument::REQUIRED, 'URL to check');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Score: ' . $this->headerService->analise($input->getArgument('url')));
    }
}

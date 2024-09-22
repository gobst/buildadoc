<?php
/**
 * This file is part of BuildADoc.
 *
 * (c) Guido Obst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
declare(strict_types=1);

namespace Command;

use Contract\Command\CommandInterface;
use Contract\Decorator\DokuWikiFormatInterface;
use Laminas\Text\Figlet\Figlet;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

#[AsCommand(
    name: 'DokuWiki:create-doc',
    description: 'Creates a class documentation for DokuWiki'
)]
class DokuWikiCommand extends Command implements DokuWikiFormatInterface, CommandInterface
{
    private const string COMMAND_SOURCE_ARGUMENT_TEXT = 'The source directory for the documentation.';
    private const string COMMAND_SOURCE_ARGUMENT_KEY = 'source';
    private const string COMMAND_DESTINATION_ARGUMENT_TEXT = 'The destination directory for the documentation.';
    private const string COMMAND_DESTINATION_ARGUMENT_KEY = 'destination';
    private const string COMMAND_NAME_ARGUMENT_TEXT = 'The name of the documentation.';
    private const string COMMAND_NAME_ARGUMENT_KEY = 'name';
    private const string COMMAND_LANGUAGE_ARGUMENT_TEXT = 'The language that should be used (en or de). Default is "de".';
    private const string COMMAND_LANGUAGE_ARGUMENT_KEY = 'language';
    private const string COMMAND_HELP_TEXT = 'This command allows you to create a class documentation for DokuWiki.';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $args = $input->getArguments();
        $language = empty($args['language']) ? self::DEFAULT_LANGUAGE : $args['language'];

        Assert::stringNotEmpty($args['source']);
        Assert::stringNotEmpty($args['destination']);
        Assert::stringNotEmpty($args['name']);
        Assert::stringNotEmpty($language);

        $sOutput = new SymfonyStyle($input, $output);
        $progressIndicator = new ProgressIndicator(
            $output,
            'normal',
            100,
            ['⠏', '⠛', '⠹', '⢸', '⣰', '⣤', '⣆', '⡇']
        );

        $this->printHeading($output);
        $this->printInfos($output);

        $progressIndicator->start(self::START_TEXT);

        $command = [
            self::SCRIPT_TYPE,
            self::SCRIPT_PATH,
            $args['source'],
            $args['destination'],
            $args['name'],
            $language,
            self::DOKUWIKI_FORMAT_KEY
        ];

        $process = new Process($command);
        $process->start();

        while ($process->isRunning()) {
            $progressIndicator->advance();
            usleep(100000);
        }

        $process->wait();

        if (!$process->isSuccessful()) {
            $sOutput->error(
                sprintf(
                    '%s%s',
                    self::ERROR_TEXT,
                    $process->getErrorOutput()
                )
            );
            return Command::FAILURE;
        }

        $progressIndicator->finish(self::START_TEXT . ' ' . self::END_TEXT);
        $sOutput->success(self::OK_TEXT);

        return Command::SUCCESS;
    }

    protected function configure(): void
    {
        $this->setHelp(self::COMMAND_HELP_TEXT);
        $this->addArgument(
            self::COMMAND_SOURCE_ARGUMENT_KEY,
            InputArgument::REQUIRED,
            self::COMMAND_SOURCE_ARGUMENT_TEXT
        )
            ->addArgument(
                self::COMMAND_DESTINATION_ARGUMENT_KEY,
                InputArgument::REQUIRED,
                self::COMMAND_DESTINATION_ARGUMENT_TEXT
            )
            ->addArgument(
                self::COMMAND_NAME_ARGUMENT_KEY,
                InputArgument::REQUIRED,
                self::COMMAND_NAME_ARGUMENT_TEXT
            )
            ->addArgument(
                self::COMMAND_LANGUAGE_ARGUMENT_KEY,
                InputArgument::OPTIONAL,
                self::COMMAND_LANGUAGE_ARGUMENT_TEXT
            );
    }

    private function printHeading(OutputInterface $output): void
    {
        $figlet = new Figlet();
        $figlet->setJustification(Figlet::JUSTIFICATION_LEFT);
        $figlet->setFont(self::FONT);
        $asciiArt = $figlet->render(self::HEADING_TXT);
        $asciiArt = preg_replace('/\n\s*\n/', "\n", $asciiArt);
        $output->writeln('');
        $output->writeln($asciiArt);
    }

    private function printInfos(OutputInterface $output): void
    {
        $output->writeln(self::INFO_TEXT);
        $output->writeln('');
    }
}

<?php

namespace App\Command;

use App\Repository\ArticlesRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LoadTestArticlesCommand extends Command
{
    private ArticlesRepository $article;

    public function __construct(ManagerRegistry $registry, ArticlesRepository $article)
    {
        parent::__construct();
        $this->article = $article;
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('test:load-articles')
//            // the short description shown while running "php bin/console list"
//            ->setDescription('Prints some text into the console.')
//            // the full command description shown when running the command with
//            // the "--help" option
//            ->setHelp('This command allows you to print some text in the console')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->article->loadTestArticles();
        $output->writeln('OK');

        return 0;
    }
}

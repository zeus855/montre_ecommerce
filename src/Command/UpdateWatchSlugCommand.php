<?php

namespace App\Command;

use App\Entity\Montre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:update-watch-slug',
    description: 'Add a short description for your command',
)]
class UpdateWatchSlugCommand extends Command
{
    private $entityManager;

    public function __construct(
         EntityManagerInterface $entityManager
        )
    {   
        parent::__construct(null);

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $montres = $this->entityManager->getRepository(Montre::class)->findAll();

        foreach($montres as $montre) {
            if (!$montre->getSlug()) {
                $montre->setTitre($montre->getTitre() . '.');

                $this->entityManager->flush();
            }
        }
       
        $io->success('Tous est OKAY !');

        return Command::SUCCESS;
    }
}

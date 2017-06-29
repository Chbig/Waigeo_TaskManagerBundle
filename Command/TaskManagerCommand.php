<?php
namespace Waigeo\TaskManagerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Cette classe permet de gérer la commande qui permet d'exécuter des tâches
 * Class TaskManagerCommand
 * @package Waigeo\TaskManagerBundle\Command
 */
class TaskManagerCommand extends ContainerAwareCommand
{
    use LockableTrait;

    /**
     * Configuration de la commande taskManager:execute
     */
    protected function configure()
    {
        $this->setName('taskManager:execute')
            ->setDescription('Execute la tâche spécifié par son nom')
            ->setHelp("Cette commande permet d'éxécuter une tâche et d'avoir une progression ainsi qu'un historique de cette exécution")
            ->addArgument('taskName', InputArgument::REQUIRED)
            ->addOption('scheduled')
        ;
    }

    /**
     * Méthode exécuté par la commande
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // On recupère le nom de la tâche a éxécuter
        $taskName = $input->getArgument('taskName');
        $isScheduled = $input->getOption('scheduled');

        // Si la tache est déjà en cours d'execution
        // TODO : Loguer le problème
        if (!$this->lock($taskName, true)) {
            $output->writeln('The command is already running in another process.');

            return 0;
        }

        // On recupère le service qui exécute la tâche
        $taskService = $this->getContainer()->get($taskName);
        if($taskService == null)
        {
            $output->writeln('Impossible de trouver le service qui lance la tâche spécifié'. get_class($taskService));
            return 0;
        }
        if($isScheduled)
            $isScheduled = TRUE;
        else
            $isScheduled = FALSE;

        $taskExecutionId = $taskService->prepare($isScheduled);
        $taskService->execute($taskExecutionId);
    }
}
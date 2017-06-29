<?php
namespace Waigeo\TaskManagerBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Waigeo\TaskManagerBundle\Entity\TaskExecutionHistory;
use Waigeo\TaskManagerBundle\Enums\TaskExecutionHistoryMessageTypeEnum;
use Waigeo\TaskManagerBundle\Enums\TaskExecutionStatusEnum;

/**
 * Classe qui gère les fonctions primaire d'une exécution de tache
 * Class BaseTaskManager
 * @package Waigeo\TaskManagerBundle\Service
 */
abstract class BaseTaskManager implements ITaskManager
{
    /**
     * @var Il s'agit du service entitymanager de Doctrine
     */
    protected $em;

    protected $taskExecutionManager;

    protected $task;

    public function __construct(EntityManager $entityManager, TaskExecutionManager $taskExecutionManager, $taskName)
    {
        $this->em = $entityManager;
        $this->taskExecutionManager = $taskExecutionManager;

        $taskRepository = $this->em->getRepository('Waigeo\TaskManagerBundle\Entity\Task');
        $this->task = $taskRepository->findOneBy(array("name" => $taskName));
    }

    /**
     * Méthode à définir, c'est elle qui préparera l'execution de la tache
     * @return mixed
     */
    abstract public function prepare($isScheduledExecution);

    /**
     * Méthode à définir, c'est elle qui préparera l'execution de la tache
     * @return mixed
     */
    protected function finalizePrepare($taskExecution){
        $taskExecution->setStatus(TaskExecutionStatusEnum::Prepared);
        $taskExecution->setProgressCount(0);
        $this->em->persist($taskExecution);
        $this->em->flush();

        return $taskExecution->getId();
    }

    /**
     * Méthode à définir, c'est elle qui executera la tâche en tant que tel
     * @return mixed
     */
    abstract public function execute($taskExecutionId);

    /**
     * Méthode qui finalise l'exécution de la tache
     * @param $taskExecution
     * @param int $status
     */
    public function finalize($taskExecution, $status = 1){
        $taskExecution->setFinishedDate(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));
        $taskExecution->setStatus($status);

        // On persiste l'execution de la tâche en base
        $this->em->persist($taskExecution);

        // On persiste les logs de l'execution en base
        // TODO : reparer
        /*foreach ($taskExecution->getLogMessagesArray() as $log){
            $this->em->persist($log);
        }*/

        // On enregistre tout en base
        $this->em->flush();
    }

    /**
     * Méthode qui augmente la progression d'une execution de tache
     * @param $taskExecution
     * @param $progressValueToAdd
     */
    public function increaseProgress($taskExecution, $progressValueToAdd){
        $newProgression = $taskExecution->getProgressCount() + $progressValueToAdd;

        $taskExecution->setProgressCount($newProgression);
        $taskExecution->setStatus(TaskExecutionStatusEnum::Running);
        $this->em->persist($taskExecution);
        $this->em->flush();
    }

    /**
     * Ajoute un log de type info dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    public function traceInformation($taskExecution, $message){
        $this->trace($taskExecution, $message, TaskExecutionHistoryMessageTypeEnum::Info);
    }

    /**
     * Ajoute un log de type warning dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    public function traceWarning($taskExecution, $message){
        $this->trace($taskExecution, $message, TaskExecutionHistoryMessageTypeEnum::Warning);
    }

    /**
     * Ajoute un log de type erreur dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    public function traceError($taskExecution, $message){
        $this->trace($taskExecution, $message, TaskExecutionHistoryMessageTypeEnum::Error);
    }

    /**
     * Ajoute un log pour l'exécution de la tâche
     * @param $taskExecution Exécution de la tache à loguer
     * @param $message Message du log
     * @param $messageType Type de message
     */
    private function trace($taskExecution, $message, $messageType){
        $taskExecutionHistory = new TaskExecutionHistory();
        $taskExecutionHistory->setCreatedDate(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));
        $taskExecutionHistory->setMessageType($messageType);
        $taskExecutionHistory->setTaskExecution($taskExecution);
        $taskExecutionHistory->setMessage($message);

        // TODO reparer
        //$taskExecution->addLogMessage($taskExecutionHistory);
    }
}
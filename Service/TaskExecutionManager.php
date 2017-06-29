<?php
namespace Waigeo\TaskManagerBundle\Service;

use Doctrine\ORM\EntityManager;

class TaskExecutionManager
{
    /**
     * @var Il s'agit du service entitymanager qui récupère les repositories
     */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Permet de récupérer une exécution de tâche grace à son identifiant
     * @param $taskExecutionId
     * @return null|object
     */
    public function getTaskExecution($taskExecutionId){
        $taskExecutionRepository = $this->em->getRepository('Waigeo\TaskManagerBundle\Entity\TaskExecution');
        $taskExecution = $taskExecutionRepository->find($taskExecutionId);

        // Si on ne trouve pas l'execution, on lève une exception
        if($taskExecution == null){
            throw new Exception("Impossible de récupérer l'exécution de tâche d'identifiant '". $taskExecutionId ."'");
        }

        return $taskExecution;
    }
}
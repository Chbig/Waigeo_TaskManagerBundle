<?php

namespace Waigeo\TaskManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Waigeo\TaskManagerBundle\Enums\TaskExecutionStatusEnum;

/**
 * Agents
 *
 * @ORM\Table(name="taskmanager_task_executions")
 * @ORM\Entity
 */
class TaskExecution
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Waigeo\TaskManagerBundle\Entity\Task")
     */
    private $task;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="startedDate", type="datetime", nullable=false)
     */
    private $startedDate = null;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishedDate", type="datetime", nullable=true)
     */
    private $finishedDate = null;

    /**
     * @var integer
     *
     * @ORM\Column(name="progressCount", type="integer")
     */
    private $progressCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalStepCount", type="integer")
     */
    private $totalStepCount;

    /**
     * Statut de l'execution de la tache.
     * 0 = En cours
     * 1 = Terminé
     * 2 = En erreur
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * Indique s'il s'agit d'une execution automatique (planifié)
     * Si false, il s'agit d'une exécution manuelle
     * @var boolean
     *
     * @ORM\Column(name="isScheduledExecution", type="boolean")
     */
    private $isScheduledExecution;

    /**
     * @var Permet de stocker les logs d'une exécution
     * ATTENTION : N'est pas persisté en base de données sur cette entité
     */
    private $logMessagesArray;


    public function __construct(Task $task, $totalStepCount, $isScheduledExecution = FALSE)
    {
        // Initialize
        $this->setTask($task);
        $this->setIsScheduledExecution($isScheduledExecution);
        $this->setStartedDate(new \DateTime('NOW', new \DateTimeZone('Europe/Paris')));
        $this->setTotalStepCount($totalStepCount);
        $this->setStatus(TaskExecutionStatusEnum::Running);
        $this->logMessagesArray = array();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set task
     *
     * @param string $task
     *
     * @return TaskExecution
     */
    public function setTask(Task $task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set startedDate
     *
     * @param string $startedDate
     *
     * @return TaskExecution
     */
    public function setStartedDate($startedDate)
    {
        $this->startedDate = $startedDate;

        return $this;
    }

    /**
     * Get startedDate
     *
     * @return string
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * Set finishedDate
     *
     * @param string $finishedDate
     *
     * @return TaskExecution
     */
    public function setFinishedDate($finishedDate)
    {
        $this->finishedDate = $finishedDate;

        return $this;
    }

    /**
     * Get finishedDate
     *
     * @return string
     */
    public function getFinishedDate()
    {
        return $this->finishedDate;
    }

    /**
     * Defini la progression de la tache
     *
     * @param string $progressCount
     *
     * @return TaskExecution
     */
    public function setProgressCount($progressCount)
    {
        $this->progressCount = $progressCount;

        return $this;
    }

    /**
     * Recupere la progression actuel de l'execution de la tache
     *
     * @return string
     */
    public function getProgressCount()
    {
        return $this->progressCount;
    }

    /**
     * Defini le nombre total d'étape dans cette exécution de tache
     *
     * @param string $progressCount
     *
     * @return TaskExecution
     */
    public function setTotalStepCount($totalStepCount)
    {
        $this->totalStepCount = $totalStepCount;

        return $this;
    }

    /**
     * Recupere le nombre total d'étape dans cette éxécution de tâche
     *
     * @return string
     */
    public function getTotalStepCount()
    {
        return $this->totalStepCount;
    }

    /**
     * Set status
     * @param string $progressCount
     *
     * @return TaskExecution
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Recupere le statut de l'execution de la tache
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set isScheduledExecution
     * @param string $isScheduledExecution
     *
     * @return TaskExecution
     */
    public function setIsScheduledExecution($isScheduledExecution)
    {
        $this->isScheduledExecution = $isScheduledExecution;

        return $this;
    }

    /**
     *
     * @return int
     */
    public function getIsScheduledExecution()
    {
        return $this->isScheduledExecution;
    }

    /**
     * Permet d'ajouter un log à l'éxécution de la tache
     * @param $taskExecutionHistory
     */
    public function addLogMessage($taskExecutionHistory)
    {
        array_push($this->logMessagesArray, $taskExecutionHistory);
    }

    /**
     * Permet de récupérer les logs de l'éxécution
     * @return int
     */
    public function getLogMessagesArray()
    {
        return $this->logMessagesArray;
    }
}

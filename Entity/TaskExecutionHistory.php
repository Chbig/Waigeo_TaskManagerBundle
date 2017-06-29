<?php

namespace Waigeo\TaskManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agents
 *
 * @ORM\Table(name="taskmanager_task_execution_histories")
 * @ORM\Entity
 */
class TaskExecutionHistory
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
     * @ORM\ManyToOne(targetEntity="Waigeo\TaskManagerBundle\Entity\TaskExecution")
     */
    private $taskExecution;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdDate", type="datetime", nullable=false)
     */
    private $createdDate = null;

    /**
     * Représente le type de message
     * 0 = Info
     * 1 = Warning
     * 2 = Error
     * @var integer
     *
     * @ORM\Column(name="messageType", type="integer")
     */
    private $messageType;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=false)
     */
    private $message = null;


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
     * Set taskExecution
     *
     * @param string $taskExecution
     *
     * @return TaskExecutionHistory
     */
    public function setTaskExecution(TaskExecution $taskExecution)
    {
        $this->taskExecution = $taskExecution;

        return $this;
    }

    /**
     * Get taskExecution
     *
     * @return string
     */
    public function getTaskExecution()
    {
        return $this->taskExecution;
    }

    /**
     * Set createdDate
     *
     * @param string $createdDate
     *
     * @return TaskExecutionHistory
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return string
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set messageType
     *
     * @param int $messageType
     *
     * @return TaskExecutionHistory
     */
    public function setMessageType($messageType)
    {
        $this->messageType = $messageType;

        return $this;
    }

    /**
     * Get messageType
     *
     * @return int
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return TaskExecutionHistory
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: Dev
 * Date: 20/06/2017
 * Time: 17:17
 */

namespace Waigeo\TaskManagerBundle\Enums;

/**
 * Représente les différents type de message d'un log d'exécution de tâche
 * Class TaskExecutionHistoryMessageTypeEnum
 * @package Waigeo\TaskManagerBundle\Enums
 */
class TaskExecutionHistoryMessageTypeEnum
{
    const Info = 0;
    const Warning = 1;
    const Error = 2;
}
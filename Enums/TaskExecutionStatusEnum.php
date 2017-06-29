<?php
/**
 * Created by PhpStorm.
 * User: Dev
 * Date: 20/06/2017
 * Time: 17:17
 */

namespace Waigeo\TaskManagerBundle\Enums;

/**
 * Représente les différents statuts d'éxécution d'une tâche
 * Class TaskExecutionStatusEnum
 * @package Waigeo\TaskManagerBundle\Enums
 */
class TaskExecutionStatusEnum
{
    const Running = 0;
    const Finished = 1;
    const Error = 2;
    const Prepared = 3;
}
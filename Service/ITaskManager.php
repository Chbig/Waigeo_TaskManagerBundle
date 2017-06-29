<?php
namespace Waigeo\TaskManagerBundle\Service;

interface ITaskManager
{
    /**
     * Méthode qui préparera l'execution de la tache
     * @return mixed
     */
    function prepare($isScheduledExecution);

    /**
     * Méthode qui executera la tâche en tant que tel
     * @return mixed
     */
    function execute($taskExecutionId);

    /**
     * Méthode qui finalise l'exécution de la tache
     * @param $taskExecution
     * @param int $status
     */
    function finalize($taskExecution, $status);

    /**
     * Méthode qui augmente la progression d'une execution de tache
     * @param $taskExecution
     * @param $progressValueToAdd
     */
    function increaseProgress($taskExecution, $progressValueToAdd);

    /**
     * Ajoute un log de type info dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    function traceInformation($taskExecution, $message);

    /**
     * Ajoute un log de type warning dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    function traceWarning($taskExecution, $message);

    /**
     * Ajoute un log de type erreur dans l'exécution de la tâche
     * @param $taskExecution
     * @param $message
     */
    function traceError($taskExecution, $message);
}
<?php

namespace Waigeo\TaskManagerBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Waigeo\TaskManagerBundle\Command\TaskManagerCommand;
use Waigeo\TaskManagerBundle\Model\ServiceResponse;


class TaskManagerController extends Controller
{
    /**
     * Prépare une execution pour la tache spécifié
     * @Route("/waigeo/taskmanager/preparetaskexecution", name="waigeoTaskManagerPrepareTaskExecution", options={"expose"=true}, condition="request.isXMLHTTPRequest()")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function prepareTaskExecution(Request $request){
        $response = new ServiceResponse();
        $serializer = $this->container->get('waigeo_task_manager.serializer');

        try{
            $taskName = $request->query->get('taskName');
            $taskManager = $this->container->get($taskName);
            $taskExecutionId = $taskManager->prepare(FALSE);

            $response->setData($taskExecutionId);
        }
        catch(Exception $e){
            $response->setSuccess(false);
        }

        $response = $serializer->serialize($response, 'json');
        return JsonResponse::fromJsonString($response);
    }

    /**
     * Lance l'execution de la tache spécifié
     * @Route("/waigeo/taskmanager/executetaskexecution", name="waigeoTaskManagerExecuteTaskExecution", options={"expose"=true}, condition="request.isXMLHTTPRequest()")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function executeTaskExecution(Request $request){
        $response = new ServiceResponse();
        $serializer = $this->container->get('waigeo_task_manager.serializer');

        try{
            // On recupere l'identifiant de l'execution de la tache ainsi que le nom de la tache a executer
            $taskExecutionId = $request->query->get('taskExecutionId');
            $taskName = $request->query->get('taskName');
            // On recupere le service qui va executer la tache
            $taskManager = $this->container->get($taskName);
            // On lance l'execution de la tache
            $taskManager->execute($taskExecutionId);

            $response->setData(true);
        }
        catch(Exception $e){
            $response->setSuccess(false);
        }

        $response = $serializer->serialize($response, 'json');
        return JsonResponse::fromJsonString($response);

        return new Response("");
    }

    /**
     * Suit la progression de l'execution de la tache spécifié
     * @Route("/waigeo/taskmanager/followtaskexecution", name="waigeoTaskManagerFollowTaskExecution", options={"expose"=true}, condition="request.isXMLHTTPRequest()")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function followTaskExecution(Request $request){
        $response = new ServiceResponse();
        $serializer = $this->container->get('waigeo_task_manager.serializer');
        $taskExecutionManager = $this->container->get('waigeo_task_manager.task_execution_manager');

        try{
            $taskExecutionId = $request->query->get('taskExecutionId');
            $response->setData($taskExecutionManager->getTaskExecution($taskExecutionId));
        }
        catch(Exception $e){
            $response->setSuccess(false);
        }

        $response = $serializer->serialize($response, 'json');
        return JsonResponse::fromJsonString($response);
    }
}
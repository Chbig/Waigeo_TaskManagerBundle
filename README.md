## Description
Ce bundle permet d'exécuter des tâches, et de garder un historique de l'exécution des tâches. Pour chaque exécution d'une tâche, on peut également visualiser ces logs. Les tâches peuvent être lancer via un controller ou via une commande. L'API fourni permet de facilement mettre en place ce système.    
Pour résumer :    
*  Exécution de taches via Controller, Service, ou Commande
*  Planification de taches via l'utilisation de la commande
*  Historisation des exécutions
*  Logs des exécutions

## Installation

1. `composer require waigeo/taskmanagerbundle`

2. Enregistrer le bundle dans le AppKernel   
```php
public function registerBundles()
{
	$bundles = [
		...
		new Waigeo\TaskManagerBundle\WaigeoTaskManagerBundle(),
		...
	];

	return $bundles;
}
```

3. Importer les routes du bundle. Dans "app/config/routing.yml" ajouter le bloc suivant
```yml
waigeo_taskmanager_routing:
    resource: "@WaigeoTaskManagerBundle/Controller/"
    type: annotation   
```

4. Mettre à jour votre schéma de base de données en exécutant la commande  
`php bin/console doctrine:schema:update --dump-sql`  
puis  
`php bin/console doctrine:schema:update --force`

## Utilisation
### Création d'une nouvelle tâche
1. Déclarer votre tache en base de données dans la table 'taskmanager_tasks'  
* Donner lui un nom.  **ATTENTION :** Le nom de votre tâche sera également le nom du service qui exécutera cette tâche.  
* Donner lui une description   

Dans notre exemple nous créons une tache d'export que nous nommerons 'cdg.export_agents_task_manager'  

### Implémentation du service qui exécutera la tâche   
Exemple de service :  
```php
<?php
namespace CDG\AppBundle\Service\Tasks;

use Symfony\Component\Config\Definition\Exception\Exception;
use Waigeo\TaskManagerBundle\Entity\TaskExecution;
use Waigeo\TaskManagerBundle\Enums\TaskExecutionStatusEnum;
use Waigeo\TaskManagerBundle\Service\BaseTaskManager;

/**
 * Service gérant l'exécution de la tache d''export des agents
 */
class ExportAgentTaskManager extends BaseTaskManager
{
    /**
     * Méthode qui prépare une exécution de la tache
     * @param bool $isScheduledExecution
     * @return mixed
     */
    public function prepare($isScheduledExecution = FALSE)
    {
        // On recupere le nombre d'éléments à traiter (à exporter dans notre cas)
        // ... on imagine recupérer le nombre d'agent à exporter (soit 20145 dans notre exemple)
        
        // On déclare une nouvelle execution de tache
        $taskExecution = new TaskExecution($this->task, 20145, $isScheduledExecution);

        // On finalise la préparation, ce qui permet de récupérer un identifiant d'exécution
        return $this->finalizePrepare($taskExecution);
    }

    /**
     * Méthode qui exécute la tache
     */
    public function execute($taskExecutionId)
    {
        try{
            // On récupère l'execution de la tache que l'on a préparé au préalable
            $taskExecution = $this->taskExecutionManager->getTaskExecution($taskExecutionId);

            
            for ($i = 1; $i <= 20145; $i++) {
                // ... on imagine la génération d'un export Excel des 20 145 agents

                // Tous les 1000 agents exporter dans un fichier Excel
                if($i % 1000 == 0){
                    // On peut loguer des choses lors de l'exécution
                    $this->traceInformation($taskExecution, "1000 agents ont été exporté");
                    // On indique à l'exécution qu'elle a progressé de 1000
                    $this->increaseProgress($taskExecution, 1000);
                }
            }

            // IMPORTANT : On finalise l'execution de la tache
            $this->finalize($taskExecution);
        }
        catch (Exception $e){
            // En cas d'erreur, on finalise l'exécution de la tache en spécifiant qu'une erreur s'est produite
            $this->finalize($taskExecution, TaskExecutionStatusEnum::Error);
        }
    }
}
```   
2. Implémenter le service qui exécutera la tâche   
* Votre service doit hériter de BaseTaskManager
* Il doit implémenter une méthode 'prepare' qui va créer une instance de TaskExecution et finaliser la préparation en appelant la méthode 'finalizePrepare'
* Il doit implémenter une méthode 'execute' qui va effectuer votre tâche (export, import, notification ...)   

Liste des méthodes accessibles héritées de BaseTaskManager :   
* * finalizePrepare($taskExecution) : Permet de persister en base l'exécution de tâche spécifier en paramètre.
* * traceInformation($taskExecution, $message) : Permet de loguer un message d'information sur l'exécution de tâche spécifié   
* * traceWarning($taskExecution, $message) : Permet de loguer un message d'avertissement sur l'exécution de tâche spécifié   
* * traceError($taskExecution, $message) : Permet de loguer un message d'erreursur l'exécution de tâche spécifié   
* * finalize($taskExecution, $status) : Permet de finaliser l'exécution d'une tâche en base.   

Liste des propriétés accessibles héritées de BaseTaskManager :   
* * task : Il s'agit de la tâche que vous avez déclaré. Celle qui devra être exécuté par le service   
* * taskExecutionManager : Il s'agit d'un service gérant les différentes exécutions de toutes les tâches. Il permet entre autre de récupérer une exécution de tâche spécifié par son identifiant

L'exemple donner un peu plus haut vous donne un modèle sur lequel vous pouvez vous appuyer   

3. Déclarer le service  
Dans 'app/config/service.yml', déclarer le service comme ci-dessous :   
```yml
cdg.export_agents_task_manager:
                class: CDG\AppBundle\Service\Tasks\ExportAgentTaskManager
                arguments: ['@doctrine.orm.entity_manager', '@waigeo_task_manager.task_execution_manager', 'cdg.export_agents_task_manager']
```   
Vous remarquerez que votre service doit obligatoirement recevoir en paramètre les services 'doctrine.orm.entity_manager' et '@waigeo_task_manager.task_execution_manager' ainsi que le nom de votre tache. Dans notre exemple 'cdg.export_agents_task_manager'.   

4. Exécuter une tâche  
Pour exécuter votre tâche vous pouvez soit appeler successivement la méthode 'prepare' et 'execute' de votre service.   
Vous pouvez également exécuter le service depuis la commande suivante :  
`php bin/console taskManager:execute cdg.export_agents_task_manager`   
Vous pouvez ainsi planifier l'exécution de votre tâche comme vous le souhaitez en exécutant cette commande depuis une tâche planifié windows ou une cronTab.   

Vous pouvez spécifier l'option --scheduled à la commande pour que le TaskManager sache que l'exécution est lancé de façon 'automatique'.   
`php bin/console taskManager:execute cdg.export_agents_task_manager --scheduled`   

## Librairie Javascript   
Le bundle fourni également une librairie Javascript afin de préparer et exécuter une tâche.   

1. Installation   
* Exécuter la commande suivante :   
`php app/console assets:install --symlink`   

* Dans la vue ou vous devez exécuter une tâche, inclure les scripts suivant : 
```twig
<script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
<script src="{{ path('fos_js_routing_js', { callback: 'fos.Router.setData' }) }}"></script>
<script src="{{ asset('bundles/waigeotaskmanager/waigeoTaskExecutionsManager.js') }}"></script>
```   

2. Utilisation   
Exemple :   
```js
waigeoTaskExecutionsManager.prepareTaskExecution(
    "cdg.export_agents_task_manager",
    function(id){
        // On execute la tache
        waigeoTaskExecutionsManager.executeTaskExecution(
            "cdg.export_agents_task_manager",
            id,
            function(taskExecution){
                console.log("L'execution de la tache est terminé")
            },
            function(){
                console.log("Une erreur s'est produite lors de l'execution de la tache");
            }
        );
    },
    function(){
        console.log("Une erreur s'est produite lors de la préparation de la tache");
    }
);
```   

Méthodes disponibles :   
* waigeoTaskExecutionsManager.prepareTaskExecution(taskName, sucessCallback, errorCallback) : Cette méthode prépare l'exécution de la tache spécifié. Vous pouvez spécifier une méthode à appeler en cas de succès et en cas d'erreur.   

* waigeoTaskExecutionsManager.executeTaskExecution(taskName, taskExecutionId, sucessCallback, errorCallback) : Cette méthode lance l'exécution de tâche spécifié. Vous pouvez spécifier une méthode à appeler en cas de succès et en cas d'erreur.






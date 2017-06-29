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
## Utilisation


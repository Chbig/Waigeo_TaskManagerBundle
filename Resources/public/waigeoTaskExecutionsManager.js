/**
 * Permet de gérer les exécutions de taches
 */
var waigeoTaskExecutionsManager = {
    /**
     * Initialise le composant de gestion des exécutions de tâche
     */
    init: function () {
        var me = this;

        me.urls = {
            prepareTask: Routing.generate('waigeoTaskManagerPrepareTaskExecution'),
            executeTask: Routing.generate('waigeoTaskManagerExecuteTaskExecution'),
            followTask: Routing.generate('waigeoTaskManagerFollowTaskExecution')
        };
    },

    /**
     * Permet de préparer une tache à s'exécuter
     * @param taskName
     */
    prepareTaskExecution: function(taskName, successCallback, errorCallback){
        var me = this;

        $.ajax(me.urls.prepareTask + "?taskName=" + taskName, {
            type: "GET",
            success: function (response) {
                successCallback(response.data);
            },
            error: function (xhr, s, arg3) {
                errorCallback();
            }
        });
    },

    /**
     * Permet de lancer l'execution de la tache spécifié
     * @param taskName
     * @param taskExecutionId
     * @param successCallback
     * @param errorCallback
     */
    executeTaskExecution: function(taskName, taskExecutionId, successCallback, errorCallback){
        var me = this;

        $.ajax(me.urls.executeTask + "?taskName=" + taskName + "&taskExecutionId=" + taskExecutionId, {
            type: "GET",
            success: function (response) {
                successCallback(response.data);
            },
            error: function (xhr, s, arg3) {
                errorCallback();
            }
        });
    },

    /**
     * Suit la progression d'une execution de tache jusqu'à se qu'elle soit terminé
     * @param taskExecutionId Identifiant de l'exécution de la tache
     * @param onProgressCallback Callback à appeller a chaque fois que l'execution progresse
     * @param errorCallback Callback à appeller en cas d'erreur
     * @param onTaskExecutionFinishedCallback Callback à appeller  à la fin de l'exécution
     */
    followTaskExecutionUntilTheEnd: function(taskExecutionId, onProgressCallback, errorCallback, onTaskExecutionFinishedCallback){
        var me  = this;

        // Toutes les 3 secondes, on demande à suivre la progression de l'execution
        var intervalId = window.setInterval(function(){
            me.followTaskExecution(taskExecutionId, function(taskExecution){
                // Si la tache est terminé ou en erreur
                if(taskExecution.status == 1 || taskExecution.status == 2){
                    // On appel le callback de fin de tache en lui passant l'etat de l'execution
                    onTaskExecutionFinishedCallback(taskExecution);
                    // On arrête de suivre la tache
                    window.clearInterval(intervalId);
                }

                // Si la tache est prête à etre lancé ou en cours
                if(taskExecution.status == 3 || taskExecution.status == 0){
                    // On appel le callback de progression en lui passant l'etat de l'execution
                    onProgressCallback(taskExecution);
                }
            }, errorCallback);
        }, 3000);
    },

    /**
     * Suit l'execution de la tache spécifié
     * @param taskExecutionId
     * @param successCallback
     * @param errorCallback
     */
    followTaskExecution: function(taskExecutionId, successCallback, errorCallback){
        var me = this;

        $.ajax(me.urls.followTask + "?taskExecutionId=" + taskExecutionId, {
            type: "GET",
            success: function (response) {
                successCallback(response.data);
            },
            error: function (xhr, s, arg3) {
                errorCallback();
            }
        });
    }
};

waigeoTaskExecutionsManager.init();

/*
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

        // On demande à suivre l'execution (il se peut qu'à se moment elle ne soit pas encore démarré
        waigeoTaskExecutionsManager.followTaskExecutionUntilTheEnd(id,
            function(taskExecution){
                console.log(taskExecution.progressCount)
            },
            function(){
                alert("une erreur s'est produite")
            },
            function(taskExecution){
                console.log("Fin de l'execution")
            }
        );
    },
    function(){
        console.log("Une erreur s'est produite lors de la préparation de la tache");
    }
);*/

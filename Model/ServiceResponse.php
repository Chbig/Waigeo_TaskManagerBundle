<?php

namespace Waigeo\TaskManagerBundle\Model;

/**
 * Cette classe représente la réponse d'un service
 * Class ServiceResponse
 * @package CDG\AppBundle\Model
 */
class ServiceResponse
{
    /**
     * @var bool Indique si tout s'est bien passé pendant l'éxécution du service
     */
    protected $success;

    /**
     * @var null Si le service doit retourner une réponse, il s'agit du contenu de la réponse
     */
    protected $data = null;

    /**
     * @var Message retourner par le service
     * (Peut indiquer la cause d'une erreur par exemple)
     */
    protected $message;

    public function __construct()
    {
        $this->success = true;
    }

    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    public function getSuccess(){
        return $this->success;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData(){
        return $this->data;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getMessage(){
        return $this->message;
    }
}
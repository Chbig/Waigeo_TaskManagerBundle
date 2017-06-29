<?php
namespace Waigeo\TaskManagerBundle\Service\Utils;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class JsonSerializer
{
    protected $serializer;

    public function __construct()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * Serialize l'objet spécifié en JSON
     * @param $objectToSerialize Objet à sérializer en JSON
     * @return Une représentation JSON de l'objet spécifié
     */
    public function serialize($objectToSerialize)
    {
        $jsonContent = $this->serializer->serialize($objectToSerialize, 'json');

        return $jsonContent;
    }
}
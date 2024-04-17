<?php

namespace App\Controller;

use App\Entity\Animal;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AnimalsController extends AbstractController
{

    public function listAllAnimals(EntityManagerInterface $entityManager): JsonResponse{
        $animals = $entityManager->getRepository(Animal::class)->findAll();
        $response = new JsonResponse('No Animal found');
        if (!$animals) {
            $response->setStatusCode(400);
            return $response;

            /*throw $this->createNotFoundException(
                'No Animal found'
            );*/
        }
        $json = [];
        foreach ($animals as $animal){
            $json[] = $animal->getJson();
        }
        return new JsonResponse(
            json_encode($json)
        );
    }
    public function getAnimalById(EntityManagerInterface $entityManager, int $id): JsonResponse{
        //echo $id;
        $animal = $entityManager->getRepository(Animal::class)->find($id);
        $response = new JsonResponse('No Animal found for id '.$id);
        if (!$animal) {
            $response->setStatusCode(400);
            return $response;
        }
        //echo $animal->getJson();
        return new JsonResponse(json_encode($entityManager->getRepository(Animal::class)->find($id)->getjson()));
    }
    public function  listAllAnimalsByCountry (EntityManagerInterface $entityManager, int $country): JsonResponse{
        $animals = $entityManager->getRepository(Animal::class)->findBy(['country' => $country]);
        $json = [];
        $response = new JsonResponse('No Animal found for country id '.$country);
        if (!$animals) {
            $response->setStatusCode(400);
            return $response;
        }
        else{
            foreach ($animals as $animal){
                $json[] = $animal->getJson();
            }
        }
        return new JsonResponse(
            json_encode($json)
        );
    }
    public function AddAnimal(Request $request, EntityManagerInterface $entityManager): JsonResponse{
        //, string $name, int $height, int $country, int $lifetime, string $martialArt, string $telephone
        $animal = new Animal();
        $animal->setName($request->query->get('name'));
        $animal->setHeight($request->query->get('height'));
        $animal->setCountry($request->query->get('country'));
        $animal->setLifetime($request->query->get('lifetime'));
        $animal->setMartialArt($request->query->get('martialArt'));
        $animal->setTelephone($request->query->get('telephone'));

        $entityManager->persist($animal);
        $entityManager->flush();
        return new JsonResponse('Animal added');
    }
    public function updateAnimal(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse{

        $animal = $entityManager->getRepository(Animal::class)->find($id);
        $response = new JsonResponse('No Animal found for id '.$id);
        if (!$animal) {
            $response->setStatusCode(400);
            return $response;
        }
        $animal->setName($request->query->get('name'));
        $animal->setHeight($request->query->get('height'));
        $animal->setCountry($request->query->get('country'));
        $animal->setLifetime($request->query->get('lifetime'));
        $animal->setMartialArt($request->query->get('martialArt'));
        $animal->setTelephone($request->query->get('telephone'));
        $entityManager->flush();
        return new JsonResponse(
            'Animal updated'
        );
    }
    public function updateAnimalCountry(Request $request, EntityManagerInterface $entityManager, int $id): JsonResponse{
        $country = $request->query->get('country');
        $animal = $entityManager->getRepository(Animal::class)->find($id);
        $response = new JsonResponse('No Animal found for id '.$id);
        if (!$animal) {
            $response->setStatusCode(400);
            return $response;
        }
        $animal->setCountry($country);
        $entityManager->flush();
        return new JsonResponse(
            'Animal Country updated'
        );
    }
    public function deleteAnimalById(EntityManagerInterface $entityManager, int $id): JsonResponse{
        $animal = $entityManager->getRepository(Animal::class)->find($id);
        $response = new JsonResponse('No Animal found for id '.$id);
        if (!$animal) {
            $response->setStatusCode(400);
            return $response;
        }
        $entityManager->remove($animal);
        $entityManager->flush();
        return new JsonResponse(
            'Animal deleted'
        );
    }
}

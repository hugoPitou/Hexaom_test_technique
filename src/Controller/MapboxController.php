<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContactRepository;
use App\Service\CallApiService;

class MapboxController extends AbstractController
{

    #[Route('/map', name: 'app_contact_map')]
    public function map(ContactRepository $contactRepository, CallApiService $callApiService): Response
    {
        $contacts = $contactRepository->findAll();
        //$allCoordinate = [];

        foreach ($contacts as $contact) {
            $address = $contact->getFullAddress();
            $fullname = $contact->getFullname();
            $dep = substr($contact->getZipcode(), 0, 2);

            if ($address != "  ") {
            $apiresult = $callApiService->getCoordinate($address);
                $coordinates = $apiresult['features'][0]['geometry']['coordinates'];
                // Vérifiez si le nom complet existe déjà dans $allCoordinate
                if (!isset($allCoordinates[$dep][$fullname])) {
                    // Si non, créez un nouveau tableau avec l'adresse
                    $allCoordinates[$dep][$fullname] = [];
                }
                // Ajoutez l'adresse au tableau correspondant au nom complet
                $allCoordinates[$dep][$fullname][0] = $coordinates[0];
                $allCoordinates[$dep][$fullname][1] = $coordinates[1];
            }
        }

        $jsonCoordination = json_encode($allCoordinates);
        //dd($jsonCoordination);
        return $this->render('mapbox/map.html.twig', [
            'allCoordinates' => $jsonCoordination,
        ]);

    }
}

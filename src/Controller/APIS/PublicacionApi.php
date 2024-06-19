<?php

namespace App\Controller\APIS;

use App\Repository\PublicacionRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicacionApi extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security=$security;
    }
    #[Route('/apis/publicaciones', name: 'app_apis_publicaciones',methods:['GET'])]
    public function get(PublicacionRepository $publicacionRepository): JsonResponse
    {
        $publicaciones=$publicacionRepository->findAll();
        $data=[];
        if ($publicaciones){
            foreach ($publicaciones as $publicacion){
                $data[]=[
                    
                ];
            }
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }

    #[Route('/apis/publicacion/{id}', name: 'app_apis_publicacionid',methods:['GET'])]
    public function getiDs(PublicacionRepository $publicacionRepository,$id): JsonResponse
    {
        $publicacion=$publicacionRepository->find($id);
        if ($publicacion){
                $data=[
                    'foto'=>$publicacion->getArchivos()
                ];
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }

}


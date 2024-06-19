<?php

namespace App\Controller\APIS;

use App\Entity\Comentario;
use App\Entity\Mensaje;
use App\Repository\ComentarioRepository;
use App\Repository\PublicacionRepository;
use App\Repository\RutaRepository;
use App\Repository\TourRepository;
use App\Repository\UserRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComentarioApi extends AbstractController
{
    private Security $security;
    public function __construct(Security $security){
        $this->security=$security;
    }
    #[Route('/apis/comentarios/{publicacion_id}', name: 'app_apis_comentarios',methods:['GET'])]
    public function get(ComentarioRepository $comentarioRepository,$publicacion_id,PublicacionRepository $publicacionRepository): JsonResponse
    {
        $comentarios=$comentarioRepository->findBy(["publicacion"=>$publicacionRepository->find($publicacion_id)]);
        $data=[];
        if ($comentarios){
            foreach ($comentarios as $comentario){
                $interacciones=[];
                foreach ($comentario->getInteracciones() as $interaccion){
                    $interacciones[]=[
                        'id'=>$interaccion->getId(),
                        'nombre'=>$interaccion->getNombre()
                    ];
                }
                $data[]=[
                    'id'=>$comentario->getId(),
                    'publicacion'=>[
                        'id'=>$comentario->getPublicacion()->getId(),
                        'archivos'=>$comentario->getPublicacion()->getArchivos(),
                        'usuario'=>[
                            'id'=>$comentario->getPublicacion()->getUsuario()->getId(),
                            'email'=>$comentario->getPublicacion()->getUsuario()->getEmail(),
                            'roles'=>$comentario->getPublicacion()->getUsuario()->getRoles(),
                            'usuario'=>$comentario->getPublicacion()->getUsuario()->getUsuario(),
                            'genero'=>$comentario->getPublicacion()->getUsuario()->getGenero(),
                            'nombre_completo'=>$comentario->getPublicacion()->getUsuario()->getNombreCompleto(),
                            'foto'=>$comentario->getPublicacion()->getUsuario()->getFoto(),
                            'descripcion'=>$comentario->getPublicacion()->getUsuario()->getDescripcion()
                        ]
                    ],
                    'interacciones'=>$interacciones,
                    'texto'=>$comentario->getTexto(),
                    'usuario'=>[
                        'id'=>$comentario->getUsuario()->getId(),
                        'email'=>$comentario->getUsuario()->getEmail(),
                        'roles'=>$comentario->getUsuario()->getRoles(),
                        'usuario'=>$comentario->getUsuario()->getUsuario(),
                        'genero'=>$comentario->getUsuario()->getGenero(),
                        'nombre_completo'=>$comentario->getUsuario()->getNombreCompleto(),
                        'foto'=>$comentario->getUsuario()->getFoto(),
                        'descripcion'=>$comentario->getUsuario()->getDescripcion()
                    ]
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
                    
                ];
            return $this->json($data,200);
        }else{
            return $this->json(["error"=>"Error"]);
        }
    }


    #[Route('/addcomentario/{id_publicacion}', name: 'add_comentario',methods:['POST'])]
    public function addComentario(UsuarioRepository $usuarioRepository,Security $security,Request $request,PublicacionRepository $publicacionRepository,$id_publicacion,EntityManagerInterface $entityManagerInterface): JsonResponse
    {
        $publicacion=$publicacionRepository->find($id_publicacion);
        $mensajejson=json_decode($request->getContent(),true);
        $comentario=new Comentario();
        $usuario=$usuarioRepository->find($security->getUser());
        $comentario->setPublicacion($publicacion);
        $comentario->setTexto($mensajejson['mensaje']);
        $comentario->setUsuario($usuario);
        $entityManagerInterface->persist($comentario);
        $entityManagerInterface->flush();
        return $this->json(["mensaje"=>"comentario agregado"],200);
    }

}
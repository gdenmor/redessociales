<?php

namespace App\Controller\APIS;

use App\Entity\Publicacion;
use App\Repository\HistoriaRepository;
use App\Repository\PublicacionRepository;
use App\Repository\TiposPublicacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiPublicacion extends AbstractController{
    #[Route('/api/addpublicacion', name: 'app_api_vivienda',methods: ['POST'])]
    public function index(ValidatorInterface $validatorInterface,TiposPublicacionRepository $tiposPublicacionRepository,Request $request,EntityManagerInterface $entityManagerInterface,Security $security,UsuarioRepository $usuarioRepository)
    {
        $json= json_decode($request->get('publicacion'),true);
        $publicacion=new Publicacion();
        $publicacion->setDescripcion($json['descripcion']);
        $publicacion->setTipo($tiposPublicacionRepository->find($json['tipo_id']));
        $publicacion->setUsuario($usuarioRepository->find($security->getUser()));

        $public = $this->getParameter('kernel.project_dir');

        $archivos = $request->files->get('archivos');
    
        if ($archivos) {
            $public = $this->getParameter('kernel.project_dir');
            $files=[];

            $directory = $public . '/imagenes/';

            // Iterar sobre cada archivo de imagen
            foreach ($archivos as $archivo) {
                $nombreArchivo = $archivo->getClientOriginalName();
                $archivo->move($directory, $nombreArchivo);

                // Obtener la ruta completa del archivo
                $rutaCompleta = $directory . $nombreArchivo;

                // Agregar el nombre del archivo a la lista de archivos
                $files[] = $nombreArchivo;
            }
            $publicacion->setArchivos($files);
        }

        if (count($validatorInterface->validate($publicacion))>0) {
            return $this->json(["error"=>$validatorInterface->validate($publicacion)],500);
        }else{
            $entityManagerInterface->persist($publicacion);
            $entityManagerInterface->flush();

            return $this->json(["exito"=>"Publicacion creada"],201);
        }
    }

    #[Route('/historia/{id}',name: 'fotos',methods:['GET'])]
    public function historia(Request $request,PublicacionRepository $publicacionRepository,$id){
        $historia=$publicacionRepository->find($id);
        $data=[];
        if ($historia){
                $data[]=[
                    'id'=>$historia->getId(),
                    'fotos'=>$historia->getArchivos()
                ];

            return $this->json($data,200);
        }else{
            return $this->json(["Vacio"=>"No content"],204);
        }
    }
    


}
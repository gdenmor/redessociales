<?php
namespace App\Controller\APIS;

use App\Entity\Mensaje;
use App\Repository\MensajeRepository;
use App\Repository\UsuarioRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ApiMensajes extends AbstractController{
    #[Route("/mensajes/{id_emisor}/{id_receptor}","muestra_comentarios",methods:['GET'])]
    public function muestra_comentarioss($id_emisor,$id_receptor,UsuarioRepository $usuarioRepository,MensajeRepository $mensajeRepository){
        $mensajes=$mensajeRepository->mensajes($id_emisor,$id_receptor,$usuarioRepository);
        if ($mensajes){
            $data=[];
            for ($i=0;$i<count($mensajes);$i++){
                $data[]=[
                    'id'=>$mensajes[$i]->getId(),
                    'emisor'=>[
                        'id'=>$mensajes[$i]->getEmisor()->getId(),
                        'foto'=>$mensajes[$i]->getEmisor()->getFoto(),
                        'usuario'=>$mensajes[$i]->getEmisor()->getUsuario()
                    ],
                    'receptor'=>[
                        'id'=>$mensajes[$i]->getReceptor()->getId(),
                        'foto'=>$mensajes[$i]->getReceptor()->getFoto(),
                        'usuario'=>$mensajes[$i]->getReceptor()->getUsuario()
                    ],
                    'mensaje'=>$mensajes[$i]->getMensaje(),
                    'fecha'=>$mensajes[$i]->getFechaMensaje(),
                    'reportado'=>$mensajes[$i]->isReportado()
                ];
            }
            return $this->json($data,200);
        }else{
            return $this->json([
               'mensaje'=>'No hay mensajes'
               ,200,
               [
                'Content-Type'=>'application/json',
                'Cache-Control'=> 'no-cache',
                'Connection'=> 'keep-alive'
               ]
            ]);
        }
    }

    #[Route("/busqueda/{busqueda}","muestra_receptor",methods:['GET'])]
    public function muestra_receptor($busqueda,UsuarioRepository $usuarioRepository,MensajeRepository $mensajeRepository){
        $usuario=$usuarioRepository->findOneBy(["usuario"=>$busqueda]);
        if ($usuario){
            $data=[];
            $data[]=[
                'id'=>$usuario->getId(),
                'foto'=>$usuario->getFoto(),
                'usuario'=>$usuario->getUsuario()
            ];
            return $this->json($data,200);
        }else{
            return $this->json([
               'receptor'=>'No existe este usuario'
            ]);
        }
    }

    #[Route("/addmensaje","add_mensaje",methods:['POST'])]
    public function muestra_comentarios(EntityManagerInterface $entityManagerInterface,Request $request,UsuarioRepository $usuarioRepository,MensajeRepository $mensajeRepository){
        $json=json_decode($request->getContent(),true);
        $mensaje=new Mensaje();
        $mensaje->setEmisor($usuarioRepository->find($json['emisor_id']));
        $mensaje->setReceptor($usuarioRepository->find($json['receptor_id']));
        $mensaje->setMensaje($json['mensaje']);
        $mensaje->setFechaMensaje(new DateTime());
        $entityManagerInterface->persist($mensaje);
        $entityManagerInterface->flush();
        $response = [
            'id' => $mensaje->getId(),
            'mensaje' => $mensaje->getMensaje(),
            'fecha_mensaje' => $mensaje->getFechaMensaje()->format('Y-m-d H:i:s'),
            'emisor' => [
                'id' => $mensaje->getEmisor()->getId(),
                'foto' => $mensaje->getEmisor()->getFoto(),
                'usuario' => $mensaje->getEmisor()->getUsuario(),
                // Agrega otras propiedades del emisor si es necesario
            ],
            'receptor' => [
                'id' => $mensaje->getReceptor()->getId(),
                'foto' => $mensaje->getReceptor()->getFoto(),
                'usuario' => $mensaje->getReceptor()->getUsuario(),
                // Agrega otras propiedades del receptor si es necesario
            ],
        ];
    
        // Devolver la respuesta en formato JSON
        return $this->json($response, 201);
    }
}
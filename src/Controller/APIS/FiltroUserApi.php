<?php
namespace App\Controller\APIS;

use App\Entity\Interaccion;
use App\Repository\InteraccionRepository;
use App\Repository\PublicacionRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FiltroUserApi extends AbstractController{
    #[Route('/usuarios/{nombre}',name: 'filtro',methods:['GET'])]
    public function addLike(Request $request,$nombre,UsuarioRepository $usuarioRepository){
        $usuarios=$usuarioRepository->findBy(["usuario"=>$nombre]);
        $data=[];
        if ($usuarios){
            for ($i=0;$i<count($usuarios);$i++){
                $data[]=[
                    'id'=>$usuarios[$i]->getId(),
                    'foto'=>$usuarios[$i]->getFoto(),
                    'username'=>$usuarios[$i]->getUsuario()
                ];
            }

            return $this->json($data,200);
        }else{
            return $this->json(["Vacio"=>"No content"],204);
        }
    }

    

    #[Route('/usu/{id}',name: 'find',methods:['GET'])]
    public function id($id,UsuarioRepository $usuarioRepository){
        $usuario=$usuarioRepository->find($id);
        $data=[];
        if ($usuario){
                $data=[
                    'id'=>$usuario->getId(),
                    'foto'=>$usuario->getFoto(),
                    'username'=>$usuario->getUsuario()
                ];

            return $this->json($data,200);
        }else{
            return $this->json(["Vacio"=>"No content"],204);
        }
    }
}
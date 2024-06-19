<?php
namespace App\Controller\APIS;

use App\Entity\Interaccion;
use App\Repository\InteraccionRepository;
use App\Repository\MensajeRepository;
use App\Repository\PublicacionRepository;
use App\Repository\UsuarioRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class InteraccionesApi extends AbstractController{
    #[Route('/addlike/{id_publicacion}',name: 'add_like',methods:['POST'])]
    public function addLike(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->addInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/removelike/{id_publicacion}',name: 'remove_like',methods:['DELETE'])]
    public function removeLike(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->removeInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/removert/{id_publicacion}',name: 'remove_rt',methods:['DELETE'])]
    public function removert(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->removeInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/removeguardado/{id_publicacion}',name: 'remove_guardado',methods:['DELETE'])]
    public function removeguardado(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->removeInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/addguardado/{id_publicacion}',name: 'add_guardado',methods:['POST'])]
    public function addguardado(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->addInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/addrt/{id_publicacion}',name: 'add_rt',methods:['POST'])]
    public function addrt(Request $request,$id_publicacion,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $publicacion=$publicacionRepository->find($id_publicacion);
        $interaccionjson=json_decode($request->getContent(),true);
        $interaccion=$interaccionRepository->findOneBy(['nombre'=>$interaccionjson['nombre']]);
        $interaccionRepository->addInteraccion($publicacion,$interaccion);
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/addseguidor/{id_seguido}',name: 'add_seguidor',methods:['POST'])]
    public function addseguidor(EntityManagerInterface $entityManagerInterface,Security $security,UsuarioRepository $usuarioRepository,Request $request,$id_seguido,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $user=$usuarioRepository->find($security->getUser());
        $usuario_seguido=$usuarioRepository->find($id_seguido);
        $usuario_seguido->addSeguidore($user);
        $user->addSeguido($usuario_seguido);
        $entityManagerInterface->flush();
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/removeseguidor/{id_seguido}',name: 'remove_seguidore',methods:['DELETE'])]
    public function removeseguidor(EntityManagerInterface $entityManagerInterface,Security $security,UsuarioRepository $usuarioRepository,Request $request,$id_seguido,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $user=$usuarioRepository->find($security->getUser());
        $usuario_seguido=$usuarioRepository->find($id_seguido);
        $usuario_seguido->removeSeguidore($user);
        $user->removeSeguido($usuario_seguido);
        $entityManagerInterface->flush();
        return $this->json(["exito"=>"Éxito",201]);
    }

    #[Route('/mostrarseguidores/{id_seguido}', name: 'remove_seguidor', methods: ['GET'])]
public function mostrarSeguidores(
    EntityManagerInterface $entityManager,
    Security $security,
    UsuarioRepository $usuarioRepository,
    Request $request,
    $id_seguido
) {

    $usuariolog=$security->getUser();
    $user=$usuarioRepository->find($usuariolog);
    // Obtener el usuario al que se le mostrarán los seguidores
    $usuarioSeguido = $usuarioRepository->find($id_seguido);

    // Verificar si el usuario existe
    if (!$usuarioSeguido) {
        return $this->json(['error' => 'El usuario no existe'], 404);
    }

    // Obtener la lista de seguidores
    $seguidores = $usuarioSeguido->getSeguidores();

    // Crear un array para almacenar la información de los seguidores
    $seguidoresData = [];

    // Iterar sobre los seguidores y obtener la información necesaria
    foreach ($seguidores as $seguidor) {
        
            $seguidoresData[] = [
                'id' => $seguidor->getId(),
                'nombre' => $seguidor->getUsuario(), // O cualquier otro campo que quieras mostrar
                'foto'=>$seguidor->getFoto()
                // Agrega más campos si es necesario
            ];
    }

    // Devolver los seguidores como respuesta JSON
    return $this->json($seguidoresData);
}

#[Route('/mostrarsiguiendo/{id_seguido}', name: 'remove_seguidos', methods: ['GET'])]
public function mostrarSiguiendo(
    EntityManagerInterface $entityManager,
    Security $security,
    UsuarioRepository $usuarioRepository,
    Request $request,
    $id_seguido
) {
    $user=$usuarioRepository->find($security->getUser());
    // Obtener el usuario al que se le mostrarán los seguidores
    $usuarioSeguido = $usuarioRepository->find($id_seguido);

    // Verificar si el usuario existe
    if (!$usuarioSeguido) {
        return $this->json(['error' => 'El usuario no existe'], 404);
    }

    // Obtener la lista de seguidores
    $seguidos = $usuarioSeguido->getSeguidos();

    // Crear un array para almacenar la información de los seguidores
    $seguidosData = [];

    // Iterar sobre los seguidores y obtener la información necesaria
    foreach ($seguidos as $seguidor) {
            $seguidosData[] = [
                'id' => $seguidor->getId(),
                'nombre' => $seguidor->getUsuario(), // O cualquier otro campo que quieras mostrar
                'foto'=>$seguidor->getFoto()
                // Agrega más campos si es necesario
            ];
    }

    // Devolver los seguidores como respuesta JSON
    return $this->json($seguidosData);
}
    #[Route("/reporr/{id_mensaje}","pruebamensaje",methods:["POST"])]
    public function reportar(EntityManagerInterface $entityManagerInterface,Request $request,$id_mensaje,PublicacionRepository $publicacionRepository,MensajeRepository $mensajeRepository){
        $mensaje=$mensajeRepository->find($id_mensaje);
        if ($mensaje->isReportado()==false){
            $mensaje->setReportado(true);
        }else{
            $mensaje->setReportado(false);
        }

        $entityManagerInterface->persist($mensaje);
        $entityManagerInterface->flush();
        
        return $this->json(["exito"=>"Éxito",201]);
    }
}
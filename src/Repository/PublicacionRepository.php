<?php

namespace App\Repository;

use App\Entity\Comentario;
use App\Entity\Interaccion;
use App\Entity\Publicacion;
use App\Entity\UsuarioInteraccion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PDO;

/**
 * @extends ServiceEntityRepository<Publicacion>
 *
 * @method Publicacion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publicacion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publicacion[]    findAll()
 * @method Publicacion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicacionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publicacion::class);
    }


    public function publicacionesSeguidos(TiposPublicacionRepository $tiposPublicacionRepository,$usuario_registrado,UsuarioRepository $usuarioRepository,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("SELECT p.* 
        FROM user_seguidos us
        INNER JOIN publicacion p ON p.usuario_id = us.usuario_target
        WHERE us.usuario_source = :id_user
        AND p.usuario_id != :id_user; AND p.tipo_id= 1");
        $resultado->bindParam(":id_user",$usuario_registrado,PDO::PARAM_INT);
        $resultado->execute();

        $publicaciones = [];

        $i = 0;

        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $publicacion=new Publicacion();

            $publicacion->setId($tuplas->id);
            $publicacion->setArchivos(json_decode($tuplas->archivos));
            $publicacion->setUsuario($usuarioRepository->find($tuplas->usuario_id));

            $comentarios=$this->comentariosPublicacion($tuplas->id,$publicacionRepository,$usuarioRepository);

            for ($i=0;$i<count($comentarios);$i++){
                $publicacion->addComentario($comentarios[$i]);
            }

            $publicacion->setTipo($tiposPublicacionRepository->find($tuplas->tipo_id));

            $interacciones=$this->interaccionesPublicacion($tuplas->id,$usuarioRepository,$publicacionRepository,$interaccionRepository);
            for ($i=0;$i<count($interacciones);$i++){
                $publicacion->addUsuarioInteraccion($interacciones[$i]);
            }
            $publicaciones[]=$publicacion;
            $i++;
        }

        return $publicaciones;
    }


    public function interaccionesPublicacion($id_publicacion,UsuarioRepository $usuarioRepository,PublicacionRepository $publicacionRepository,InteraccionRepository $interaccionRepository){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select * from usuario_interaccion where publicacion_id=:id_pub;");
        $resultado->bindParam(":id_pub",$id_publicacion,PDO::PARAM_INT);
        $resultado->execute();

        $interacciones = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $interaccion=new UsuarioInteraccion();

            $interaccion->setUsuario($usuarioRepository->find($tuplas->usuario_id));      
            $interaccion->setPublicacion($publicacionRepository->find($tuplas->publicacion_id)); 
            $interaccion->setInteraccion($interaccionRepository->find($tuplas->interaccion_id));
            
            
            $interacciones[]=$interaccion;
            $i++;
        }

        return $interacciones;
    }


    public function comentariosPublicacion($id_publicacion,PublicacionRepository $publicacionRepository,UsuarioRepository $usuarioRepository){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select * from comentario where publicacion_id=:id_publicacion;");
        $resultado->bindParam(":id_publicacion",$id_publicacion,PDO::PARAM_INT);
        $resultado->execute();

        $comentarios = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $comentario=new Comentario();

            $comentario->setPublicacion($publicacionRepository->find($tuplas->publicacion_id));
            $comentario->setTexto($tuplas->texto);
            $comentario->setUsuario($usuarioRepository->find($tuplas->usuario_id));
            
            $interacciones=$this->interaccionesComentarios($tuplas->id);

            for ($i=0;$i<count($interacciones);$i++){
                $comentario->addInteraccione($interacciones[$i]);
            }
            
            
            $comentarios[]=$comentario;
            $i++;
        }

        return $comentarios;
    }

    public function interaccionesComentarios($id_comentario){
        $conexion = new PDO("mysql:host=127.0.0.1:3306;dbname=redsocial;charset=utf8mb4", "root", "Root");
        $resultado = $conexion->prepare("select i.* from comentario_interaccion ci
                                        inner join interaccion i
                                        on ci.interaccion_id=i.id
                                        where ci.comentario_id=:id_comentario;");
        $resultado->bindParam(":id_comentario",$id_comentario,PDO::PARAM_INT);
        $resultado->execute();

        $interacciones = [];

        $i = 0;


        while ($tuplas = $resultado->fetch(PDO::FETCH_OBJ)) {
            $interaccion=new Interaccion();

            $interaccion->setNombre($tuplas->nombre);            
            
            
            $interacciones[]=$interaccion;
            $i++;
        }

        return $interacciones;
    }

    
}

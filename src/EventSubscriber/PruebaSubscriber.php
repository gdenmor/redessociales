<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Entity\Usuario;
use App\Event\CorreoEvent;
use App\Event\EventoCorreo;
use App\Service\Correo;
use App\Service\CorreoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\SecurityEvents;

class PruebaSubscriber implements EventSubscriberInterface{

    public function __construct(private CorreoService $mailer,private Security $security,private EntityManagerInterface $entityManagerInterface){

    }
    public static function getSubscribedEvents(){
        return [
            CorreoEvent::class=> 'mandaCorreo',
        ];
    }

    public function mandaCorreo(CorreoEvent $event){
        $user=$this->security->getUser();

        if ($user) {
            $userdb = $this->entityManagerInterface->getRepository(Usuario::class)->find($user);

            // Verifica si se encontró el usuario en la base de datos
            if ($userdb instanceof Usuario) {
                // Ahora puedes enviar el correo al usuario autenticado
                $this->mailer->EnviaCorreo("Mensaje para el usuario logueado", $userdb->getEmail(), 'Asunto del correo');
            }
        }
    }
}
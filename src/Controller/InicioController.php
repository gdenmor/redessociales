<?php

namespace App\Controller;

use App\Entity\Publicacion;
use App\Entity\User;
use App\Entity\Usuario;
use App\Form\PublicacionType;
use App\Form\UsuarioType;
use App\Repository\ComentarioRepository;
use App\Repository\ContactoRepository;
use App\Repository\HistoriaRepository;
use App\Repository\InteraccionRepository;
use App\Repository\MensajeRepository;
use App\Repository\PublicacionRepository;
use App\Repository\TiposPublicacionRepository;
use App\Repository\UsuarioRepository;
use App\Service\CorreoService;
use App\Service\DispatcherService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InicioController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(Request $request,AuthenticationUtils $authenticationUtils, Security $security): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    #[Route('/rol', name: 'app_rol')]
    public function rol(UsuarioRepository $usuarioRepository,AuthenticationUtils $aut,Request $request,EntityManagerInterface $entity)
    {
        $usuario=$usuarioRepository->find($this->getUser());
        if ($this->isGranted('ROLE_ADMIN')) {
            if ($usuario->isBaneado()==false){
                return $this->redirectToRoute('app_main');
            }
            return $this->redirectToRoute('app_main');
        } else if ($this->isGranted("ROLE_USER")){
            if ($usuario->isBaneado()==false){
                return $this->redirectToRoute('app_main');
            }
            $this->addFlash('error','El usuario esta baneado. Si quiere acceder a su perfil contacte con un administrador');
            return $this->redirectToRoute('app_login');
        }
    }

    #[Route('/forgotpassword', name: 'app_forgotpassword')]
    public function olvidacontrasenia(EntityManagerInterface $entityManagerInterface,UserPasswordHasherInterface $userPasswordHasher,Request $request,Security $security,UsuarioRepository $usuarioRepository, CorreoService $correoService): Response
    {
        if ($request->isMethod('POST')) {
            $user=$usuarioRepository->findOneBy(["email"=>$request->request->get('email')]);
            $password=$request->request->get('password');
            $repitepassword=$request->request->get('repeatpassword');
            if ($user){
                if ($user->getEmail()){
                    if ($repitepassword==$password){
                        $hashedPassword = $userPasswordHasher->hashPassword($user, $password);
                        $user->setPassword($hashedPassword);
                        $entityManagerInterface->persist($user);
                        $entityManagerInterface->flush();
                        $email=$user->getEmail();
                        $correoService->EnviaCorreo('La contraseña ha sido modificada con éxito',$email,'Contraseña modificada');
                        return $this->redirectToRoute('app_login');
                    }else{
                        $this->addFlash('error','Las contraseñas no coinciden');
                    }
                }  
            }else{
                $this->addFlash('error','El usuario no existe');
            }
            
        }
        return $this->render('olvida_contrasenia.html.twig');
    }
    

    #[Route('/registro', name: 'app_registro')]
    public function registro(ValidatorInterface $validatorInterface,Request $request,EntityManagerInterface $entityManager,DispatcherService $dispatcherService): Response
    {
        if ($request->isMethod('POST')) {
            // Recuperamos los datos del formulario
            $nombre = $request->request->get('username');
            $email = $request->request->get('email');
            $contrasenia = $request->request->get('password');
            $nombrecompleto=$request->request->get('fullName');
            $genero=$request->request->get('gender');
            $user=new Usuario();
            $user->setUsuario($nombre);
            $user->setEmail($email);
            $user->setPassword($contrasenia);
            $user->setNombreCompleto($nombrecompleto);
            $user->setGenero($genero);
            $user->setRoles(["ROLE_USER"]);

            $errors = $validatorInterface->validate($user);
            if (count($errors) == 0) {
                $entityManager->persist($user);
                $entityManager->flush();
                $dispatcherService->lanzaEvento($user);
            }else{
                for ($i=0; $i < count($errors); $i++){
                    $this->addFlash('error',$errors[$i]->getMessage());
                }
                return $this->redirectToRoute('app_registro');
            }
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registro.html.twig');
    }

    #[Route('/main', name: 'app_main')]
    #[IsGranted('ROLE_USER')]
    public function main(TiposPublicacionRepository $tiposPublicacionRepository,PublicacionRepository $publicacionRepository,Security $security,UsuarioRepository $usuarioRepository,InteraccionRepository $interaccionRepository): Response
    {
        $user=$security->getUser();
        if ($user==null){
            return $this->redirectToRoute('app_login');
        }
        $usuario=$usuarioRepository->find($user);
        $publicaciones=$publicacionRepository->publicacionesSeguidos($tiposPublicacionRepository,trim($usuario->getId()),$usuarioRepository,$publicacionRepository,$interaccionRepository);
        $historias=$publicacionRepository->findBy(["tipo"=>$tiposPublicacionRepository->find(2)]);
        return $this->render('main.html.twig',[
            'publicaciones'=>$publicaciones,
            'historias'=>$historias
        ]);
    }

    #[Route('/busca', name: 'app_búsqueda')]
    #[IsGranted('ROLE_USER')]
    public function busqueda(Security $security,UsuarioRepository $usuarioRepository): Response
    {
        if ($security->getUser()==null){
            return $this->redirectToRoute('app_login');
        }
        $usuarios=$usuarioRepository->findAll();
        return $this->render('busqueda.html.twig',[
            'usuarios'=>$usuarios
        ]);
    }

    #[Route('/buscausuario/{id}', name: 'app_busqueda_user')]
    #[IsGranted('ROLE_USER')]
    public function busquedausuario(Security $security,UsuarioRepository $usuarioRepository,$id): Response
    {
        if ($security->getUser()==null){
            return $this->redirectToRoute('app_login');
        }
        $usuario=$usuarioRepository->find($id);
        return $this->render('busquedausuario.html.twig',[
            'usuario'=>$usuario
        ]);
    }


    #[Route('/perfil/{id}', name: 'perfil')]
    #[IsGranted('ROLE_USER')]
    public function perfil(Security $security,PublicacionRepository $publicacionRepository,$id=0): Response
    {
        if ($security->getUser()==null){
            return $this->redirectToRoute('app_login');
        }
        //$publicaciones=$publicacionRepository->findAll();
        return $this->render('perfil.html.twig',[
            'id'=>$id
        ]);
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(UsuarioRepository $usuarioRepository,Security $security,PublicacionRepository $publicacionRepository,$id=0): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        return $this->render('admin.html.twig',[
        ]);
    }

    #[Route('/baneausuarios', name: 'banea')]
    #[IsGranted('ROLE_ADMIN')]
    public function banea(Security $security,UsuarioRepository $usuarioRepository,MensajeRepository $mensajeRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        $mensajes=$mensajeRepository->findBy(["reportado"=>true]);
        $filtromensajes=[];
        for ($i=0;$i<count($mensajes);$i++){
            if ($mensajes[$i]->getReceptor()->isBaneado()==false){
                $filtromensajes[]=$mensajes[$i];
            }
        }
        return $this->render('banea.html.twig',[
            'mensajes'=>$filtromensajes
        ]);
    }

    #[Route('/baneausuario/{id}', name: 'baneaid')]
    #[IsGranted('ROLE_ADMIN')]
    public function baneaid(Security $security,$id,EntityManagerInterface $entityManagerInterface,UsuarioRepository $usuarioRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        $usuario=$usuarioRepository->find($id);
        $usuario->setBaneado(true);
        $entityManagerInterface->persist($usuario);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('banea');
    }

    #[Route('/desbaneausuarios', name: 'desbanea')]
    #[IsGranted('ROLE_ADMIN')]
    public function desbanea(Security $security,UsuarioRepository $usuarioRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

       
        $usuarios=$usuarioRepository->findBy(["baneado"=>true]);
        return $this->render('desbanea.html.twig',[
            'usuarios'=>$usuarios
        ]);
    }

    #[Route('/desbaneausuario/{id}', name: 'desbaneaid')]
    #[IsGranted('ROLE_ADMIN')]
    public function desbaneaid(Security $security,$id,EntityManagerInterface $entityManagerInterface,UsuarioRepository $usuarioRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        $usuario=$usuarioRepository->find($id);
        $usuario->setBaneado(false);
        $entityManagerInterface->persist($usuario);
        $entityManagerInterface->flush();
        return $this->redirectToRoute('desbanea');
    }

    #[Route('/borrausuarios', name: 'borra')]
    #[IsGranted('ROLE_ADMIN')]
    public function borra(Security $security,UsuarioRepository $usuarioRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        return $this->render('borra.html.twig',[
            'usuarios'=>$usuarioRepository->findAll()
        ]);
    }

    #[Route('/borrausuario/{id}', name: 'borraid')]
    #[IsGranted('ROLE_ADMIN')]
public function borraid(Security $security,UsuarioRepository $usuarioRepository, $id, EntityManagerInterface $entityManager,ComentarioRepository $comentarioRepository,InteraccionRepository $interaccionRepository,MensajeRepository $mensajeRepository,PublicacionRepository $publicacionRepository): Response
{
    $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

    $usuario = $usuarioRepository->find($id);

    $comentarios = $comentarioRepository->findBy(["Usuario"=>$usuario]);
    foreach ($comentarios as $comentario) {
        $entityManager->remove($comentario);
        $entityManager->flush();
    }

    $mensajes = $mensajeRepository->findBy(["emisor"=>$usuario]);
    foreach ($mensajes as $mensaje) {
        $entityManager->remove($mensaje);
        $entityManager->flush();
    }

    $mensajes1 = $mensajeRepository->findBy(["receptor"=>$usuario]);
    foreach ($mensajes1 as $mensaje) {
        $entityManager->remove($mensaje);
        $entityManager->flush();
    }

    $publicaciones = $publicacionRepository->findBy(["Usuario"=>$usuario]);

    foreach ($publicaciones as $publicacion) {
        $entityManager->remove($publicacion);
        $entityManager->flush();
    }

    $entityManager->remove($usuario);
    $entityManager->flush();

    // Redirigir a alguna parte de la aplicación después de la eliminación
    return $this->redirectToRoute('borra');
}
    #[Route('/modificausuarios', name: 'modifica')]
    #[IsGranted('ROLE_ADMIN')]
    public function modifica(Security $security,UsuarioRepository $usuarioRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        //$publicaciones=$publicacionRepository->findAll();
        return $this->render('modifica.html.twig',[
            'usuarios'=>$usuarioRepository->findAll()
        ]);
    }

    #[Route('/modificausuario/{id}', name: 'modificaid')]
    #[IsGranted('ROLE_ADMIN')]
    public function modificaid(Security $security,UsuarioRepository $usuarioRepository,$id,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        $usuario=$usuarioRepository->find($id);
        $form=$this->createForm(UsuarioType::class,$usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $fotoFile = $form->get('foto')->getData();

            if ($fotoFile) {
                $newFilename = uniqid().'.'.$fotoFile->guessExtension();

                $publicDirectory = $this->getParameter('kernel.project_dir') . '/public';

                try {
                    $fotoFile->move(
                        $publicDirectory . '/imagenes',
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Error al cargar el archivo: '.$e->getMessage());
                }

                $usuario->setFoto($newFilename);
            } else {
                $usuario->setFoto("");
            }
            $entityManagerInterface->persist($usuario);
            $entityManagerInterface->flush();
            $this->redirectToRoute('modifica');
        }
        return $this->render('modificaid.html.twig',[
            'form'=>$form->createView(),
        ]);
    }

    #[Route('/creausuarios', name: 'crea')]
    #[IsGranted('ROLE_ADMIN')]
    public function crea(Security $security,UsuarioRepository $usuarioRepository,UserPasswordHasherInterface $passwordHasher,PublicacionRepository $publicacionRepository,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $usuario=new Usuario();
        $form=$this->createForm(UsuarioType::class,$usuario);
        $form->handleRequest($request);
        if ($form->isSubmitted()&&$form->isValid()){
            $usuario->setPassword($passwordHasher->hashPassword($usuario,$usuario->getPassword()));
            $usuario->setRoles(["ROLE_USER"]);
            $entityManagerInterface->persist($usuario);
            $entityManagerInterface->flush();
            $this->addFlash('success','Usuario creado correctamente');
        }
        return $this->render('crea.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/mensajes', name: 'mensajes')]
    #[IsGranted('ROLE_USER')]
    public function mensajes(UsuarioRepository $usuarioRepository,Security $security,PublicacionRepository $publicacionRepository,ContactoRepository $contactoRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        $usuarios=$usuarioRepository->usuariosescritos($usuarioRepository->find($security->getUser())->getId());
        return $this->render('mensajes.html.twig',[
            'contactos' => $usuarios,
        ]);
    }

    #[Route('/explorar', name: 'explorar')]
    #[IsGranted('ROLE_USER')]
    public function explorar(Security $security,UsuarioRepository $usuarioRepository,PublicacionRepository $publicacionRepository,TiposPublicacionRepository $tiposPublicacionRepository): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }

        
        $tipo=$tiposPublicacionRepository->find(1);
        $publicaciones=$publicacionRepository->findBy(["tipo"=>$tipo]);
        return $this->render('explorar.html.twig',[
            'publicaciones' => $publicaciones
        ]);
    }

    #[Route('/creapublicacion', name: 'crea_publicacion')]
    #[IsGranted('ROLE_USER')]
    public function creapublicacion(Security $security,UsuarioRepository $usuarioRepository,PublicacionRepository $publicacionRepository,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('creapublicacion.html.twig',[
            
        ]);
    }

    #[Route('/creahistoria', name: 'crea_historia')]
    #[IsGranted('ROLE_USER')]
    public function creahistoria(Security $security,UsuarioRepository $usuarioRepository,PublicacionRepository $publicacionRepository,Request $request,EntityManagerInterface $entityManagerInterface): Response
    {
        $user = $security->getUser();

        if ($user === null) {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('creahistoria.html.twig',[
            
        ]);
    }

    
}

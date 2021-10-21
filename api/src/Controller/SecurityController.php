<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class  SecurityController extends Controller
{

  /**
    * @Route("/login", name="security_login")
    */
   public function login(AuthenticationUtils $helper): Response
   {
       return $this->render('Security/login.html.twig', [
           // dernier username saisi (si il y en a un)
           'last_username' => $helper->getLastUsername(),
           // La derniere erreur de connexion (si il y en a une)
           'error' => $helper->getLastAuthenticationError(),
       ]);
   }

   /**
    * La route pour se deconnecter.
    *
    * Mais celle ci ne doit jamais être executé car symfony l'interceptera avant.
    *
    *
    * @Route("/logout", name="security_logout")
    */
    
   public function logout(): void
   {
       throw new \Exception('This should never be reached!');
   }

   /**
    * @Route("/register", name="user_registration")
    */

   public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
   {
     $user = new User();
     $form = $this->createForm(UserType::class, $user);

     $form->handleRequest($request);
     if ($form->isSubmitted() && $form->isValid()) {

       $password = $passwordEncoder->encodePassword($user, $user->getPassword());
       $user->setPassword($password);

       // Par defaut l'utilisateur aura toujours le rôle ROLE_USER
       $user->setRoles(['ROLE_USER']);

     // On enregistre l'utilisateur dans la base
      $em = $this->getDoctrine()->getManager();
      $em->persist($user);
      $em->flush();

     return $this->redirectToRoute('security_login');
   }

   return $this->render(
     'register.html.twig',
     array('form' => $form->createView())
   );
 }
}

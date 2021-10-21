<?php
// src/Controller/BlogController.php
namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractController
{
    /**
     * @Route("/api/user", methods={"GET", "HEAD"}, name="index_user")
     */

    public function IndexApi()
    {
        $user = $this->getDoctrine()
            ->getRepository(\App\Entity\User::class)
            ->findOneBy(['email' => "marvin@epitech.eu"]);
        $data = [];
        if ($user){
            $data['email'] = $user->getEmail();
            $data['username'] = $user->getUsername();
            $data['roles'] = $user->getRoles();
            return ($this->json($data, 201));
        }
        return ($this->json([], 404));
    }


    /**
     * @Route("/api/user", methods={"POST"}, name="create_user")
     */

    public function userCreateApi()
    {

        $email = "user";
        $user = new User();
        $user->setPassword("toto4242");
        $user->setRoles(["USER"]);
        $user->setUsername("Marvin42");
        $user->setEmail("marvin@epitech.eu");


        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->json([], 201);
    }


}

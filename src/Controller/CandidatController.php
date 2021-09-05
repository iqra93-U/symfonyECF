<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CandidatController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }



    /**

     * @Route("/candidat", name="candidat")
     */
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $candidate = new User();
        $candidateForm =$this->createForm(UserType::class , $candidate);
        $candidateForm->handleRequest($request);
        if($candidateForm->isSubmitted() && $candidateForm->isValid()){
            $candidate = $candidateForm->getData();
            $candidate->setPassword($passwordHasher->hashPassword($candidate,$candidate->getPassword()));
            $candidate->setRoles(["ROLE_USER"]);
            $this->entityManager->persist($candidate);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_login');
        }

        return $this->render('candidat/index.html.twig', [
            'controller_name' =>'CandidatController',
            'candidatForm' => $candidateForm->createView(),

        ]);

    }
    /**
     * @Route("/candidat/info", name="candidat_info")
     */
    //information of specific user who is login

    public function getInfo(): Response
    {

        return $this->render('candidat/info.html.twig');
    }

    /**
     * @Route("/candidat/modification", name="candidat_modify")
     */
    public function modifyInfo(Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user= $this->getUser();
        $candidateEditForm =$this->createForm(UserType::class,$user );
        $candidateEditForm->handleRequest($request);
        if($candidateEditForm->isSubmitted() && $candidateEditForm->isValid()){
            $user->setPassword($passwordHasher->hashPassword($user,$user->getPassword()));
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('message', 'profile Updated');
        }
        return $this->render('candidat/edit.html.twig', [
            'candidatEditForm' => $candidateEditForm->createView(),

        ]);

    }
}

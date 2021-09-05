<?php

namespace App\Controller;


use App\Entity\Documents;
use App\Entity\User;
use App\Form\DocType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * @Route("/roles", name="roles")
     */
    public function roles():Response{
        $user = $this->getUser();
        $allRoles=  $user->getRoles();
//        dd($allRoles);
            if(in_array("ROLE_ADMIN",$allRoles)){
                return $this->redirectToRoute('admin');
            }
            elseif (in_array("ROLE_COMM",$allRoles)){
                return $this->redirectToRoute('commercial');
            }
            else{
                return $this->redirectToRoute('candidat_info');
            }

                }

    /**
     * @Route("/documents", name="documents")
     */

    public function docs(Request $request, EntityManagerInterface $entityManager):Response{


        $user = $this->getUser();
        $document = new Documents();
        $docForm = $this->createForm(DocType::class,$document );
        $docForm->handleRequest($request);
        if($docForm->isSubmitted() && $docForm->isValid()){
//            $File = $document->getName();
            $File = $docForm->get('name')->getData();
            $fileName = md5(uniqid()).'.'.$File->guessExtension();
            $File->move($this->getParameter('upload_directory'), $fileName);
            $document->setName($fileName);

            $document = $docForm-> getData();
            $user->addDoc($document);
            $entityManager->persist($document);
            $entityManager->flush();

        }

        return $this->render('documents/index.html.twig', [
            'controller_name' => 'HomeController',
            'docForm' => $docForm->createView(),
            'document' => $document

        ]);
    }



    /**
     * @Route("/commercial", name="commercial")
     */
            public function commercial(EntityManagerInterface $entityManager):Response{
                $candidate = $this->entityManager->getRepository(User::class)->findAll();
                return $this->render('commercial/index.html.twig', [
                    'users' => $candidate,
                ]);
            }

    /**
     * @Route("/commercial/modification/{id}", name="commercial_modification")
     */
    public function modifyInfo(Request $request,User $user): Response
    {

        $candidateEditForm =$this->createForm(UserType::class , $user);
        $candidateEditForm->handleRequest($request);
        if($candidateEditForm->isSubmitted() && $candidateEditForm->isValid()){
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        }
        return $this->render('commercial/edit.html.twig', [
            'edit_form' => $candidateEditForm->createView(),

        ]);

    }
    /**
     * @Route("/commercial/doc", name="commercial_doc")
     */

    public function doc(): Response{

        return $this->redirectToRoute('documents');
    }

    /**
     * @Route("/commercial/experience", name="commercial_experience")
     */

    public function comExp(): Response{
        return $this->redirectToRoute('experience');
    }





}


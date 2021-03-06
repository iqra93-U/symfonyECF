<?php

namespace App\Controller;


use App\Entity\Documents;
use App\Entity\Experiences;
use App\Entity\User;
use App\Form\DocType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;
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
                $experience = $this->entityManager->getRepository(Experiences::class)->findAll();
                return $this->render('commercial/index.html.twig', [
                    'users' => $candidate,
                    'experiences' => $experience,
                    
                ]);
            }
                public function search(EntityManagerInterface $entityManager):Response{

                $form = $this->createFormBuilder()
                ->setAction($this->generateUrl('commercial_searchhandle'))
                    ->add('query', TextType::class)
                    ->add('search', SubmitType::class, [
                        'attr' => [
                            'class'=> 'btn btn-primary'
                        ]
                    ])
                    ->getForm();
                    return $this->render('commercial/search.html.twig', [
                       'search'=> $form->createView()
                 ]);

            }

    /**
     * @Route("/commercial/searchhandle", name="commercial_searchhandle")
     */
            public function searchHandle(Request $request, UserRepository $userRepository){
                $query= $request->request->get('form')['query'];

                if($query){
                    $user = $userRepository->find($query);
                    // $user = $this->entityManager->getRepository(User::class)->find($query);
                }
                dump($user); die;
                return $this->render('commercial/result.html.twig', [
                    'user'=> $user
              ]);
       
            }




            // public function search(EntityManagerInterface $entityManager):Response{
           
            //     $search = new User();
            //     $name= $search->getFirstName();
            //     $searchuser = $this->entityManager->getRepository(User::class)->findBy(['firstName' =>$name]);
            //     return $this->render('commercial/index.html.twig', [
            //           'search'=> $searchuser->createView()
            //     ]);
            // }
            // public function search(Request $request):Response{

            //     $search = new User();
            //     $form = $this->createForm(User::class,$search );
            //     $form->handleRequest($request);

            //     $users =[];
            //     if ($form->isSubmitted() && $form->isValid()){
            //         $name= $search->getFirstName();
            //         if($name!="")
            //         $users = $this->getDoctrine()->getRepository(User::class)->findBy(['firstName' =>$name]);
            //         else
            //             $users = $this->getDoctrine()->getRepository(User::class)->findAll();

            //     }
            //     return $this->render('commercial/index.html.twig', [
            //         'form' => $form->createView(),
            //         'users' => $users,
                   
                  
                   

            //     ]);
            // }


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


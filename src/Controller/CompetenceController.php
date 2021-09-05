<?php

namespace App\Controller;

use App\Entity\Competence;

use App\Form\CompetenceType;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class CompetenceController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/competence", name="competence")
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $competence = new Competence();
        $competenceForm =$this->createForm(CompetenceType::class , $competence);
        $competenceForm ->handleRequest($request);
        if($competenceForm->isSubmitted() && $competenceForm->isValid()){
            $competence = $competenceForm->getData();
            $entityManager->persist($competence);
            $entityManager->flush();

        }
        return $this->render('competence/index.html.twig', [
            'competenceForm' => $competenceForm->createView(),

        ]);

    }


    /**
     * @Route("/competence/info", name="competence_info")
     */
    public function getInfo(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();
        $competence = new Competence();
        $competenceForm =$this->createForm(CompetenceType::class , $competence);
        $competenceForm ->handleRequest($request);
        if($competenceForm->isSubmitted() && $competenceForm->isValid()){
            $competence = $competenceForm->getData();
          $user->addCompetence($competence);
            $entityManager->persist($competence);
            $entityManager->flush();

        }
        $competence = $this->entityManager->getRepository(Competence::class)->findAll();
        return $this->render('competence/info.html.twig',[
            'competences' => $competence,
            'competenceForm' => $competenceForm->createView(),
        ]);
    }




    /**
     * @Route("/competence/modification/{id}", name="competence_modify")
     */

    public function EditComp(EntityManagerInterface $entityManager, Request $request, Competence $competence): Response
    {


        $competenceEditForm =$this->createForm(CompetenceType::class , $competence);
        $competenceEditForm ->handleRequest($request);
        if($competenceEditForm->isSubmitted() && $competenceEditForm->isValid()){
            $competenceEdit = $competenceEditForm->getData();
            $entityManager->persist($competenceEdit);
            $entityManager->flush();

        }

        return $this->render('competence/edit.html.twig', [
            'competenceEditForm' => $competenceEditForm->createView(),


        ]);

    }


}

<?php

namespace App\Controller;

use App\Entity\Experiences;
use App\Form\ExperienceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExperienceController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/experience", name="experience")
     */

    public function getInfo(EntityManagerInterface $entityManager,Request $request): Response

    {
        $experience = new Experiences();
        $experienceForm = $this->createForm(ExperienceType::class,$experience );
        $experienceForm->handleRequest($request);

        if($experienceForm->isSubmitted() && $experienceForm->isValid()){
            $experience =$experienceForm->getData();

            $user = $this->getUser();
            $user->addExperience($experience);

            $entityManager->persist($experience);
            $entityManager->flush();
        }

        return $this->render('experience/index.html.twig', [
            'experienceForm' => $experienceForm->createView(),
            'experiences' => $experience

        ]);
    }


    /**
     * @Route("/experience/modification/{id}", name="experience_modification")
     */

    public function editExp(EntityManagerInterface $entityManager, Request $request, Experiences $experiences): Response
    {

        $experienceForm = $this->createForm(ExperienceType::class,$experiences );
        $experienceForm ->handleRequest($request);
        if($experienceForm->isSubmitted() && $experienceForm->isValid()){
            $experienceEdit = $experienceForm->getData();
            $entityManager->persist($experienceEdit);
            $entityManager->flush();

        }
        return $this->render('experience/edit.html.twig', [
            'experienceEditForm' => $experienceForm->createView(),


        ]);
    }
}

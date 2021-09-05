<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {

        $entreprise = new Entreprise();
        $entrepriseForm = $this->createForm(EntrepriseType::class,$entreprise );
        $entrepriseForm->handleRequest($request);
        if($entrepriseForm->isSubmitted() && $entrepriseForm->isValid()){
            $entreprise = $entrepriseForm->getData();
            $this ->entityManager->persist($entreprise);
            $this->entityManager->flush();
        }

        return $this->render('entreprise/index.html.twig', [
            'controller_name' => 'EntrepriseController',
            'entrepriseForm' => $entrepriseForm->createView(),
        ]);
    }
}

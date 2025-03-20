<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Repository\EntrepriseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EntrepriseController extends AbstractController
{
    #[Route('/entreprise', name: 'app_entreprise')]
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findBy([], ["raisonSociale"=> "ASC"]);
        return $this->render('entreprise/index.html.twig', [
            'entreprises' => $entreprises
        ]);
    }

    // on le defini avant la fonction show car l'ordi va d'abbord chercher entreprise/id avant entreprise/new se qui cree une erreur
    #[Route('/entreprise/new', name: 'new_entreprise')] 
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        $entreprise = new Entreprise();
        // on cree une nouvelle entreprise 
        $form = $this->createForm(EntrepriseType::class, $entreprise); 
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $entreprise = $form->getData();

            $entityManager->persist($entreprise); // c'est la prepare en PDO
            $entityManager->flush(); // c'est l'execute en PDO

            return $this->redirectToRoute('app_entreprise');
        }

        return $this->render('entreprise/new.html.twig',
                ['formAddEntreprise' => $form]);
    }

    // {id} est la variable que l'on recupere dans l'URL
    #[Route('/entreprise/{id}', name: 'show_entreprise')] 
    public function show(Entreprise $entreprise): Response{
        return $this->render('entreprise/show.html.twig',
                ['entreprise' => $entreprise]);
    }
    
}

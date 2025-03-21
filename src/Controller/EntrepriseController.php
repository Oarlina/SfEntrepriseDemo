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

    // on le defini avant la fonction car l'ordi va d'abbord chercher entreprise/id avant entreprise/new se qui cree une erreur
    // la méthode va modifier l'entreprise ( les valeurs déjà mis dans la BDD seront écrite et ont pourra modifier et enrgistrer dans la BDD) ou la creer
    #[Route('/entreprise/new', name: 'new_entreprise')] 
    #[Route('/entreprise/{id}/edit', name: 'edit_entreprise')] 
    public function new_edit(Entreprise $entreprise = null, Request $request, EntityManagerInterface $entityManager): Response{
        if (!$entreprise){
            $entreprise = new Entreprise();
        }            
        // on cree une nouvelle entreprise 
        $form = $this->createForm(EntrepriseType::class, $entreprise); 
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()){
            $entreprise = $form->getData();
            
            $entityManager->persist($entreprise); // c'est la prepare en PDO
            $entityManager->flush(); // c'est l'execute en PDO
            
            return $this->redirectToRoute('app_entreprise');
        }
        
        return $this->render('entreprise/new.html.twig',
        ['formAddEntreprise' => $form,
        'edit' => $entreprise->getId()]);
    }
    
    // supprime l'entreprise
    #[Route('/entreprise/{id}/delete', name: 'delete_entreprise')] 
    public function entreprise_delete(Entreprise $entreprise=null, Request $request, EntityManagerInterface $entityManager): Response{
        if (!$entreprise){
            $entreprise = new Entreprise();
        }            
        // on cree une nouvelle entreprise 
        $form = $this->createForm(EntrepriseType::class, $entreprise); 
        $form->handleRequest($request);
        
        // si le formulaire n'est pas valide et envoyer
        if (!($form->isSubmitted() && $form->isValid())){
            return $this->render('entreprise/new.html.twig',
            ['formAddEntreprise' => $form]);
        }
        
        $entreprise = $form->getData();
        
        $entityManager->persist($entreprise); // c'est la prepare en PDO
        $entityManager->flush(); // c'est l'execute en PDO
        
        return $this->redirectToRoute('app_entreprise');
    }

    // {id} est la variable que l'on recupere dans l'URL
    #[Route('/entreprise/{id}', name: 'show_entreprise')] 
    public function show(Entreprise $entreprise): Response{
        return $this->render('entreprise/show.html.twig',
                ['entreprise' => $entreprise]);
    }
    
}

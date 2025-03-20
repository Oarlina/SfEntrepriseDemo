<?php

namespace App\Controller;

use App\Entity\Employe;
use App\Form\EmployeType;
use App\Repository\EmployeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class EmployeController extends AbstractController
{
    #[Route('/employe', name: 'app_employe')]
    public function index(EmployeRepository $employeRepository): Response
    {
        $employes = $employeRepository->findBy([], ['nom' => 'ASC']);
        return $this->render('employe/index.html.twig', [
            'employes' => $employes,
        ]);
    }

    // on le defini avant la fonction show car l'ordi va d'abbord chercher entreprise/id avant entreprise/new se qui cree une erreur
    #[Route('/employe/new', name: 'new_employe')] 
    public function new(Request $request, EntityManagerInterface $entityManager): Response{
        $employe = new Employe();
        // on cree une nouvelle Employe 
        $form = $this->createForm(EmployeType::class, $employe); 
        $employe = new Employe();
        // on cree un nouvel employe 
        $form = $this->createForm(EmployeType::class, $employe); 
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()){
            $employe = $form->getData();

            $entityManager->persist($employe); // c'est la prepare en PDO
            $entityManager->flush(); // c'est l'execute en PDO

            return $this->redirectToRoute('app_employe');
        }
        
        return $this->render('employe/new.html.twig',
                ['formAddEmploye' => $form]);
    }

    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }
}

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
    /* 
        Route=  c'est un attribut, c'est natif de Symfony
                -> donne d'abord l'arborescence faite sur pour acceder a la page et le name est le nom de la route ce qui permet l'appeler plus facilement dans le code

        Request = représente les supperglobales ( $_GET, $_POST, $_FILES, $_COOKIE, $_SESSION, ...) c'est un objet
        EntityManagerInterface = l'entity Manager est un object qui permet d'enregistrer, de chercher des objets dans la BDD
        Response = c'est un objet. récupère la réponse, le statut du code et un tableau des tetes HTTP
                -> permet de faire des requetes SQL

        $form->isSubmitted() = si on a cliquer sur le bouton valider
        $form->isValid() = si les variables sont celle qui ont ete attendu (c'est un booléen)
        $this->render() = vient de twig. c'est une méthode qui permet de généré une réponse HTML en éxécutant un fichier twig
    */
    // on le defini avant la fonction show car l'ordi va d'abbord chercher entreprise/id avant entreprise/new se qui cree une erreur
    #[Route('/employe/new', name: 'new_employe')] 
    #[Route('/employe/{id}/edit', name: 'edit_employe')] 
    public function new(Employe $employe = null , Request $request, EntityManagerInterface $entityManager): Response{
        if (!$employe){
            // on cree un nouvel employe 
            $employe = new Employe();
        }
        // on cree le formulaire de l'employé
        $form = $this->createForm(EmployeType::class, $employe); 
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $employe = $form->getData();

            $entityManager->persist($employe); // c'est la prepare en PDO
            $entityManager->flush(); // c'est l'execute en PDO

            return $this->redirectToRoute('app_employe');
        }
        
        return $this->render('employe/new.html.twig',
                ['formAddEmploye' => $form,
                'edit' => $employe->getId()]);
    }
    // ma fonction va supprimer un employe
    #[Route('/employe/{id}/delete', name: 'delete_employe')]
    public function delete(Employe $employe, EntityManagerInterface $entityManager): Response {
        $entityManager->remove($employe); // on prepare la suppression de l'employe
        $entityManager->flush(); // on supprime l'employe

        return $this->redirectToRoute('app_employe');
    }

    #[Route('/employe/{id}', name: 'show_employe')]
    public function show(Employe $employe): Response
    {
        return $this->render('employe/show.html.twig', [
            'employe' => $employe,
        ]);
    }
}

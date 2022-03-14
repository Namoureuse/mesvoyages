<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Visite;
use App\Form\VisiteType;
use App\Repository\VisiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminVoyagesController
 *
 * @author Sonia
 */
class AdminVoyagesController extends AbstractController {
    /**
     * 
     * @var VisiteRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    
    /**
     * 
     * @param VisiteRepository $repository
     * @param EntityManagerInterface $om
     */
    public function __construct(VisiteRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }

        /**
     * @Route("/admin", name="admin.voyages")
     * @return Response
     */
    public function index(): Response {
        $visites = $this->repository->findAllOrderBy('datecreation', 'DESC');
        return $this->render("admin/admin.voyages.html.twig", [
            'visites' => $visites
        ]);
    }
    
    /**
     * @Route("/admin/suppr/{id}", name="admin.voyage.suppr")
     * @param Visite $visite
     * @return Response
     */
    public function suppr(Visite $visite): Response {
        $this->om->remove($visite);
        $this->om->flush(); //permet d'envoyer des ordres vers la bdd
        return $this->redirectToRoute('admin.voyages');
    }
    
    /**
     * @Route("/admin/edit/{id}", name="admin.voyage.edit")
     * @param Visite $visite
     * @param Request $request
     * @return Response
     */
    public function edit(Visite $visite, Request $request): Response {
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        $formVisite->handleRequest($request);
        if($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->om->flush();
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.edit.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
    
    /**
     * @Route("/admin/ajout", name="admin.voyage.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response {
        $visite = new Visite();
        $formVisite = $this->createForm(VisiteType::class, $visite);
        
        $formVisite->handleRequest($request);
        if($formVisite->isSubmitted() && $formVisite->isValid()) {
            $this->om->persist($visite); //enregistre en local la visite
            $this->om->flush();
            return $this->redirectToRoute('admin.voyages');
        }
        
        return $this->render("admin/admin.voyage.ajout.html.twig", [
            'visite' => $visite,
            'formvisite' => $formVisite->createView()
        ]);
    }
}

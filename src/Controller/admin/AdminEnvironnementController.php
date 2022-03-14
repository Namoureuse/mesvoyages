<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller\admin;

use App\Entity\Environnement;
use App\Repository\EnvironnementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AdminEnvironnementController
 *
 * @author Sonia
 */
class AdminEnvironnementController extends AbstractController{
    /**
     * 
     * @var EnvironmentRepository
     */
    private $repository;
    
    /**
     * 
     * @var EntityManagerInterface
     */
    private $om;
    
    /**
     * 
     * @param EnvironnementRepository $repository
     * @param EntityManagerInterface $om
     */
    function __construct(EnvironnementRepository $repository, EntityManagerInterface $om) {
        $this->repository = $repository;
        $this->om = $om;
    }
    
    /**
     * @Route("/admin/environnements", name="admin.environnements")
     * @return Response
     */
    public function index(): Response {
        $environnements = $this->repository->findAll();
        return $this->render("admin/admin.environnements.html.twig", [
            'environnements' => $environnements
        ]);
    }
    
    /**
     * @Route("/admin/environnement/suppr/{id}", name="admin.environnement.suppr")
     * @param Environnement $environnement
     * @return Response
     */
    public function suppr(Environnement $environnement): Response {
        $this->om->remove($environnement);
        $this->om->flush();
        return $this->redirectToRoute('admin.environnements');
    }
    
    /**
     * @Route("admin/environnement/ajout", name="admin.environnement.ajout")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response
     */
    public function ajout(\Symfony\Component\HttpFoundation\Request $request): Response {
        $nomEnvironnement = $request->get("nom");
        $environnement = new Environnement();
        $environnement->setNom($nomEnvironnement);
        $this->om->persist($environnement);
        $this->om->flush();
        return $this->redirectToRoute('admin.environnements');
    }
    
}

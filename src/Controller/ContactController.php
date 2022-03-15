<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of ContactController
 *
 * @author Sonia
 */
class ContactController extends AbstractController {
    /**
     * @Route("/contact", name="contact")
     * @return Response
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response {
        $contact = new Contact();
        $formContact = $this->createForm(ContactType::class, $contact);
        $formContact->handleRequest($request);
        if($formContact->isSubmitted() && $formContact->isValid()){
            // envoi du mail
            $this->envoiMail($mailer, $contact);
            $this->addFlash('succes', 'message envoyé');
            return $this->redirectToRoute('contact');
        }
        return $this->render("pages/contact.html.twig", [
            'contact' => $contact,
            'formcontact' => $formContact->createView()
        ]);
    }
    
    /**
     * @param \Swift_Mailer $mailer
     * @param Contact $contact
     */
    public function envoiMail(Swift_Mailer $mailer, Contact $contact){
        $message = (new Swift_Message('Message du site de voyages'))
            ->setFrom($contact->getEmail())
            ->setTo('contact@mesvoyages.fr')
            ->setBody(
                $this->renderView(
                    'pages/_email.html.twig', [
                        'contact' => $contact
                    ]
                ),
                'text/html'
            )
        ;
        $mailer->send($message);
    }
}

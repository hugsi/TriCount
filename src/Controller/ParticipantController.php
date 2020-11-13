<?php

namespace App\Controller;


use App\Entity\Participant;
use App\Form\ParticipantSupprimerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant", name="participant")
     */
    public function index()
    {
        return $this->render('participant/index.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }

    /**
     * @Route("/ardoises/supprimer/participants/{idPart}", name="participant_supprimer")
     */
    public function supprimer($idPart, Request $request)
    {
        //Initialiser le formulaire avec la bonne catégorie passée en parametre
        $repo = $this->getDoctrine()->getRepository(Participant::class);
        $participant= $repo->find($idPart);

        //Création du fomulaire
        $form = $this->createForm(ParticipantSupprimerType::class, $participant);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->remove($participant);

            //générer l'update
            $em->flush();

            //aller à la liste des catégories
            return $this->redirectToRoute("ardoises");
        }

        return $this->render("participant/supprimer.html.twig", [
            "formulaire" => $form->createView(),
            'participant' => $participant
        ]);
    }
}

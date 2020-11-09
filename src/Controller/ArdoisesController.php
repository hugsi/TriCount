<?php

namespace App\Controller;

use App\Entity\Ardoise;
use App\Entity\Join;
use App\Entity\Participant;
use App\Entity\Transaction;
use App\Form\AjouterTransacType;
use App\Form\ArdoiseSupprimerType;
use App\Form\ArdoiseType;
use App\Form\ParticipantAjouterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArdoisesController extends AbstractController
{
    /**
     * @Route("/", name="ardoises")
     */
    public function index()
    {
        //pour aller chercher les catégories : le repository
        $repo = $this->getDoctrine()->getRepository(Ardoise::class);

        //récupérer toutes les catégories
        $ardoises = $repo->findAll();

        return $this->render('ardoises/index.html.twig', [
            "ardoises" => $ardoises,
        ]);
    }

    /**
     * @Route("/ardoises/ajouter", name="ardoises_ajouter")
     */
    public function ajouter(Request $request)
    {
        //Créer une categorie vide
        $ardoise = new Ardoise();

        //Création du fomulaire
        $form = $this->createForm(ArdoiseType::class, $ardoise);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->persist($ardoise);

            //générer l'insert
            $em->flush();

            //aller à la liste des catégories
            return $this->redirectToRoute("ardoises");
        }

        return $this->render("ardoises/ajouter.html.twig", [
            "formulaire" => $form->createView()
        ]);
    }
    /**
     * @Route ("/ardoises/transac/{idJoin}", name="ardoises_transac")
     */
    public function transac($idJoin, Request $request){
        $repo = $this->getDoctrine()->getRepository(Join::class);
        $table = $repo->find($idJoin);

        $transaction = new Transaction();

        $form = $this->createForm(AjouterTransacType::class, $transaction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $transaction->setAssoc($table);
            $em->flush();
            }

        return $this->render("ardoises/ajouterTransac.html.twig", [
            "formulaire" => $form->createView(),
        ]);

    }
    /**
     * @Route ("/ardoises/depenses/{id}", name="ardoises_depenses")
     */
    public function depenses($id, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Ardoise::class);
        $ardoise = $repo->find($id);

        $repo1 = $this->getDoctrine()->getRepository(Participant::class);
        $participants = $repo1->findAll();

        $part = new Participant();

        //Création du fomulaire
        $form = $this->createForm(ParticipantAjouterType::class, $part);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->persist($part);
            $join = new Join();
            $join->setParticipant($part);
            $join->setArdoise($ardoise);
            $em->persist($join);
            $em->flush();
        }

        $repo2 = $this->getDoctrine()->getRepository(Join::class);
        $joinArdoise = $repo2->findBy(['ardoise' => $ardoise]);
        $repo3 = $this->getDoctrine()->getRepository(Transaction::class);
        $transaction = $repo3->findBy(['assoc' => $joinArdoise]);

        return $this->render("ardoises/modifier.html.twig", [
            "formulaire" => $form->createView(),
            "ardoise" => $ardoise,
            "joinArdoise" => $joinArdoise,
            "transaction" => $transaction,
            "participants" => $participants
        ]);


    }

    /**
     * @Route("/ardoises/modifier/{id}", name="ardoises_modifier")
     */
    public function modifier($id, Request $request)
    {

        //Initialiser le formulaire avec la bonne catégorie passée en parametre
        $repo = $this->getDoctrine()->getRepository(Ardoise::class);
        $ardoise = $repo->find($id);

        //Création du fomulaire
        $form = $this->createForm(ArdoiseType::class, $ardoise);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->persist($ardoise);

            //générer l'update
            $em->flush();

            //aller à la liste des catégories
            return $this->redirectToRoute("ardoises");
        }

        return $this->render("ardoises/modifierNom.html.twig", [
            "formulaire" => $form->createView(),
            "ardoise" => $ardoise
        ]);
    }

    /**
     * @Route("/ardoises/supprimer/{id}", name="ardoises_supprimer")
     */
    public function supprimer($id, Request $request)
    {
        //Initialiser le formulaire avec la bonne catégorie passée en parametre
        $repo = $this->getDoctrine()->getRepository(Ardoise::class);
        $ardoise = $repo->find($id);

        //Création du fomulaire
        $form = $this->createForm(ArdoiseSupprimerType::class, $ardoise);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->remove($ardoise);

            //générer l'update
            $em->flush();

            //aller à la liste des catégories
            return $this->redirectToRoute("ardoises");
        }

        return $this->render("ardoises/supprimer.html.twig", [
            "formulaire" => $form->createView(),
            'ardoises' => $ardoise
        ]);
    }
}



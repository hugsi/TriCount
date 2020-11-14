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
    public function transac($idJoin, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Join::class);
        $table = $repo->find($idJoin);

        $transaction = new Transaction();

        $form = $this->createForm(AjouterTransacType::class, $transaction);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($transaction);
            $transaction->setAssociation($table);
            $em->flush();

            return $this->redirectToRoute("ardoises_depenses", ["id" => $transaction->getAssociation()->getArdoise()->getId()]);
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

        $part = new Participant();

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
        $transaction = $repo3->findBy(['association' => $joinArdoise]);
        $idArd = $ardoise->getId();
        $em = $this->getDoctrine()->getManager();
        $sql =
            "SELECT SUM(t.valeur) as sommeArdoise, count(DISTINCT j.participant_id) as total_participant
        FROM `join` j
        LEFT JOIN `transaction` t ON t.association_id = j.id
        WHERE j.ardoise_id = $idArd";

        $connection = $em->getConnection();
        $cleanreq = $connection->prepare($sql);
        $cleanreq->execute();
        $result = $cleanreq->fetch();
        $sommeArdoise = $result['sommeArdoise'];
        $nbParticipant = $result['total_participant'];
        do {
            if ($nbParticipant == 0) {
                break;
            }
        $moyenne = $sommeArdoise / $nbParticipant;
        } while (0);


        $sql2 =
            "SELECT SUM(t.valeur) as sommeParticipant , p.nom as nom 
FROM participant p JOIN `join` j ON j.participant_id = p.id 
LEFT JOIN `transaction` t ON j.id = t.association_id
WHERE j.ardoise_id = $idArd 
GROUP BY j.participant_id";
        $connection = $em->getConnection();
        $cleanreq = $connection->prepare($sql2);
        $cleanreq->execute();
        $result2 = $cleanreq->fetchAll();

        $participants = array(
            'excedentaire' => array(),
            'deficitaire' => array()
        );
        foreach ($result2 as $participant) {
            $diff = $participant['sommeParticipant'] - $moyenne;
            // tu checks si le participant doit des sous (excedentaire == false) ou si on lui en doit (excedentaire == true)
            if ($diff <= 0) {
                $participant['diff'] = abs($diff);
                array_push($participants['deficitaire'], $participant);
            } else {
                $participant['diff'] = abs($diff);
                array_push($participants['excedentaire'], $participant);
            }
        }

        return $this->render("ardoises/modifier.html.twig", [
            "formulaire" => $form->createView(),
            "ardoise" => $ardoise,
            "joinArdoise" => $joinArdoise,
            "transaction" => $transaction,
            "sommeArdoise" => $sommeArdoise,
            "participants" => $participants,
            "totalParticipant" => $result2
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



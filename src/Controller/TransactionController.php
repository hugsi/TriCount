<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Form\TransactionModifierType;
use App\Form\TransactionSupprimerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends AbstractController
{
    /**
     * @Route("/transaction", name="transaction")
     */
    public function index()
    {
        return $this->render('transaction/index.html.twig', [
            'controller_name' => 'TransactionController',
        ]);
    }

    /**
     * @Route("/transaction/supprimer/{idTransac}", name="transaction_supprimer")
     */
    public function supprimer($idTransac, Request $request)
    {
        //Initialiser le formulaire avec la bonne catégorie passée en parametre
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $transaction= $repo->find($idTransac);

        //Création du fomulaire
        $form = $this->createForm(TransactionSupprimerType::class, $transaction);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->remove($transaction);

            //générer l'update
            $em->flush();

            return $this->redirectToRoute("ardoises_depenses",["id"=>$transaction->getAssociation()->getArdoise()->getId()]);
        }

        return $this->render("transaction/supprimer.html.twig", [
            "formulaire" => $form->createView(),
            'transaction' => $transaction
        ]);
    }

    /**
     * @Route("/transaction/modifier/{idTransac}", name="transaction_modifier")
     */
    public function modifier($idTransac, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Transaction::class);
        $transaction = $repo->find($idTransac);

        //Création du fomulaire
        $form = $this->createForm(TransactionModifierType::class, $transaction);

        //récupération du POST
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //Récupération de l'entity manager
            $em = $this->getDoctrine()->getManager();

            // dire au manager qu'on veut garder notre objet dans la BDD
            $em->persist($transaction);

            //générer l'update
            $em->flush();


            return $this->redirectToRoute("ardoises_depenses",["id"=>$transaction->getAssociation()->getArdoise()->getId()]);
        }

        return $this->render("transaction/modifier.html.twig", [
            "formulaire" => $form->createView(),
            "transaction" => $transaction
        ]);
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Saison;
use App\Entity\Sentences;

class ShuffleController extends AbstractController
{
    /**
     * @Route("/shuffle", name="shuffle")
     */
    public function index()
    {
        $saisons = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->findAll();

        return $this->render('shuffle/index.html.twig', [
            'saisons' => $saisons,
        ]);
    }

        /**
     * @Route("/shuffle/new}", name="newshuffle")
     */
    public function new()
    {
        $entityManager = $this->getDoctrine()->getManager();

        if(isset($_POST['name'])){
            $saison = new Saison();
            $saison->setName($_POST['name']);
            $entityManager->persist($saison);
            $entityManager->flush();
            return $this->redirectToRoute('getshuffle', [
                'id' => $saison->getId(),
            ]);

        }else{
            return $this->render('shuffle/new.html.twig', [
            ]);
        }


    }


    /**
     * @Route("/shuffle/{id}", name="getshuffle")
     */
    public function get($id)
    {
        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id);

        return $this->render('shuffle/get.html.twig', [
            'saison' => $saison,
        ]);
    }

     /**
     * @Route("/shuffle/increment/{id}", name="incrementshuffle")
     */
    public function increment($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id);

        $sentence = new Sentences();
        $sentence->setContent("");
        $sentence->setConstraintnumber("");
        $sentence->setSaison($saison);

        $entityManager->persist($sentence);

        $entityManager->flush();
        return $this->redirectToRoute('getshuffle', [
            'id' => $id,
        ]);

    }

    /**
     * @Route("/shuffle/delete/{id}", name="deleteshuffle")
     */
    public function delete($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($saison);
        $entityManager->flush();
        

        return $this->redirectToRoute('shuffle', [
            'id' => $id,
        ]);

    }

        /**
     * @Route("/shuffle/delete/{id}", name="shuffleshuffle")
     */
    public function shuffle($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id);

        
        $newSentencesTab=shuffleTab($saison.getSentences);

        $number = 1;
        foreach($s as $newSentencesTab){
            $s->setOrders = $number;
            $entityManager->remove($s);
            $entityManager->flush();
            $number++; 
        }
        

        return $this->redirectToRoute('shuffle', [
            'id' => $id,
        ]);

    }
 
}
function shuffleTab($tab){
    $array = Array();
    //TODO
    return $array; 
}
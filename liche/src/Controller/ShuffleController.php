<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Saison;
use App\Entity\Sentences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
     * @Route("/shuffle/increment/{id}/{nbr}", name="incrementshuffle")
     */
    public function increment($id, $nbr)
    {

        $entityManager = $this->getDoctrine()->getManager();
	
        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id);
		
        
        for ($i=1; $i<=$nbr; $i++){
      	    $sentence = new Sentences();
        	$sentence->setContent("");
       	 	$sentence->setConstraintnumber("");
        	$sentence->setSaison($saison);
		
        $entityManager->persist($sentence);
		
        }
        
        
        
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
    
            /**
     * @Route("/put", name="putsentences")
     */
    public function put()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $sentence = $this->getDoctrine()
        ->getRepository(Sentences::class)
        ->find($_POST['id']);

        $sentence->setContent($_POST['content']);
        $sentence->setConstraintnumber($_POST['constraint']);
        $entityManager->persist($sentence);

        $entityManager->flush();

        $response = new Response(
            'OK',
            Response::HTTP_OK,
            array('content-type' => 'text/html')
        );

        return $response;
    }
    /**
     * @Route("/shuffle/{id_saison}/delete/{id}", name="deletesentence")
     */
    public function deletesaison($id_saison, $id)
    {
    	$sentence = $this->getDoctrine()
    	->getRepository(Sentences::class)
    	->find($id);
    	
    	$entityManager = $this->getDoctrine()->getManager();
    	$entityManager->remove($sentence);
    	$entityManager->flush();
    	
    	return $this->redirectToRoute('getshuffle', [
    			'id' => $id_saison
    	]);
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
    
    
    
 

 
}

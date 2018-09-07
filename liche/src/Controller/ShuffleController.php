<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Saison;
use App\Entity\Sentences;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use src\Repository\SentencesRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;

class ShuffleController extends AbstractController
{





/**
     * @Route("/", name="home")
     */
    public function home()
    {
        

        return $this->render('shuffle/home.html.twig', [
        ]);
    }







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
            $saison->setisShuffle(false);
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
        
       
      
        $saison->setisShuffle(false);
        
        $entityManager->flush();
  
        return $this->redirectToRoute('getshuffle', [
        		'id' => $id,
        		
        		
        ]);

    }
    
    
    
    
    
    /**
     * @Route("/shuffle/shuffle/{id}", name="shufflethis")
     */
    public function shufflethis($id)
    {
    	$tenta=0;
    	
    	
    	$saison = $this->getDoctrine()
    	->getRepository(Saison::class)
    	->find($id);
    	
    	
    	
    	$val = set_time_limit(30);
    	
    	$errorconst=array();
    	$errorto="false";
    	$repo = $this->getDoctrine()->getRepository(Sentences::class);
    	$error=array();
    	$phrases=array();
    	$pavecc=array();
    	$psansc=array();
    	$arraycglo=array();
    	
    	/* count du nombre total */
    	$nombretotal = $repo->createQueryBuilder('phrases')
    	->select('COUNT(phrases)')
    	->where('phrases.saison=?1')
    	->setParameter(1, $id)
    	->getQuery()
    	->getSingleScalarResult();
    	
    	
    	$rangenumber=range(1,$nombretotal);
    	$arrayscglo=array();
    	$i=0;
    	
    	
    	
    	$test = $repo->findBy(array('saison' => $id));
    	
    	
    	
    	
    	
    	
    
    	
    	
    		
    		
    		
    	/* affichage des phrases */
    	
    
    	foreach($test as $try){
    		array_push($phrases, array('phrase'=> $try->getContent(),'const'=> $try->getConstraintnumber(),'id' =>$try->getId()));
    	}
   
		
    	
    	
  
    	#--------Séparation contraintes-------------
    	foreach($phrases as $phrase){
    		if ($phrase['const']){
    			array_push($pavecc, $phrase);
    			
    		}
    		else {
    			array_push($psansc, $phrase);
    		}
    		
    	}
    	#-------------------------------------------
    	
    	
    	
    	foreach($pavecc as $const){
    		
    		
    	
    		
    		
    		
    		
    		
    		#--------Traitement supp--------------------
    		
    		if (preg_match("/^\>/",$const['const'])) {
    			$tempsupp = preg_replace( '/[^0-9]/', '', $const['const']);
    			$tempsupp += 1;
    			if ($tempsupp>$nombretotal){
    				array_push($error, array('phrase'=>"votre contrainte '" . $const['phrase'] .  "' depasse le nombre de phrases, la phrase est supprimée")) ;
    				
    			}
    			else{
    				$numbsupp = rand($tempsupp ,$nombretotal);
    				while (array_search($numbsupp,array_column($arraycglo, 'nbr')) !== false) {
    					
    					
    					
    					
    					
    					
    					
    					$numbsupp = rand($tempsupp,$nombretotal);
    					
    					
    				}
    				
    				array_push($arraycglo, array('phrase'=> $const['phrase'],'nbr'=> $numbsupp, 'id'=>$const['id']));
    			}
    		}
    		#-------------------------------------------
    		
    		
    		#--------Traitement inf--------------------
    		if (preg_match("/^\</",$const['const'])) {
    			$tempinf = preg_replace( '/[^0-9]/', '', $const['const']);
    			$tempinf -= 1;
    			if ($tempinf>$nombretotal){
    				array_push($error, array('phrase'=>"votre contrainte '" . $const['phrase'] . "' depasse le nombre de phrases, la condition est ignorée"));
    				$tempinf = $nombretotal;
    				
    				$numbinf = rand(1,$tempinf);
    				while (array_search($numbinf, array_column($arraycglo, 'nbr')) !== false) {
    					
    					
    					
    					$numbinf = rand(1,$tempinf);
    					
    					
    					
    					
    					
    				}
    				array_push($arraycglo, array('phrase'=> $const['phrase'],'nbr'=> $numbinf, 'id'=>$const['id']));
    				
    			}
    			else{
    				$numbinf = rand(1,$tempinf);
    				while (array_search($numbinf, array_column($arraycglo, 'nbr')) !== false) {
    					
    					
    					
    					
    					
    					
    					
    					
    					$numbinf = rand(1,$tempinf);
    					
    				}
    				
    				array_push($arraycglo, array('phrase'=> $const['phrase'],'nbr'=> $numbinf, 'id'=>$const['id']));
    				
    			}
    		}
    		#-------------------------------------------
    		
    	
    		if (!preg_match("/^\</",$const['const'])){
    			if (!preg_match("/^\>/",$const['const'])){
    			
    			
    			array_push($errorconst, array('phrase'=>"vos contraintes doivent commencer par < ou >"));
    			
    			return $this->render('shuffle/get.html.twig', [
    					'errorconst' => $errorconst,
    					'saison'=> $saison,
    					
    			]);
    	      }
    		
    		}
    		
    		
    		
    		
    		
    		
    		
    		
    	}
    	
    	#--------unset les valeurs possibles--------------------
    	foreach($arraycglo as $try){
    		$uns = $try['nbr'];
    		$find = array_search($uns,$rangenumber);
    		unset($rangenumber[$find]);
    		
    	}
    	#-------------------------------------------
    	
    	
    	
    	
    	#--------le random--------------------
    	
    	shuffle($rangenumber);
    	#-------------------------------------------
    	
    	
    	#--------Ajout tableau phrases et nombre--------
    	
    	foreach($psansc as $phrasesansc){
    		array_push($arrayscglo, array('phrase'=> $phrasesansc['phrase'],'nbr'=> $rangenumber[$i], 'id' => $phrasesansc['id']));
    		$i+=1;
    	}
    	#-------------------------------------------
    	
    	#--------merge tableau avec et sans const--------
    	$arrayglo = array_merge($arraycglo,$arrayscglo);
   
    	#-------------------------------------------
    	
    	
    	if(empty($error)){
            $entityManager = $this->getDoctrine()->getManager();
            foreach($arrayglo as $save){
                $object = $this->getDoctrine()
                ->getRepository(Sentences::class)
                ->find($save['id']);
                
                $object->setOrders($save['nbr']);
            }
            $saison = $this->getDoctrine()
            ->getRepository(Saison::class)
            ->find($id);
            $saison->setisShuffle(true);
            $entityManager->flush();


        }
    	
    	

    	
    	
    	
    	return $this->render('shuffle/shuffle.html.twig', [
    			'id' => $id,
    			'error'=>$error,
    			'arrayglo'=>$arrayglo,
    			'saison'=>$saison,
    			'errorto'=>$errorto,
    			

    			
    	]);
    }
    
  
    
    
    
    
    
    /**
     * @Route("/shuffle/show/{id}", name="shuffleshow")
     */
    public function show($id)
    {
    
   
    	
    	
    	$repo = $this->getDoctrine()->getRepository(Sentences::class);
    	
    	
    	$show=$repo->findBySaison($id);
  
    	
  
    	
    	
    	
    	
    	
    	
    	return $this->render('shuffle/show.html.twig', [
    			'show'=>$show,
    			'id'=>$id,
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
     * @Route("/shuffle/put", name="putsentences")
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

        $saison = $this->getDoctrine()
        ->getRepository(Saison::class)
        ->find($id_saison);
        $saison->setisShuffle(false);


        
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

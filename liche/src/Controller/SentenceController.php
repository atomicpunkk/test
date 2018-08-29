<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sentences;
use Symfony\Component\HttpFoundation\Response;

class SentenceController extends AbstractController
{
    /**
     * @Route("/shuffle/{id_saison}/delete/{id}", name="deletesentence")
     */
    public function delete($id_saison, $id)
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
}

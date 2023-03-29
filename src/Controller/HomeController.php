<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class HomeController extends AbstractController
{
  /**
   * Page d'accueil
   * 
   * @return Response
   */
  #[Route(path: '/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
      
        //Tous les articles en bdd 
        $articles = $entityManager->getRepository(Article::class)->findAll();
        return $this->render('home/index.html.twig', [
            'articles' => $articles,
        ]);
    }
}

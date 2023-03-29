<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\ArticleType;

class ArticleController extends AbstractController
{
  /**
   * Visualiser un article
   * 
   * 
   * 
   * @param int $id Identifiant de l'article
   * 
   * @return Response
   */
    
   #[Route(path: '/{$id}', name: 'app_article')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        
    
         //On récupère l'article qui correspond à l'id passé dans l'url
         $article = $entityManager->getRepository(Article::class)->findBy(['id'=>$id]);
        
         return $this->render('article/index.html.twig', [
            'article' => $article,
        ]);
    }

    /**
     * Modifier/ajouter un article
     */
    #[Route(path: '/add', name: 'app_article_add')]
    public function edit(EntityManagerInterface $entityManager, Request $request, int $id=null):Response 
    {
    

         if($id) {
            $mode ='update';
            $article = $entityManager->getRepository(Article::class)->findBy(['id' => $id]);
         }
         else {
            $mode = 'new';
            $article = new Article();
         }

    $form = $this->createForm(ArticleType::class, $article);
    $form ->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
            $this->saveArticle($entityManager, $article, $mode);

            return $this->redirectToRoute('article_edit', array('id'=>$article->getId()));
    }

    $parameters = array(
        'form'=>$form,
        'article'=>$article,
        'mode'=>$mode
    );

    return $this->render('article/edit.html.twig', $parameters);
    }

/**
 * Supprimer un article
 */
public function remove(EntityManagerInterface $entityManager, int $id):Response
{
 //Entity Manager de Symfony 
 //On récupère l'article qui correspond à l'id passé dans l'url
 $article = $entityManager->getRepository(Article::class)->findBy(['id'=>$id]);

 //On supprime l'article de la bdd 
 $entityManager->remove($article);
 $entityManager->flush();

 return $this->redirectToRoute('homepage');

}


/**
 * Compléter l'article avec des informations avant enregistrement
 * 
 * @param Article $article
 * @param string $mode
 * 
 * @return Article
 */
    private function completeArticleBeforeSave(Article $article, string $mode){
        if($article->getDate()){
            $article->setDate(new \Datetime());
        }
        $article->setAuteur($this->getUser());

        return $article;
    }

/**
 * Enregistrer un article en bdd
 * 
 * @param Article $article
 * @param string $mode
 */
private function saveArticle(EntityManagerInterface $entityManager ,Article $article, string $mode){
    $article = $this->completeArticleBeforeSave($article, $mode);

    $entityManager->persist($article);
    $entityManager->flush();
    $this->addFlash('success', 'Enregistré avec succès');
}

}

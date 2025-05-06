<?php

namespace App\Controller;

use App\Entity\Article; // Importation de l'entité Article
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManager; // Importation de l'EntityManager (non utilisé ici)
use Doctrine\ORM\EntityManagerInterface; // Interface pour interagir avec la base de données via Doctrine
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController; // Contrôleur de base de Symfony
use Symfony\Component\HttpFoundation\Request; // Permet de gérer les requêtes HTTP
use Symfony\Component\Routing\Annotation\Route; // Permet de définir des routes via des annotations

class ArticleController extends AbstractController {

	// Définition d'une route accessible via /create-article, nommée "create-article"
	#[Route('/create-article', name: "create-article")]
	public function displayCreateArticle(Request $request, EntityManagerInterface $entityManager) {

		// Vérifie si la requête HTTP est de type POST (formulaire soumis)
		if ($request->isMethod("POST")) {

			// Récupération des données envoyées via le formulaire
			$title = $request->request->get('title');
			$description = $request->request->get('description');
			$content = $request->request->get('content');
			$image = $request->request->get('image');

			// méthode 1
			// permet de créer un article manuellement avec les setters
			// utiliser les fonctions "set"
			// pour remplir les données de l'instance de classe Article
			//$article = new Article();
			//$article->setTitle($title);
			//$article->setDescription($description);
			//$article->setContent($content);
			//$article->setImage($image);
			//$article->setIsPublished(true);
			//$article->setCreatedAt(new \DateTime());

			// méthode 2
			// création d'un article via le constructeur
			// permet de faire de l'encapsulation (passage des données via le constructeur)
			$article = new Article($title, $content, $description, $image);

			// Enregistre l'article en base de données
			// persist() prépare l'entité à être sauvegardée
			// flush() exécute la requête SQL d'insertion
			$entityManager->persist($article);
			$entityManager->flush();
		}

		// Affiche le formulaire de création d'article (même si la méthode est GET)
		return $this->render('create-article.html.twig');
	}

    #[Route('/list-articles', name: 'list-articles')] // Route définissant l'URL accessible pour lister les articles
    public function displayListArticles(ArticleRepository $articleRepository) {
    
        // permet de faire une requête SQL SELECT * sur la table article
        $articles = $articleRepository->findAll(); // Récupération de tous les articles en base via le repository
    
        return $this->render('list-articles.html.twig', [
            'articles' => $articles // Passage des articles à la vue Twig pour affichage
        ]);
    
        // Fin de la méthode displayListArticles
    }

    // Route définissant l'URL accessible pour afficher le détail d'un article
    #[Route('/detail-article/{id}', name: "detail-article")] 
    public function displayDetailArticle($id, ArticleRepository $articleRepository) {
    
        // Récupère l'article correspondant à l'identifiant fourni via le repository
        $article = $articleRepository->find($id);

        // si l'article n'a pas été trouvé pour l'id demandé
		// on envoie l'utilisateur vers la page qui affiche une erreur 404
		if (!$article) {
			return $this->redirectToRoute('404');
		}

        // Rend le template Twig 'detail-article.html.twig' en passant l'article comme variable
        return $this->render('detail-article.html.twig', [
        'article' => $article
    ]);


    }


	#[Route('/delete-article/{id}', name: "delete-article")]
	public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager) 
	{
		// pour supprimer un article, je dois d'abord le récupérer
		$article = $articleRepository->find($id);

		// j'utilise la méthode remove de la classe EntityManager qui prend en parametre l'article à supprimer
		$entityManager->remove($article);
		$entityManager->flush();

		// j'ajoute un message flash pour notifier que l'article est supprimé
		$this->addFlash('success', 'Article supprimé');

		// je redirige vers la page de liste
		return $this->redirectToRoute('list-articles');
	}

	#[Route(path: '/update-article/{id}', name: "update-article")]
public function displayUpdateArticle($id, ArticleRepository $articleRepository, Request $request, EntityManagerInterface $entityManager) {

	// Récupération de l'article correspondant à l'ID passé dans l'URL
	$article = $articleRepository->find($id);

	// Vérifie si la requête est de type POST (formulaire soumis)
	if ($request->isMethod("POST")) {

		// Récupération des données envoyées via le formulaire
		$title = $request->request->get('title');
		$description = $request->request->get('description');
		$content = $request->request->get('content');
		$image = $request->request->get('image');
					
		// Méthode 1 : mise à jour de l'article avec les fonctions set (setter)
		//$article->setTitle($title);
		//$article->setDescription($description);
		//$article->setContent($content);
		//$article->setImage($image);

		// Méthode 2 : mise à jour via une méthode update (respecte l'encapsulation)
		$article->update($title, $content, $description, $image);

		// Enregistre les modifications en base de données
		$entityManager->persist($article);
		$entityManager->flush();


		// j'ajoute un message flash pour notifier que l'article est supprimé
		$this->addFlash('success', 'Article modifié');


	
	}

	// Affiche le formulaire de mise à jour avec les données actuelles de l'article
	return $this->render('update-article.html.twig', [
		'article' => $article
	]);

}

}
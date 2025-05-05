<?php

namespace App\Controller;

use App\Entity\Article; // Importation de l'entité Article
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

}

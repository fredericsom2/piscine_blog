<?php

namespace App\Controller;

// Importation de l'entité Category
use App\Entity\Category;
// Importation du repository de Category pour interagir avec la base de données
use App\Form\CategoryForm;
use App\Repository\CategoryRepository;
use DateTime;
// Importation de l'EntityManager (non utilisé ici)
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityManager;
// Classe de base pour les contrôleurs Symfony
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Importation de la classe Request (non utilisée ici)
use Symfony\Component\HttpFoundation\Request;
// Annotation pour définir les routes
use Symfony\Component\Routing\Annotation\Route;

// Déclaration du contrôleur pour les catégories
class CategoryController extends AbstractController {

    // Définition de la route "/category" avec le nom "category"
    #[Route('/categories', name:'categories')]
    // Méthode pour afficher la liste des catégories
    public function displayListCategory(CategoryRepository $categoryRepository) {
        
        // Récupération de toutes les catégories depuis la base de données
        $categories = $categoryRepository->findAll();

        // Rendu du template Twig avec les données des catégories
        return $this->render('categories.html.twig', ['category' => $categories]);
    }

        #[Route('/categorie/{id}', name:'show-category')]
        public function showCategory(CategoryRepository $categoryRepository,$id) {
    
            $category = $categoryRepository->find($id);
    
            
    
            return $this->render('detail-category.html.twig', ['category' => $category]);
        }


        #[Route('/create-category', name: "create-category")]
        public function displayCreateCategory(Request $request, EntityManagerInterface $entityManager) {
    
            // je créé une instance de category
            $category = new Category();
    
            // je créé le formulaire 
            // en utilisant le gabarit de formulaire "CategoryForm" généré avec "make:form"
            // et l'instance de category
            $categoryForm = $this->createForm(CategoryForm::class, $category);
    
            // je stocke dans la variable du formulaire les données envoyées en POST
            $categoryForm->handleRequest($request);
    
            // je regarde s'il y a bien des données envoyées en POST
            if ($categoryForm->isSubmitted()) {
                // si oui, je sauvegarde la category
                // dont les propriétés ont été automatiquement remplies 
                // par symfony et le système de formulaire
                $category->setCreatedAt(new \DateTime());
                $entityManager->persist($category);
                $entityManager->flush();
            }
    
    
            return $this->render('create-category.html.twig', [
                'categoryForm' => $categoryForm->createView()
            ]);
    
        }

        // POUR MODIFIER LA CATEGORY
        #[Route("/update-category/{id}", name: "update-category")] // Déclare une route avec un paramètre {id} et un nom de route "update-category"
        public function displayUpdateCategory($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $entityManager) {
        
            $category = $categoryRepository->find($id); // Récupère la catégorie en base de données à partir de son ID
        
            $categoryForm = $this->createForm(CategoryForm::class, $category); // Crée un formulaire lié à l'entité Category
        
            $categoryForm->handleRequest($request); // Gère la requête HTTP et lie les données au formulaire
        
            if ($categoryForm->isSubmitted()) { // Vérifie si le formulaire a été soumis (mais pas si valide)
                $entityManager->persist($category); // Prépare l'entité à être enregistrée (pas nécessaire ici car l'objet est déjà géré)
                $entityManager->flush(); // Applique les changements en base de données
            }
            
            return $this->render('update-category.html.twig', [ // Rend le template Twig en passant la vue du formulaire
                'categoryForm' => $categoryForm->createView()
            ]);
        }
        
    
    }


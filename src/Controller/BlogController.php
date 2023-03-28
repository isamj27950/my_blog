<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\POstFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PostRepository $repo): Response
    {
        //1-je recupere tous les posts
        $posts = $repo->findAll();
        //dd($posts);
        //2.j'envoie la data a ma vue
        return $this->render('blog/index.html.twig',compact('posts'));    
    }

    #[Route('/post/{id}',name: 'app_show')]
    public function show($id,PostRepository $repo): Response
    {
        //je recupere le poste avec l'id
        $post = $repo->find($id);
        //je passe à la vue
        return $this->render('blog/show.html.twig', compact('post'));
    }

    #[Route('/post/delete/{id}',name: 'app_delete',methods: ['GET', 'DELETE'])]
    public function delete($id,PostRepository $repo, EntityManagerInterface $em): Response
    {
        //je recupere le poste avec l'id
        $post = $repo->find($id);
        //2-je supprime le post
        $em->remove($post);
        //3-vide la chasse
        $em->flush();
        //4-redirection vers la page d'accueil
        return $this->redirectToRoute('app_home');
    }

    #[Route('/create',name: 'app_create')]
    public function create(PostRepository $repo): Response
    {
        //1-creer un nv obj
        $post = new Post;
        
        //2-create form
        $form = $this->createForm(POstFormType::class,$post);
        $showForm = $form->createview();
        //3-envoie du formulaire ds la vue
        return $this->render('blog/create.html.twig',compact('showForm'));
    }


}

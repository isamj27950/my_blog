<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\POstFormType;
use Doctrine\ORM\EntityManager;
use App\Repository\PostRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Stmt\TryCatch;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

    #[Route('/create',name: 'app_create', methods: ['GET','POST'])]
    public function create( Request $request, EntityManagerInterface $em): Response
    {
        //1-creer un nv obj
        $post = new Post;
        
        //2-create form
        $form = $this->createForm(POstFormType::class,$post);

        //ajouter en bdd
        //1-recupere data de mes input
        $form->handleRequest($request);
        //2-soumision du formulaire
        if($form->isSubmitted() && $form->isValid()){
        //stock les data du user
            $newPost = $form->getData();
            //dd($newPost);
            //verifie si une img a ete choisi
            $imagePath = $form->get('url_img')->getData();
            if($imagePath){
                //donne a l'image un new name
                $newFileName = uniqid(). '.' . $imagePath->guessExtension();
                try {
                //deplacer l'image dans le dossier public/upload
                $imagePath->move(
                    $this->getParameter('kernel.project_dir') . '/public/upload',
                    $newFileName
                );
                } catch (FileException $e) {
                    return new Response($e->getMessage());
                }

                //apres avoir deplacer user image
                //j'envoie l'url en BDD en concatenant le dossier /upload + newfilename
                $newPost->setUrlImg('/upload/' . $newFileName);
            }
            //set le champ created_at avec la date de l'envoie du formulaire
            $newPost->setCreatedAt(new DateTimeImmutable());
            //persiste les data de user enter
            $em->persist($newPost);
            $em->flush();
            //redictection
            return $this->redirectToRoute('app_home');
        }
        

        //3-envoie du formulaire ds la vue
        return $this->render('blog/create.html.twig',[
            'showForm' => $form->createView()
        ]);
    }
    #[Route('/update/{id}',name: 'app_update', methods: ['GET','POST'])]
    public function update($id, PostRepository $repo, Request $request, EntityManagerInterface $em): Response
    {
        //1-Je recuperre le post avec l'id
        $post = $repo->find($id);
        //2-create form + prés rempli le formulaire avec $post
        $form = $this->createForm(POstFormType::class,$post);
        //Ajouter en bdd
        //1-recupere data de mes input
        $form->handleRequest($request);
        //recupere l'url de l'ima enBDD
        $imagePath = $form->get('url_img')->getData();
        //2-soumision du formulaire
        if($form->isSubmitted() && $form->isValid()){
            //verifie si une img a ete choisi
            if($imagePath){
                //on vérifie que l'image existe ds la db
                if($post->getUrlImg() !== null ){
                    //renomme l'image qu'il a choisit
                    $newFileName = uniqid(). '.' . $imagePath->guessExtension();
                    //donne a l'image un new name    
                    try {
                    //deplacer l'image dans le dossier public/upload
                    $imagePath->move(
                        $this->getParameter('kernel.project_dir') . '/public/upload',
                        $newFileName
                    );
                    }catch (FileException $e) {
                        return new Response($e->getMessage());
                    }
                    //je modifie l'urlimag ds $post
                    $post->setUrlImg('/upload/'. $newFileName);
                    $em->flush();
                    return $this->redirectToRoute('app_home');
                } else {
                    $post->setUrlImg($form->get('url_img')->getData());
                    $em->flush();
                    return $this->redirectToRoute('app_home');
                }
            }
            //recupere lesdata user s'il modifie ou non
            
            $post->setUpdatedAt(new DateTimeImmutable());
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        //3-envoie du formulaire ds la vue
        return $this->render('blog/update.html.twig',[
            'showForm' => $form->createView()
        ]);
    }
}

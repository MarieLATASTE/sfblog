<?php

namespace App\Controller;

use App\Entity\Article;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(LoggerInterface $logger): Response
    {
        $bdd_articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();
            //->find(2);

        // $logger->info(count($bdd_articles)); try1


        return $this->render('article/list.html.twig', [
            //    'number' => count($bdd_articles), try1
            'articles' => $bdd_articles,
            'subtitle' => 'I\'m late! I\'m late! For a very important date!',
            'description' => 'Why, sometimes I have believed as many as six impossible things before breakfast',
        ]);
    }

     /**
     * @Route("/article/{id}", name="detail")
     */
    public function detail($id): Response
    {
        $bdd_article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->find($id);
            //->find(2);

        // $logger->info(count($bdd_articles)); try1==>console.log de JS

        return $this->render('article/detail.html.twig', [
            //    'number' => count($bdd_articles), try1
            'article' => $bdd_article,
            'subtitle' => 'I\'m late! I\'m late! For a very important date!',
            'description' => 'Why, sometimes I have believed as many as six impossible things before breakfast',
        ]);
    }
    /**
     * @Route("/articlecreate", name="create")
     */
    public function create(Request $request, LoggerInterface $logger): Response
{
     $form = $this->createFormBuilder(null, array(
       'csrf_protection' => false,
  ))
        ->add('title', TextType::class, [
            'attr' => [
                'placeholder' => "Insert your title" 
            ]
        ])
        ->add('description', TextareaType::class, [
            'attr' => [
                'placeholder' => "Insert your text here"
            ]
        ])
        ->add('image', TextType::class, [
            'attr' => [
                'placeholder' => "Insert your image"
            ]
        ])
        ->add('save', SubmitType::class, [
            'label' => 'Create Your Article'])
        ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->handleRequest($request);
            // data is an array with "title", "image", and "description" keys
            $data = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $article = new Article();
            $article->setTitle($data["title"]);
            $article->setDescription($data["description"]);
            $article->setImage($data["image"]);

            $entityManager->persist($article);
            $entityManager->flush();
        }
    
        return $this->render('article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
      
}

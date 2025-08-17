<?php

namespace App\Controller;

use App\Controller\Admin\PlaneteCrudController;
use App\Entity\NewsletterSubscriber;
use App\Entity\Planete;
use App\Entity\User;
use App\Form\NewsletterForm;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

// use Symfony\Component\Messenger\Event\SendMessageToTransportsEvent;


final class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $planetes = $em->getRepository(Planete::class)->findAll();

        return $this->render('index/index.html.twig', [
            'planetes' => $planetes,
        ]);
    }

    // La page pour aller sur les informations d'une seule planète 

    #[Route('/planete/{id}', name: 'planete_show')]

    public function show(Planete $planete): Response
    {
        // Récupère la collection de satellites liés à cette planète
        $satellites = $planete->getSatellites();

        return $this->render('planete/show.html.twig', [
            'planete' => $planete,
            'satellites' => $planete->getSatellites(),
        ]);
    }


    // CODE FU FORMULAIRE 
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe')]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em, // Dépendance : em = Entity Manager = Gestionnaire d'entités
        MailerInterface $mailer
    ): Response {
        // Je crée une instance d'un inscrit
        $newsletterSubscriber = new NewsletterSubscriber();
        // Je crée un formulaire de newsletter et j'y relie l'instance
        $newsletterForm = $this->createForm(NewsletterForm::class, $newsletterSubscriber);

        // Je passe les données de la requête au formulaire
        // pour qu'il détermine s'il doit traiter les données POST ou non
        $newsletterForm->handleRequest($request);

        if ($newsletterForm->isSubmitted() && $newsletterForm->isValid()) {
            // entity manager 
            $em->persist($newsletterSubscriber);
            $em->flush();

            // message de notification à l'utilisateur
            $this->addFlash('success', "Votre inscription a bien été prise en compte");

            // Envoyer un email à l'utilisateur
            $email = (new Email())
                ->from('hello@example.com')
                ->to($newsletterSubscriber->getEmail())
                ->subject('Merci pour votre inscription!')
                ->text('Votre inscription à la newsletter SF a bien été prise en compte, mericii!')
                ->html('<p>Votre inscription à la newsletter SF a bien été prise en compte, mericii!</p>');

            $mailer->send($email);


            return $this->redirectToRoute('app_index');
        }

        return $this->render('index/newsletter_subscribe.html.twig', [
            'newsletter_form' => $newsletterForm
        ]);
    }

    //////// LA PAGE DU UPLOAD FICHIER DU USER
    #[Route('/upload', name: 'profile_upload')]
    public function upload(Request $request, 
    SluggerInterface $slugger,
    EntityManagerInterface $em): Response
    {
        $user  = new User;
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            dump($form->getData()); // ou dd($form)
            dump($form->getErrors(true, false));
        }
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('profilePic')->getData();
    
            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename =$safeFilename. uniqid() . '.' . $file->guessExtension();
                
                $file->move(
                    $this->getParameter('upload_directory'),
                    $newFilename
                );

                $user->setProfilePicFilename($newFilename);
                $em->persist($user);
                $em->flush();
    
                $this->addFlash('success', 'Fichier uploadé : ' . $newFilename);
            }
        }
    
        return $this->render('upload/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    //////////////EASYADMIN

    #[Route('/admin', name: 'admin')]
public function gerer(): Response
{
  //return parent::index();

  // Option 1. You can make your dashboard redirect to some common page of your backend
  //
  $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
  return $this->redirect($adminUrlGenerator->setController(PlaneteCrudController::class)->generateUrl());

  //...
}
    
}

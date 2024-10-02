<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/user")]
class UserController extends AbstractController
{


    public function __construct(private UtilisateurRepository $utilisateurRepository)
    {
    }

    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        $users=$this->utilisateurRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'menu' => [
                ['caption' => 'Utilisateurs', 'route' => 'app_user'],
                ['caption' => 'Ajouter un utilisateur', 'route' => 'user_add']
            ]
        ]);
    }
    
    #[Route("/add", name: 'user_add', methods: ['get'])]
    public function addUser():Response{
        $user =new Utilisateur();
        $user->setLogin("Bob");
        $form=$this->createForm(UtilisateurFormType::class,$user,[
            'action'=>$this->generateUrl('user_add')
        ]);
        $form->add('save',SubmitType::class,['label'=>'Valider']);
        return $this->render('user/add.html.twig',['form'=>$form,'menu'=>[
            ['caption'=>'Utilisateurs','route'=>'app_user'],
            ['caption'=>'Ajouter un utilisateur','route'=>'user_add']
        ]]);
    }

    #[Route('/add',methods:['post'])]
    public function submitAddUser(EntityManagerInterface $entityManager,Request $request,ValidatorInterface $validator):Response {
        $user=new Utilisateur();
        $form=$this->createForm(UtilisateurFormType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $user=$form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success',"L'utilisateur {$user->getLogin()} a été créé !");
        }else{
            $errors=$validator->validate($user);
            foreach ($errors as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }
        return  $this->redirectToRoute('app_user');
    }

    #[Route("/delete/{id}",name: "user_delete")]
    public function delete(int $id,EntityManagerInterface $entityManager){
        $user=$this->utilisateurRepository->find($id);
        if($user!=null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute("app_user");
    }
}

<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Entity\Operation;
use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use App\Repository\CompteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route("/user")]
class UserController extends AbstractController
{
    private UtilisateurRepository $utilisateurRepository;
    private CompteRepository $compteRepository;

    public function __construct(UtilisateurRepository $utilisateurRepository, CompteRepository $compteRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
        $this->compteRepository = $compteRepository;
    }

    #[Route('/', name: 'app_user')]
    public function index(): Response
    {
        $users = $this->utilisateurRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
            'menu' => [
                ['caption' => 'Utilisateurs', 'route' => 'app_user'],
                ['caption' => 'Ajouter un utilisateur', 'route' => 'user_add']
            ]
        ]);
    }

    #[Route("/add", name: 'user_add', methods: ['get'])]
    public function addUser(): Response
    {
        $user = new Utilisateur();
        $user->setLogin("Bob");
        $form = $this->createForm(UtilisateurFormType::class, $user, [
            'action' => $this->generateUrl('user_add')
        ]);
        $form->add('save', SubmitType::class, ['label' => 'Valider']);
        return $this->render('user/add.html.twig', [
            'form' => $form->createView(),
            'menu' => [
                ['caption' => 'Utilisateurs', 'route' => 'app_user'],
                ['caption' => 'Ajouter un utilisateur', 'route' => 'user_add']
            ]
        ]);
    }

    #[Route('/add', methods: ['post'])]
    public function submitAddUser(EntityManagerInterface $entityManager, Request $request, ValidatorInterface $validator): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(UtilisateurFormType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', "L'utilisateur {$user->getLogin()} a été créé !");
        } else {
            $errors = $validator->validate($user);
            foreach ($errors as $error) {
                $this->addFlash('danger', $error->getMessage());
            }
        }
        return $this->redirectToRoute('app_user');
    }

    #[Route("/delete/{id}", name: "user_delete")]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $this->utilisateurRepository->find($id);
        if ($user !== null) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute("app_user");
    }

    #[Route('/compte/{id}', name: 'compte_view')]
    public function viewCompte(int $id): Response
    {
        $compte = $this->compteRepository->find($id);
        if (!$compte) {
            throw $this->createNotFoundException("Le compte n'existe pas.");
        }

        return $this->render('view.html.twig', [
            'compte' => $compte,
        ]);
    }

    #[Route('/compte/{id}/operation/add', name: 'operation_add')]
    public function addOperation(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $compte = $this->compteRepository->find($id);
        if (!$compte) {
            throw $this->createNotFoundException("Le compte n'existe pas.");
        }

        $operation = new Operation();
        $operation->setCompte($compte);

        $form = $this->createFormBuilder($operation)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Débit' => 'debit',
                    'Crédit' => 'credit'
                ],
                'label' => 'Type d\'opération'
            ])
            ->add('montant', MoneyType::class, ['label' => 'Montant'])
            ->add('save', SubmitType::class, ['label' => 'Ajouter opération'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operation = $form->getData();

            // Modifie le solde en fonction du type d'opération
            if ($operation->getType() === 'debit') {
                $compte->setSolde($compte->getSolde() - $operation->getMontant());
            } else {
                $compte->setSolde($compte->getSolde() + $operation->getMontant());
            }

            $entityManager->persist($operation);
            $entityManager->flush();

            return $this->redirectToRoute('compte_view', ['id' => $id]);
        }

        return $this->render('Operation/add.html.twig', [
            'form' => $form->createView(),
            'compte' => $compte,
        ]);
    }
}

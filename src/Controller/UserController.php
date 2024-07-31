<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em, private RoleHierarchyInterface $roleHierarchy)
    {
    }

    #[Route('/update-role/{id}/{role}', name: "update_role", methods: ["POST"])]
    public function updateRole(Request $request, UserRepository $userRepository, int $id, String $role): Response
    {
        $user = $userRepository->find($id);

        if (!in_array($role, ['ROLE_USER', 'ROLE_ADMIN'])) {
            // TODO
        }

        // Remove all existing roles and add the new role
        $user->setRoles([$role]);
        $this->em->flush();

        return $this->redirectToRoute('admin.users');
    }
}

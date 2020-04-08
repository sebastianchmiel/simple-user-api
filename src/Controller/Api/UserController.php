<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request, Response, JsonResponse};
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Repository\User\UserRepository;
use App\Form\User\UserType;
use App\Utils\Form\ErrorFormatter;
use App\Utils\User\{Roles, UserConverter};

/**
 * @Route("/api/users")
 * 
 * User CRUD
 * 
 * @author Sebastian Chmiel <s.chmiel2@confronter.pl>
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"})
     * 
     * get all users
     * 
     * @param UserRepository $repository
     * 
     * @return JsonResponse
     */
    public function getAll(UserRepository $repository): JsonResponse
    {
        $users = $repository->findBy([], ['username' => 'ASC']);

        return new JsonResponse(UserConverter::multiToArray($users), Response::HTTP_OK);
    }

    /**
     * @Route("/{user<\d+>}", methods={"GET"})
     * 
     * get single user data
     * 
     * @param User|null $user
     * 
     * @return JsonResponse
     */
    public function getSingle(?User $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(UserConverter::toArray($user), Response::HTTP_OK);
    }

    /**
     * @Route("/", methods={"POST"})
     * 
     * add user
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * 
     * @return JsonResponse
     */
    public function add(Request $request, UserPasswordEncoderInterface $passwordEncoder): JsonResponse
    {
        $user = new User();

        return $this->saveUser($request, $passwordEncoder, $user);
    }

    /**
     * @Route("/{user<\d+>}", methods={"PATCH"})
     * 
     * edit user
     * 
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param User|null $user
     * 
     * @return JsonResponse
     */
    public function edit(Request $request, UserPasswordEncoderInterface $passwordEncoder, ?User $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->saveUser($request, $passwordEncoder, $user);
    }

    /**
     * @Route("/{user<\d+>}", methods={"DELETE"})
     * 
     * delete user
     * 
     * @param User|null $user
     * 
     * @return JsonResponse
     */
    public function delete(?User $user): JsonResponse
    {
        if (!$user) {
            return new JsonResponse(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        try {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        } catch (\Exception $ex) {
            return new JsonResponse(['error' => 'Something goes wrong'], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * save user (add or update)
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param User $user
     * 
     * @return JsonResponse
     */
    public function saveUser(Request $request, UserPasswordEncoderInterface $passwordEncoder, User $user): JsonResponse
    {
        $requestData = $request->request->all();

        $form = $this->createForm(UserType::class, $user);
        $form->submit($requestData, false);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                // encode the plain password
                if ($form->get('plainPassword')->getData()) {
                    $user->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    );
                }

                $user->setRoles([Roles::ROLE_USER, Roles::ROLE_API]);

                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return new JsonResponse(UserConverter::toArray($user), Response::HTTP_OK);
            } else {
                return new JsonResponse(['errors' => ErrorFormatter::format($form)], Response::HTTP_BAD_REQUEST);
            }
        }

        return new JsonResponse(['error' => 'Something goes wrong'], Response::HTTP_BAD_REQUEST);
    }
}

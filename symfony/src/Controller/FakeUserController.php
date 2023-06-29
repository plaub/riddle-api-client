<?php

namespace App\Controller;

use App\Models\DTO\FakeUserDTO;
use App\Models\DTO\ResponseDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FakeUserController extends AbstractController
{
    #[Route('/api/fake/user', name: 'app_fake_user')]
    public function index(): JsonResponse
    {
        $responseDTO = new ResponseDTO();
        $responseDTO->status = 200;
        $responseDTO->message = 'Success';

        $user = new FakeUserDTO();
        $user->UUID = '1234567890';
        $user->username = 'John Doe';
        $user->email = 'john@riddle.com';

        $responseDTO->data = $user;

        return $this->json($responseDTO);
    }
}

<?php

namespace App\Controller;

use App\Models\DTO\ResponseDTO;
use App\Models\DTO\RiddleDTO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RiddleController extends AbstractController
{
    private function getHttpClient(HttpClientInterface $httpClient): HttpClientInterface
    {
        return $httpClient->withOptions([
            'base_uri' => 'https://www.riddle.com',
            'headers' => [
                'X-RIDDLE-BEARER' => 'Bearer ' . $_ENV['RIDDLE_ACCESS_TOKEN'],
            ]
        ]);
    }

    #[Route('/api/riddle/list', name: 'app_riddle_list', methods: ['GET'])]
    public function index(HttpClientInterface $httpClient, SerializerInterface $serializer): JsonResponse
    {
        $response = $this->getHttpClient($httpClient)->request(
            'POST',
            '/api/v3/riddle/list'
        );

        $statusCode = $response->getStatusCode();

        $responseDTO = new ResponseDTO();
        $responseDTO->status = $statusCode;
        $responseDTO->requested_url = $response->getInfo('url');

        if ($statusCode !== 200) {
            $responseDTO->message = 'Error';

            return $this->json($responseDTO);
        }

        $content = $response->toArray();

        $responseDTO->message = 'Success';

        $filteredContent = array_values(array_filter($content['data'], function ($item) {
            return $item['published']['at'] !== null;
        }));

        $responseDTO->data = $serializer->deserialize(
            json_encode($filteredContent),
            RiddleDTO::class . '[]',
            'json'
        );

        return $this->json($responseDTO);
    }
}

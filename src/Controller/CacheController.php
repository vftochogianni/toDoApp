<?php

namespace ToDoApp\Controller;

use Psr\Log\LoggerInterface;
use ToDoApp\Application\Cache\CacheService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @codeCoverageIgnore
 */
class CacheController extends AbstractController
{
    private CacheService $service;
    private LoggerInterface $logger;

    public function __construct(CacheService $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function getAction(Request $request): Response
    {
        try {
            $cacheKey = $this->getKeyFromRequest($request);

            $item = $this->service->getCacheKey($cacheKey);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse($item, 200);
    }

    public function setAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true)['json'];

        try {
            $this->service->setCacheKey($this->getKeyFromRequest($request), $data['value']);
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(null, 204);
    }

    public function deleteAction(Request $request): Response
    {
        try {
            $this->service->deleteCacheKey($this->getKeyFromRequest($request));
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());

            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(null, 204);
    }

    private function getKeyFromRequest(Request $request): string
    {
        return $request->get('key');
    }
}

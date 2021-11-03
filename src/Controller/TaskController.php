<?php

namespace ToDoApp\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ToDoApp\Application\Task\Command\CompleteTask;
use ToDoApp\Application\Task\Command\CreateTask;
use ToDoApp\Application\Task\Command\DeleteTask;
use ToDoApp\Application\Task\Command\UpdateTaskName;
use ToDoApp\Application\Task\TaskService;
use ToDoApp\Domain\Exception\LengthTooLongException;
use ToDoApp\Domain\Exception\LengthTooShortException;
use ToDoApp\Domain\Exception\NotValidException;

class TaskController extends BaseController
{
    private TaskService $taskService;

    public function __construct(EventDispatcherInterface $eventDispatcher, LoggerInterface $logger, ManagerRegistry $registry, TaskService $taskService)
    {
        parent::__construct($eventDispatcher, $logger, $registry);
        $this->taskService = $taskService;
    }

    public function createAction(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';

        try {
            $taskId = $this->dispatchCommand(new CreateTask($name));
        } catch (NotValidException | LengthTooShortException | LengthTooLongException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], 400);
        }

        return new JsonResponse(['id' => $taskId->value()], 201);
    }

    public function updateAction(Request $request): Response
    {
        $id = (int) $request->get('id');
        $data = json_decode($request->getContent(), true);
        $name = $data['name'] ?? '';

        $this->dispatchCommand(new UpdateTaskName($id, $name));

        return new JsonResponse([], 204);
    }

    public function completeAction(Request $request): Response
    {
        $id = (int) $request->get('id');

        $this->dispatchCommand(new CompleteTask($id));

        return new JsonResponse([], 204);
    }

    public function deleteAction(Request $request): Response
    {
        $id = (int) $request->get('id');

        $this->dispatchCommand(new DeleteTask($id));

        return new JsonResponse([], 204);
    }

    public function getAllAction(Request $request): Response
    {
        return new JsonResponse($this->taskService->getAllTasks(), 200);
    }
}

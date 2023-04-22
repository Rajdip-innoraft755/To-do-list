<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * HomeController is to manage all tasks for to-do-list app it used for add,
 * remove, mark as done item in the list.
 *
 *   @author rajdip <rajdip.roy@innoraft.com>
 */
class HomeController extends AbstractController
{

  /**
   * It stores a object of EntityManagerInterface class
   * It is to manage persistance and retriveal Entity object from Database.
   *
   *   @var object
   */
  public $em;

  /**
   * It is to store the object of Task Class of Entity, it is used to set
   * and get the values of Task Class object.
   *
   *   @var object
   */
  public $task;

  /**
   * It is to store the object of TaskRepository class ,it used
   * to retrieve data of Task Entity.
   */
  public $taskRepo;

  /**
   * This is a constructor used for initialize an object of HomeController
   * class, the main use of this function to initialize all the important class
   * and interface's object which are used by the other functions this class.
   *
   *   @param EntityManagerInterface $em
   *     Accepts the EntityManagerInterface class object as argument.
   *
   *   @return void
   *     Constructor returns nothing.
   *
   */
  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
    $this->task = new Task();
    $this->taskRepo = $this->em->getRepository(Task::class);
  }

  /**
   * This function is to display the form to add task and tasks
   * previously added.
   *
   *   @Route("/", name = "index")
   *     This route takes user to the one and only page of this application.
   *
   *   @return Response
   *     Returns the reponse to the only page to display.
   */
  public function index(): Response
  {
    $task = $this->taskRepo->findAll();
    return $this->render("base.html.twig",[
      "task" => $task,
    ]);
  }

  /**
   * This function is to recieve the task data input by user and store it in
   * database.
   *
   *   @Route("/addtask", name = "/addtask")
   *     This route doesn't take user to a new page it just for response to the
   *     ajax call.
   *
   *   @param Request $rq
   *     Accepts the Request object to handle the user input data.
   *
   *   @return JsonResponse
   *     Returns the success message to the calling ajax function.
   */
  public function addTask(Request $rq): JsonResponse
  {
    $task = $rq->request->get("task");
    $taskTime = date("Y-m-d H:i:s", time());
    $this->task->setTaskContent($task);
    $this->task->setTaskAddTime($taskTime);
    $this->task->setDone("no");
    $this->em->persist($this->task);
    $this->em->flush();
    return new JsonResponse(json_encode([
      "success" => TRUE,
    ]));
  }

  /**
   * This function is to mark a task item as done based on the user's choice
   *
   *   @Route("/markasdone")
   *     This route doesn't take user to a new page it just for response to the
   *     ajax call.
   *
   *   @param Request $rq
   *     Accepts the Request object to handle the input data send by ajax call.
   *
   *   @return JsonResponse
   *     Returns the success message to the calling ajax function.
   */
  public function markAsDone(Request $rq): JsonResponse
  {
    $taskId = $rq->request->get("taskId");
    $this->task = $this->taskRepo->findOneBy([
      "id" => $taskId,
    ]);
    $this->task->setDone("yes");
    $this->em->persist($this->task);
    $this->em->flush();
    return new JsonResponse(json_encode([
      "success" => TRUE,
    ]));
  }

  /**
   * This function is to delete any item from the list based on the user's
   * choice.
   *
   *   @Route("/delete")
   *     This route doesn't take user to a new page it just for response to the
   *     ajax call.
   *
   *   @param Request $rq
   *     Accepts the Request object to handle the input data send by ajax call.
   *
   *   @return JsonResponse
   *     Returns the success message to the calling ajax function.
   */
  public function delete(Request $rq): JsonResponse
  {
    $taskId = $rq->request->get("taskId");
    $this->task = $this->taskRepo->findOneBy([
      "id" => $taskId,
    ]);
    $this->em->remove($this->task);
    $this->em->flush();
    return new JsonResponse(json_encode([
      "success" => TRUE,
    ]));
  }

  /**
   * This function is used for load the tasks without page refresh.
   *
   *   @Route("/task", name = "task")
   *     This route doesn't take user to a new page it just for response to the
   *     ajax call.
   *
   *   @return Response
   *     Returns the response with the task item to the calling ajax.
   */
  public function task(): Response
  {
    $task = $this->taskRepo->findAll();
    return $this->render("home/tasks.html.twig", [
      "task" => $task,
    ]);
  }
}

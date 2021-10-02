<?php

namespace App\Controller;

use App\Domain\Agregate\Building;
use App\Domain\Command\CheckInUserCommand;
use App\Domain\Command\CheckOutUserCommand;
use App\Domain\Command\RegisterNewBuildingCommand;
use App\Domain\DTO\BuildingsList;
use App\Domain\Query\BuildingDetailsDomainQuery;
use App\Domain\Query\ListBuildingsQuery;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\CommandBusHandler;
use App\Infrastructure\QueryBusHandler;
use App\Infrastructure\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends AbstractController
{
    public function __construct(
        private CommandBusHandler      $commandBus,
        private QueryBusHandler $queryBus,
    )
    {
    }


    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var BuildingsList $buildings */
        $buildings = $this->queryBus->query(new ListBuildingsQuery());


        return $this->render('building/index.html.twig', [
            'buildings' => $buildings
        ]);
    }

    #[Route("/register-new-building", name: "register")]
    public function register(Request $request): Response {
        $name     = $request->get("name");

        /** @var Uuid $uuid */
        $uuid = $this->commandBus->execute(new RegisterNewBuildingCommand($name));

        return $this->redirectToRoute("building", ["uuid" => $uuid->toString()]);
    }

    #[Route("/building/{uuid}", name: "building")]
    public function details(string $uuid): Response {

        $details = $this->queryBus->query(new BuildingDetailsDomainQuery($uuid));

        return $this->render('building/details.html.twig', ['building' => $details]);
    }

    #[Route("/building/{uuid}/checkin", name: "checkin")]
    public function checkin(string $uuid, Request $request): Response {
        $this->commandBus->execute(new CheckInUserCommand($uuid, $request->get("username")));

        return $this->redirectToRoute("building", ["uuid" => $uuid]);
    }
    #[Route("/building/{uuid}/checkout", name: "checkout")]
    public function checkout(string $uuid, Request $request): Response {
        $this->commandBus->execute(new CheckOutUserCommand($uuid, $request->get("username")));

        return $this->redirectToRoute("building", ["uuid" => $uuid]);
    }
}

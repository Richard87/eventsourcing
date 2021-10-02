<?php

namespace App\Controller;

use App\Domain\Agregate\Building;
use App\Domain\Command\CheckInUserCommand;
use App\Domain\Command\CheckOutUserCommand;
use App\Domain\Command\RegisterNewBuildingCommand;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\CommandBusHandler;
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
        private EntityManagerInterface $em,
        private CommandBusHandler      $commandBus,
    )
    {
    }


    #[Route('/', name: 'index')]
    public function index(): Response
    {
        /** @var list<\App\Domain\ReadModels\Building> $buildings */
        $buildings = $this->em->getRepository(\App\Domain\ReadModels\Building::class)->findAll();


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
        $building = $this->em->getRepository(\App\Domain\ReadModels\Building::class)->findOneBy(["uuid" => $uuid]);
        return $this->render('building/details.html.twig', ['uuid' => $uuid, "building" => $building]);
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

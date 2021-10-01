<?php

namespace App\Controller;

use App\Domain\Agregate\Building;
use App\Domain\Repository\BuildingRepository;
use App\Infrastructure\Uuid;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BuildingController extends AbstractController
{
    public function __construct(
        private BuildingRepository $buildingRepository,
        private EntityManagerInterface $em,
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
        $building = Building::new($name);

        $this->buildingRepository->persist($building);

        return $this->redirectToRoute("building", ["uuid" => $building->aggregateRootId()]);
    }

    #[Route("/building/{uuid}", name: "building")]
    public function details(string $uuid): Response {
        $building = $this->em->getRepository(\App\Domain\ReadModels\Building::class)->findOneBy(["uuid" => $uuid]);
        return $this->render('building/details.html.twig', ['uuid' => $uuid, "building" => $building]);
    }

    #[Route("/building/{uuid}/checkin", name: "checkin")]
    public function checkin(string $uuid, Request $request): Response {
        $building = $this->buildingRepository->retrieve(Uuid::fromString($uuid));

        $building->checkInUser($request->get("username"));
        $this->buildingRepository->persist($building);

        return $this->redirectToRoute("building", ["uuid" => $building->aggregateRootId()]);
    }
    #[Route("/building/{uuid}/checkout", name: "checkout")]
    public function checkout(string $uuid, Request $request): Response {
        $building = $this->buildingRepository->retrieve(Uuid::fromString($uuid));

        $building->checkOutUser($request->get("username"));
        $this->buildingRepository->persist($building);

        return $this->redirectToRoute("building", ["uuid" => $building->aggregateRootId()]);
    }
}

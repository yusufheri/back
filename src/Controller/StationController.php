<?php


namespace App\Controller;


use App\Entity\Station;
use App\Form\Type\SearchFilterType;
use App\Form\Type\StationType;
use App\Repository\StationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


/**
 * @Route ("/station")
 *@IsGranted("ROLE_ADMIN")
 */

class StationController extends AbstractController
{

    /**
     * @Route("/listStations", name="station_list", methods={"GET"})
     */
    public function list( Request $request)
    {

        $searchForm = $this->createForm(SearchFilterType::class);
        $searchForm->handleRequest($request);
        $repository = $this->getDoctrine()->getRepository(Station::class);
        $items = $repository->findAll();
//        $countPanels = $repository->CountPanelsByStation();
        //        dd($countPanels);


//        return $this->json($items);
//        return new Response($limit);
        return $this->render('/Stations/ListStations.html.twig',['stations'=> $items, 'search'=> $searchForm->createView(),]);
//        'countPanels'=> $countPanels
    }

    /**
     * @Route("/{id}", name="station_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("station", class="App:Station")
     */
    public function station($station)
    {

        return $this->render('Stations/DetailsStation.html.twig', ['station'=> $station] );
//        return $this->json($station
////            $this->getDoctrine()->getRepository(Station::class)->find($id)
//        );

    }

    /**
     * @Route("/list/{gouvernorat}", name="station_by_gouvernorat", methods={"GET"})
     * @ParamConverter("station", class="App:Station", options={"mapping": {"gouvernorat": "gouvernorat"}})
     */
    public function stationByGouvernorat($station)
    {
        return $this->json($station);
//            $this->getDoctrine()->getRepository(Station::class)->findOneBy(['adresse' => $adresse])
    }

    /**
     * @Route("/add", name="station_add", methods={"POST", "GET"})
     */
    public function add(Request $request)
    {
        $station = new Station();


        $form = $this->createForm(StationType::class,$station);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            var_dump($form->getData());die();
//            $event = $form->getData();
            $user=$this->getUser();
            $station->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($station);
            $entityManager->flush();

            return $this->redirectToRoute('station_list');
        }
        return $this->render('/Stations/AddStation.html.twig', array(
            'format' => $form->createView(),
        ));
//        /** @var Serializer $serializer */
//        $serializer = $this->get('serializer');
//
//        $station = $serializer-> deserialize($request->getContent(), Station::class, 'json');
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($station);
//        $em->flush();
//
//        return $this->json($station);

    }

    /**
     * @Route("/delete/{id}", name="station_delete", methods={"GET", "DELETE"})
     */
    public function delete(Station $station)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($station);
        $em->flush();
        return $this->redirectToRoute('station_list');
//        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/edit/{id}", name="station_edit",methods={"GET","POST"})
     * @ParamConverter("station", class="App:Station")
     */
    public function editPanel(Request $request, $station)
    {
        $form = $this->createForm(StationType::class,$station);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'edit successfully!');
            return $this->redirectToRoute('station_list');
        }

        return $this->render('Stations/EditStation.html.twig', [
            'format' => $form->createView()
        ]);
    }

    /**
     * @Route("/search", name="search_station")
     */
    public function recherche(Request $request ,StationRepository $repository) {

        $query = $request->request->get('search_filter')['search'];
        if($query){
            $stations= $repository->findAllQueryBuilder($query);
        }

        return $this->render('Stations/SearchStation.html.twig', ['stations' => $stations ]);
//        dump($request->request->get( "search_filter")['search']);die();
    }



}
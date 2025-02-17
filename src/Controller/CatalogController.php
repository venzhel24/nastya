<?php

namespace App\Controller;

use App\Entity\CatalogItem;
use App\Form\CatalogItemType;
use App\Service\CatalogLabelService;
use App\Service\CatalogService;
use App\Service\UploadService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/catalog')]
class CatalogController extends AbstractController
{
    public function __construct(
        private readonly CatalogService $catalogService,
        private readonly UploadService $uploadService,
        private readonly CatalogLabelService $labelService,
    ) {}

    /**
     * @throws InvalidArgumentException
     */
    #[Route('/', name: 'catalog_index', methods: ['GET'])]
    public function index(): Response
    {
        $catalogItems = $this->catalogService->getAllItems();
        $labels = $this->labelService->getCatalogLabels();

        return $this->render('catalog/catalog.html.twig', compact('catalogItems', 'labels'));
    }

    #[Route('/new', name: 'catalog_create', methods: ['GET'])]
    public function create(): Response
    {
        $form = $this->createForm(CatalogItemType::class);

        return $this->render('catalog/add_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/', name: 'catalog_store', methods: ['POST'])]
    public function store(Request $request): Response
    {
        $item = new CatalogItem();
        $form = $this->createForm(CatalogItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $photo = $form->get('photo')->getData();

            if ($photo) {
                $fileName = $this->uploadService->uploadFile($photo);
                $photoUrl = $this->uploadService->getFileUrl($fileName);
                $item->setPhotoPath($photoUrl);
            }

            $this->catalogService->saveItem($item);
            return $this->redirectToRoute('catalog_index');
        }

        return $this->render('catalog/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'catalog_edit', methods: ['GET'])]
    public function edit(CatalogItem $item): Response
    {
        $form = $this->createForm(CatalogItemType::class, $item);
        return $this->render('catalog/manage.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    /**
     * @throws ExceptionInterface
     */
    #[Route('/{id}', name: 'catalog_update', methods: ['POST', 'PUT'])]
    public function update(Request $request, CatalogItem $item): Response
    {
        $form = $this->createForm(CatalogItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $this->catalogService->saveItem($item);
            return $this->redirectToRoute('catalog_index');
        }

        return $this->render('catalog/manage.html.twig', [
            'form' => $form->createView(),
            'item' => $item,
        ]);
    }

    #[Route('/{id}', name: 'catalog_show', methods: ['GET'])]
    public function show(CatalogItem $catalogItem): Response
    {
        $labels = $this->labelService->getCatalogItemLabels();
        return $this->render('catalog/show.html.twig', compact('catalogItem', 'labels'));
    }

    #[Route('/{id}', name: 'catalog_delete', methods: ['DELETE'])]
    public function delete(CatalogItem $item): Response
    {
        $this->catalogService->deleteItem($item);
        return $this->redirectToRoute('catalog_index');
    }
}

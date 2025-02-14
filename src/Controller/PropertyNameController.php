<?php

namespace App\Controller;

use App\Service\PropertyNameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/admin/property-names')]
class PropertyNameController extends AbstractController
{
    private PropertyNameService $propertyNameService;

    public function __construct(PropertyNameService $propertyNameService)
    {
        $this->propertyNameService = $propertyNameService;
    }

    #[Route('/', name: 'property_names_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('catalog_index');
        }

        $labels = $this->propertyNameService->getAllLabels();

        if ($request->isMethod('POST')) {
            $newLabels = $request->request->all();
            foreach ($newLabels as $key => $value) {
                $this->propertyNameService->updateDisplayName($key, $value);
            }

            $this->addFlash('success', 'Метки обновлены!');

            return $this->redirectToRoute('property_names_edit');
        }

        return $this->render('admin/properties/catalog_props.html.twig', [
            'labels' => $labels,
        ]);
    }
}

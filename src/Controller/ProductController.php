<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tag;

use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Service\FileUploaderService;

/**
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * @Route("/list", name="product_index", methods="GET")
     */
    public function index(ProductRepository $productRepository): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
            'products' => $productRepository->findBy(array(), array('createdAt' => 'DESC')),
            ]
        );
    }

    /**
     * @Route("/create", name="product_new", methods="GET|POST")
     */
    public function new(Request $request, FileUploaderService $fileUploader, TagRepository $tagRepository): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('image_path')->getData() != null) {
                // retrieve file
                $file = $form->get('image_path')->getData();
                // upload service
                $fileName = $fileUploader->upload($file);
                $product->setImagePath($fileName);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_index');
        }

        return $this->render('product/new.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'tags' => $tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="product_show", methods="GET")
     */
    public function show(Product $product): Response
    {
        $productTags = $product->getTags();
        return $this->render('product/show.html.twig', [
                'product' => $product,
                'tags' => $productTags
            ]);
    }

    /**
     * @Route("/{id}/edit", name="product_edit", methods="GET|PATCH")
     */
    public function edit(Request $request, Product $product, FileUploaderService $fileUploader, TagRepository $tagRepository): Response
    {   
        $session = new Session();
        if($request->isMethod('GET')) {            
            $session->set('currentImage', $product->getImagePath());
        }  
        // Set PATCH to ignore missing input  
        $form = $this->createForm(ProductType::class, $product, ['method' => 'PATCH']);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image_path')->getData();
            // If product image removed or changed delete old file
            if($this->checkIfImageChanged($session->get('currentImage'), $file)) {
                $fileUploader->deleteUploadedFile($session->get('currentImage'));
            }
            // File upload
            if($file != null && !is_string($file)) {
                $fileName = $fileUploader->upload($file);
            } else {
                $fileName = $file;
            }  
            $product->setImagePath($fileName);           
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('product_edit', [
                'id' => $product->getId(),
                'tags' => $tagRepository->findAll()
            ]);
        }
        
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
            'tags' => $tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods="DELETE")
     */
    public function delete(Request $request, Product $product, FileUploaderService $fileUploader): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            if($product->getImagePath() !== null) {
                $fileUploader->deleteUploadedFile($product->getImagePath());
            }
            $em->remove($product);
            $em->flush();
        }

        return $this->redirectToRoute('product_index');
    }

    /**
     * Description: Return true if current product image is changed
     * @param $oldImage - current product related image
     * @param $newImage - new product's image
     */
    private function checkIfImageChanged($oldImage, $newImage)
    {
        if( $oldImage != $newImage && $oldImage != null) {
            return true;
        } else {
            return false;
        }
    }
}

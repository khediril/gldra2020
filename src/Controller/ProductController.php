<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    private $repo;

    public function __construct(ProductRepository $r)
    {
        $this->repo = $r;
    }

    /**
     * @Route("/add", name="product_add")
     */
    public function add()
    {
        $produit = new Product();

        $produit->setName("tomate");
        $produit->setPrice(1200);
        $produit->setDescription("Description de tomate");

        $em = $this->getDoctrine()->getManager();

        $em->persist($produit);
        $em->flush();

        return $this->render('product/add.html.twig', [
            'controller_name' => 'ProductController',
        ]);
    }
    /**
     * @Route("/add/{name}/{price}/{description}", name="product_add2")
     */
    public function add2($name, $price, $description)
    {
        $produit = new Product();

        $produit->setName($name);
        $produit->setPrice($price);
        $produit->setDescription($description);

        $em = $this->getDoctrine()->getManager();

        $em->persist($produit);
        $em->flush();

        return $this->render('product/add.html.twig', [
            'produit' => $produit,
        ]);
    }
    /**
     * @Route("/list", name="product.list")
     */
    public function list()
    {
        $produits = $this->getDoctrine()->getRepository(Product::class)->findAll();



        return $this->render('product/list.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/byprice/{min}/{max}", name="product_byprice")
     */
    public function listByPrice($min, $max)
    {
        $produits = $this->repo->findByPriceRange($min, $max);

        return $this->render('product/list.html.twig', [
            'produits' => $produits,
        ]);
    }
    /**
     * @Route("/show/{id}", name="produit.show")
     */
    public function show($id)
    {

        $produit = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   ' . $id . 'est inexistant...'
            );
        }

        return $this->render('product/show.html.twig', [
            'produit' => $produit,
        ]);
    }
    /**
     * @Route("/delete/{id}", name="product_delete")
     */
    public function delete($id)
    {
        $produit = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   ' . $id . 'est inexistant...'
            );
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($produit);
        $em->flush();
        return $this->redirectToRoute('product.list');
    }
    /**
     * @Route("/update/{id}/{description}", name="product_update")
     */
    public function update($id, $description)
    {
        $produit = $this->getDoctrine()->getRepository(Product::class)->find($id);

        if (!$produit) {
            throw $this->createNotFoundException(
                'Le produit de id :   ' . $id . 'est inexistant...'
            );
        }
        $em = $this->getDoctrine()->getManager();
        $produit->setDescription($description);
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('product.list');
    }
    /**
     * @Route("/formadd", name="product_formadd")
     */
    public function formadd(Request $request)
    {
        $produit = new Product();

        $form = $this->createFormBuilder($produit)
            ->add('name', TextType::class)
            ->add('price', IntegerType::class)
            ->add('description', TextareaType::class)
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,

                // uses the User.username property as the visible option string
                'choice_label' => 'id',

                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])

            ->add('image', FileType::class, [
                'label' => 'Image',

                // unmapped means that this field is not associated to any entity property
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Svp choisir un fichier de type jpeg ou png',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Ajouter'])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            //$task = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            ///////////////////////////////////////////////////
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('image')->getData();
            var_dump($brochureFile);
            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $originalFilename;
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $produit->setImage($newFilename);
            }





            ///////////////////////////////////////
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('product.list');
        }

        return $this->render('product/formadd.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Tag;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/product/{id<\d+>}", name="product")
     */
    public function detail(Product $product /*$id, Request $request, ProductRepository $productRepo*/)
    {
        // /product/5
        // $id = $_GET['id'];
        //$id = $request->get('id');
        //SELECT * FROM product WHERE id = :id
        //$product = $productRepo->find($id);
        return $this->render('product/detail.html.twig', [
            'product' => $product,
        ]);
    }


    /**
    * @IsGranted("IS_AUTHENTICATED_FULLY")
    * @Route("/product/add", name="add_product")
    * @Route("/product/edit/{id<\d+>}", name="edit_product")
    */
    public function addProduct(EntityManagerInterface $objectManager, Request $request, SluggerInterface $slugger, Product $product = null){
        //if($this->getUser() === null){
        //  return $this->redirectToRoute('home');
        //return $this->createAccessDeniedException();
        //return $this->createAccessDeniedException();
        //}
        if($product === null){
            $product = new Product();
        }
        $form = $this->createForm(ProductType::class);
        $form->add('submit', SubmitType::class, [
            'label' => ($product->getId() ? "Editer" : "Ajouter") . " votre produit"
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $product->setUser($user);
            $product->setRef(substr(str_shuffle(md5(random_int(0, 1000000))), 0, 25));
            foreach ($product->getTags() as $tag){
                if(empty($tag->getId())){
                    $tag->setSlug($slugger->slug($tag->getName())->lower()->toString());
                    $objectManager->persist($tag);
                }
            }
            $objectManager->persist($product);
            $objectManager->flush();
            return $this->redirectToRoute('profile');
        }
        return $this->render('product/product-form.html.twig',[
            'form' => $form->createView()
        ]);
    }


    /**
    * @Route("/product/tag/{slug}", name="products_by_tags")
    */
    public function productByTag(Tag $tag, ProductRepository $productRepository){
        // SELECT * FROM products WHERE tag_id = :tag_id
        $products = $productRepository->findByTag($tag);

        return $this->render('product/product_by_tags.html.twig', [
            'tag' => $tag,
            'products' => $products
        ]);
    }
}

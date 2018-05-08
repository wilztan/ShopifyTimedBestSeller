<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    private $homeController;
    private $product;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeController $homeController)
    {
        $this->url = DB::table('webconfigs')->where('name','API_url')->first()->value1;
        $this->HomeController = $homeController;
        $this->product = DB::table('products');
    }

    /**
    * Database Product Update
    * @return response result
    */
    public function index()
    {
       $product = $this->product;
       $product->truncate();
       $total_page = $this->HomeController->getPageCount($this->url."products/count.json?status=any&limit=250");
       $result = $this->insertProduct($product,$total_page);
       return $result;
    }

    /**
    * Insert Product to database
    * @param DB product
    * @param string total_page
    * @return JSON result
    */
    public function insertProduct($product,$total_page)
    {
        $product_refresh = 0;
        $error = 0;
        for($page = 0 ; $page< $total_page ; $page++){
            $url = $this->url."products.json?fields=id,tags,published_at&limit=250&page=".$page;
            $data = json_decode(file_get_contents($url));
            foreach ($data->products as $item) {
               if(
                   $product->insert([
                     'product_id'=>$item->id,
                     'product_tags'=>$item->tags,
                     'product_released'=>$item->published_at
                   ])
               )
                   $product_refresh++;
               else {
                   $error++;
               }
            }
        }
        return $result = [
            'error'=>$error,
            'insert'=>$product_refresh,
         ];
    }
}

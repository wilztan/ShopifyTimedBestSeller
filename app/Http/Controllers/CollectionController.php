<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class CollectionController extends Controller
{
    private $url;
    private $total_best;
    private $homeController;
    private $product;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(HomeController $homeController)
    {
        $webConfig = DB::table('webconfigs');
        $this->url = $webConfig->where('name','API_url')->first()->value1;
        $this->total_best = DB::table('webconfigs')->where('name','total_best')->first()->value1;
        $this->HomeController = $homeController;
        $this->product = DB::table('products');
    }

    /**
    * Deleting Previous Collection
    * @return Response JSON
    */
    public function refreshDB($type)
    {
        $url = "";
        if(DB::table('collections')->where('type','=',$type)->count()!=0){
            $collection = DB::table('collections')->orderBy('id', 'desc')->first();
            $url = $this->url."custom_collections/".$collection->collection_id.".json";
            return $this->HomeController->sendCollection(
              $url,
              false,
              'DELETE',
              $collection->collection_id
            );
        }
        return "No Database";
    }

    /**
    * Create Collection Body for Sending
    * @return JSON response
    */
    public function collection_body($order,$title)
    {
        $countedArray = array_count_values($order);
        arsort($countedArray);
        $top_item = array();
        $top_item = array_keys(array_slice($countedArray,0,$this->total_best,true));
        $response = array();
        $detail_collection = array();
        $detail_collection['title']=$title;
        $detail_collection['sort_order']='manual';
        $detail_collection['collects'] = array();
        $collection = array();
        for($item = 0 ; $item < $this->total_best ; $item++){
            if($item < count($top_item)){
                $collection["product_id"] = $top_item[$item];
                array_push($detail_collection["collects"],$collection);
            }
        }
        $response["custom_collection"] = $detail_collection;
        return $response;
    }

    /**
    * read Order from shopify
    * @return Array Order
    */
    public function readOrder($tags,$time)
    {

        $array = array();
        $total_page = $this->HomeController->getPageCount($this->url."orders/count.json?status=any&limit=250");
        $date = $this->HomeController->getDate("-".$time." days");
        for($page = 0 ; $page < $total_page ; $page++){
            $url = $this->url."orders.json?fields=line_items&status=any&limit=250&created_at_min=".$date."&page=".$page;
            $data = json_decode(file_get_contents($url));
            foreach ($data->orders as $datas) {
                foreach($datas->line_items as $line_item){
                    if($tags=0){
                        array_push($array,$line_item->product_id."");
                    }else{
                        $tag = $this->product->where('product_id','=',$line_item->product_id."")->first()->product_tags;
                        if(strpos($tags,$tag)!==false){
                            array_push($array,$line_item->product_id."");
                        }
                    }
                }
            }
        }
        return $array;
    }

    /**
    * get id of sent Collection
    * @return string collection_id
    */
    public function getCollectionId($result)
    {
        $collection_id="";
        foreach ($result  as $data) {
            $collection_id = $data->id;
        }
        return $collection_id;
    }

    /**
    * Save Collection to Database in Order to Delete it Later
    * @return string Message
    */
    public function saveCollection($result,$type)
    {
        $message = "";
        if(
            DB::table('collections')->insert([
                'collection_id' => $result,
                'type'=>$type
            ])
        ){
            $message = "Inserted ".$result;
        }else{
            $message = "Failed";
        }
        return $message;
    }

    /**
    * Get Best Seller By Tags
    * $type = "BestSellingThisMonth";
    * $tags = 0
    * @return response JSON
    */
    public function getBestSeller($type,$tags,$time)
    {
        $res = $this->refreshDB($type);

        $order = $this->readOrder($tags,$time);

        $response = $this->collection_body($order,$type);

        $url = $this->url."custom_collections.json";

        $result = $this->HomeController->sendCollection($url,true,"",json_encode($response));

        $result = json_decode($result);

        $collection_id = $this->getCollectionId($result);

        $message = $this->saveCollection($collection_id,$type);

        return $date = [
            'message' => $message,
            'deleted db' => $res,
        ];
    }
}

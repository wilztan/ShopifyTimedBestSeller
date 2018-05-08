<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /*
    * Check if Application can be use
    * return @Response
    */
    function get()
    {
        $db_name =  env('DB_DATABASE');
        try {
            echo "<h1>URL LIST</h1>";
            echo "<h4>url/product = Refresh Product</h4>";
            echo "<h4>/bestseller/{name}/{tagsor0forgeneral}/{timeonDays}</h4></br><p>example : url/bestseller/BestSellingThisMonth/0/30</p>";
        } catch (\Exception $e) {
            die("Could not connect to the database.  Please check your configuration.");
        }
    }

    /**
    * get total page of a Product, Collection, Etc
    * @return string page
    */
    public function getPageCount($url)
    {
        $page = json_decode(file_get_contents($url));
      	$page = $page->count;
      	$page = $page/250;
      	$page = ceil($page);
      	$page = number_format ($page);
      	return $page;
    }

    /**
    * Get Date for last Monthly
    * @return string DATE
    * 1 Month for "-1 month"
    * 2 Weeks for "-14 days"
    */
    function getDate($previous)
    {
        $date = date('Y-m-d', strtotime($previous));
    		return $date;
    }


    /**
    * Sending Request Through CURL
    * @return Response JSON
    */
    public function sendCollection($url,$header,$method,$post)
    {
      	$chs = curl_init();
    		curl_setopt($chs, CURLOPT_URL,$url);
        if($header==true){
            curl_setopt( $chs, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }
        if($method!=""){
            curl_setopt($chs, CURLOPT_CUSTOMREQUEST, $method);
        }
        if($post!==""){
            curl_setopt($chs, CURLOPT_POSTFIELDS, $post);
        }
			  curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
			  $results = curl_exec($chs);
			  curl_close($chs);
        return $results;
    }
}

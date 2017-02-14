<?php

namespace App\Http\Controllers;
use Exception;
use App\Images;
use DB;

class ImagesController extends Controller
{
	public function getImageList() {
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$images = Images::skip(30*$page)->take(30)->get();
		return view('image_list', [
        	'images'=>$images,
        	'current_page'=>$page,
        ]);	
	} 

}

<?php

namespace App\Http\Controllers;
use File;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
class PublicController extends Controller
{
	public function fetch(){
		$data = file_get_contents(public_path().'/data/data.json');
		//$data = json_decode($data);
		//print_R($data);exit;
		echo $data;
	}
	public function index(){
		
		return view('welcome');
	}
	public function add_product(){
		$rules = array(
      'name'  =>  'required',
      'quantity'  =>  'required|numeric',
      'price'=>'required',
		);
    $validator = Validator::make(Input::all(), $rules);
    if ($validator->fails()) {
      $return['result']=0; 
      $errors = $validator->errors()->getMessages();
      $i=0;
      foreach($errors as $k=>$val){
        $return['message'][$i]['name']=$k;
        $return['message'][$i]['message']=$val;
        $i++;
      } 
      
    }else{
			$contents = [
				'name'  =>  Input::get('name'),
				'quantity'  =>  Input::get('quantity'),
				'price'=>Input::get('price'),
				'created_at'=>date('Y-m-d H:i:s'),
			];
		
			$old_data = file_get_contents(public_path().'/data/data.json');
			$old_data = json_decode($old_data);
			
			array_push($old_data, $contents);
			
			$jsonData = json_encode($old_data);
			
			//$jsonData = json_encode($old_data);
			file_put_contents(public_path().'/data/data.json', $jsonData);
			$return['result'] = 1;
			
		}
		echo json_encode($return);
	}	
}
<?php
namespace App\Helpers;
use Ixudra\Curl\Facades\Curl;
use Session;
use DB;
use App\Models\Approvedorder;
use Illuminate\Support\Facades\Storage;


class commonHelper{
	
	public static function callAPI($method, $url, $data=array(),$files=array()){

        
		$url=env('APP_URL').'/api'.$url;

        if($method == 'GET'){

            return $response = Curl::to($url)
			->returnResponseObject()
            ->get();

        }elseif($method == 'PUT'){

            return $response = Curl::to($url)

            ->withData(['title'=>'Test', 'body'=>'body goes here', 'userId'=>1])
			->returnResponseObject()
            ->put();

        }elseif($method == 'DELETE'){

            return $response = Curl::to($url)

                ->delete();
        }elseif($method == 'patch'){

            return $response = Curl::to($url)

                ->withData(['title'=>'Test', 'body'=>'body goes here', 'userId'=>1])
				->returnResponseObject()
                ->patch();
        }elseif($method == 'POST'){

            return $response = Curl::to($url)
                ->withData($data)
				->returnResponseObject()
                ->post();
                
        }elseif($method == 'POSTFILE'){
			
            return $response = Curl::to($url)
                ->withData($data)
				->withFile($files['file_input'],$files['image_file'], $files['getMimeType'], $files['getClientOriginalName']) 
                ->post();
                
        }elseif($method == 'userTokenpost'){

            return $response = Curl::to($url)
                ->withData($data)
                ->withBearer(Session::get('userToken'))
				->returnResponseObject()
                ->post();
                
        }elseif($method == 'userTokenget'){
            return $response = Curl::to($url)
            ->withBearer(Session::get('userToken'))
			->returnResponseObject()
            ->get();
        }
        
    }
	public static function createShoppingWalletData($userId, $txnType, $amount, $status, $remark = '-')
    {
        $shoppingWallet = self::getShoppingWalletBalance($userId);
        $currentUserBalance = $shoppingWallet['crSuccess'] + $shoppingWallet['crPending'] - $shoppingWallet['drSuccess'] - $shoppingWallet['drPending'];

        $wallet = new ShoppingWallet();
        $wallet->txn_id = 'SHOP-' . strtotime("now") . rand(11, 99);
        $wallet->user_id = $userId;
        $wallet->type = $txnType;
        $wallet->amount = $amount;
        $wallet->status = $status;
        $wallet->save();

        $walletStatement = new Shoppingwalletstatement();
        $walletStatement->txn_id = $wallet->txn_id;
        $walletStatement->user_id = $userId;
        $walletStatement->opening_balance = $currentUserBalance;

        if ($txnType == 'Cr') {
            $walletStatement->credit_balance = $amount;
            $currentUserBalance += $amount;
        }

        if ($txnType == 'Dr') {
            $walletStatement->debit_balance = $amount;
            $currentUserBalance -= $amount;
        }

        $walletStatement->closing_balance = $currentUserBalance;
        $walletStatement->remark = $remark;
        $walletStatement->status = $status;
        $walletStatement->save();

        return ['txnid' => $wallet->txn_id];
    }

    public static function getShoppingWalletBalance($userId)
    {
        $result = \App\Models\ShoppingWallet::select(
            DB::raw("SUM(COALESCE(CASE WHEN type = 'Cr' AND (status='Success' OR status='Refund') THEN amount END,0)) as crSuccess"),
            DB::raw("SUM(COALESCE(CASE WHEN type = 'Cr' AND status='Pending' THEN amount END,0)) as crPending"),
            DB::raw("SUM(COALESCE(CASE WHEN type = 'Cr' AND status='Failed' THEN amount END,0)) as crFailed"),
            DB::raw("SUM(COALESCE(CASE WHEN type = 'Dr' AND status='Success' THEN amount END,0)) as drSuccess"),
            DB::raw("SUM(COALESCE(CASE WHEN type = 'Dr' AND status='Pending' THEN amount END,0)) as drPending")
        )
            ->where('user_id', $userId)
            ->first()
            ->toArray();

        return $result;
    }
	public static function buildMenu($parent, $menu, $sub = NULL) {

        $html = "";

        if (isset($menu['parents'][$parent])){
            if (!empty($sub)) {
                $html .= "<ul id=" . $sub . " class='ml-menu'><li class=\"ml-menu\">" . $sub . "</li>\n";
            } else {
                $html .= "<ul class='list'>\n";
            }

            foreach ($menu['parents'][$parent] as $itemId) {
                
				$active=(request()->is($menu['items'][$itemId]['active_link'])) ? 'active' :'';

				$terget = null;
                if (!isset($menu['parents'][$itemId])) { //if condition is false only view menu
                    $html.= "<li class='".$active."' >\n  <a $terget title='" . $menu['items'][$itemId]['label'] . "' href='" . url($menu['items'][$itemId]['link']) . "'>\n <em class='" . $menu['items'][$itemId]['icon'] . " fa-fw'></em><span>" . $menu['items'][$itemId]['label'] . "</span></a>\n</li> \n";
				}
				
                if (isset($menu['parents'][$itemId])) { //if condition is true show with submenu
                    $html .= "<li class='" . $active . "'>\n  <a onclick='return false;' class='menu-toggle' href='#" . $menu['items'][$itemId]['label'] . "'> <em class='" . $menu['items'][$itemId]['icon'] . " fa-fw'></em><span>" . $menu['items'][$itemId]['label'] . "</span></a>\n";
                    $html .= self::buildMenu($itemId, $menu, $menu['items'][$itemId]['label']);
                    $html .= "</li> \n";
                }
				
            }
            $html .= "</ul> \n";
			
        }

        return $html;

    }

	public static function getSidebarMenu(){
		
		if(Session::has('fivefernsadminmenu')){

			$result=Session::get('fivefernsadminmenu');

			$menu = array(
				'items' => array(),
				'parents' => array()
			);
	
			foreach ($result as $v_menu) {
				$menu['items'][$v_menu['menu_id']] = $v_menu;
				$menu['parents'][$v_menu['parent']][] = $v_menu['menu_id'];
			}
	
			return  \App\Helpers\commonHelper::buildMenu(0, $menu);
		}

	}
  
	public static function getOtp(){
		
        $otp = mt_rand(1000,9999);

        return $otp;
	}
	
	public static function sendMsg($url){
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_exec($ch);
		curl_close($ch);
	}

    public static function emailSendToUser($data){
        
        $to = $data['email'];
        $subject = $data['subject'];

		\Mail::send('email_templates.'.$data['template'],  ['data' => $data], function($message) use ($to, $subject) {
			$message->from(env('MAIL_USERNAME'), env('MAIL_FROM_NAME'));
			$message->subject($subject);  
			$message->to($to);
			
		});
	}
	   
    function uploadFile($file,$folder){

		$filename = strtotime(date('Y-m-d H:i:s')).rand(11,99) . '.' . $file->getClientOriginalExtension();
		$file->move(public_path('/uploads/'.$folder), $filename);

		// image convert in to webp
		$ext=$file->getClientOriginalExtension();
		$quality = "50";
		$dir = public_path('/uploads/'.$folder.'/');
		$img=imagecreatefromstring(file_get_contents($dir.$filename));
		unlink($dir.$filename);
		$filename =preg_replace('"\.(jpg|jpeg|png|webp)$"','.webp', $filename);  
		imagewebp($img, $dir.$filename, 50); 

		return $filename;
	}

	function uploadFileFromUrl($url, $folder)
{
    $imageContent = file_get_contents($url);
    $ext = pathinfo($url, PATHINFO_EXTENSION);

    // Generate a unique filename
    $filename = strtotime(now()) . rand(11, 99) . '.' . $ext;

    // Specify the disk you want to use (you may need to configure this in your filesystems.php)
    $disk = 'public';

    // Save the image to the specified disk with the given file name
    Storage::disk($disk)->put("uploads/{$folder}/{$filename}", $imageContent);

    // Convert the image to webp
    $dir = public_path("/uploads/{$folder}/");
    $img = imagecreatefromstring($imageContent);
    unlink($dir . $filename);
    $filename = preg_replace('"\.(jpg|jpeg|png|webp)$"', '.webp', $filename);
    imagewebp($img, $dir . $filename, 50);
// echo $filename;die;
    return $filename;
}

	public static function getCategoryTreeForAddCategory($parent_id) {
        $arr = array();
        $sql = \App\Models\Category::where([
									['status','1'],
									['parent_id',$parent_id],
									['store_id',\Auth::user()->id],
									['recyclebin_status','0']
									])->orderBy('name','ASC')->get();
        foreach ($sql as $row) {
			
			$final=[
				'id'=>$row->id,
				'title'=>ucfirst($row->name),
			];

			$childParent=\App\Helpers\commonHelper::getCategoryTreeForAddCategory($row->id);

			if(!empty($childParent)){
				$final['subs']=$childParent;
			}
           $arr[] = $final;
         }
        return $arr;
    }

	public static function getParentId($id){
		
		$idResult=commonHelper::getParentCategoryTreeId($id);

		return rtrim($idResult,',');
	
	}

	public static function getParentCategoryTreeId($id){
		
		$ids='';
		
		$result=\App\Models\Category::where('id',$id)->first();
		
		if(!empty($result)){
			
			if($result->parent_id>0){
				
				$ids.=commonHelper::getParentCategoryTreeId($result->parent_id);
			}
			
			$ids.=$result->id.',';
			
		}
		
		return $ids;
	}

	public static function getParentName($id){
		
		$nameResult=commonHelper::getCategoryTreeById($id);
		
		return rtrim($nameResult,' > ');
		
	}

	public static function getCategoryTreeById($id){
		
		$name='';
		
		$result=\App\Models\Category::where('id',$id)->first();
		
		if(!empty($result)){
			
			if($result->parent_id>0){
				
				$name.=commonHelper::getCategoryTreeById($result->parent_id);
			}
			
			$name.=ucfirst($result->name).' > ';
			
		}
		
		return $name;
	}

	public static function getCategoryTree($id){
		
		$category=[];
		
		$categoryResult=commonHelper::getSubcategoryById($id);
		
		if($categoryResult){
			
			foreach($categoryResult as $element){
					
				$childResult=commonHelper::getCategoryTree($element['id']);

				if($childResult){
					
					$element['child']=$childResult;
				}
				
				$category[]=$element;
			}
		}
		
		return $category;
	}

	public static function getSubcategoryById($id){
		
		return \App\Models\Category::where([
										['parent_id', $id],
										['status','1'],
										['recyclebin_status','0'],
										])->get()->toArray();
	}

	public static function getCategoryTreeForAddProduct($parent_id) {
		
        $arr = array();
        $sql = \App\Models\Category::where([
									['status','1'],
									['parent_id',$parent_id],
									['recyclebin_status','0']
									])->orderBy('name','ASC')->get();


        foreach ($sql as $row) {

			$data = \App\Helpers\commonHelper::getCategoryTreeForAddProduct($row->id);
			if(count($data)>0){
				$isSelectable= false;
			}else{
				$isSelectable= true;
			}
			

		   $final=[
				'id' => $row->id,
				'title'=>ucfirst($row->name),
				'isSelectable'=> $isSelectable,
			];

			$childParent=\App\Helpers\commonHelper::getCategoryTreeForAddProduct($row->id);

			if(!empty($childParent)){
				$final['subs']=$childParent;
			}
           $arr[] = $final;

        }
        return $arr;
    }

	public static function getVaraintDisplayLayoutName($id){
		
		$data=array(
			'1'=>'Dropdown swatch',
			'2'=>'visual swatch',
			'3'=>'Text swatch',
		);
		
		return $data[$id];
	}

	public static function getAttributeByparentId($id){

		return \App\Models\Variant_attribute::where('variant_id',$id)->where('status','1')->orderBy('sort_order','ASC')->get();
		
	}

	public static function getTotalProductByCategory($id){
		
		$query=\App\Models\Product::Select('products.name','products.category_id','variantproducts.id as variantproductid','variantproducts.sale_price','variantproducts.discount_type','variantproducts.discount_amount','variantproducts.slug','variantproducts.images')->where([
			['products.status','=','1'],
			['products.recyclebin_status','=','0'],
			['variantproducts.status','=','1'],
			['variantproducts.recyclebin_status','=','0'],
			['categories.recyclebin_status','=','0'],
			['categories.status','=','1'],
			])->join('variantproducts','variantproducts.product_id','=','products.id')
			->join('categories','products.category_id','=','categories.id')->groupBy('products.id');

		if($id){

			$getSlugCategoryId=\App\Models\Category::where('id',$id)->first();

			$childCategory=[];
			if($getSlugCategoryId){

				$childCategory=commonHelper::getCategoryTreeidsArray($getSlugCategoryId->id); 
				$childCategory[]=$getSlugCategoryId->id;

			} 

			$query->whereIn('products.category_id',$childCategory);

		}

		$product = $query->get()->toArray();
		
		return count($product);

	}

	public static function getCategoryTreeidsArray($id){

		$idsResult=commonHelper::getCategoryTreeids($id);
		
		$idArray=array();

		if(rtrim($idsResult,' , ')){

			$idArray=explode(',',rtrim($idsResult,' , '));
		}

		return $idArray;
		
	}
	
	public static function getCategoryTreeids($id){
		
		$ids="";
		
		$categoryResult=commonHelper::getSubcategoryById($id);
		
		if($categoryResult){
			
			foreach($categoryResult as $element){
					
				$childResult=commonHelper::getCategoryTreeids($element['id']);

				if($childResult){
					
					$ids.=$childResult;

				}

				$ids.=$element['id'].',';
			}
		}
		
		return $ids;
	}

	public static function getSkuCode($name,$no){

		$nameArray=explode(' ',$name);

		$sku="";
		foreach($nameArray as $namea){

			$sku.=ucfirst(substr($namea, 0, 1));
		}

		$sku.=str_pad($no, 4, '0', STR_PAD_LEFT);

		return $sku;
	}

	public static function getOfferProductPrice($salePrice,$discountType,$discountAmount){

		$discountAmount=$discountAmount;
		if($discountType=='1'){
			
			$discountAmount=round((($salePrice*$discountAmount)/100),2);
		}
		
		return $salePrice-$discountAmount;
	}

	public static function updateMenu($designationId){

		$user_menu=\App\Models\User_role::select('user_roles.*','menus.*','menus.id as menuid')->leftJoin('menus','user_roles.menu_id','=','menus.id')->where('user_roles.designation_id',$designationId)->where('menus.status','1')->orderBy('sort','ASC')->get()->toArray();
                
		$all_menu=\App\Models\Menu::get()->toArray();

		$restricted_link = array();
		foreach ($all_menu as $data1) {
			$duplicate = false;
			foreach ($user_menu as $data2) {
				if ($data1['id'] === $data2['menuid']) {
					$duplicate = true;
				}
			}
			if ($duplicate === false) {
				$restricted_link[] = $data1['link'];
			}
		}

		$exception_uris = $restricted_link;

		Session::put('fivefernsadminrexceptionurl',$exception_uris);
		Session::put('fivefernsadminmenu',$user_menu); 

	}
	
	public static function offerprice($salePrice, $discountType, $discountAmount) {
		if ($discountType == '1') {
			$discountedPrice = $salePrice * (100 - $discountAmount) / 100;
		} else if ($discountType == '2') {
			$discountedPrice = $salePrice - $discountAmount;
		} else {
			$discountedPrice = $salePrice;
		}
		
		return $discountedPrice;
	}

	public static function getVaraintName($variantIds,$variantAttributes){

		$attribute = '';

		if (strlen($variantIds) > 0  && ($variantIds[0] != '') && strlen($variantAttributes) > 0 && ($variantAttributes[0] !='')) {
		$variantArray = explode(',', $variantIds);
		$attributeArray = explode(',', $variantAttributes);
	}
	
		$variants = \App\Models\Variant::whereIn('id', $variantArray)->where('status', '1')->get();
		$attributes = \App\Models\Variant_attribute::whereIn('id', $attributeArray)->where('status', '1')->get();
	
		foreach ($variants as $variant) {
			$attributeResult = $attributes->firstWhere('variant_id', $variant->id);
			if ($attributeResult) {
				$attribute .= '<label class="labelbold">' . $variant->name . '</label>: ' . $attributeResult->title . ', ';
			}
		}
	
		return rtrim($attribute, ' ,');
	}

	public static function generateUniqueID($fullName) {
        // Extract first name and last name from the full name
        $names = explode(' ', $fullName);
        $firstName = $names[0];
        $lastName = isset($names[1]) ? $names[1] : '';
    
        // Get the first letter of the first name and the last name
        $firstNameInitial = strtoupper(substr($firstName, 0, 1));
        $lastNameInitial = strtoupper(substr($lastName, 0, 1));
    
        // Generate a random 5-digit number
        $randomNumber = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
    
        // Combine the initials and the random number to form the unique ID
        $uniqueID = $firstNameInitial . $lastNameInitial . $randomNumber;
    
        return $uniqueID;
    }

	public static function getPaymentStatusName($id){
		switch ($id) {
			case 0:
				return 'Payment Unpaid';
			case 1:
				return 'Failed';
			case 2:
				return 'Payment Paid';
			case 3:
				return 'Refund Initiated';
			case 4:
				return 'Refund In Progress';
			case 5:
				return 'Refund Completed';
			case 6:
				return 'Payment Initiated';
			case 7:
				return 'Payment Failed';
			case 8:
				return 'Refund Failed';
			default:
				return 'Unknown Payment Status';
		}
	}

	public static function creatShoppingWalletData($userId, $txnType,$amount,$status, $remark='-'){

		$shoppingWallet=commonHelper::getShoppingWalletBalance($userId);
		$currentUserBalance=$shoppingWallet['crSuccess']+$shoppingWallet['crPending']-$shoppingWallet['drSuccess']-$shoppingWallet['drPending'];
	
		$wallet=new \App\Models\ShoppingWallet();
		$wallet->txn_id='SHOP-'.strtotime("now").rand(11,99);
		$wallet->user_id=$userId;
		$wallet->type=$txnType;
		$wallet->amount=$amount;  
		$wallet->status=$status;
		$wallet->save();
	
	
		//create shopping wallet statement
		$walletStatement=new \App\Models\Shoppingwalletstatement();
		$walletStatement->txn_id=$wallet->txn_id;
		$walletStatement->user_id=$userId;
		$walletStatement->opening_balance=$currentUserBalance;
	
		if($txnType=='Cr'){
	
			$walletStatement->credit_balance=$amount;
			$currentUserBalance+=$amount;
		}
	
		if($txnType=='Dr'){
	
			$walletStatement->debit_balance=$amount;
			$currentUserBalance-=$amount;
		}
		 
		$walletStatement->closing_balance=$currentUserBalance;
		$walletStatement->remark=$remark;
		$walletStatement->status=$status;
		$walletStatement->save();
	
		return array('txnid'=>$wallet->txn_id);
	
	}
	
	
	
	
	
	public static function getProductDetailById($name){
		$urlData =\App\Models\Variantproduct::where('meta_title',$name)->first();
	
		if(!empty($urlData)){
	
			return $urlData;
		}else{
	
			return "Data not found.";
		}
	
	}

	public static function getStateNameById($id){
		
		$result=\App\Models\State::where('id',$id)->first();
		
		if($result){

			return ucfirst($result->name);

		}else{

			return 'N/A';

		}
	}

	public static function getCityNameById($id){
		
		$result=\App\Models\City::where('id',$id)->first();
		
		if($result){

			return ucfirst($result->name);

		}else{

			return 'N/A';

		}
		
	}

	

	
}


?>
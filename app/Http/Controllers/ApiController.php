<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\Controller;
    use Auth;
    use Voucherify\ClientException;
    use App\Models\Category;
    use App\Models\Product;
    use App\Models\Cart;


    class ApiController extends Controller{
        
        /**
         * Create a new controller instance.
         *
         * @return void
         */
        protected $jwt;
        private $post;
        private $token;
        private $status;
        private $jsondata;
        private $status_code;

        public function __construct(Request $request){
            $this->jsondata = (object)[];
            $this->message = "Success";
            $this->error_code = "no_error_found";
            $this->status = false;
            $this->status_code = 200;

            $json = json_decode(file_get_contents('php://input'),true);
            if(!empty($json)){
                $this->post = $json;
            }else{
                $this->post = $request->all();
            }
        }

        private function populateresponse($data){
            $data['message'] = (!empty($data['message']))?"":$this->message;
            $data['error'] = "";
            $data['error_code'] = "";

            if(empty($data['status'])){
                $data['status'] = $this->status;
                $data['error_code'] = $this->message;
                $data['error'] = $this->message;
            }
            
            $data['status_code'] = $this->status_code;
            
            $data = json_decode(json_encode($data),true);

            array_walk_recursive($data, function(&$item){
                
                if (gettype($item) == 'integer' || gettype($item) == 'float' || gettype($item) == 'NULL'){
                    $item = trim($item);
                }
            });

            if(empty($data['data'])){
                $data['data'] = (object) $data['data'];
            }

            $data['message'] = $data['message'];
            return $data;
        }

        /**
         * [This method is used for general] 
         * @param  Request
         * @return Json Response
         */

        public function getCategoryList(Request $request){
            $this->status       = true;

            $getCategory = Category::select('*')->get();

            $this->jsondata = [
                'category'  => $getCategory,
            ]; 

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );
        }

        public function getProductList(Request $request){
            $this->status       = true;

            $getProduct = Product::select('*')->get();

            $this->jsondata = [
                'product'  => $getProduct,
            ]; 

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );
        }

        public function getProductListCat(Request $request,$id=NULL){

            $this->status       = true;

            $getProductCat = json_decode(json_encode(Category::where('category.id',$id)->with(
                                [
                                    'products'=>function($q){
                                    }
                                ]
                            )->get()),true);

            $this->jsondata = [
                'productCat'  => $getProductCat,
            ]; 

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );
        }

        public function addCart(Request $request,$id=NULL){
            $validator = \Validator::make($request->all(), [
                "quantity" => 'required',
            ],[
                'quantity.required' => 'Enter Quantity'
            ]);

            if($validator->fails()){
                $this->message = $validator->messages()->first();
            }else{
                $this->status = true;
                $cart_exists  =Cart::where('user_id',\Auth::user()->id)->where('product_id',$id)->first();
                
                if(empty($cart_exists)){
                    $cartid = Cart::insert([
                                        'user_id'=>\Auth::user()->id,
                                        'product_id'=>$id,
                                        'quantity'=>$request->quantity,
                                        'created_at'=>date('Y-m-d h:i:s'),
                                        'updated_at'=>date('Y-m-d h:i:s'),
                                    ]);
                }
                else{
                    $cartid = Cart::where('user_id',\Auth::user()->id)
                                    ->where('product_id',$id)
                                    ->update([
                                        'quantity'=>$cart_exists->quantity+$request->quantity,
                                        'updated_at'=>date('Y-m-d h:i:s'),
                                    ]);
                }

                $data = Cart::select('*')->where('user_id',\Auth::user()->id)->get();

                $this->jsondata = [
                    'cart'  => $data,
                ]; 
                $this->message = 'Product has been succesfully added to cart.';

            }

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );

        }

        public function getCart(Request $request){

            $this->status       = true;
            $getCart = json_decode(json_encode(Cart::where('user_id',\Auth::user()->id)->with(
                            [
                                'productdetail'=>function($q){
                                }
                            ]
                        )->get()),true);

            $this->jsondata = [
                'productCat'  => $getCart,
            ]; 

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );
        }

    }

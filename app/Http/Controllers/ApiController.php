<?php

    namespace App\Http\Controllers;

    use App\Http\Requests;
    use Illuminate\Http\Request;
    use Illuminate\Validation\Rule;
    use Illuminate\Support\Facades\DB;
    use App\Http\Controllers\Controller;

    use Auth;
    use File;
    use Models\Listings;
    use Models\Industries;

    use Voucherify\VoucherifyClient;
    use Voucherify\ClientException;
    use App\Models\Category;
    use App\Models\Product;


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
            $data['error'] = /*trans(sprintf("general.%s",$data['message']));*/ $data['message'];
            $data['error_code'] = "";

            if(empty($data['status'])){
                $data['status'] = $this->status;
                $data['error_code'] = $this->message;
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

            $data['message'] = $data['message'];/*trans('general.'.$data['message']);*/
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
            // dd(json_decode(json_encode(Category::with(
            //     [
            //         'products'=>function($q){
            //         }
            //     ]
            // )->get()),true));
            // dd('111');
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

        /**
         * [This method is used for login] 
         * @param  Request
         * @return Json Response
         */

        public function login(Request $request){
            $request->replace($this->post);

            $validate = \Validator::make($request->all(), [
                'email'    => 'required|email|max:255',
                'password' => 'required',
            ],[
                'email.required'    => 'M0010',
                'email.email'       => 'M0011',
                'password.required' => 'M0013'
            ]);

            if($validate->fails()){
                $this->message = $validate->messages()->first();
            }else {
                $result = \Models\Users::findByEmail($this->post['email'],['id_user','password','type','first_name','last_name','name','email','status','api_token','chat_status','is_subscribed','latitude','longitude','currency','social_account']);
                $match = \Hash::check($this->post['password'], $result['password']);

                /*$token = Auth::attempt(['email' => $this->post['email'], 'password' => $this->post['password'], 'id_user' => $request['id_user']]);*/
                if(!empty($match)){
                    if(!empty($result)){
                        if($result['status'] == 'pending'){
                            $this->message = 'M0046';
                            $this->jsondata = [
                                'type'          => 'confirm',
                                'title'         => 'M0043',
                                'messages'      => 'M0046',
                                'button_one'    => 'M0044',
                                'button_two'    => 'M0045',
                                'token'         => ''
                            ];
                        }else if($result['status'] == 'inactive'){
                            $this->message = 'M0002';
                            $this->jsondata = [
                                'type'          => 'alert',
                                'title'         => 'M0026',
                                'messages'      => 'M0002',
                                'button'        => 'M0027',
                                'token'         => ''
                            ];
                        }else if($result['status'] == 'suspended'){
                            $this->message = 'M0003';
                            $this->jsondata = [
                                'type'          => 'alert',
                                'title'         => 'M0026',
                                'messages'      => 'M0003',
                                'button'        => 'M0027',
                                'token'         => ''
                            ];
                        }else if($result['status'] == 'trashed'){
                            $this->message = 'M0004';
                            $this->jsondata = [
                                'type'          => 'alert',
                                'title'         => 'M0026',
                                'messages'      => 'M0004',
                                'button'        => 'M0027',
                                'token'         => ''
                            ];
                        }else{
                            $device_uuid      = @(string)$this->post['device_uuid'];
                            $device_token     = @(string)$this->post['device_token'];
                            $device_type      = @(string)$this->post['device_type'];
                            $device_name      = @(string)$this->post['device_name'];
                            $latitude         = @(string)$this->post['latitude'];
                            $longitude        = @(string)$this->post['longitude'];
                            $thumb_configured = \Models\ThumbDevices::is_device_configured($result["id_user"],$device_uuid);
                            
                            if(!empty($device_uuid) && $thumb_configured == DEFAULT_NO_VALUE){
                                \Models\ThumbDevices::remove_touch_login($device_uuid);
                            }

                            $this->message    = 'M0000';
                            $this->status     = true;
                            $this->jsondata   = self::__dologin($result,$device_token,$device_type,$device_name,$latitude,$longitude,$device_uuid);
                        }
                    }else{
                        $this->message = 'M0004';
                        $this->jsondata = [
                            'type'          => 'alert',
                            'title'         => 'M0026',
                            'messages'      => 'M0004',
                            'button'        => 'M0027',
                            'token'         => ''
                        ];
                    }
                }else{
                    $this->message = 'M0004';
                    $this->jsondata = [
                        'type'          => 'alert',
                        'title'         => 'M0026',
                        'messages'      => 'M0004',
                        'button'        => 'M0027',
                        'token'         => ''
                    ];
                }
            }

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );            
        }

        /**
         * [This method is used for logout] 
         * @param  Request
         * @return Json Response
         */

        public function logout(Request $request){
            $request->replace($this->post);

            $device_token   = (string) trim($request->device_token);
            $this->status   = true;
            
            if(!empty($request->device_token)){
                $isDeviceRemoved = \Models\Devices::remove($request->id_user,$request->device_token);
            }

            return response()->json(
                $this->populateresponse([
                    'status' => $this->status,
                    'data' => $this->jsondata
                ])
            );     
        }

    }

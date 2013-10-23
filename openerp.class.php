<?php
/* 
 * BASED ON ORIGINAL:
 * 		OpenERP PHP connection script. Under GPL V3 , All Rights Are Reserverd , tejas.tank.mca@gmail.com
 *
 * 		@Author : Tejas L Tank.
 * 		@Email : tejas.tank.mca@gmail.com
 * 		@Country : India
 * 		@Date : 14 Feb 2011
 * 		@License : GPL V3
 * 		@Contact : www.facebook.com/tejaskumar.tank or www.linkedin.com/profile/view?id=48881854
 *
 * FORK DATAS
 * 
 * 		@Developer : Francesco OpenCode Apruzzese
 * 		@Email : cescoap@gmail.com
 * 		@Country : Italy
 * 		@Contact : http://www.linkedin.com/in/francescoapruzzese
 *
 */
session_start();

include("xmlrpc-2.2.2/lib/xmlrpc.inc");

class OpenERP {

	public $server = "http://localhost:8069/xmlrpc/";
	public $database = "test";
	public $uid = "";/**  @uid = once user succesful login then this will asign the user id */
	public $username = ""; /*     * * @userid = general name of user which require to login at openerp server */
	public $password = "";/** @password = password require to login at openerp server * */

	public function login($username = "admin", $password="a", $database="test", $server="http://localhost:8069/xmlrpc/") {

		$this->server = $server;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;

		$sock = new xmlrpc_client($this->server . 'common');
		$msg = new xmlrpcmsg('login');
		$msg->addParam(new xmlrpcval($this->database, "string"));
		$msg->addParam(new xmlrpcval($this->username, "string"));
		$msg->addParam(new xmlrpcval($this->password, "string"));

		$resp = $sock->send($msg);
		$val = $resp->value();
		$id = $val->scalarval();

		if ($id > 0) {
			$this->uid = $id;
			return $id; //* userid of succesful login person *//
			}
		else {
			return -1; //** if userid not exists , username or password wrong.. */
			}

		}

	public function create($values, $model_name) {

		$client = new xmlrpc_client($this->server . "object");

		//   ['execute','userid','password','module.name',{values....}]
		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
		$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
		$msg->addParam(new xmlrpcval($this->password, "string"));/** password */
		$msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
		$msg->addParam(new xmlrpcval("create", "string"));/** method which u like to execute */
		$msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
		$resp = $client->send($msg);

		if ($resp->faultCode())
			return -1; /* if the record is not created  */
		else
			return $resp->value()->scalarval();  /* return new generated id of record */

		}

	public function write($ids, $values, $model_name) {

		$client = new xmlrpc_client($this->server . "object");

		$id_val = array();
		$count = 0;
		foreach ($ids as $id)
			$id_val[$count++] = new xmlrpcval($id, "int");

		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
		$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
		$msg->addParam(new xmlrpcval($this->password, "string"));/** password */
		$msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
		$msg->addParam(new xmlrpcval("write", "string"));/** method which u like to execute */
		$msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
		$msg->addParam(new xmlrpcval($values, "struct"));/** parameters of the methods with values....  */
		$resp = $client->send($msg);

		if ($resp->faultCode())
			return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
		else
			return $resp->value()->scalarval();  /* return new generated id of record */

		}

	public function read($ids, $fields, $model_name) {

		$client = new xmlrpc_client($this->server."object");
		$client->return_type = 'phpvals';

		$id_val = array();
		$count = 0;
		foreach ($ids as $id)
			$id_val[$count++] = new xmlrpcval($id, "int");

		$fields_val = array();
		$count = 0;
                if ($fields!=null) {
                    foreach ($fields as $field)
                            $fields_val[$count++] = new xmlrpcval($field, "string");
                } else {
                    $fields_val=null;
                }

		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
		$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
		$msg->addParam(new xmlrpcval($this->password, "string"));/** password */
		$msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
		$msg->addParam(new xmlrpcval("read", "string"));/** method which u like to execute */
		$msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
		$msg->addParam(new xmlrpcval($fields_val, "array"));/** parameters of the methods with values....  */
		$resp = $client->send($msg);

		if ($resp->faultCode())
			return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
		else
			return $resp->value();

		}

	public function unlink($ids , $model_name) {

		$client = new xmlrpc_client($this->server . "object");
		$client->return_type = 'phpvals';

		$id_val = array();
		$count = 0;
		foreach ($ids as $id)
			$id_val[$count++] = new xmlrpcval($id, "int");

		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
		$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
		$msg->addParam(new xmlrpcval($this->password, "string"));/** password */
		$msg->addParam(new xmlrpcval($model_name, "string"));/** model name where operation will held * */
		$msg->addParam(new xmlrpcval("unlink", "string"));/** method which u like to execute */
		$msg->addParam(new xmlrpcval($id_val, "array"));/** ids of record which to be updting..   this array must be xmlrpcval array */
		$resp = $client->send($msg);

		if ($resp->faultCode())
			return -1;  /* if the record is not writable or not existing the ids or not having permissions  */
		else
			return 0;

		}

	function traverse_structure($ids) {

		$return_ids = array();
		$iterator = new RecursiveArrayIterator($ids);
		while ( $iterator -> valid() ) {
			if ( $iterator -> hasChildren() ) { 
				$return_ids = array_merge( $return_ids, $this->traverse_structure($iterator -> getChildren()) ); 
				} 
			else { 
				if ($iterator -> key() == 'int') {
					$return_ids = array_merge( $return_ids, array( $iterator -> current() ) );
					}
				}
			$iterator -> next(); 
			}
		return $return_ids;

		}
    
	public function searchFilter($relation,$attribute,$operator,$keys, $keys_type) {
            $key = array(
			new xmlrpcval(array(new xmlrpcval($attribute , "string"),
			new xmlrpcval($operator,"string"),
			new xmlrpcval($keys, $keys_type)),"array"),
			);
            return $this->search($relation, $key);
        }
            
        public function search($relation,$key=null) {

		$client = new xmlrpc_client($this->server . "object");

		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));  //* database name */
		$msg->addParam(new xmlrpcval($this->uid, "int")); /* useid */
		$msg->addParam(new xmlrpcval($this->password, "string"));/** password */
		$msg->addParam(new xmlrpcval($relation, "string"));
		$msg->addParam(new xmlrpcval("search", "string"));
                $msg->addParam(new xmlrpcval($key, "array"));
                
		$resp = $client->send($msg);
                if ($resp->faultCode()){
                    return 'Error: '.$resp->faultString();
                } else {
                   
                    $val = $resp->value();
                    $ids = $val->scalarval();
                
                    return $this->traverse_structure($ids);
                }
	}
	
	public function call_function($model,$function,$ids,$params) {

		$client = new xmlrpc_client($this->server . "object");

		$id_val = array();
		$count = 0;
		foreach ($ids as $id)
			$id_val[$count++] = new xmlrpcval($id, "int");
			
		$msg = new xmlrpcmsg('execute');
		$msg->addParam(new xmlrpcval($this->database, "string"));
		$msg->addParam(new xmlrpcval($this->uid, "int"));
		$msg->addParam(new xmlrpcval($this->password, "string"));
		$msg->addParam(new xmlrpcval($model, "string"));
		$msg->addParam(new xmlrpcval($function, "string"));
		$msg->addParam(new xmlrpcval($id_val, "array"));
		
		// Send parameter to function
		foreach ($params as $param){
			$param_value = $param[0];
			$param_type = $param[1];
			$msg->addParam(new xmlrpcval($param_value, $param_type));
			}

		// Functions return values
 		$resp = $client->send($msg);
		if ($resp->faultCode()){
			return 'Error: '.$resp->faultString();
			}
		else {
			$res = $resp->value();
			return $res;
			}
 
		}

}

?>

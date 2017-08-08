<?php

// Helper method to get a string description for an HTTP status code
// From http://www.gen-x-design.com/archives/create-a-rest-api-with-php/ 
function getStatusCodeMessage($status)
{
    // these could be stored in a .ini file and loaded
    // via parse_ini_file()... however, this will suffice
    // for an example
    $codes = Array(
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => '(Unused)',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported'
    );

    return (isset($codes[$status])) ? $codes[$status] : '';
}

// Helper method to send a HTTP response code/message
function sendResponse($status = 200, $body = '', $content_type = 'text/html')
{
    $status_header = 'HTTP/1.1 ' . $status . ' ' . getStatusCodeMessage($status);
    echo $body;
}
	function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
  $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
  $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
  $result = str_replace($escapers, $replacements, $value);
  return $result;
}
class GetProductInfo {

    private $db;
	private $route='f';



    // Constructor - open DB connection
    function __construct() {
		$includes_path = defined('INCLUDES_PATH') ? INCLUDES_PATH : 'includes/';
		include $includes_path . 'config.php';


	   $this->db = new PDO('pgsql:host='.$cfg['db_server'].';dbname='.$cfg['db_database'], $cfg['db_username'], $cfg['db_password']);
		$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
    }

    // Destructor - close DB connection
    function __destruct() {
      //  $this->db->close();
    }

    // Main method to redeem a code
    function redeem() {
    
        // Check for required parameters
        if (isset($_GET["x1"])&&isset($_GET["x2"])&&isset($_GET["y1"])&&isset($_GET["y2"])) {
			
		
	 			
				$stmt = $this->db->prepare("SELECT row_to_json(fc)
 FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
 FROM (SELECT 'Feature' As type
    , ST_AsGeoJSON(ST_Transform(lg.geom, 3857))::json As geometry
    , row_to_json((lg.seq, lg.cost, lg.name, lg.heading,lg.gid)) As properties
   FROM pgr_fromAtoBR('roads',?,?,?,?) As lg) As f )  As fc;");
  
 /*   $stmt = $this->db->prepare("SELECT *,st_asgeojson(geom) AS geojson FROM pgr_fromAtoB('roads',?,?,?,?)"); */
			

          $stmt->execute(array($_GET["x1"],$_GET["y1"],$_GET["x2"],$_GET["y2"]));
		
        }else{
			
		$stmt = $this->db->prepare("SELECT row_to_json(fc)
 FROM ( SELECT 'FeatureCollection' As type, array_to_json(array_agg(f)) As features
 FROM (SELECT 'Feature' As type
    , ST_AsGeoJSON(ST_Transform(lg.geom, 3857))::json As geometry
    , row_to_json((lg.gid,lg.type)) As properties
   FROM roads As lg) As f )  As fc;");
		$stmt->execute();
$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
			sendResponse(200, json_encode($res,true));	
        return true;

		}

		$res=$stmt->fetchAll(PDO::FETCH_ASSOC);
		
		
		        
/* $output    = '';
$rowOutput = '';
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	
	 /*    $rowOutput = (strlen($rowOutput) > 0 ? ',' : '') . '{"type": "Feature", "geometry": ' . $row['geojson'] . ', "properties": {';
    $props = '';
    $id    = '';
    foreach ($row as $key => $val) {
        if ($key != "geojson") {
            $props .= (strlen($props) > 0 ? ',' : '') . '"' . $key . '":"' . escapeJsonString($val) . '"';
        }
        if ($key == "id") {
            $id .= ',"id":"' . escapeJsonString($val) . '"';
        }
    }
    
    $rowOutput .= $props . '}';
    $rowOutput .= $id;
    $rowOutput .= '}'; 
    //$output .= $rowOutput;
    $output .= $row['row_to_json'];
   
   
} */


		//$result=array('result'=>$res);
	
	//	$this->route= json_encode($result);
			//$result=array('result'=>$output);
	
	//$manage =json_decode($res,true);
	sendResponse(200, json_encode($res,true));	
        return true;

    }
	
	function getjson(){
		return $this->route;
	}

}

// This is the first thing that gets called when this page is loaded
// Creates a new instance of the RedeemAPI class and calls the redeem method
header('Content-Type: text/html;');
$api = new GetProductInfo;
$api->redeem();
 //$getjson=$api->getjson();

?>



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
		echo '1';
        // Check for required parameters
        if (isset($_GET["x1"])&&isset($_GET["x2"])&&isset($_GET["y1"])&&isset($_GET["y2"])) {
			
		// Put parameters into local variables
           // $cars = $_GET["cars"];
            $shifts = $_GET["shifts"];
			$x1=$_GET["x1"];
			$y1=$_GET["y1"];
			$x2=$_GET["x2"];
			$y2=$_GET["y2"];
			
			//$carsA = explode(",", $cars);
			$shiftsA = explode(",", $shifts);
			
			//SELECT id FROM roads_vertices_pgr ORDER BY the_geom <-> st_transform(ST_SetSRID(ST_Point(x1,y1),4326),3857) LIMIT 1
			
			$startPid=$this->getSingleValue("SELECT id FROM roads_vertices_pgr ORDER BY the_geom <-> st_transform(ST_SetSRID(ST_Point(".$x1.",".$y1."),4326),3857) LIMIT 1","id");
			$endPid=$this->getSingleValue("SELECT id FROM roads_vertices_pgr ORDER BY the_geom <-> st_transform(ST_SetSRID(ST_Point(".$x2.",".$y2."),4326),3857) LIMIT 1","id");
			
			//SELECT gid FROM satleha,(select the_geom from roads_vertices_pgr where id=1) as ver     ORDER BY satleha.thegeom <-> ver.the_geom LIMIT 1
			
			
			/* $nextvertext=$this->looptable('select roads_vertices_pgr.id as verid,sa.*  from roads_vertices_pgr, (SELECT * from satleha where gid='.$firstbin.') as sa where sa.empty=0  ORDER BY sa.thegeom <-> roads_vertices_pgr.the_geom limit 1'); */
			
			$totalCapecity=$this->getSingleValue("select sum(capecity) from satleha ","sum");
			echo $totalCapecity;

			
			
		
	
			foreach ($shiftsA as $shift) {
				
				

				echo 'Shift:'.$shift."\r\n";
				 $round=0;
				 $filledCappecity=0;	
				 $this->fillsatlha();				 
				 $firstbin=$this->gettable("SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id=".$startPid.") as ver     ORDER BY satleha.thegeom <-> ver.the_geom LIMIT 1");
				while ($filledCappecity < $totalCapecity) {
				
				 echo 'filledCappecity:'.$filledCappecity."\r\n and totall is:".$totalCapecity;		
				
				 $round+=1;
				 echo 'Round:'.$round."\r\n";
				 $nextemptycar=$this->looptable("SELECT  * from cars where inuse=1  order by time");
					

				while  ($car = $nextemptycar->fetch(PDO::FETCH_ASSOC)){	
					$carCap=$car['capecity'];
					$carID=$car['id'];
					echo 'carid :'.$carID.'with cap'.$carCap."\r\n"; 
					if($filledCappecity > $totalCapecity){
						echo 'break on :'.$filledCappecity."\r\n"; 
						break;  
					}
					
					//SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id=1) as ver  ORDER BY satleha.thegeom <-> ver.the_geom
					if($round<2){
						//سری اول است که ماشین حرکت میکند. از نقطه شروع راه می افتد.سطل های اطراف
						//نقطه شروع باید جمع شود
						/* $allbins=$this->looptable('SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id='.$startPid.') as ver where satleha.empty=0  ORDER BY satleha.thegeom <-> ver.the_geom'); */
						$previousVertex=$startPid;
						
					}else
					{
						/* $allbins=$this->looptable('SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id='.$endPid.') as ver where satleha.empty=0  ORDER BY satleha.thegeom <-> ver.the_geom'); */
						$previousVertex=$endPid;
						
					}
					
					///سطل قبلی همیشه نزدیکترین سطل به محل وجود خودرو یعنی
					//prrviousVertex است
					 $firstbin=$this->gettable("SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id=".$previousVertex.") as ver     ORDER BY satleha.thegeom <-> ver.the_geom LIMIT 1");
					$Periviousbin=$firstbin['gid'];
					echo 'Periviousbin'. $Periviousbin;
					$satlcount=0;
					$carFilledCap=0;
					
					//این وایل مشکل دارد
					while ($this->isanysatl()) {
						
					
							$nextvertext=$this->gettable('select roads_vertices_pgr.id as verid,sa.*   from roads_vertices_pgr, (select s1.* from satleha as s1,(SELECT * from satleha where gid='.$Periviousbin.') as s2 WHERE s1.empty=0 ORDER BY s1.thegeom <-> s1.thegeom LIMIT 1) as sa ORDER BY sa.thegeom <-> roads_vertices_pgr.the_geom limit 1');
							$satlCap=$nextvertext['capecity'];
							
							$satlId=$nextvertext['gid'];
							$nearestvertextosatl=$nextvertext['verid'];
							
						echo 'Periviousbin'. $Periviousbin;
						echo 'nearestvertextosatl'. $nearestvertextosatl;

							
							$carFilledCap+=$satlCap;
							$filledCappecity+=$satlCap;
							//echo 'satl:'.$satlId;
							if($carFilledCap>$carCap){
								//باید برود به مقصد. از سطل آخر
								//باید از previousVertex به nearestvertextosatl ّرود
								$this->routeFromPids($previousVertex,$endPid,$carID,$shift,$round);
								echo 'break by car:'.$filledCappecity."\r\n"; 
								echo 'Car Full cap:'.$carFilledCap."\r\n"; 
								break;  
							}else
							{
								
								
								$this->routeFromPids($previousVertex,$nearestvertextosatl,$carID,$shift,$round);
								$this->emptysatl($satlId,$carID,$shift,$round);
								
								///بایستی نزدیکترین سطل خالی به سطل قبلی مشخص شود
								$Periviousbin=$satlId;
								$previousVertex=$nearestvertextosatl;
								$satlcount+=1;
							}
							
						}
						
						
						
						///بایستی زمان رفت و برگشت ماشین محاسبه و به روز رسانی شود و از لوپ خارج شود
					//	select sum(cost) from result where car=1 and shift=1 and reount=
					$catCost=$this->	getSingleValue('select sum(cost) as c from result where car='.$carID.' and shift='.$shift.' and reount='.$round,'c');
					$finalcost=(2*$catCost)+($satlcount*100);
					$this->savetimeforcar($carID,$finalcost);
					
					
					}
					
				}	
					
					
			}
	 			 
				

    }
	}
	
	function isanysatl(){
		//SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id='.$endPid.') as ver where satleha.empty=0
		
		  $q = $this->db->query('SELECT count(*) as c FROM satleha where satleha.empty=0');
		  $f = $q->fetch();
		  $result = $f['c'];
		
		
		if($result>0){
			return true;
		}else{
			return false; 
		}
	}
	
	
	function getSingleValue($query,$columnName)
		{
		  $q = $this->db->query($query);
		  $f = $q->fetch();
		  $result = $f[$columnName];
		  return $result;
		}
	function gettable($query)
		{
		  $q = $this->db->query($query);
		  $f = $q->fetch();
		  //$result = $f[$columnName];
		  return $f;
		}
	
	function looptable($query){
		$stmt = $this->db->prepare($query);
		
		if ($stmt->execute()) {
			return $stmt;
		}
		return null;
	}
	
	function emptysatl($id,$carID,$shift,$round){
		$stmt = $this->db->prepare('UPDATE  satleha SET  empty=1 WHERE gid='.$id);
		
		if ($stmt->execute()) {
			$stmt = $this->db->prepare("INSERT INTO salinfo ( gid, shift, round, car) VALUES (".$id.",".$shift.",".$round.",".$carID.")");
			$stmt->execute();
			return $stmt;
		}
		return null;
	}

	function savetimeforcar($car,$time){
		$stmt = $this->db->prepare('UPDATE  cars SET  time='.$time.' WHERE id='.$car);
		
		if ($stmt->execute()) {
			return $stmt;
		}
		return null;
	}
	

	
	function fillsatlha(){
		$stmt = $this->db->prepare('UPDATE  satleha SET  empty=0');
		
		if ($stmt->execute()) {
			return $stmt;
		}
		return null;
	}
	
 	function routeFromPids($start,$end,$car,$shift,$round){
		$shiftcolumn='shif4';
		switch ($shift) {
			case 1:
				$shiftcolumn='shif1';
				break;
			case 2:
				$shiftcolumn='shif2';
				break;
			case 3:
				$shiftcolumn='shif3';
				break;
			
			default:
				$shiftcolumn='shif4';
		}
		
		
		
		
		$query="insert INTO result (geom, cost, car, shift,reount) SELECT st_union(b.thegeom) as geom,sum(a.cost) as cost,".$car.", ".$shift.",".$round." FROM pgr_dijkstra(' SELECT gid AS id,         source,         target,       (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS cost, (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS reverse_cost      FROM roads',   ".$start.", ".$end.", directed := true) AS a LEFT JOIN roads as b ON (a.edge = b.gid) ;";
		
	
		$ss = $this->db->prepare($query);
		
		if ($ss->execute()) {
			return $ss;
		}
		return null;
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



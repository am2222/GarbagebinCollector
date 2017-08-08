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
			
			
			 $startsatlPid=$this->getSingleValue("SELECT vertexid as id FROM satleha ORDER BY thegeom <-> st_transform(ST_SetSRID(ST_Point(".$x1.",".$y1."),4326),3857) LIMIT 1","id");
			$endsatlPid=$this->getSingleValue("SELECT vertexid id FROM satleha ORDER BY thegeom <-> st_transform(ST_SetSRID(ST_Point(".$x2.",".$y2."),4326),3857) LIMIT 1","id"); 
			
			
			echo 'startpid:'. $startPid. '  end pid: '.$endPid;
			echo 'startsatlpid:'.$startsatlPid. ' endsatlpid '.$endsatlPid;
			
			//SELECT gid FROM satleha,(select the_geom from roads_vertices_pgr where id=1) as ver     ORDER BY satleha.thegeom <-> ver.the_geom LIMIT 1
			
			
			/* $nextvertext=$this->looptable('select roads_vertices_pgr.id as verid,sa.*  from roads_vertices_pgr, (SELECT * from satleha where gid='.$firstbin.') as sa where sa.empty=0  ORDER BY sa.thegeom <-> roads_vertices_pgr.the_geom limit 1'); */
			
			$totalCapecity=$this->getSingleValue("select sum(capecity) from satleha ","sum");
			//echo $totalCapecity;
			//حذف نتایج قبلی از دیتابیس
			$this->emptyresult();
			
		
	
			foreach ($shiftsA as $shift) {
				
				

				echo 'شیفت شماره:'.$shift. PHP_EOL ;
				 $round=0;
				 $filledCappecity=0;	
				while ($filledCappecity < $totalCapecity) {
				//while ($this->isanysatl()) {
				
				// echo 'filledCappecity:'.$filledCappecity."\r\n and totall is:".$totalCapecity;		
				
				 $round+=1;
				 echo 'مرحله شمار:'.$round. PHP_EOL ;
				 
				 
				 	if($round<2){
							
							$this->reorderpoints($startsatlPid,$endsatlPid);
							$previoustpoint=$startPid;
						}else
						{
							$this->reorderpoints($endsatlPid,$endsatlPid);
							$previoustpoint=$endPid;
							
						}
						
						
				 $nextemptycar=$this->looptable("SELECT  * from cars where inuse=1  order by time");
					while  ($car = $nextemptycar->fetch(PDO::FETCH_ASSOC)){	
						
						$carCap=$car['capecity'];
						$carID=$car['id'];
						//echo ' :'.$carID.'with cap'.$carCap."\r\n"; 
						
						$filledCappecity+=$carCap;
						//$satlcount=$this->getsatlcountforacar($carCap);
						$satlcount=$this->route($carCap,$shift,$previoustpoint,$endPid,$round,$carID);//($carCap);
						//$this->routeFromPids($endPid,$previoustpoint,$carID,$shift,$round,$carCap);
						//خالی کردن سطلها
						//$this->Emptybins($carCap,$shift,$round,$carID);
					
									///بایستی زمان رفت و برگشت ماشین محاسبه و به روز رسانی شود و از لوپ خارج شود
						////	select sum(cost) from result where car=1 and shift=1 and reount=
						$catCost=$this->	getSingleValue('select sum(cost) as c from result where car='.$carID.' and shift='.$shift.' and reount='.$round,'c');
						$finalcost=(2*$catCost)+($satlcount*100);
						$this->savetimeforcar($carID,$finalcost);
						echo 'وزن مسیر حرکت خودرو'.$car['name'].' در مرحله '.$round.' در شیفت '.$shift.$catCost. PHP_EOL ;
						//echo 'زمان حرکت خودرو برای رسیدن به مقصد'.$finalcost."\r\n";
					}

				
					
				
					
					
			}
	 			 
				

    }
	
	
	 $shiftsr=$this->looptable("SELECT sum(cost) as s,shift as sh FROM result GROUP BY sh ORDER BY s");
					$i=0;
					while  ($sh = $shiftsr->fetch(PDO::FETCH_ASSOC)){	
						$i=$i+1;
						$cost=$sh['s'];
						$shiftv=$sh['sh'];
						
						echo 'اولویت شماره'.$i.' در شیفت شماره '.$shiftv.' با وزن '.$cost. PHP_EOL ;
						
					}
	
	
	
	}
	}
	
	
		function route($cap,$shift,$start,$end,$round,$car){
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
		
		  $q = $this->db->query("with satl as ( SELECT    *
FROM     (SELECT id2, capecity ,gid,empty,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
          FROM   vertexorder WHERE empty=0) t
WHERE    cum_sum < ".$cap."

ORDER BY id2 ASC
),
car as(
  SELECT    id2
FROM     satl),

route as (
				SELECT cost,roads.thegeom as geom,seq
					FROM pgr_dijkstraVia(
					' SELECT gid AS id,
						 source,
						 target,
						 (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS cost, (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS reverse_cost
					  FROM roads',
						ARRAY(
							select * from
							   (select ".$start." as id2) as b UNION ALL
					(select id2 from
				(SELECT    * from car) as a  UNION ALL
				Select ".$end.")

						),directed := true
					) AS r left JOIN roads ON r.edge = roads.gid),
result as (
insert INTO result (geom, cost, car, shift,reount)
    SELECT st_union(r.geom), sum(r.cost) as cost,".$car.", ".$shift.",".$round." from route as r
    RETURNING id),
seq as (
insert INTO roadseq ( geom,seq,roadid)
    SELECT route.geom,route.seq,result.id from route,result ),
  info as (
INSERT INTO salinfo ( gid, shift, round, car)
SELECT   satl.gid,".$shift.",".$round.",".$car."
FROM    satl),
fill as  (update satleha set empty=1 where gid in (
SELECT   satl. gid
FROM     satl)),
fill2 as  (update vertexorder set empty=1 where gid in (
SELECT   satl. gid
FROM     satl))
select count(*) from satl;");
		  $f = $q->fetch();
		  $result = $f['count'];
		
		return $result;
		
	}
	
	
	
	
	
	function getsatlcountforacar($cap){
		//SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id='.$endPid.') as ver where satleha.empty=0
		
		  $q = $this->db->query('  select count(*) as c from
(SELECT    id2
FROM     (SELECT id2, capecity ,gid,empty,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
          FROM   vertexorder WHERE empty=0) t
WHERE    cum_sum < '.$cap.'

ORDER BY id2 ASC) as a');
		  $f = $q->fetch();
		  $result = $f['c'];
		
		return $result;
		
	}
	
		function isanysatl(){
		//SELECT * FROM satleha,(select the_geom from roads_vertices_pgr where id='.$endPid.') as ver where satleha.empty=0
		
		  $q = $this->db->query('SELECT count(*) as c FROM vertexorder where empty=0');
		  $f = $q->fetch();
		  $result = $f['c'];
		
		
		if($result>0){
			return true;
		}else{
			return false; 
		}
	}
	
	
	
	function reorderpoints($start,$end)
		{
			///خالی کردن جدول
		 $stmt = $this->db->prepare("delete  FROM vertexorder;");
		   $stmt->execute();
		  //پر کردن مجدد
		$stmt = $this->db->prepare("INSERT into vertexorder
  SELECT a.*,s.capecity,s.empty,s.gid
  FROM pgr_tsp('select r.id::INTEGER as id,ST_X(r.the_geom) AS x, ST_Y(r.the_geom) AS y  from roads_vertices_pgr as r RIGHT JOIN
(
SELECT   gid, capecity,vertexid
FROM        satleha t) as s on s.vertexid=r.id',
               ".$start.",
               ".$end."
  ) as a LEFT JOIN satleha as s on s.vertexid=a.id2");
		  $stmt->execute();
		//  return $result;
		}	
		function emptyresult()
		{
			///خالی کردن جدول
			/* 			delete  FROM result;
			delete  FROM roadseq;
			delete  FROM salinfo;
			delete  FROM vertexorder; */
			$stmt = $this->db->prepare("update satleha set empty=0;");
		   $stmt->execute(); 
			
		 $stmt = $this->db->prepare("delete  FROM result;");
		   $stmt->execute(); 
		   
		   $stmt = $this->db->prepare("delete  FROM roadseq;");
		   $stmt->execute();
		   
		$stmt = $this->db->prepare("delete  FROM salinfo;");
		   $stmt->execute();
		   
		    $stmt = $this->db->prepare("delete  FROM vertexorder;");
		   $stmt->execute();

		}	
	function Emptybins($capesity,$shift,$round,$carID)
		{
		

		
		
		
		$stmt = $this->db->prepare("INSERT INTO salinfo ( gid, shift, round, car) 
SELECT    gid,".$shift.",".$round.",".$carID."
FROM     (SELECT id2, capecity ,gid,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
          FROM   vertexorder WHERE empty=0) t
WHERE    cum_sum < ".$capesity."
ORDER BY id2 ASC ");
		 $stmt->execute();
		 
		 
		 		 $stmt = $this->db->prepare("update vertexorder set empty=1 where gid in (
SELECT    gid
FROM     (SELECT id2, capecity ,gid,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
          FROM   vertexorder WHERE empty=0) t
WHERE    cum_sum < ".$capesity."
ORDER BY id2 ASC )");
		 $stmt->execute();
/* 		INSERT INTO salinfo ( gid, shift, round, car)
SELECT    gid
FROM     (SELECT id2, capecity ,gid,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
          FROM   vertexorder WHERE empty=0) t
WHERE    cum_sum < 10000
ORDER BY id2 ASC ) */
		
		
		
		
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
	
	function savesatlinfo($id,$carID,$shift,$round){
		
			$stmt = $this->db->prepare("INSERT INTO salinfo ( gid, shift, round, car) VALUES (".$id.",".$shift.",".$round.",".$carID.")");
			$stmt->execute();
			return $stmt;
		
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
	
 	function routeFromPids($end,$start,$car,$shift,$round,$cap){
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
		
		
		
		
						$query="insert INTO result (geom, cost, car, shift,reount)
				SELECT st_union(roads.thegeom), sum(r.cost) as cost,".$car.", ".$shift.",".$round."
					FROM pgr_dijkstraVia(
					$$ SELECT gid AS id,
						 source,
						 target,
						 (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS cost, (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS reverse_cost 
					  FROM roads$$,
						ARRAY(
							select * from
							   (select ".$start." as id2) as b UNION ALL
					(select id2 from
				(SELECT    id2
				FROM     (SELECT id2, capecity ,gid,empty,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
						  FROM   vertexorder WHERE empty=0) t
				WHERE    cum_sum < ".$cap."

				ORDER BY id2 ASC) as a  UNION ALL
				Select ".$end.")

						),directed := true
					) AS r left JOIN roads ON r.edge = roads.gid;";
		
		
		
		
		$stmt = $this->db->prepare($query);
		
	
			$stmt->execute();
			$id = $this->db->lastInsertId('table_name_id_seq');
			
					$query="insert INTO roadseq ( geom,seq,roadid)
				SELECT roads.thegeom,seq,".$id." 
					FROM pgr_dijkstraVia(
					$$ SELECT gid AS id,
						 source,
						 target,
						 (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS cost, (0.2*  st_length(geom))+(0.045*(1/width))+(0.15*roads.".$shiftcolumn.")+(0.36*t)+(0.030*roads.speed) AS reverse_cost 
					  FROM roads$$,
						ARRAY(
							select * from
							   (select ".$start." as id2) as b UNION ALL
					(select id2 from
				(SELECT    id2
				FROM     (SELECT id2, capecity ,gid,empty,  SUM(capecity)OVER (ORDER BY seq ASC) AS cum_sum
						  FROM   vertexorder WHERE empty=0) t
				WHERE    cum_sum < ".$cap."

				ORDER BY id2 ASC) as a  UNION ALL
				Select ".$end.")

						),directed := true
					) AS r left JOIN roads ON r.edge = roads.gid;";
					
					$stmt1 = $this->db->prepare($query);
		//echo 'id is'.$id;
		//echo 'q is'.$query;
					$stmt1->execute();
	
		
	
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



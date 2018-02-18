

<?php 

error_reporting(0);
$connect = mysqli_connect("localhost", "root", "", "search_engine");

//site name 
$start = "https://accounts.google.com/ServiceLogin/signinchooser?service=mail&passive=true&rm=false&continue=https%3A%2F%2Fmail.google.com%2Fmail%2F&ss=1&scc=1&ltmpl=default&ltmplcache=2&emr=1&osid=1&flowName=GlifWebSignIn&flowEntry=ServiceLogin";
$already_crawled = array();
$limit = 1;




function follow_links($url){

	global $already_crawled;
	global $limit;
	global $connect;

	$doc = new DOMDocument();
	$doc->loadHTML(file_get_contents($url));

	$linklist = $doc->getElementsByTagName("a");

	foreach ($linklist as $link){

		$l = $link->getAttribute("href");
		$t = $link->textContent;

		if(substr($l,0,1) == "/" && substr($l,0,2) != "//"){
			$l = "http://".parse_url($url)["host"].$l;
		}
		else if(substr($l,0,2)=="//"){
			$l = "http://".parse_url($url)["host"].substr($l, 2);
		}
		else if(substr($l,0,2)=="./"){
			$l = "http://".parse_url($url)["host"].dirname(parse_url($url)["path"]).substr($url,0);
		}
		else if(substr($l,0,1)=="#"){
			$l = "http://".parse_url($url)["host"].parse_url($url)["path"].$l;

		}
		else if(substr($l,0,3)=="../"){
			$l = "http://".parse_url($url)["host"].$l;
		}
		else if(substr($l,0,4)!="http"){
			$l = "http://".parse_url($url)["host"]."/".$l;
		}


		if(!in_array($l, $already_crawled)){
			$already_crawled[] = $l;

			$query = "INSERT INTO `table2` (`title`,`link`) VALUES ('$t','$l')";
			$res = mysqli_query($connect,$query);
			if(!$res){
				echo "Could Not be inserted<br>";
			}
			else{
				echo "Inserted<br>";
			}
			echo "title : $t , link : $l";
			$limit++;
			if($limit>2000){
				die();
			}

		}

	}

	foreach ($already_crawled as $site) {
		follow_links($site);
	}

}


follow_links($start);

?>

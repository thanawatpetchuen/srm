<?   
class acmeCache{ 
 
 function fetch($name, $refreshSeconds = 0){ 
	  if(!$refreshSeconds) $refreshSeconds = 60;
	  $cacheFile = acmeCache::cachePath($name); 
	  if(file_exists($cacheFile) and ((time()-filemtime($cacheFile))< $refreshSeconds)){
		   $cacheContent = unserialize(file_get_contents($cacheFile));
	  }   
	  return $cacheContent;
 } 
 
 function save($name, $cacheContent){
  $cacheFile = acmeCache::cachePath($name);   
  acmeCache::savetofile($cacheFile, serialize($cacheContent));
 }             

 function cachePath($name){ 
  $cacheFolder = $GLOBALS['cache_folder'];   
  if(!$cacheFolder) $cacheFolder = trim($_SERVER['DOCUMENT_ROOT'],'/').'/_cache/';   
	return $cacheFolder . md5(strtolower(trim($name))) . '._cache';
 }     
 
 function savetofile($filename, $data){ 
	  $dir = trim(dirname($filename),'/').'/'; 
	  acmeCache::forceDirectory($dir);  	  
	  $file = fopen($filename, 'w');  
	  chmod ($filename, 0777);
	  fwrite($file, $data); fclose($file); 
 }                                      
                            
		function forcePath($path){ 
				$dir = trim(dirname($path),'/').'/';
				acmeCache::forceDirectory($dir);
		}
		function forceDirectory($dir){ 
			return is_dir($dir) or (acmeCache::forceDirectory(dirname($dir)) and mkdir($dir, 0777)); 
		} 

}

class admincpCache{ 

		 function fetch($name, $refreshSeconds = 0){
		  if(!$refreshSeconds) $refreshSeconds = 60;
		  $cacheFile = admincpCache::cachePath($name); 
		  if(file_exists($cacheFile) and ((time()-filemtime($cacheFile))< $refreshSeconds)) 
			   $cacheContent = unserialize(file_get_contents($cacheFile));
		  return $cacheContent;
		 } 
		 
		 function save($name, $cacheContent){
		  $cacheFile = admincpCache::cachePath($name);    
		  admincpCache::savetofile($cacheFile, serialize($cacheContent));
		 }           
		 
		 function cachePath($name){
		  $cacheFolder = "_cache/admincp/";  
		  if(!$cacheFolder) $cacheFolder = trim($_SERVER['DOCUMENT_ROOT'],'/').'/_cache/admincp/';   
			return $cacheFolder . md5(strtolower(trim($name))) . '._cache';
		 }                           
		 
		 function savetofile($filename, $data){ 
			  $dir = trim(dirname($filename),'/').'/'; 
			  admincpCache::forceDirectory($dir);  	  
			  $file = fopen($filename, 'w');  
			  chmod ($filename, 0777);
			  fwrite($file, $data); fclose($file);
		 } 
	 
			function forcePath($path){
				$dir = trim(dirname($path),'/').'/';
				admincpCache::forceDirectory($dir);
		}
		function forceDirectory($dir){ 
			return is_dir($dir) or (admincpCache::forceDirectory(dirname($dir)) and mkdir($dir, 0777)); 
		} 

}


class userCache{ 
			
		 function fetch($name, $refreshSeconds = 0){
		  if(!$refreshSeconds) $refreshSeconds = 60;
		  $cacheFile = userCache::cachePath($name); 
		  if(file_exists($cacheFile) and ((time()-filemtime($cacheFile))< $refreshSeconds)) 
			   $cacheContent = unserialize(file_get_contents($cacheFile));
		  return $cacheContent;
		 } 
		 
		 function save($name, $cacheContent){
		  $cacheFile = userCache::cachePath($name);   		   
		  userCache::savetofile($cacheFile, serialize($cacheContent));
		 }           
		 
		 function cachePath($name){
		  $cacheFolder = "_cache/suso/";  
		  if(!$cacheFolder) $cacheFolder = trim($_SERVER['DOCUMENT_ROOT'],'/').'/_cache/suso/';   
			return $cacheFolder . md5(strtolower(trim($name))) . '._cache';
		 }  
		 
		 function savetofile($filename, $data){
			  $dir = trim(dirname($filename),'/').'/'; 
			  userCache::forceDirectory($dir);  	  
			  $file = fopen($filename, 'w');  
			  chmod ($filename, 0777);
			  fwrite($file, $data); fclose($file);
		 } 
		 
		function forcePath($path){
				$dir = trim(dirname($path),'/').'/';
				userCache::forceDirectory($dir);
		}
		function forceDirectory($dir){
			return is_dir($dir) or (userCache::forceDirectory(dirname($dir)) and mkdir($dir, 0777)); 
		}  

}
?>
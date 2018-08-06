<? 
class page_class{
	
	var $html = ""; // whole page html
	var $template = ""; // template file
	var $blocks = array(); // html blocks
	      
	function construct_page($_flag=false){
		$html = "";
		if($_flag) echo "<HR>".$_SERVER['PHP_SELF']."<BR>";
		if($_flag) echo "template [".$this->template."]<BR>";
		if(file_exists($this->template)) 
			$html = file_get_contents($this->template); 
		else 
			echo "File [".$this->template."] not exits.";
		$i = 0;
     	foreach($this->blocks as $tag => $code){
			$html = str_replace("{".$tag."}", $code, $html);
			$html = str_replace("  "," ", $html);
			//$html = str_replace("\n","", $html);
			if($_flag) echo "blocks [".$tag."]<BR>"; 
			$i++; 
		}  
		if($_flag) echo "<HR>";
		$this->html = $html;
		return $html;
	} 
	function output_page(){
		print($this->html);
	}	
	function string_page(){
		return $this->html;
	}                 
	function string_html_page(){
		return str_replace("\n", "<BR>", $this->html);
	}       
	function get_language(){
		global $cfg;
		if(isset($_COOKIE[$cfg['site']['cookie_prefix']."language"])){
			$cfg['language'] = $_COOKIE[$cfg['site']['cookie_prefix']."language"];
		}
	}	
	function set_language($langID){
		global $cfg;
		setcookie($cfg['site']['cookie_prefix']."language", $langID, time()+(60*60*24*365), '/', $cfg['site']['cookie_dimain']);
		$cfg['language'] = $langID;
	}
}
?>
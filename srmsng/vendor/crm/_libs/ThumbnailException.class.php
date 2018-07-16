<? 
	class ThumbnailException extends Exception{
		public function __construct($message=null, $code = 0){
			parent::__construct($message, $code);
			error_log('Error in '.$this->getFile(). 'Line: '.$this->getLine(). 'Error: '.$this->getMessage());
		}	
	}
	class ThumbnailFileException extends ThumbnailException{	}
	class ThumbnailNotSupportedException extends ThumbnailException{	}
	
	class Thumbnail{
		private $maxWidth;
		private $maxHeight;
		private $scale;
		private $inflate;
		private $types;
		private $imgLoaders;
		private $imgCreators;
		private $source;
		private $sourceWidth;
		private $sourceHeight;
		private $sourceMime;
		private $thumb;
		private $thumbWidth;
		private $thumbHeight;

		public function __construct($maxWidth, $maxHeight, $scale=true, $inflate=true){
			$this->maxWidth = $maxWidth;
			$this->maxHeight = $maxHeight;
			$this->scale = $scale;
			$this->inflate = $inflate;
			$this->types  = array('image/pjpeg', 'image/jpeg', 'image/png', 'image/gif');
			$this->imgLoaders = array('image/pjpeg'=>'imagecreatefromjpeg', 'image/jpeg'=>'imagecreatefromjpeg', 'image/png'=>'imagecreatefrompng', 'image/gif'=>'imagecreatefromgif');
			$this->imgCreators = array('image/pjpeg'=>'imagejpeg', 'image/jpeg'=>'imagejpeg', 'image/png'=>'imagepng', 'image/gif'=>'imagegif');
		}                                                    
		public function loadFile($image){
			if(!$dims = @getimagesize($image)){
				throw new ThumbnailFileException('Could not find image: '.$image);
			}
			if(in_array($dims['mime'], $this->types)){
				$loader = $this->imgLoaders[$dims['mime']];
				$this->source = $loader($image);
				$this->sourceWidth = $dims[0];
				$this->sourceHeight = $dims[1];
				$this->sourceMime = $dims['mime'];
				$this->initThumb();
				return true;
			}else{
				throw new ThumbnailNotSupportedException('Image MIME Type '.$dims['mime'].' not supported');
			}
		}
		public function loadData($image, $mime){
			
			if(in_array($mime, $this->types)){
				if($this->source = @imagecreatefromstring($image)){
					$this->sourceWidth = imagesx($this->source);
					$this->sourceHeight = imagesy($this->source);
					$this->sourceMime = $mime;
					$this->initThumb();
				return true;
				}else{
						throw new ThumbnailFileException('Could not load image from string');
				} 
			}else{
				throw new ThumbnailNotSupportedException('Image MIME Type '.$mime.' not supported');
			}

		}
		
		public function buildThumb($file = null){
			//echo "buildThumb...<BR>";
			$creator = $this->imgCreators[$this->sourceMime];
			//echo "\$creator[$creator]\$this->sourceMime[".$this->sourceMime."]<BR>";
			if(isset($file)){
				return $creator($this->thumb, $file);
			}else{
				return $creator($this->thumb);
			}
		}
		
		public function getMime(){
			return $this->sourceMime;		
		}

		public function getThumbWidth(){
			return $this->thumbWidth;		
		}
		public function getThumbHeight(){
			return $this->thumbHeight;		
		}
		
		private function initThumb(){
			if($scale){
				if($this->sourceWidth > $this->sourceHeight){
					$this->thumbWidth = $this->maxWidth;
					$this->thumbHeight = floor($this->sourceHeight * ($this->maxWidth/$this->sourceWidth));
				}else if($this->sourceWidth < $this->sourceHeight){
					$this->thumbHeight = $this->maxHeight;
					$this->thumbWidth = floor($this->sourceWidth * ($this->maxHeight/$this->sourceHeight));
				}			
			}else{
				$this->thumbWidth = $this->maxWidth;
				$this->thumbHeight = $this->maxHeight;
			}
			$this->thumb = imagecreatetruecolor($this->thumbWidth, $this->thumbHeight);
			if($this->sourceWidth <= $this->maxWidth &&  $this->sourceHeight<=$this->maxHeight && $this->inflate==false){
				$this->thumb = $this->source;
			}else{
				imagecopyresampled($this->thumb, $this->source ,0 ,0 ,0 ,0, $this->thumbWidth, $this->thumbHeight, $this->sourceWidth, $this->sourceHeight);
			}
		}
		
	} 
	
	function calculateThumbnail($__width, $__height){
			
			if(empty($GLOBALS["__THUMBNAIL_WIDTH"]))
				$GLOBALS["__THUMBNAIL_WIDTH"] = 80;
			if(empty($GLOBALS["__THUMBNAIL_HEIGHT"]))
				$GLOBALS["__THUMBNAIL_HEIGHT"] = 80;
			
			$_percent = 2;
			
			$__percent_width = ($__width/($GLOBALS["__THUMBNAIL_WIDTH"]*$_percent));
			//echo "<h1>\$__percent_width($__percent_width)</h1>";
			$__new_width = ($__width/$__percent_width);
			$__new_height = ($__height/$__percent_width);
			//echo "<h1>1. \$__new_width($__new_width)</h1>";
			//echo "<h1>1. \$__new_height($__new_height)</h1>";
			if($__new_width>=$GLOBALS["__THUMBNAIL_WIDTH"] && $__new_height>=$GLOBALS["__THUMBNAIL_HEIGHT"])
				return array(intval($__new_width), intval($__new_height));
			
			$__percent_height = ($__height/($GLOBALS["__THUMBNAIL_HEIGHT"]*$_percent));
			$__new_width = ($__width/$__percent_height);
			$__new_height = ($__height/$__percent_height);
			//echo "<h1>2. \$__new_width($__new_width)</h1>";
			//echo "<h1>2. \$__new_height($__new_height)</h1>";
			if($__new_width>=$GLOBALS["__THUMBNAIL_WIDTH"] && $__new_height>=$GLOBALS["__THUMBNAIL_HEIGHT"])
				return array(intval($__new_width), intval($__new_height));
			
	}

	
?>
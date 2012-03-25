<?php

App::import( 'Component', 'Upload' );

class UploadImageComponent extends UploadComponent {
	
	/*----------------------------------------
	 * Attributes
	 ----------------------------------------*/
	
	private $resizeScale = false;
	
	private $thumbnailPath = false;
	
	private $thumbnailScale = false;
	
	/*----------------------------------------
	 * PUBLIC Methods
	 ----------------------------------------*/
	
	public function initialize( &$controller, $settings = array() ){
		
		if( empty( $settings[ 'allow' ] ) )
			$settings[ 'allow' ] = 'images';
		else
			$this->type = 'images';
		
		parent::initialize( $controller, $settings );
		
		if( !empty( $settings[ 'scale' ] ) )
			$this->resizeScale = $settings[ 'scale' ];
			
		if( !empty( $settings[ 'thumbScale' ] ) )
			$this->thumbnailScale = $settings[ 'thumbScale' ];
	}
	
	public function create(){
		
		if( $this->thumbnailScale )
			$this->setThumbnailPath( $this->controller->{$this->modelName}->thumbsPath );
		
		if( parent::create() ){
			
			// redimensionando a imagem (se houver)
			if( $this->resizeScale )
				if( !$this->resize() )
					return false;
			
			// criando miniatura
			if( $this->thumbnailScale )
				if( !$this->createThumbnail() )
					return false;
			
			return true;
		}
		
		return false;
	}
	
	public function getError(){
		
		$error = "ERRO DE ENVIO DE ARQUIVO: ";
		
		switch( $this->error ){
			
			case 4:
				$error .= "Este tipo de arquivo (". $this->getExt( $this->name ) .") n&atilde;o permite redimensionamento";
				break;
				
			default:
				return parent::getError();
		}
		
		return $error;
	}
	
	public function deleteOld( $filename ){
		
		parent::deleteOld( $filename );
		
		if( $this->thumbnailScale )
			parent::deleteOld( $filename, $this->thumbnailPath );
	}
	
	/*----------------------------------------
	 * PRIVATE Methods
	 ----------------------------------------*/
	
	private function setThumbnailPath( $path ){

		$this->thumbnailPath = ROOT . DS . ".." . DS . $path;
		$this->thumbnailPath = eregi_replace( "/", DS, $this->thumbnailPath );
		$this->thumbnailPath = eregi_replace( "\\\\", DS, $this->thumbnailPath );
    }

	private function resize( $scale = null, $destinyPath = null ){
		
		$filetype 		= $this->getExt( $this->name );
		$sourcePath		= $this->path . DS . $this->name;
		$imgSource 		= null;
		
		if( !$scale )
			$scale = $this->resizeScale;
			
		if( !$destinyPath )
			$destinyPath = $sourcePath;
		else
			$destinyPath = $destinyPath . DS . $this->name;
		
		if( $filetype == "jpg" || $filetype == "jpeg" )
			$imgSource = imagecreatefromjpeg( $sourcePath );
			
		elseif( $filetype == "gif" )
			$imgSource = imagecreatefromgif( $sourcePath );
				
		elseif( $filetype == "png" ){
			
			$imgSource = imagecreatefrompng( $sourcePath );
			// imagealphablending($imgSource, true); // setting alpha blending on
			// imagesavealpha($imgSource, true); // save alphablending setting (important)
		}
			
		else {
			
			$this->error = 4; // arquivo nao eh imagem permitido para resize
			return false;
		}
		
		$trueWidth 	= imagesx( $imgSource );
		$trueHeight = imagesy( $imgSource );
		$width		= $trueWidth;
		$height		= $trueHeight;
		   
		if( $trueWidth >= $trueHeight ){
			
			if( $trueWidth > $scale ){
				$width	= $scale;
				$height = ($width / $trueWidth) * $trueHeight;
			}
		  
		} else {
		
			if( $trueHeight > $scale ){
				$height	= $scale;
				$width  = ($height / $trueHeight) * $trueWidth;
			}
		}
		
		$imgDestiny = imagecreatetruecolor( $width, $height );

		if( $filetype == "png" ){
			
		    imagesavealpha( $imgDestiny, true );
		    $trans_colour = imagecolorallocatealpha( $imgDestiny, 0, 0, 0, 127 );
		    imagefill( $imgDestiny, 0, 0, $trans_colour );
		}

		imagecopyresampled( $imgDestiny, $imgSource, 0, 0, 0, 0, $width, $height, $trueWidth, $trueHeight );
		
		// Save the resized image
		if( $filetype == "jpg" || $filetype == "jpeg" )
			imagejpeg( $imgDestiny, $destinyPath, 80 );
		
		elseif( $filetype == "gif" )
			imagegif( $imgDestiny, $destinyPath );
		
		elseif( $filetype == "png" )
			imagepng( $imgDestiny, $destinyPath );
		
		return true;
	}
	
	private function createThumbnail(){

		return $this->resize( $this->thumbnailScale, $this->thumbnailPath );
	}
	
}
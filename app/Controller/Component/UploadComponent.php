<?php

class UploadComponent extends Component {

	/*----------------------------------------
	 * Attributes
	 ----------------------------------------*/
    
    private $allowedExtensions = array();

	private $fileField;
	
	private $source;
	
	private $uploaded = false;

	private $maxSize;
	
	private $maxSizeName;
	
	private $maxLength = 50;
	
	protected $controller;
	
	protected $name;
	
	protected $error;
	
	protected $modelName;
	
	protected $path;
	
	protected $type;

	/*----------------------------------------
	 * Constructor
	 ----------------------------------------*/
	
	function __construct(ComponentCollection $collection, $settings = array()) {
		parent::__construct($collection, $settings);
	}
	
	/*----------------------------------------
	 * PUBLIC Methods
	 ----------------------------------------*/

	public function startup( &$controller ){

        $this->path    			= ROOT . DS . ".." . DS;
        $this->maxSize 			= 10*1024*1024; // 10MB
		$this->maxSizeName		= "10Mb";
    }

	public function initialize( &$controller, $settings = array() ){
		
		$this->controller = &$controller;
		
		if( !empty( $settings[ 'allow' ] ) ){
			
			if( is_array( $settings[ 'allow' ] ) ){
				
				$this->allowedExtensions = $settings[ 'allow' ];
				$this->type = "custom";
				
			} else {
				
				$this->type = $settings[ 'allow' ];
				
				switch( $settings[ 'allow' ] ){
					
					case 'documents':
						$this->allowedExtensions = array( 'doc', 'docx', 'pdf', 'txt', 'rtf', 'txt' );
						break;
						
					case 'images':
						$this->allowedExtensions = array( 'jpg', 'png', 'gif' );
						break;
						
					case 'any':
						$this->allowAllExtensions = true;
						break;
				}
			}
		}
		
		if( !empty( $settings[ 'field' ] ) )
			$this->fileField = $settings[ 'field' ];
		else
			$this->fileField = "arquivo";
			
		if( !empty( $settings[ 'model' ] ) )
			$this->modelName = $settings[ 'model' ];
	}

	public function thereIsFile(){
		
		if( empty( $this->controller->data[ $this->modelName ][ $this->fileField ][ 'name' ] ) ){
			
			$this->controller->{$this->modelName}->data[ $this->modelName ][ $this->fileField ] = null;
			return false;
		}
		
		return true;
	}
	
	public function create(){
		
		$this->source	= $this->controller->data[ $this->modelName ][ $this->fileField ];
		$this->name		= $this->source[ 'name' ];
		
		// setando o caminho de destino
		$this->choosePath();
		
		if( $this->verifyUpload() ){
			
			// verificando se ja existe algum arquivo com o mesmo nome
			$this->verifyFileExists( $this->name );

			// movendo o arquivo
	        if( move_uploaded_file( $this->source[ "tmp_name" ], $this->path .DS. $this->name ) ){
	        	
				$this->controller->{$this->modelName}->data[ $this->modelName ][ $this->fileField ] = $this->name;
	        	$this->uploaded = true;
	        	return true;
	        }
		} 
		
		return false;
	}
	
	public function getError(){
	
		$error = "ERRO DE ENVIO DE ARQUIVO: ";
		
    	if( $this->source[ 'error' ] ){
	
			switch( $this->source[ 'error' ] ){
				
				case UPLOAD_ERR_INI_SIZE:
					$error .= "Arquivo maior que o permitido.";
					break;
					
				case UPLOAD_ERR_FORM_SIZE:
					$error .= "Arquivo de formul&aacute; maior que o permitido.";
					break;
				
				case UPLOAD_ERR_PARTIAL:
					$error .= "Arquivo enviado parcialmente. Conex&atilde;o perdida.";
					break;

				case UPLOAD_ERR_NO_FILE:
					$error .= "Sem arquivo.";
					break;
					
				case UPLOAD_ERR_NO_TMP_DIR:
					$error .= "Falta de pasta temporaria.";
					break;

				case UPLOAD_ERR_CANT_WRITE:
					$error .= "Sem permiss&atilde;o para escrever em disco.";
					break;
					
				case UPLOAD_ERR_EXTENSION:
					$error .= "Extens&atilde;o n&atilde;o permitida.";
					break;
					
				default:
					$error .= "Ocorreu um erro.";
					break;
			}
			
		} else {
			
			switch( $this->error ){
				
				case 1:
					$error .= "Arquivo n&atilde;o foi enviado.";
					break;
					
				case 2:
					$error .= "Tipo de arquivo n&atilde;o permitido.";
					break;
					
				case 3:
					$error .= "Arquivo maior que o permitido ({$this->maxSizeName}).";
					break;
					
				default:
					$error .= "Ocorreu um erro no envio.";
					break;
			}
		}
		
		return $error;
    }
	
	public function delete(){
    	
    	if( $this->uploaded )
   			unlink( $this->path .DS. $this->name );
    }

	public function deleteOld( $filename, $path = null ){
		
		if( $this->uploaded ){
			
			if( $filename ){

				if( !$path )
					$path = $this->path;

				unlink( $path .DS. $filename );
			}
		}
	}
	
	/*----------------------------------------
	 * PRIVATE Methods
	 ----------------------------------------*/
	
	private function setPath( $path ){

		$this->path = $this->path . $path;
		$this->path = eregi_replace( "/", DS, $this->path );
		$this->path = eregi_replace( "\\\\", DS, $this->path );
    }

	private function choosePath(){
		
		if( $this->type == 'images' )
			$this->setPath( $this->controller->{$this->modelName}->imagesPath );	
		else
			$this->setPath( $this->controller->{$this->modelName}->filesPath );
	}
	
    private function verifyUpload(){
	
		if( !$this->source[ 'error' ] ){
			
			if( $this->source[ 'size' ] <= $this->maxSize ){
			
		        if( is_uploaded_file( $this->source[ "tmp_name" ] ) ){
			
					// verificar se o arquivo eh permitido
					if( $this->type != 'any' ){
				
						$ext = $this->getExt( $this->source[ "name" ] );
			
			            if( in_array( $ext, $this->allowedExtensions ) )
			                return true;
						else
							$this->error = 2; // tipo de arquivo nao permitido
		
					} else
						return true;
			
		        } else
					$this->error = 1; // arquivo nao foi enviado
					
			} else
				$this->error = 3; // arquivo muito grande
		}

        return false;
    }

	protected function getExt( $file ){
		
        $p = explode( ".", $file );
		
		if( sizeof( $p ) > 1 )
        	return strtolower( $p[ count( $p ) - 1 ] );
		else
			return null;
    }

	private function verifyFileExists(){

		// verificando tamanho do nome do arquivo
		$this->name = $this->stripName( $this->name );
		
		// substituindo caracteres especiais
		$this->name = $this->filterName( $this->name );

		// verificando se arquivo ja existe
        if( file_exists( $this->path . DS . $this->name ) )
            $this->renameFile();
    }

	private function stripName( $name ){
		
		if( strlen( $name ) > $this->maxLength ){
			
			$ext  = $this->getExt( $name );
			$name = explode( ".", $name );
			
			if( sizeof( $name ) > 1)
				$name = array_slice( $name, 0, sizeof( $name ) - 1 );

	        $name = implode( ".", $name );
			$name = substr( $name, 0, $this->maxLength-5 ) . "." . $ext;
		}

		return $name;
	}
    
	private function filterName( $name ){
		
		return preg_replace( "/[^[:alnum:][:punct:]]/", "_", $name );
	}

    private function renameFile(){

        $ext = $this->getExt( $this->name );
        $file_tmp = explode( ".", $this->name ); //	Nome do arquivo, sem extensao
        $file_tmp = array_slice( $file_tmp, 0, sizeof( $file_tmp ) - 1 );
        $file_tmp = implode( ".", $file_tmp );
		$file_tmp = substr( $file_tmp, 0, 15 );

        do {
        	
            $file_tmp = str_replace( array( '=', '+', '/', '*' ), "", base64_encode( date( "Hisu" ) . $file_tmp ) );

        } while( file_exists( $this->path . DS . $file_tmp . "." . $ext ) );
        
        $this->name = $file_tmp . "." . $ext;
    }
	
}
<?
define(drop_shadow_rightbottom,1);
define(drop_shadow_righttop,2);

if (!class_exists('m2brimagem'))
{
    class m2brimagem
    {
    	// arquivos
    	var $origem, $img;
    	// dimens�es
    	var $largura, $altura, $nova_largura, $nova_altura;
    	// dados do arquivo
    	var $extensao, $tamanho, $arquivo, $diretorio;
    	// cor de fundo para preenchimento
    	var $rgb;
    	// mensagem de erro
    	var $erro;
        // extensao nova (for�ada) - arquivo salvo como JPG mas � PNG
        var $extensao_forcada;

    	// construtor
    	function m2brimagem($origem='') {

    		$this->origem			= $origem;
    		$this->img				= '';
    		$this->largura			= 0;
    		$this->altura			= 0;
    		$this->nova_largura		= 0;
    		$this->nova_altura		= 0;
    		$this->extensao			= '';
    		$this->tamanho			= '';
    		$this->arquivo			= '';
    		$this->diretorio		= '';
    		$this->rgb				= array(0, 0, 0);

    		$this->dados();

    	} // fim construtor

    	// retorna dados da imagem
    	function dados() {

    		// mensagem padr�o, sem erro
    		$this->erro = 'OK';

    		// verifica se imagem existe
    		if (!is_file($this->origem)) {
    	   		$this->erro = 'Erro: Arquivo de imagem n�o encontrado!';
    		} else {
    			// dados do arquivo
    			$this->dadosArquivo();

    			// verifica se � imagem
    			if (!$this->eImagem()) {
    				$this->erro = 'Erro: Arquivo '.$this->origem.' n�o � uma imagem!';
    			} else {
    				// pega dimens�es
    				$this->dimensoes();

    				// cria imagem para php
    				$this->criaImagem();
    			}
    		}

    		return true;
    	} // fim dados

    	// retorna msg de erro ou OK
    	function valida() {
    		return $this->erro;
    	} // fim valida

    	// carrega imagem (nova imagem, fora do construtor)
    	function carrega($origem='') {
    		$this->origem			= $origem;
    		$this->dados();
    		return true;
    	} // fim carrega

    //------------------------------------------------------------------------------
    // dados da imagem

    	// seta as dimens�es do arquivo
    	function dimensoes($fromimage=false) {
            if ($fromimage)
            {
                $this->largura          = imagesx($this->img);
                $this->altura           = imagesy($this->img);
            }
            else
            {
    		    $tamanho_original 	= getimagesize($this->origem);
    		    $this->largura 	 	= $tamanho_original[0];
    		    $this->altura	 	= $tamanho_original[1];
            }
    		return true;
    	} // fim dimensoes

    	// seta dados do arquivo
    	function dadosArquivo() {
    		// imagem de origem
    		$pathinfo = pathinfo($this->origem);
            if (strlen($this->extensao_forcada) > 0)
                $this->extensao     = $this->extensao_forcada;
            else
    		    $this->extensao 	= strtolower($pathinfo['extension']);
    		$this->arquivo		= $pathinfo['basename'];
    		$this->diretorio	= $pathinfo['dirname'];
    		$this->tamanho		= filesize($this->origem);
    		return true;
    	} // fim dadosArquivo

    	// verifica se � imagem
    	function eImagem() {
    		$extensoes = array('jpg','jpeg','gif','bmp','png');
    		if (!in_array($this->extensao, $extensoes))
    			return false;
    		else
    			return true;
    	} // fim validaImagem

    //------------------------------------------------------------------------------
    // manipula��o da imagem

    	// cria imagem para manipula�ao com o GD
    	function criaImagem($s='') {
            if ($s > '')
            {
                $this->img = imagecreatefromstring($s);
                $this->dimensoes(true);
            }
            else
            {
    		    switch($this->extensao) {
    			    case 'gif':
    				    $this->img	= imagecreatefromgif($this->origem);
    				    break;
    			    case 'jpg':
    				    $this->img	= imagecreatefromjpeg($this->origem);
    				    break;
    			    case 'jpeg':
    				    $this->img	= imagecreatefromjpeg($this->origem);
    				    break;
    			    case 'png':
    				    $this->img	= imagecreatefrompng($this->origem);
    				    break;
    			    case 'bmp':
    				    // requer util.inc.php
    				    $this->img	= imagecreatefrombmp($this->origem);
    				    break;
    		    }
            }
    		return true;
    	} // fim criaImagem

    //------------------------------------------------------------------------------
    // fun��es para redimensionamento

    	// redimensiona imagem
    	function redimensiona($nova_largura=0, $nova_altura=0, $tipo='', $rgb='') {

    		// seta vari�veis passadas via par�metro
    		$this->nova_largura		= $nova_largura;
    		$this->nova_altura		= $nova_altura;
    		$this->rgb				= $rgb;

    		// define se s� passou nova largura ou altura
    		if (!$this->nova_largura && !$this->nova_altura) {
    			return false;
    		// s� passou altura
    		} elseif (!$this->nova_largura) {
    			$this->nova_largura = $this->largura/($this->altura/$this->nova_altura);
    		// s� passou largura
    		} elseif (!$this->nova_altura) {
    			$this->nova_altura = $this->altura/($this->largura/$this->nova_largura);
    		}

    		// redimensiona de acordo com tipo
    		if ($tipo == 'crop') {
    			$this->img = $this->resizeCrop();
    		} elseif ($tipo == 'fill') {
    			$this->img = $this->resizeFill();
    		} else {
    			$this->img = $this->resize();
    		}


    		return true;

    	} // fim redimensiona

    	// redimensiona proporcionalmente
    	// novas altura ou largura ser�o modificadas
    	function resize() {
    		// propor��o
    		// largura > altura
    		if ($this->largura > $this->altura) {
    			$r_largura 	= $this->nova_largura;
    			$r_altura	= round($this->altura / ($this->largura/$this->nova_largura));
    		// largura <= altura
    		} elseif ($this->largura <= $this->altura) {
    			$r_altura 	= $this->nova_altura;
    			$r_largura	= round($this->largura / ($this->altura/$this->nova_altura));
    		}

    		// cria imagem de destino tempor�ria
    		$imgtemp	= imagecreatetruecolor($r_largura, $r_altura);

    		imagecopyresampled($imgtemp, $this->img, 0, 0, 0, 0, $r_largura, $r_altura, $this->largura, $this->altura);
            
            $this->altura  = $r_altura;
            $this->largura = $r_largura;
    		return $imgtemp;
    	} // fim resize()

    	// redimensiona imagem sem cropar, proporcionalmente,
    	// preenchendo espa�o vazio com cor rgb especificada
    	function resizeFill() {
    		// cria imagem de destino tempor�ria
    		$imgtemp	= imagecreatetruecolor($this->nova_largura, $this->nova_altura);

    		// adiciona cor de fundo � nova imagem
    		$corfundo = imagecolorallocate($imgtemp, $this->rgb[0], $this->rgb[1], $this->rgb[2]);
    		imagefill($imgtemp, 0, 0, $corfundo);

    		// salva vari�veis para centraliza��o
    		$dif_y = $this->nova_altura;
    		$dif_x = $this->nova_largura;

    		// verifica altura e largura
    		if ($this->largura > $this->altura) {
    			$this->nova_altura	= (($this->altura * $this->nova_largura)/$this->largura);
    		} elseif ($this->largura <= $this->altura) {
    			$this->nova_largura	= (($this->largura * $this->nova_altura)/$this->altura);
    		}  // fim do if verifica altura largura

    		// copia com o novo tamanho, centralizando
    		$dif_x = ($dif_x-$this->nova_largura)/2;
    		$dif_y = ($dif_y-$this->nova_altura)/2;
    		imagecopyresampled($imgtemp, $this->img, $dif_x, $dif_y, 0, 0, $this->nova_largura, $this->nova_altura, $this->largura, $this->altura);

            $this->altura  = $this->nova_altura;
            $this->largura = $this->nova_largura;
    		return $imgtemp;
    	} // fim resizeFill()

    	// redimensiona imagem, cropando para encaixar no novo tamanho,
    	// sem sobras
    	// baseado no script original de Noah Winecoff
    	// http://www.findmotive.com/2006/12/13/php-crop-image/
    	function resizeCrop() {
    		// cria imagem de destino tempor�ria
    		$imgtemp	= imagecreatetruecolor($this->nova_largura, $this->nova_altura);

    		// m�dia altura/largura
    		$hm	= $this->altura/$this->nova_altura;
    		$wm	= $this->largura/$this->nova_largura;

    		// 50% para c�lculo do crop
    		$h_height = $this->nova_altura/2;
    		$h_width  = $this->nova_largura/2;

    		// largura > altura
    		if ($wm > $hm) {
    			$adjusted_width = $this->largura / $hm;
            	$half_width = $adjusted_width / 2;
            	$int_width = $half_width - $h_width;
            	imagecopyresampled($imgtemp, $this->img, -$int_width, 0, 0, 0, $adjusted_width, $this->nova_altura, $this->largura, $this->altura);
    		// largura <= altura
    		} elseif (($wm <= $hm)) {
    			$adjusted_height = $this->altura / $wm;
    			$half_height = $adjusted_height / 2;
    			$int_height = $half_height - $h_height;
    			imagecopyresampled($imgtemp, $this->img, 0, -$int_height, 0, 0, $this->nova_largura, $adjusted_height, $this->largura, $this->altura);
   		    }
            $this->altura  = $this->nova_altura;
            $this->largura = $this->nova_largura;
    		return $imgtemp;
    	} // fim resizeCrop

        
        function drop_shadow($rgb, $pos=drop_shadow_rightbottom)
        {
            $temp = imagecreatetruecolor($this->largura, $this->altura);    
            imagefill($temp, 0, 0, imagecolorallocate($temp, $rgb[0], $rgb[1], $rgb[2]));          
            imagecopy($temp,$this->img,-2,-2,0,0,$this->largura,$this->altura);
            $this->img = $temp;       
        }
        
    //------------------------------------------------------------------------------
    // flipa imagem
    // baseada na fun��o original de relsqui
    // http://www.php.net/manual/en/ref.image.php#62029

    	function flip($tipo='h') {
    		$w = imagesx($this->img);
    		$h = imagesy($this->img);

    		$imgtemp = imagecreatetruecolor($w, $h);

    		// vertical
    		if ($tipo == 'v') {
    			for ($y = 0; $y < $h; $y++) {
    				imagecopy($imgtemp, $this->img, 0, $y, 0, $h - $y - 1, $w, 1);
    			}
    		}

    		// horizontal
    		if ($tipo == 'h') {
    			for ($x = 0; $x < $w; $x++) {
    				imagecopy($imgtemp, $this->img, $x, 0, $w - $x - 1, 0, 1, $h);
    			}
    		}

    		$this->img = $imgtemp;

    		return true;
    	} // fim flip

    //------------------------------------------------------------------------------
    // gira imagem

    	function girar($graus,$rgb) {
    		$corfundo	= imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);
    		$this->img	= imagerotate($this->img,$graus,$corfundo);
    		return true;
    	} // fim girar

     function sobrepoe($imagem,$x=0,$y=0,$alfa=100) {
        // dimens�es
        $largura = $imagem->largura;
        $altura  = $imagem->largura;
        // retorna imagens com merge
        imagealphablending($imagem->img, true);
        imagecopymerge($this->img,$imagem->img,$x,$y,0,0,$largura,$altura, $alfa);
        return true;
    } // fim sobrep�e imagem

    
    //------------------------------------------------------------------------------
    // marcas d'�gua

    	// adiciona texto � imagem
    	function legenda($texto,$tamanho=10,$x=0,$y=0,$rgb,$truetype=false,$fonte='') {
    		$cortexto = imagecolorallocate($this->img, $rgb[0], $rgb[1], $rgb[2]);

    		// truetype ou fonte do sistema?
    		if ($truetype == true) {
    			imagettftext($this->img, $tamanho, 0, $x, $y, $cortexto, $fonte, $texto);
    		} else {
    			imagestring($this->img, $tamanho, $x, $y, $texto, $cortexto);
    		}

    		return true;
    	} // fim legenda

    	// adiciona imagem de marca d'�gua
    	function marca($imagem,$x=0,$y=0,$alfa=100) {
    		// cria imagem tempor�ria para merge
    		if ($imagem) {
    			$pathinfo = pathinfo($imagem);
    			switch(strtolower($pathinfo['extension'])) {
    				case('jpg'):
    					$marcadagua = imagecreatefromjpeg($imagem);
    					break;
    				case('jpeg'):
    					$marcadagua = imagecreatefromjpeg($imagem);
    					break;
    				case('png'):
    					$marcadagua = imagecreatefrompng($imagem);
    					break;
    				case('gif'):
    					$marcadagua = imagecreatefromgif($imagem);
    					break;
    				case('bpm'):
    					$marcadagua = imagecreatefrombmp($imagem);
    					break;
    				default:
    					return false;
    			}
    		} else {
    			return false;
    		}
    		// dimens�es
    		$marca_w	= imagesx($marcadagua);
    		$marca_h	= imagesy($marcadagua);
    		// retorna imagens com merge
    		imagealphablending($marcadagua, true);
    		imagecopy($this->img,$marcadagua,$x,$y,0,0,$marca_w,$marca_h);
    		return true;
    	} // fim marca


    //------------------------------------------------------------------------------
    // gera imagem de sa�da

    	// retorna sa�da de acordo com tipo definido
    	// browser ou arquivo
    	function grava($destino='', $salvar=false, $qualidade=90) {

    		// dados do arquivo de destino
    		if ($destino) {
    			$pathinfo = pathinfo($destino);
    			$extensao_destino = strtolower($pathinfo['extension']);
    		}

    		// valida extens�o de destino
    		if (!isset($extensao_destino)) {
    			$extensao_destino = $this->extensao;
    		} else {
    			$extensoes = array('jpg','jpeg','gif','bmp','png');
    			if (!in_array($extensao_destino, $extensoes))
    				return false;
    		}

    		if ($extensao_destino == 'jpg' || $extensao_destino == 'jpeg' || $extensao_destino == 'bmp') {
    			if ($salvar == true && $destino) {
    				imagejpeg($this->img,$destino,$qualidade);
    			} else {
    				header("Content-type: image/jpeg");
    				imagejpeg($this->img);
    				imagedestroy($this->img);
    				exit;
    			}
    		} elseif ($extensao_destino == 'png') {
    			if ($salvar == true && $destino) {
    				imagepng($this->img,$destino);
    			} else {
                    header("Content-type: image/png");
    				imagepng($this->img);
    				imagedestroy($this->img);
    				exit;
    			}
    		} elseif ($extensao_destino == 'gif') {
    			if ($salvar == true && $destino) {
    				imagegif($this->img,$destino);
    			} else {
    				header("Content-type: image/gif");
    				imagegif($this->img);
    				imagedestroy($this->img);
    				exit;
    			}
    		}
    	} // fim grava
    }
}
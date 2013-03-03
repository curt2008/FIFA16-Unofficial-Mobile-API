<?php  
/**
* @llygoden
* @author - Rob McGhee
* @URL - www.robmcghee.com
* @date - 12/10/12
* @version - 1.0
**/

class EAHashor {

	//initialise the class
	public function __construct() {
	
	}

	public function eaEncode($string){ 
		$a = "67452301"; 
		$b = "EFCDAB89"; 
		$c = "98BADCFE"; 
		$d = "10325476";  

		$words = $this->init($string);  

		for($i = 0; $i <= count($words)/16-1; $i++){         
			$A = $a;         
			$B = $b;         
			$C = $c;         
			$D = $d;           

			/* ROUND 1 */         
			$this->FF ($A, $B, $C, $D, $words[0 + ($i * 16)], 7, "d76aa478");          
			$this->FF ($D, $A, $B, $C, $words[1 + ($i * 16)], 12, "e8c7b756");          
			$this->FF ($C, $D, $A, $B, $words[2 + ($i * 16)], 17, "242070db");          
			$this->FF ($B, $C, $D, $A, $words[3 + ($i * 16)], 22, "c1bdceee");          
			$this->FF ($A, $B, $C, $D, $words[4 + ($i * 16)], 7, "f57c0faf");          
			$this->FF ($D, $A, $B, $C, $words[5 + ($i * 16)], 12, "4787c62a");          
			$this->FF ($C, $D, $A, $B, $words[6 + ($i * 16)], 17, "a8304613");          
			$this->FF ($B, $C, $D, $A, $words[7 + ($i * 16)], 22, "fd469501");          
			$this->FF ($A, $B, $C, $D, $words[8 + ($i * 16)], 7, "698098d8");          
			$this->FF ($D, $A, $B, $C, $words[9 + ($i * 16)], 12, "8b44f7af");          
			$this->FF ($C, $D, $A, $B, $words[10 + ($i * 16)], 17, "ffff5bb1");          
			$this->FF ($B, $C, $D, $A, $words[11 + ($i * 16)], 22, "895cd7be");          
			$this->FF ($A, $B, $C, $D, $words[12 + ($i * 16)], 7, "6b901122");          
			$this->FF ($D, $A, $B, $C, $words[13 + ($i * 16)], 12, "fd987193");          
			$this->FF ($C, $D, $A, $B, $words[14 + ($i * 16)], 17, "a679438e");          
			$this->FF ($B, $C, $D, $A, $words[15 + ($i * 16)], 22, "49b40821");           
			
			/* ROUND 2 */         
			$this->GG ($A, $B, $C, $D, $words[1 + ($i * 16)], 5, "f61e2562");          
			$this->GG ($D, $A, $B, $C, $words[6 + ($i * 16)], 9, "c040b340");          
			$this->GG ($C, $D, $A, $B, $words[11 + ($i * 16)], 14, "265e5a51");          
			$this->GG ($B, $C, $D, $A, $words[0 + ($i * 16)], 20, "e9b6c7aa");          
			$this->GG ($A, $B, $C, $D, $words[5 + ($i * 16)], 5, "d62f105d");          
			$this->GG ($D, $A, $B, $C, $words[10 + ($i * 16)], 9, "02441453");          
			$this->GG ($C, $D, $A, $B, $words[15 + ($i * 16)], 14, "d8a1e681");          
			$this->GG ($B, $C, $D, $A, $words[4 + ($i * 16)], 20, "e7d3fbc8");          
			$this->GG ($A, $B, $C, $D, $words[9 + ($i * 16)], 5, "21e1cde6");          
			$this->GG ($D, $A, $B, $C, $words[14 + ($i * 16)], 9, "c33707d6");          
			$this->GG ($C, $D, $A, $B, $words[3 + ($i * 16)], 14, "f4d50d87");          
			$this->GG ($B, $C, $D, $A, $words[8 + ($i * 16)], 20, "455a14ed");          
			$this->GG ($A, $B, $C, $D, $words[13 + ($i * 16)], 5, "a9e3e905");          
			$this->GG ($D, $A, $B, $C, $words[2 + ($i * 16)], 9, "fcefa3f8");          
			$this->GG ($C, $D, $A, $B, $words[7 + ($i * 16)], 14, "676f02d9");          
			$this->GG ($B, $C, $D, $A, $words[12 + ($i * 16)], 20, "8d2a4c8a");           
			
			/* ROUND 3 */         
			$this->HH ($A, $B, $C, $D, $words[5 + ($i * 16)], 4, "fffa3942");          
			$this->HH ($D, $A, $B, $C, $words[8 + ($i * 16)], 11, "8771f681");          
			//HH ($C, $D, $A, $B, $words[11 + ($i * 16)], 16, "6d9d6122");
			//EA change to use 14 rather than 16 as seen in the commented line
			$this->HH ($C, $D, $A, $B, $words[11 + ($i * 16)], 14, "6d9d6122");
			$this->HH ($B, $C, $D, $A, $words[14 + ($i * 16)], 23, "fde5380c");          
			$this->HH ($A, $B, $C, $D, $words[1 + ($i * 16)], 4, "a4beea44");          
			$this->HH ($D, $A, $B, $C, $words[4 + ($i * 16)], 11, "4bdecfa9");          
			$this->HH ($C, $D, $A, $B, $words[7 + ($i * 16)], 16, "f6bb4b60");          
			$this->HH ($B, $C, $D, $A, $words[10 + ($i * 16)], 23, "bebfbc70");         
			$this->HH ($A, $B, $C, $D, $words[13 + ($i * 16)], 4, "289b7ec6");          
			$this->HH ($D, $A, $B, $C, $words[0 + ($i * 16)], 11, "eaa127fa");          
			$this->HH ($C, $D, $A, $B, $words[3 + ($i * 16)], 16, "d4ef3085");          
			$this->HH ($B, $C, $D, $A, $words[6 + ($i * 16)], 23, "04881d05");          
			$this->HH ($A, $B, $C, $D, $words[9 + ($i * 16)], 4, "d9d4d039");          
			$this->HH ($D, $A, $B, $C, $words[12 + ($i * 16)], 11, "e6db99e5");          
			$this->HH ($C, $D, $A, $B, $words[15 + ($i * 16)], 16, "1fa27cf8");          
			$this->HH ($B, $C, $D, $A, $words[2 + ($i * 16)], 23, "c4ac5665");           
			
			/* ROUND 4 */         
			$this->II ($A, $B, $C, $D, $words[0 + ($i * 16)], 6, "f4292244");          
			$this->II ($D, $A, $B, $C, $words[7 + ($i * 16)], 10, "432aff97");          
			$this->II ($C, $D, $A, $B, $words[14 + ($i * 16)], 15, "ab9423a7");          
			$this->II ($B, $C, $D, $A, $words[5 + ($i * 16)], 21, "fc93a039");          
			$this->II ($A, $B, $C, $D, $words[12 + ($i * 16)], 6, "655b59c3");          
			$this->II ($D, $A, $B, $C, $words[3 + ($i * 16)], 10, "8f0ccc92");          
			$this->II ($C, $D, $A, $B, $words[10 + ($i * 16)], 15, "ffeff47d");          
			$this->II ($B, $C, $D, $A, $words[1 + ($i * 16)], 21, "85845dd1");          
			$this->II ($A, $B, $C, $D, $words[8 + ($i * 16)], 6, "6fa87e4f");          
			$this->II ($D, $A, $B, $C, $words[15 + ($i * 16)], 10, "fe2ce6e0");          
			$this->II ($C, $D, $A, $B, $words[6 + ($i * 16)], 15, "a3014314");          
			$this->II ($B, $C, $D, $A, $words[13 + ($i * 16)], 21, "4e0811a1");          
			$this->II ($A, $B, $C, $D, $words[4 + ($i * 16)], 6, "f7537e82");          
			$this->II ($D, $A, $B, $C, $words[11 + ($i * 16)], 10, "bd3af235");          
			$this->II ($C, $D, $A, $B, $words[2 + ($i * 16)], 15, "2ad7d2bb");          
			$this->II ($B, $C, $D, $A, $words[9 + ($i * 16)], 21, "eb86d391");
			//EA change to use this last line twice
			$this->II ($B, $C, $D, $A, $words[9 + ($i * 16)], 21, "eb86d391");
			
			$this->addVars($a, $b, $c, $d, $A, $B, $C, $D);         
		}   
			
		$MD5 = '';   
			
		foreach (array($a, $b, $c, $d) as $x) {       
			$MD5 .= implode('', array_reverse(str_split($this->leftpad($x, 8), 2)));   
		}          

		return $MD5; 
	}  

	/* General functions */  
	function hexbin($str){         
		$hexbinmap = array(	"0" => "0000", 
							"1" => "0001", 
							"2" => "0010", 
							"3" => "0011"
							, "4" => "0100" 
							, "5" => "0101" 
							, "6" => "0110"                                                 
							, "7" => "0111"                                                 
							, "8" => "1000"                                                
							, "9" => "1001"                                                
							, "A" => "1010"                                                
							, "a" => "1010"                                                
							, "B" => "1011"                                                
							, "b" => "1011"                                                
							, "C" => "1100"                                                
							, "c" => "1100"                                                
							, "D" => "1101"                                                
							, "d" => "1101"                                                
							, "E" => "1110"                                                 
							, "e" => "1110"                                              
							, "F" => "1111"                                              
							, "f" => "1111");         					
		$bin = "";     

		for ($i = 0; $i < strlen($str); $i++){         
			$bin .= $hexbinmap[$str[$i]];     
		}     

		$bin = ltrim($bin, '0');          
		// echo "Original: ".$str."  New: ".$bin."<br />";     
		return $bin; 
	}  

	function strhex($str){     
		$hex = "";     
		
		for ($i = 0; $i < strlen($str); $i++){         
			$hex = $hex.$this->leftpad(dechex(ord($str[$i])), 2);     
		}     
		
		return $hex; 
	}   

	/* MD5-specific functions */  
	function init($string){         
		$len = strlen($string) * 8;         
		$hex = $this->strhex($string); // convert ascii string to hex         
		$bin = $this->leftpad($this->hexbin($hex), $len); // convert hex string to bin         
		$padded = $this->pad($bin);         
		$padded = $this->pad($padded, 1, $len);         
		$block = str_split($padded, 32);          
		
		foreach ($block as &$b) {             
			$b = implode('', array_reverse(str_split($b, 8)));         
		}          
		
		return $block; 
	}  

	function pad($bin, $type=0, $len = 0){         
		if($type == 0){         
			$bin = $bin."1";         
			$buff = strlen($bin) % 512;         
			if($buff != 448){                 
				while(strlen($bin) % 512 != 448){                          
					$bin = $bin."0";                 
				}   
			}
		}         
		// append length (b) of string to latter 64 bits         
		elseif($type == 1){             
			$bLen = $this->leftpad(decbin($len), 64);        
			$bin .= implode('', array_reverse(str_split($bLen, 8)));       
		}   
		return $bin; 
	}  

	/* MD5 base functions */  
	function F($X, $Y, $Z){         
		$X = hexdec($X);
		$Y = hexdec($Y); 
		$Z = hexdec($Z);
		$calc = (($X & $Y) | ((~ $X) & $Z)); // X AND Y OR NOT X AND Z   
		return  $calc;  
	}  

	function G($X, $Y, $Z){ 
		$X = hexdec($X);
		$Y = hexdec($Y);
		$Z = hexdec($Z);
		$calc = (($X & $Z) | ($Y & (~ $Z))); // X AND Z OR Y AND NOT Z
		return  $calc;  
	}  

	function H($X, $Y, $Z){
		$X = hexdec($X);
		$Y = hexdec($Y);
		$Z = hexdec($Z);
		$calc = ($X ^ $Y ^ $Z); // X XOR Y XOR Z
		return  $calc;  
	}  

	function I($X, $Y, $Z){         
		$X = hexdec($X);
		$Y = hexdec($Y);
		$Z = hexdec($Z);
		$calc = ($Y ^ ($X | (~ $Z))) ; // Y XOR (X OR NOT Z)
		return  $calc;  
	}  

	/* MD5 round functions */  
	/* $A - hex, $B - hex, $C - hex, $D - hex (F - dec) 
	$M - binary 
	$s - decimal 
	$t - hex 
	*/ 
	function FF(&$A, $B, $C, $D, $M, $s, $t){         
		$A = hexdec($A);
		$t = hexdec($t);
		$M = bindec($M);
		$A = ($A + $this->F($B, $C, $D) + $M + $t) & 0xffffffff; //decimal
		$A = $this->rotate($A, $s);
		$A = dechex((hexdec($B) + hexdec($A)) & 0xffffffff); 
	}  

	function GG(&$A, $B, $C, $D, $M, $s, $t){ 
		$A = hexdec($A);
		$t = hexdec($t);
		$M = bindec($M);
		$A = ($A + $this->G($B, $C, $D) + $M + $t) & 0xffffffff; //decimal
		$A = $this->rotate($A, $s);
		$A = dechex((hexdec($B) + hexdec($A)) & 0xffffffff); 
	}  

	function HH(&$A, $B, $C, $D, $M, $s, $t){
		$A = hexdec($A);
		$t = hexdec($t);
		$M = bindec($M);
		$A = ($A + $this->H($B, $C, $D) + $M + $t) & 0xffffffff; //decimal
		$A = $this->rotate($A, $s);
		$A = dechex((hexdec($B) + hexdec($A)) & 0xffffffff); 
	}  

	function II(&$A, $B, $C, $D, $M, $s, $t){
		$A = hexdec($A);
		$t = hexdec($t);
		$M = bindec($M);
		$A = ($A + $this->I($B, $C, $D) + $M + $t) & 0xffffffff; //decimal
		$A = $this->rotate($A, $s);
		$A = dechex((hexdec($B) + hexdec($A)) & 0xffffffff); 
	}  

	// shift 
	function rotate ($decimal, $bits) { //returns hex     
		return dechex((($decimal << $bits) |  ($decimal >> (32 - $bits))) & 0xffffffff); 
	}  

	function addVars(&$a, &$b, &$c, &$d, $A, $B, $C, $D){    
		$A = hexdec($A);
		$B = hexdec($B);
		$C = hexdec($C);
		$D = hexdec($D); 
		$aa = hexdec($a);
		$bb = hexdec($b);
		$cc = hexdec($c);
		$dd = hexdec($d);
		
		$aa = ($aa + $A) & 0xffffffff;
		$bb = ($bb + $B) & 0xffffffff;
		$cc = ($cc + $C) & 0xffffffff;
		$dd = ($dd + $D) & 0xffffffff;
		
		$a = dechex($aa); 
		$b = dechex($bb);
		$c = dechex($cc);
		$d = dechex($dd); 
	}
	  
	function leftpad($needs_padding, $alignment) {
		if (strlen($needs_padding) % $alignment) {
			$pad_amount    = $alignment - strlen($needs_padding) % $alignment;
			$left_pad      = implode('', array_fill(0, $pad_amount, '0'));
			$needs_padding = $left_pad . $needs_padding;   
		}   
		
		return $needs_padding; 
	}
}

?>
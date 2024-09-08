<?php 

class Widget extends DB {
    public static function alertRed($name,$content,$link) {
        echo "<script>    
                $.alert({
                    title: 'Hello There!',
                    content: '".$content."',
                    type: 'dark',
                    typeAnimated: true,
                    buttons: {
                        ".$name.": function(){
                            location.href = '".$link."';
                        }
                    }
                });
            </script>";
    }

    public static function jsAlert($content,$link) {
        echo "<script>    
                alert('".$content."');
                window.location.replace('".$link."');
            </script>";
    }
    public static function justalert($content) {
        echo "<script>    
                alert('".$content."');
            </script>";
    }

    public static function alertGreen($content) {
        echo "<script>
                $.alert({
                    title: 'Alert!',
                    content: '".$content."',
                });
            </script>";
    }

    public static function setPageTitle($page) {
        echo "<script>
                $(document).ready(function ()
                {
                    document.title = 'ALD | ".$page."';
                });
            </script>";
    }

    public static function backBtn($link){
        echo '<a class="nav-link p-2 text-white" style="font-size:1rem;" href="'.$link.'"><i class="fas fa-arrow-left"></i> Back</a>';
    }

    public static function generatecid($n){ 
		  
		// Take a generator string which consist of 
		// all numeric digits 
		$generator = "1934052678"; 
	  
		// Iterate for n-times and pick a single character 
		// from generator and append it to $result 
		  
		// Login for generating a random character from generator 
		//     ---generate a random number 
		//     ---take modulus of same with length of generator (say i) 
		//     ---append the character at place (i) from generator to result 
	  
		$result = ""; 
	  
		for ($i = 1; $i <= $n; $i++) { 
			$result .= substr($generator, (rand()%(strlen($generator))), 1); 
		} 
	  
		// Return result 
		return $result; 
    }
    public static function check_uid($cid){
        while(true){
            $query = DB::query("select * from customers where uid='$cid'");
            if(empty($query)){
                break;
            }else{
                $cid = Widget::generatecid('7');
            }
        }
    
        return $cid;
    }
    public static function check_aid($cid){
        while(true){
            $query = DB::query("select * from agents where aid='$cid'");
            if(empty($query)){
                break;
            }else{
                $cid = Widget::generatecid('7');
            }
        }
    
        return $cid;
    }
    public static function getIndianCurrency(float $number){
        $decimal = round($number - ($no = floor($number)), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise = ($decimal > 0) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
        return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise;
    }

}




?>
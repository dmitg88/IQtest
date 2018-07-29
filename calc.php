<?
/* var_dump($_POST); */


   /* $DepositDate = DateTime::createFromFormat('Y-m-d', $_POST[DepositDate]);  */ 
   $DepositDate = date($_POST[DepositDate]);   
   $DepositAmount = intval($_POST[DepositAmount]);
   $DepositTerm = intval($_POST[DepositTerm]);
   $AddingToDepositAmount = intval($_POST[AddingToDepositAmount]); 

   
  
   $daysn = cal_days_in_month(CAL_GREGORIAN, date('m',$_POST[DepositDate]), date('Y',$_POST[DepositDate])); //количество дней в месяце   
   $daysy = date('L', mktime(1,1,1,1,1,$_POST[DepositDate]))?366:365; // количество дней в году, проверка на високосный год   
   $summn = 0; 
   $summpre = $DepositAmount; //сумма на счете на конец прошлого месяца (или дня вклада депозита)
   if ($_POST[AddingToDeposit] == "Yes") {
   $summadd = $AddingToDepositAmount; // сумма ежемесячного пополнения депозита
   } else {
	 $summadd = 0;  
   };
   $percent = 0.1;
   
   $WorkingYear = date('Y',strtotime($DepositDate));
   $WorkingMonth = date('m',strtotime($DepositDate));
   
   $WorkingMonth = date("$WorkingYear-$WorkingMonth-01");

   
   for ($i = 1; $i <= $DepositTerm; $i++) {  
	   for ($j = 1; $j <= 12; $j++) {
		  if ($i==1 && $j==1) {
			   $daysn = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime($DepositDate)), date('Y',strtotime($DepositDate))) - date('j',strtotime($DepositDate));
		   } else if ($i == $DepositTerm && $j == 12) {		        
			   $daysn = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime($DepositDate)), date('Y',strtotime($WorkingMonth))) - date('j',strtotime($DepositDate));
 			   
		   } else { 		   
			 $daysn = cal_days_in_month(CAL_GREGORIAN, date('m',strtotime($WorkingMonth)), date('Y',strtotime($WorkingMonth))); 
		 		   };
		   
		  $summn = $summpre + ($summpre + $summadd)*$daysn*($percent / $daysy);
		  $summpre = $summn;
/* 		  echo json_encode($WorkingMonth); */		  
		  $WorkingMonth = date('Y-m-d', strtotime("+1 month", strtotime($WorkingMonth)));	
		  
	   };	    
	   $daysy = date('L', mktime(1,1,1,1,1,($_POST[DepositDate])+1))?366:365;   
   }; 
 
 


   /* echo json_encode($daysy); */
   $summn = ceil($summn - $DepositAmount);
   echo json_encode($summn);
?>
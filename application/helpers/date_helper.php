<?php 
	function getPastDate($requiredDate, $numberOfDays){
		$requiredDay;
		$requiredMonth;
		$requiredYear = $requiredDate->format('Y');
		if($requiredDate->format('m') == "2" || "4" || "6" || "8" || "9" || "11"){
			if($requiredDate->format('d') < $numberOfDays){
				$requiredMonth = $requiredDate->format('m') - 1;
				$requiredDay = $requiredDate->format('d') + $numberOfDays + 1;
			}else{
				$requiredDay = $requiredDate->format('d') - $numberOfDays;
				$requiredMonth = $requiredDate->format('m');
			}
		}elseif($requiredDate->format('m') == "5" || "7" || "10" || "12"){
			if($requiredDate->format('d') < $numberOfDays){
				$requiredMonth = $requiredDate->format('m') - 1;
				$requiredDay = $requiredDate->format('d') + $numberOfDays;
			}else{
				$requiredDay = $requiredDate->format('d') - $numberOfDays;
				$requiredMonth = $requiredDate->format('m');
			}
		}elseif ($requiredDate->format('m') == "3") {
			if($requiredDate->format('d') < $numberOfDays){
				$requiredMonth = $requiredDate->format('m') + $numberOfDays - 2;
				$requiredDay = $requiredDate->format('d') + $numberOfDays;
			}else{
				$requiredDay = $requiredDate->format('d') - $numberOfDays;
				$requiredMonth = $requiredDate->format('m');
			}
		}elseif($requiredDate->format('m') == "1"){
			if($requiredDate->format('d') < $numberOfDays){
				$requiredMonth = 12;
				$requiredDay = $requiredDate->format('d') + $numberOfDays + 1;
				$requiredYear = $requiredDate->format('Y') - 1;
			}else{
				$requiredDay = $requiredDate->format('d') - $numberOfDays;
				$requiredMonth = $requiredDate->format('m');
			}
		}

		$pastDate = $requiredDate->setDate($requiredYear,$requiredMonth,$requiredDay);

		return $pastDate;
	}

	
	function checkDateTimediff($updatedDateTime){
		$dateToday = new DateTime();
		$interval = $dateToday->diff($updatedDateTime);

		if($interval->format('%d') == "0"){
			if($interval->format('%h') == "0"){
				if($interval->format('%i') == "0"){
					if($interval->format('%s') == "0"){

					}else{
						$updatedOn = $interval->format('%s');
						if($updatedOn == "1"){
							return $updatedOn." second ago";
							die();
						}else{
							return $updatedOn." seconds ago";	
							die();
						}
					}
				}else{
					$updatedOn = $interval->format('%i');
					if($updatedOn == "1"){
						return $updatedOn." minute ago";
						die();
					}else{
						return $updatedOn." minutes ago";	
						die();
					}
				}
			}else{
				$updatedOn = $interval->format('%h');
				if($updatedOn == "1"){
					return $updatedOn." hour ago";
					die();
				}else{
					return $updatedOn." hours ago";
					die();
				}
			}
		}else{
			$updatedOn = $interval->format('%d');
			if($updatedOn == "1"){
				return $updatedOn." day ago";
				die();
			}else{
				return $updatedOn." days ago";
				die();
			}
		}
	}
?>
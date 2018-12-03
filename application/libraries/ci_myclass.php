<?php 

class Ci_myclass 
{
	public function lvlacc($var)
	{
		switch ($var) {
			case '1':
				$usr = "Admin";
				break;
			case '2':
				$usr = "Pengguna";
				break;
			case '3':
				$usr = "Penyelia";
				break;
		}

		return $usr;
	}
}

?>
<?
class db_sql{
   //Propriétés
   var $host = "";
   var $Database = "";
   var $User     = "";
   var $Password = "";

   var $Link_ID   = 0;
   var $Query_ID  = 0;
   var $Record    = array();
   var $Record_brut    = array();
   var $Errno     = 0;
   var $Error     = "";
   var $Sqlstate = "";
   var $Success   = 0;
   var $nb_enr    ="";
   var $N_row     =0;

	//Constructeur
	function db_sql($req = ""){
      include "client/base.php";
      $this->connect();
      if ($req) {
         $this->q($req);
      }
	}
	//Méthode connexion base
	function connect() {
		$this->Link_ID=mysqli_connect($this->host,$this->User,$this->Password,$this->Database);
		if (!$this->Link_ID) {
			$this->Errno = 1;
			$this->Error = "Erreur de connexion";
			$this->halt("1 - Connexion impossible, contacter votre administrateur",$this->Modetrace);
		}
	}
	
	function close() {
		@mysqli_close($this->Link_ID);
	}	
   
	function begin() {
		@mysqli_query("BEGIN");
	}
   
   function commit() {
		@mysqli_query("COMMIT");
    }
   
	function rollback() {
		@mysqli_query("ROLLBACK");
    }
   
	function num_rows() {
		return @mysqli_numrows($this->Query_ID);
	}

	//Méthode exécution de requête
	function q($req){
		if($req == ""){
			return 0;
		}
		//Gestion des traces
		if ($this->Modetrace == 1) {
			$id_file = fopen("temp/db.log","a+");
			fputs($id_file,"\n".$req.";\n");
			fputs($id_file,"\nconnexion : ".$this->Link_ID->thread_id.";\n");
			fclose($id_file);
			$this_micro_time_deb = microtime();
		}
		//ID requete
		$GLOBALS[last_link_id]=$this->Link_ID;
		$this->Query_ID = @mysqli_query($this->Link_ID, $req);
		if(!$this->Query_ID){
			$this->Error = mysqli_error($this->Link_ID);
			$this->Errorno = mysqli_errno($this->Link_ID);
			$this->Sqlstate = mysqli_sqlstate($this->Link_ID);
			$this->halt("3 - Requête SQL impossible à exécuter<br><br>".mysqli_errno($this->Link_ID)."<br><br>".mysqli_errno($this->Link_ID)."<br><br>".mysqli_sqlstate($this->Link_ID)."<br><br>".$req,$this->Modetrace);
		}
		// Gestion des traces
		if ($this->Modetrace == 1) {
			$this_micro_time_fin = microtime();
			$id_file = fopen("temp/db.log","a+");
			$temp = (substr($this_micro_time_fin,0,5)-substr($this_micro_time_deb,0,5)) + (strStr($this_micro_time_fin," ")-strStr($this_micro_time_deb," "));
			fputs($id_file,"\n".substr($temp,0,5)."\n");
			fclose($id_file);
		}
		$this->N_row = 0;
		return $this->Query_ID;
	}
	//Méthode n
	function n(){
		$this->Record_brut=@mysqli_fetch_array($this->Query_ID,MYSQL_ASSOC);
		if(!$this->Record=@mysqli_fetch_array($this->Query_ID)) {
			$stat=0;
		} else {
			foreach($this->Record as $i => $j) {
				$this->Record[strtolower($i)] = $j;
			}
			$stat=$this->Record;
			$this->N_row+=1;
		}
		return $stat;
	}
	//Méthode fetch
   function f($Name) {
      return $this->Record[strtolower($Name)];
   }
   //Méthode gestion des messages d'erreurs
   function halt($msg,$modetrace) {
          echo "<table width='300' align='center' bgcolor='#DODODO'><tr><td bgcolor='#000099' align='center'><span style='font-family:verdana;font-size:10px;color:white;font-weight:bold'>ERREUR</span></td></tr>";
          echo "<tr><td align='center'><span style='font-family:verdana;font-size:10px'>".$msg."</span></td></tr>";
          if($this->Modetrace==1){
                  echo "<tr><td align='center'><PRE>".$req."</PRE><span style='font-family:verdana;font-size:10px'>".$this->Errno," - ",$this->Error."</span></td></tr>";
                  mysqli_close($this->Link_ID);
                  echo "<tr><td><span style='font-family:verdana;font-size:10px'>Connection arrétée</span></td></tr>";
                }
          echo "<tr><td align='center'><a class='lien1' href='javascript:history.go(-1)'><span style='font-family:verdana;font-size:10px'>BACK</span></a></td></tr></table>";
          exit;
   }
   static function last_id() {
		return mysqli_insert_id($GLOBALS[last_link_id]);
   }
}
?>
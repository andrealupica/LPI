<?php

	// Definisco delle costanti per il db MySQL
	define("DB_HOST", "h2mysql19");
	define("DB_USER", "efof_i13lupand");
	define("DB_PASS", "lupand1");
	define("DB_NAME", "efof_samtinfoch17");

	//estendo cla classe PDO
	class DBMysql extends PDO
	{
		//inserisco i valori delle costanti nelle variabili della classe
		private $host = DB_HOST;
		private $user = DB_USER;
		private $pass = DB_PASS;
		private $dbname = DB_NAME;

		public function __construct()
		{
			try{
				//creo PDO per mysql
				$dns = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
				parent::__construct($dns, $this->user, $this->pass);
				//setto attributo per ritornare errori PDOException
				$this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				// meglio disabilitare gli emulated prepared con i driver MySQL
				$this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				}
			//se ci sono errori li ritorno
			catch (PDOException $e){
				echo $e->getMessage();
			}
		}
  }

  $conn = new DBMysql(); // creazione della variabile per la connessione al db
	?>

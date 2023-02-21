<?php

class TallaModel{

   
    public function __construct($name=null,$id=null) {
        $this->enlace=new MySqlConnect();       
    }
    public function all(){
        try {
            //Consulta sql
			$vSql = "SELECT * FROM talla;";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
				
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }

    public function get($id){
        try {
            //Consulta sql
			$vSql = "SELECT * FROM talla where idTalla=$id";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }
    public function getTallasBici($idBicicleta){
        try {
            //Consulta sql
			$vSql = "SELECT t.idTalla, t.nombre, t.descripcion
            FROM talla t, bicicleta_talla bt
            where bt.idTalla=t.idTalla and bt.idBicicleta=$idBicicleta";
			$this->enlace->connect();
            //Ejecutar la consulta
			$vResultado = $this->enlace->ExecuteSQL ( $vSql);
			// Retornar el objeto
			return $vResultado;
		} catch ( Exception $e ) {
			die ( $e->getMessage () );
		}
    }

    
}
?>
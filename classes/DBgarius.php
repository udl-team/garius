<?php

class DBgarius
{
	private static $db;
	private function __construct(){}

	static function db_connect()
	{
		switch(DB_TYPE)
		{
			case 'mysql':
				return self::mysql_connect();
				break;
			default:
				throw new Exception ('[Erreur] Type de base inconnu');
		}
	}
	
 	private function mysql_connect()
 	{
 		if( !self::$db)
 		{
 			if( ! self::$db= new mysqli(DB_SERVEUR, DB_LOGIN, DB_PWD))
 			{
 				throw new Exception("Connexion impossible à la base de données : ".self::$db->connect_error);
 			}
 			self::$db->select_db(DB_DATABASE);
 			self::$db->set_charset('utf8');
 		}
 		return self::$db;
 	}
}

?>
<?php
/**
 * Classe Person
 * identite d'une personne
 *
 *
 * @author F.Bomy
 * DSI - Lille2  10/2015
 *
 */
class Person
{
	protected $uid;
	
	protected $t_attributs=array();
	protected $template;
	
	//objet de connexion
	private $dbgarius;
	
	//Requêtes//
	//private $req_select_person_by_uid='SELECT p.ID_PERSONNE as uid,p.NOM_USUEL as nom_usuel,p.NOM_PATRONYMIQUE as nom_patronymique,p.PRENOM as prenom,c.CIVILITE as civilite,p.DATE_NAISSANCE as date_naissance,GROUP_CONCAT(DISTINCT ucs.ID_SUPANN_CATEGORIE SEPARATOR \',\') as categories FROM UDL_PERSONNES p JOIN UDL_CIVILITES AS c ON p.ID_CIVILITE=c.ID_CIVILITE JOIN UDL_CATEGORIES_SUPANN as ucs ON ucs.ID_COMPTE=p.ID_PERSONNE WHERE p.ID_PERSONNE = %uid';
	
	/**
	 * Constructeur
	 * si uid => chargement
	 * sinon objet vide
	 *
	 * @param integer $uid
	 * @return boolean
	 */
	function __construct($uid,$template="basic")
	{
		try 
		{
			if(!$this->dbgarius=DBgarius::db_connect())
				throw new Exception("[Erreur] Echec du chargement des informations de connections à la base de données GARIUs");
				
			if(!$this->set_template($template."_person"))
				throw new Exception("[Erreur] Echec dans le chargement du template person '$template'");
				
			if($uid)
			{
				if(!$this->load($uid))
					throw new Exception("uid = $uid | [Erreur] Echec du chargement de l'identite");
			}
			else
				if(!$this->initialize())
					throw new Exception("[Erreur] Echec de l'initialisation");
				
			return true;
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
			return false;
		}
	}
	
	/**
	 * 'setter' template
	 * initialise le tableau d'attribut
	 * 
	 * @param string $template
	 */
	protected function set_template($template)
	{
		try 
		{
			$fichier_template=CONNECTEUR_DIR."/".$template.".template.json";
			
			if(!file_exists($fichier_template))
				throw new Exception("[Erreur] Le fichier template $fichier_template n'existe pas!");
			
			if(!($person_template=json_decode(file_get_contents($fichier_template),true)))
				throw new Exception("[Erreur] Impossible de lire les informations du template $fichier_template");
			
			//initialisation tableau d'attributs
			$this->t_attributs=$person_template['attributs'];
			
			$this->template=$template;
			
			return true;
		}
		catch (Exception $e)
		{
			echo "\n".$e->getMessage();
		
			//supann_syslog(__FUNCTION__,$e->getMessage());
			return FALSE;
		}
	}
	
	/**
	 * 'Getter' uid
	 */
	function get_uid()
	{
		return $this->uid;
	}
	
	/**
	 * 'Setter' uid
	 * @param integer $uid
	 */
	private function set_uid($uid)
	{
		try
		{
			if(!is_int($uid))
				throw new Exception("uid = $uid | [Erreur] uid invalide");
			$this->uid=$uid;
			return TRUE;
		}
		catch (Exception $e)
		{
			echo "\n".$e->getMessage();
				
			//supann_syslog(__FUNCTION__,$e->getMessage());
			return FALSE;
		}
	}
	
	/**
	 * Initialisation d'un skelette d'identité
	 *
	 * @return boolean
	 */
	function initialize()
	{
		try
		{
			//TODO
			return true;
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
	
			return false;
		}
	}
	
	/**
	 * Chargement de l'identité
	 *  
	 * @param int $uid
	 * @return boolean
	 */
	function load($uid)
	{
		try
		{
			//fixe uid
			if(!$this->set_uid($uid))
				throw new Exception("uid= $uid | [Erreur] Impossible de charger l'uid");
			
			//requête
			$resultat=$this->dbgarius->query(str_replace("<uid>",$this->get_uid(),$this->template['requete']));
			
			if($resultat && $row=$resultat->fetch_assoc())
			{
				foreach($row as $key=>$value)
				{
					if(array_key_exists($key, $this->t_attributs))
						if(is_array($this->t_attributs[$key]))
							$this->t_attributs[$key]=explode(',',$value);
						else
							$this->t_attributs[$key]=$value;
				}
			}
			else
				throw new Exception("uid = ".$this->get_uid()." | Cet uid n'est pas dans la table des Personnes");
			
			return true;
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
				
			return false;
		}
	}
	
	/**
	 * Sauvegarde de l'identité
	 * 
	 * @return boolean
	 */
	function save()
	{
		try 
		{
			//TODO
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
		
			return false;
		}
	}
	
	/**
	 * Synchro avec un annuaire openldap
	 * 
	 * @param obj $ldap
	 * @return boolean
	 */
	function openldap_sync($ldap)
	{
		try
		{
			//TODO
			return true;
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
			return false;
		}
	}
	
	/**
	 * Cherche une liste de personne répondant aux critères de recherches
	 * 
	 */
	public function search_uid_list($json_search)
	{
		try
		{
			//TODO
			
			return "";
		}
		catch(Exception $e)
		{
			echo "\n".$e->getMessage();
			return false;
		}
	}
	
	
	
	/**
	 * Retourne les infos sous la forme d'un string formatté
	 * 
	 * @return string
	 */
	public function to_string()
	{
		$tampon="";
		foreach($this->t_attributs as $key=>$value)
			if(is_array($value))
				$tampon.="\n$key: ".implode(", ",$value);
			else
				$tampon.= "\n$key: ".$value;
		
		$tampon.="\n";
		
		return $tampon;
	}
	
	/**
	 * Retourne les infos sous la forme d'un json
	 * 
	 * @return json
	 */
	public function to_json($array = NULL)
	{
		try 
		{
			/* reconstruction manuelle
			if(!$array)$array=$this->t_attributs;
			if(is_array($array))
			{
				$json=null;
					
				foreach($array as $key=>$data)
				{
					
					if(is_array($data))
					{
						$json.=',"'.$key.'":[';
							
						foreach($data as $data_key=>$sub_data)
						{
							if(is_array($sub_data))
								$json.=$this->to_json($sub_data).',';
						}
						$json=trim($json,",");
						$json.=']';
					}
					else
					{
						$json.=',"'.$key.'":"'.$data.'"';
					}
				}
			}
			return "{".trim($json,",")."}";
			*/
							
			return json_encode($this->t_attributs);
			
		} 
		catch (Exception $e) 
		{
			echo "\n".$e->getMessage();
			return false;
		}	
	}
}


?>
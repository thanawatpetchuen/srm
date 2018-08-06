<? 
	
	class AbstractBaseService{
			
			var $tableFields = array();
			var $tablesField = array();
			var $tablePrimaryKey = "";
			var $tableName = "";
			
			var $search_not_array = array("created_by", "last_upd_by");
			
			function search($pageNavigator, $_connection){     
						
						if(empty($this->tableName))
								return NULL; 
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag='N' ";	
						
						$_keys = array_keys($this->tableFields);
						for($i=0;$i<sizeof($_keys);$i++){
							if(!in_array($_keys[$i], $this->search_not_array) && $_REQUEST["searchby_".$_keys[$i]]!=""){
								if(substr($_keys[$i], -3)=="_id")
									$_sql.=" AND  _table.{$_keys[$i]} like '%".($_REQUEST["searchby_".$_keys[$i]])."%' ";
								else
									$_sql.=" AND  _table.{$_keys[$i]} like '%".$_REQUEST["searchby_".$_keys[$i]]."%' ";
							} 
						}              
						
						///$_sql.=" AND _table.language ='".$GLOBALS["__language"]."' ";						

						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.created DESC ";
						}
						
						mysql_query("SET character_set_results=tis620");
						mysql_query("SET character_set_client=tis620");
						mysql_query("SET character_set_connection=tis620");
						
						if(!empty($pageNavigator)){
								$_sql.="  LIMIT ".$pageNavigator->currentPageFirstRecordNo.", ".$pageNavigator->recordPerPage;
								mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
								
								$_sql_rets = mysql_query($_sql) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());		
						 		//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<br>";
								//echo "Found size =>".sizeof($_sql_rets)."[{$pageNavigator->currentPageFirstRecordNo}]<br>";
								return $this->fill2Array($_sql_rets, $pageNavigator->currentPageFirstRecordNo);	
						}else{ 
								//mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
								$_sql_rets = mysql_query($_sql) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
								//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<BR>num_rows >>[".mysql_num_rows($_sql_rets)."]<br>";   	   
								//echo "mysql_affected_rows[".mysql_affected_rows()."]<br>";
								$_arrtest = $this->fill2Array($_sql_rets, 0);	
								//echo "_arrtest[".sizeof($_arrtest)."]<br>";
								return mysql_num_rows($_sql_rets);
						}
			}
			function search_by_language($pageNavigator, $_connection){     
						
						if(empty($this->tableName))
								return NULL; 
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag='N' ";	
						
						$_keys = array_keys($this->tableFields);
						for($i=0;$i<sizeof($_keys);$i++){
							if(!in_array($_keys[$i], $this->search_not_array) && $_REQUEST["searchby_".$_keys[$i]]!=""){
								if(substr($_keys[$i], -3)=="_id")
									$_sql.=" AND  _table.{$_keys[$i]} like '%".($_REQUEST["searchby_".$_keys[$i]])."%' ";
								else
									$_sql.=" AND  _table.{$_keys[$i]} like '%".$_REQUEST["searchby_".$_keys[$i]]."%' ";
							} 
						}              
						
						///$_sql.=" AND _table.language ='".$GLOBALS["__language"]."' ";						

						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.order_no ";
						}
						
						mysql_query("SET character_set_results=tis620");
						mysql_query("SET character_set_client=tis620");
						mysql_query("SET character_set_connection=tis620");
						
						if(!empty($pageNavigator)){
								$_sql.="  LIMIT ".$pageNavigator->currentPageFirstRecordNo.", ".$pageNavigator->recordPerPage;
								mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
								$_sql_rets = mysql_query($_sql) or die("Invalid query: [$_sql]" . mysql_error());										
								$_rets = $this->fill2Array($_sql_rets, $pageNavigator->currentPageFirstRecordNo);	
								//echo "??????? >> ".$this->tableName."_language<br>";
								if(in_array($this->tableName."_language", $GLOBALS["__APP_TABLES"])){
									$_keyfield = $this->tablePrimaryKey;
									for($j=0;$j<sizeof($_rets);$j++){
										$_sql2 = "select * from ".$this->tableName."_language where sys_del_flag = 'N'  and  ".$this->tablePrimaryKey." = ".$_rets[$j]->$_keyfield;
										mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
										$_lang_rets = mysql_query( $_sql2) or die("Invalid query: [$_sql2]" . mysql_error());			
										$_cnt_item=0;
										while ($_langrow = mysql_fetch_array($_lang_rets)){	 
											$_alllanguage[$_langrow['language']] = $_langrow;

											$_cnt_item++;	
										}
										$_rets[$j]->languages = $_alllanguage;
									}
								}
								//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<br>";
								//echo "Found size =>".sizeof($_sql_rets)."[{$pageNavigator->currentPageFirstRecordNo}]<br>";
								return $_rets;
						}else{ 
								//mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]" . mysql_error());
								$_sql_rets = mysql_query($_sql) or die("Invalid query: [$_sql]" . mysql_error());
								//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<BR>num_rows >>[".mysql_num_rows($_sql_rets)."]<br>";   	   
								//echo "mysql_affected_rows[".mysql_affected_rows()."]<br>";
								$_arrtest = $this->fill2Array($_sql_rets, 0);	
								//echo "_arrtest[".sizeof($_arrtest)."]<br>";
								return mysql_num_rows($_sql_rets);
						}
			}
			function listLanguage($_connection){     
						
						if(empty($this->tableName))
								return NULL; 
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag = 'N' ";
						$_sql.=" AND  _table.publish_flag = 'Y' ";	

						$_keys = array_keys($this->tableFields);
						for($i=0;$i<sizeof($_keys);$i++){
							if(!in_array($_keys[$i], $this->search_not_array) && $_REQUEST["searchby_".$_keys[$i]]!=""){
								if(substr($_keys[$i], -3)=="_id")
									$_sql.=" AND  _table.{$_keys[$i]} like '%".($_REQUEST["searchby_".$_keys[$i]])."%' ";
								else
									$_sql.=" AND  _table.{$_keys[$i]} like '%".$_REQUEST["searchby_".$_keys[$i]]."%' ";
							} 
						}              
						
						///$_sql.=" AND _table.language ='".$GLOBALS["__language"]."' ";						

						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.order_no ";
						}
						
						mysql_query("SET character_set_results=tis620");
						mysql_query("SET character_set_client=tis620");
						mysql_query("SET character_set_connection=tis620");
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [".$GLOBALS["__MYSQLDB"]["DB_NAME"]."][$_sql]" . mysql_error());
						$_sql_rets = mysql_query($_sql) or die("Invalid query: [".$GLOBALS["__MYSQLDB"]["DB_NAME"]."][$_sql]" . mysql_error());								
						$_rets = $this->fill2Array($_sql_rets, $pageNavigator->currentPageFirstRecordNo);	
						//echo "??????? >> ".$this->tableName."_language<br>";
						if(in_array($this->tableName."_language", $GLOBALS["__APP_TABLES"])){
							$_keyfield = $this->tablePrimaryKey;
							for($j=0;$j<sizeof($_rets);$j++){
								$_sql2 = "select * from ".$this->tableName."_language where sys_del_flag = 'N'  and  ".$this->tablePrimaryKey." = ".$_rets[$j]->$_keyfield;
								mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql2]". mysql_error());
								$_lang_rets = mysql_query( $_sql2) or die("Invalid query:[$_sql2]". mysql_error());			
								$_cnt_item=0;
								while ($_langrow = mysql_fetch_array($_lang_rets)){	 
									$_alllanguage[$_langrow['language']] = $_langrow;
									$_cnt_item++;	
								}
								$_rets[$j]->languages = $_alllanguage;
							}
						}
						//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<br>";
						//echo "Found size =>".sizeof($_sql_rets)."[{$pageNavigator->currentPageFirstRecordNo}]<br>";
						return $_rets;			 
			}
			

			function lists($_connection){                   
												
						if(empty($this->tableName))
								return NULL;
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag='N' ";	
						$_keys = array_keys($this->tableFields);
						for($i=0;$i<sizeof($_keys);$i++){ 
							if(!in_array($_keys[$i], $this->search_not_array) && $_REQUEST["listby_".$_keys[$i]]!=""){
								if(substr($_keys[$i], -3)=="_id")
									$_sql.=" AND  _table.{$_keys[$i]} = '".($_REQUEST["listby_".$_keys[$i]])."' ";
								else
									$_sql.=" AND  _table.{$_keys[$i]} = '".$_REQUEST["listby_".$_keys[$i]]."' ";
							}
						}               
						
						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.order_no ";
						}
						
						mysql_query("SET character_set_results=tis620");
						mysql_query("SET character_set_client=tis620");
						mysql_query("SET character_set_connection=tis620");
						
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]". mysql_error());
						$_sql_rets = mysql_query($_sql) or die("Invalid query: [$_sql]". mysql_error());			
						//echo "mysql_affected_rows[".mysql_affected_rows()."]<br>";
						//echo "[$_sql]<br>";
						return $this->fill2Array($_sql_rets, $pageNavigator->currentPageFirstRecordNo);	
						
			}  

			function existsKeyByField($_key, $_table, $_field, $_connection){    
						$_sql="	SELECT *  ";
						$_sql.="	FROM $_table _table ";
						$_sql.="	WHERE  _table.$_field = '$_key'	";
						$_sql.="	AND  _table.sys_del_flag = 'N'	";
						//echo "[".$this->tableName."][$_sql]<br>";
						mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]);
						mysql_query("SET character_set_results=tis620");
						$_sql_rets = mysql_query($_sql);
						//echo "\$_connection >>[".$GLOBALS["__mysqldb"]['mysql_dbname']."]".$_connection."<br>";
						//echo "mysql_affected_rows() >>[".mysql_affected_rows()."]<br>";
						if(mysql_affected_rows()>0){
							if ($row = mysql_fetch_array($_sql_rets)){	
								$_REQUEST[$this->tablePrimaryKey] = ($row[$this->tablePrimaryKey]); 
							}
							return true;	 
						}  
						return false;
			}  

			function existsKeyByFields($_table, $_fields, $_connection){    
			
						$_sql="	SELECT *  ";
						$_sql.="	FROM $_table _table ";
						$_sql.="	WHERE  _table.sys_del_flag = 'N'	";
						
						$_keys = array_keys($_fields);
						for($i=0;$i<sizeof($_fields);$i++){
							$_sql.="	AND  _table.".$_keys[$i]." = '".$_fields[$_keys[$i]]."'	";
						}

						//echo "[".$this->tableName."][$_sql]<br>";
						
						mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]);
						mysql_query("SET character_set_results=tis620");
						$_sql_rets = mysql_query($_sql); 
						//echo "\$_connection >>[".$GLOBALS["__mysqldb"]['mysql_dbname']."]".$_connection."<br>";
						//echo "mysql_affected_rows() >>[".mysql_affected_rows()."]<br>";
						if(mysql_affected_rows()>0){
							if ($row = mysql_fetch_array($_sql_rets)){	
								$_REQUEST[$this->tablePrimaryKey] = ($row[$this->tablePrimaryKey]); 
							}
							return true;	 
						}  
						return false;
			}
			
			function servicecreate($_connection){      
							//print_r($_REQUEST);
							$_sql = $this->sqlCreateGenerator();  
							//echo "<hr>\$_sql[$_sql]<hr>";
							if(empty($_sql))
								return NULL;			  
							
							mysql_query("SET character_set_client=tis620");
							mysql_query("SET character_set_connection=tis620");
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]". mysql_error());
							mysql_select_db($GLOBALS["__MYSQLDB"]["DB_NAME"]);
							$retrows = mysql_query($_sql); 
							if(!$retrows){ throw new Exception("Error [$_sql]");	}
							$_mysql_insert_id = mysql_insert_id(); 
							$_REQUEST[$this->tablePrimaryKey] = ($_mysql_insert_id);     
							return ($_mysql_insert_id);     
			}
			
			function sqlCreateGenerator(){
				if($this->tableName=="")
					return NULL;
				$_jump_fields = array("sys_del_flag", "remote_ip", "created", "created_by", "last_upd_by", "last_upd");
				$_fields = "sys_del_flag, remote_ip,created, created_by, last_upd_by, last_upd ";
				$_values = "'N', '{$_SERVER['REMOTE_ADDR']}', NOW(), ".($GLOBALS['__SSO_USER_SECURITY']*1).", ".($GLOBALS['__SSO_USER_SECURITY']*1).", NOW() ";
				//echo "\$this->tableName >>[".$this->tableName."]<br>";
				//print_r($this->tablesField);
				//echo "<br>";
				$_keys = array_keys($this->tablesField[$this->tableName]);
				$tableFields = $this->tablesField[$this->tableName];
				if(empty($_keys) || empty($tableFields))
					return NULL;
				//print_r($tableFields);
				$append_index = 0;			
				for($keys=0;$keys<sizeof($_keys);$keys++){               
					if($_keys[$keys]!=$this->tablePrimaryKey){
						    
		 					//echo "??????? [{$_keys[$keys]}] value is [{$_REQUEST[$_keys[$keys]]}] <br>";
							
							$_reqvalue = "";
							$_reqkey = $_keys[$keys];
							if(!in_array($_reqkey, $_jump_fields) && strpos($_reqkey, "_id") && !empty($_REQUEST[$_reqkey])){
								//echo "[$_reqkey] case 1<BR>";
								$_reqvalue = trim($_REQUEST[$_reqkey]);
							}else if(!in_array($_reqkey, $_jump_fields) && substr($_reqkey, -4)=="_psw" && !empty($_REQUEST[$_reqkey])){
								$_reqvalue = md5(trim($_REQUEST[$_reqkey]));
							}else if(!in_array($_reqkey, $_jump_fields) && !empty($_REQUEST[$_reqkey])){
								//echo "$_reqkey ->".$_REQUEST[$_reqkey]."<BR>";
								$_reqvalue = trim(html_filter($_REQUEST[$_reqkey]));
							}
							
							if(!in_array($_reqkey, $_jump_fields) && $_REQUEST[$_keys[$keys]]!=NULL && $_REQUEST[$_keys[$keys]]!=""){
								//echo "append value+field type [{$keys}] [{$_keys[$keys]}] [{$tableFields[$_keys[$keys]]}]<br>";
								$_fields .= ", ".trim($_keys[$keys]);
								if($tableFields[$_keys[$keys]]=='int' || $tableFields[$_keys[$keys]]=='real'){
										if(strpos($_keys[$keys], "_id")){
											$_values .= ", ".($_reqvalue)*1;
											//echo "decode [$_values]<BR>";
										}else{		
											$_values .= ", ".$_reqvalue*1;
										}
								}else if($tableFields[$_keys[$keys]]=='string' || $tableFields[$_keys[$keys]]=='blob'){ 
									$_values .= ", '".trim($_reqvalue)."'	";
								}else if($tableFields[$_keys[$keys]]=='datetime' || $tableFields[$_keys[$keys]]=='date'){
									if($_reqvalue!="NOW()")
										$_values .= ", ".InsertMysqlDate($_reqvalue);
									else
										$_values .= ", $_reqvalue";
								}
								
								$append_index++;

							}

					 }
				}		 
				$_sql = "INSERT INTO ".$this->tableName." ($_fields) VALUES ($_values);	";
				return $_sql;
			}

			function servicecreates($_size, $_connection){     		 
							$_sql = $this->sqlCreatesGenerator($_size);
							//echo "$_sql<BR>";
					 		if(empty($_sql))
								return NULL;
							
							mysql_query("SET character_set_results=tis620") or die("Invalid query: [$_sql]". mysql_error());
							
							$_sqls = explode(";", $_sql);
							for($i=0;$i<sizeof($_sqls);$i++){
								if(!empty($_sqls[$i]) && trim($_sqls[$i])!=""){
									//echo $_sqls[$i]."<hr>";
									$retrows = mysql_query( $_sqls[$i]);
									//if($retrows==0){ //throw new Exception("<hr>{$_sqls[$i]}<hr>Error !!");	}
								} 		                    			  
							}
							return mysql_insert_id();          							
			}  
			
			function sqlCreatesGenerator($_size){
				//echo "create [{$service_insert_tables[0]}] sqlGenerator<br>";
				//echo "create [{$service_insert_tables[0]}] sqlGenerator<br>";
				//echo "tablesField >>".$this->tablesField[$service_insert_tables[0]]."<BR>";				
				if(empty($_size))
					return;
				$tableFields = $this->tablesField[$this->tableName];
				$_keys = array_keys($tableFields);
				if(empty($_keys) || empty($tableFields) && $_size<1)
					return NULL;
				$append_index = 0;          
								
				for($l=0;$l<$_size;$l++){
					
					$_fields = "sys_del_flag	";
					$_values = " 'N' ";
					
					for($keys=0; $keys<sizeof($_keys);$keys++){       
					//echo "??????? Field >>".$_keys[$keys]."<br>";    
					//	if($_REQUEST[$_keys[$keys]][$l]!=NULL && $_REQUEST[$_keys[$keys]][$l]!=""){
						if(!empty($_REQUEST[$_keys[$keys]])){							
							if(is_array($_REQUEST[$_keys[$keys]])){
								//echo "array _REQUEST[".$_keys[$keys]."] case  array 1.<br>";
								$_reqvalue = $_REQUEST[$_keys[$keys]][$l];
								//echo $_keys[$keys]."[$l] = [$_reqvalue]<br>";
							}else{
								//echo "not array _REQUEST[".$_keys[$keys]."] case not array 2.<br>"; 
								$_reqvalue = $_REQUEST[$_keys[$keys]];
							}
							
							if(strpos($_keys[$keys], "_id")){
								$_reqvalue = (trim($_reqvalue));
							}if(substr($_keys[$keys], -4)=="_psw"){
								$_reqvalue = md5(trim($_reqvalue));
							}else{
								$_reqvalue = trim(html_filter($_reqvalue)); 
							}

							$_fields .= ", ".trim($_keys[$keys]);
							if($tableFields[$_keys[$keys]]=='int' || $tableFields[$_keys[$keys]]=='real'){
								$_values .= ", ".trim($_reqvalue)*1;
							}else if($tableFields[$_keys[$keys]]=='string' || $tableFields[$_keys[$keys]]=='blob'){
								if(substr($_keys[$keys], -4)=="_psw"){
									$_values .= ", '".md5(trim($_reqvalue))."'";
								}else{
									$_values .= ", '".trim($_reqvalue)."'";
								}
							}else if($tableFields[$_keys[$keys]]=='datetime' || $tableFields[$_keys[$keys]]=='date'){
								$_values .= ", ".InsertMysqlDate($_reqvalue);
							}else{
								echo "!!! invalid type [".$tableFields[$_keys[$keys]]."]<br>";
							}   
							
							$append_index++;
							
						} 
						
					}
				    	
					$_sql .= "INSERT INTO ".$this->tableName." (remote_ip, created_by, created, $_fields) VALUES ('{$_SERVER['REMOTE_ADDR']}',  ".($GLOBALS['__SSO_USER_SECURITY']*1).", NOW(), $_values);\n";
					
				}  
				
				return $_sql;
				
			}
			
			function serviceupdate($__PARAM, $_connection){     			
				 
				$_sql = $this->sqlUpdateGenerator($__PARAM);             
				//echo "$_sql<br>";
				
				if(empty($_sql))
					return NULL; 

				mysql_query("SET character_set_client=tis620");
				mysql_query("SET character_set_connection=tis620");
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());				
				return mysql_query($_sql);
			}
			
			function serviceupdates($_connection){     
				
				$_prikeys = $_REQUEST[$this->tablePrimaryKey];
								
				$_keys = array_keys($this->tablesField[$this->tableName]);
				$tableFields = $this->tablesField[$this->tableName];
				/*
				print_r($_keys);
				echo "<hr>";
				*/
				
				//print_r($tableFields);
				//echo "<hr>";
				
				$_jump_fields = array($this->tablePrimaryKey, "sys_del_flag", "remote_ip", "created", "last_upd", "last_upd_by");
				
				for($i=0;$i<sizeof($_prikeys);$i++){
					
					$_sql = "UPDATE ".$this->tableName." SET last_upd = NOW(),  last_upd_by = ".$GLOBALS["__SSO_USER_SECURITY"].", remote_ip = '".$_SERVER['REMOTE_ADDR']."', last_upd_by = ".($GLOBALS['__SSO_USER_SECURITY']*1);
										
					//echo "???? sql ?????? $i ??? Fields ????? (".sizeof($_keys).") Columes<br>";

					for($keys=0;$keys<sizeof($_keys);$keys++){             
						
							//echo "keys >>(".$_keys[$keys].") value is [".$_REQUEST[$_keys[$keys]][$i]."]<br>";
							if(!in_array($_keys[$keys], $_jump_fields) && !empty($_REQUEST[$_keys[$keys]][$i])){
								//echo "append keys >>(".$_keys[$keys].")<br>";
								if(substr($_keys[$keys], -3)=="_id"){
									$_sql .= ", ".$_keys[$keys]." = ".($_REQUEST[$_keys[$keys]]);
								}else{
									if($tableFields[$_keys[$keys]] == "string" || $tableFields[$_keys[$keys]] == "blob")
										$_sql .= ", ".$_keys[$keys]." = '".$_REQUEST[$_keys[$keys]][$i]."'";
									else if($tableFields[$_keys[$keys]] == "int" || $tableFields[$_keys[$keys]] == "int")
										$_sql .= ", ".$_keys[$keys]." = '".($_REQUEST[$_keys[$keys]][$i]*1)."'";
									else
										$_sql .= ", ".$_keys[$keys]." = ".$_REQUEST[$_keys[$keys]][$i];
								}
							}					

					}

					$_sql .= " WHERE ".$this->tablePrimaryKey." = ".($_prikeys[$i]);
					mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
					//echo "$_sql<br>";
					$retrows = mysql_query($_sql);
					if($retrows==0){ throw new Exception("<hr>{$_sqls[$i]}<hr>Error !!");	
						
					}
				}
				return true;
			}
			
			function sqlUpdateGenerator($__PARAM){  
				$_fields = " last_upd = now()";
				$_values = "";
				$_keys = array_keys($this->tablesField[$this->tableName]);
				$tableFields = $this->tablesField[$this->tableName];
				if(empty($_keys) || empty($tableFields))
					return NULL;
				//print_r($tableFields);
				$append_index = 0;
								
				$_req_keys = array_keys($__PARAM);
				for($p=0;$p<sizeof($_req_keys);$p++){
					$_reqkeys[$_req_keys[$p]] = $_req_keys[$p];
				}

				for(!empty($__PARAM[$keys]) && $keys=0;$keys<sizeof($_keys);$keys++){               
					
					//echo "++ name [{$_keys[$keys]}] value is [{$_REQUEST[$_keys[$keys]]}] <br>";

					//if($_REQUEST[$_keys[$keys]]!=NULL && $_REQUEST[$_keys[$keys]]!="" && $_keys[$keys]!=$this->tablePrimaryKey){
					
					if($_keys[$keys]!="created" && $_keys[$keys]!="created_by" && $_reqkeys[$_keys[$keys]]!="" && $_keys[$keys]!=$this->tablePrimaryKey){
						
						//echo "++ append value+field type [{$keys}] [{$_keys[$keys]}] [{$tableFields[$_keys[$keys]]}]<br>";

						if($tableFields[$_keys[$keys]]=='int' || $tableFields[$_keys[$keys]]=='real'){
							if(strpos($_keys[$keys], "_id")){
								if($_fields=="")
									$_fields .= "".$_keys[$keys]." = ".($_REQUEST[$_keys[$keys]]*1);
								else
									$_fields .= ", ".$_keys[$keys]." = ".($_REQUEST[$_keys[$keys]]*1);
							}else{
								if($_fields=="")
									$_fields .= "".$_keys[$keys]." = ".trim($_REQUEST[$_keys[$keys]])*1;
								else
									$_fields .= ", ".$_keys[$keys]." = ".trim($_REQUEST[$_keys[$keys]])*1;
							}

						}else if($tableFields[$_keys[$keys]]=='string' || $tableFields[$_keys[$keys]]=='blob'){
							if(substr($_keys[$keys], -4)=="_psw"){
								if($_fields=="")
									$_fields .= " ".$_keys[$keys]." = '".md5(trim(html_filter($_REQUEST[$_keys[$keys]])))."'";
								else
									$_fields .= ", ".$_keys[$keys]." = '".md5(trim(html_filter($_REQUEST[$_keys[$keys]])))."'";
							}else{
								if($_fields=="")
									$_fields .= " ".$_keys[$keys]." = '".(html_filter($_REQUEST[$_keys[$keys]]))."'";
								else
									$_fields .= ", ".$_keys[$keys]." = '".(html_filter($_REQUEST[$_keys[$keys]]))."'";
							}
						}else if($tableFields[$_keys[$keys]]=='datetime' || $tableFields[$_keys[$keys]]=='date'){
							if($_keys[$keys]=="" && $_keys[$keys]!="created"){
								//echo "1(".$_keys[$keys].")<br>";
								$_fields .= " ".$_keys[$keys]." = ".InsertMysqlDate($_REQUEST[$_keys[$keys]], $_REQUEST[$_keys[$keys]."_hour"], $_REQUEST[$_keys[$keys]."_minute"]);
							}else if($_keys[$keys]!="created" && $_keys[$keys]!="job_order_call_datetime" && $_keys[$keys]!="job_order_visit_datetime"){
								//echo "2(".$_keys[$keys].")<br>";
								$_fields .= ", ".$_keys[$keys]." = ".InsertMysqlDate($_REQUEST[$_keys[$keys]], $_REQUEST[$_keys[$keys]."_hour"], $_REQUEST[$_keys[$keys]."_minute"]);
							}
						}                 
						$append_index++;
					}
				}
				
				$tableFields = $this->tablesField[$this->tableName];
				//echo "\$tableFields >>".$tableFields[$this->tablePrimaryKey]."<BR>";
				
				$_sql = " UPDATE  ".$this->tableName."  set  $_fields , last_upd_by = ".$GLOBALS["__SSO_USER_SECURITY"];
				
				if($tableFields[$this->tablePrimaryKey]=="string")
						$_sql .= "	   where ".$this->tablePrimaryKey." = '".($_REQUEST[$this->tablePrimaryKey])."';";
				else if($tableFields[$this->tablePrimaryKey]=="int")
						$_sql .= "	   where ".$this->tablePrimaryKey." = ".($_REQUEST[$this->tablePrimaryKey]).";";
				return $_sql;
			}			
			
			function serviceget($_connection){     

				//echo "tablePrimaryKey >>[".$this->tablePrimaryKey."]<br>";
				
				if(empty($_REQUEST[$this->tablePrimaryKey]))
					throw new Exception("NULL PARAMETER[".$this->tablePrimaryKey."]values(".$_REQUEST[$this->tablePrimaryKey].") ++ ");
				//echo "tablePrimaryKey >>[".($_REQUEST[$this->tablePrimaryKey])."]<br>";
				if(!is_numeric(($_REQUEST[$this->tablePrimaryKey])))
					throw new Exception("NULL PARAMETER"); 
				
				$tableFields = $this->tablesField[$this->tableName];
				//echo "\$tableFields >>".$tableFields[$this->tablePrimaryKey]."<BR>";

			    $_sql =" SELECT * FROM ".$this->tableName." _table  ";
				if($tableFields[$this->tablePrimaryKey]=="string")
					 $_sql .= "  WHERE  _table.".$this->tablePrimaryKey." = '".($_REQUEST[$this->tablePrimaryKey])."'	";	
				else if($tableFields[$this->tablePrimaryKey]=="int")
					 $_sql .= "  WHERE  _table.".$this->tablePrimaryKey." = ".($_REQUEST[$this->tablePrimaryKey])."	";	
				
				//echo "$_sql<br>";
				
				mysql_query("SET character_set_results=tis620");
				$_sql_rets = mysql_query($_sql);
				if(mysql_num_rows($_sql_rets)!=1)
					throw new Exception("Error [$_sql]"); 
				return $this->fill2Request($_sql_rets);

			}
			function serviceget_utf8($_connection){                                

				//echo "tablePrimaryKey >>[".$this->tablePrimaryKey."]<br>";
				
				if(empty($_REQUEST[$this->tablePrimaryKey]))
					throw new Exception("NULL PARAMETER"); 
				//echo "tablePrimaryKey >>[".($_REQUEST[$this->tablePrimaryKey])."]<br>";
				if(!is_numeric(($_REQUEST[$this->tablePrimaryKey])))
					throw new Exception("NULL PARAMETER"); 
				
				$tableFields = $this->tablesField[$this->tableName];
				//echo "\$tableFields >>".$tableFields[$this->tablePrimaryKey]."<BR>";

			    $_sql =" SELECT * FROM ".$this->tableName." _table  ";
				if($tableFields[$this->tablePrimaryKey]=="string")
					 $_sql .= "  WHERE  _table.".$this->tablePrimaryKey." = '".($_REQUEST[$this->tablePrimaryKey])."'	";	
				else if($tableFields[$this->tablePrimaryKey]=="int")
					 $_sql .= "  WHERE  _table.".$this->tablePrimaryKey." = ".($_REQUEST[$this->tablePrimaryKey])."	";	
				
				//echo "$_sql<br>";

				mysql_query("SET character_set_results=utf8");
				$_sql_rets = mysql_query($_sql);
				if(mysql_num_rows($_sql_rets)!=1)
					throw new Exception("Error [$_sql]"); 
				return $this->fill2Request($_sql_rets);
			}
			function servicegetlang($tablePrimaryKey, $_connection){     
				if(empty($_REQUEST[$tablePrimaryKey]))
					throw new Exception("NULL PARAMETER"); 
				//echo "tablePrimaryKey >>[".($_REQUEST[$this->tablePrimaryKey])."]<br>";
				if(!is_numeric(($_REQUEST[$tablePrimaryKey])))
					throw new Exception("NULL PARAMETER key is not numeric !!"); 
				
				$tableFields = $this->tablesField[$this->tableName];
				//echo "\$tableFields >>".$tableFields[$this->tablePrimaryKey]."<BR>";

			    $_sql =" SELECT * FROM ".$this->tableName." _table  ";
				if($tableFields[$tablePrimaryKey]=="string")
					 $_sql .= "  WHERE  _table.".$tablePrimaryKey." = '".($_REQUEST[$tablePrimaryKey])."'	";	
				else if($tableFields[$tablePrimaryKey]=="int")
					 $_sql .= "  WHERE  _table.".$tablePrimaryKey." = ".($_REQUEST[$tablePrimaryKey])."	";	
				//echo "$_sql<br>";
				mysql_query("SET character_set_results=tis620");
				$_sql_rets = mysql_query($_sql);
				if(mysql_num_rows($_sql_rets)==0)
					throw new Exception("Error [$_sql]");
				$_allRet = $this->fill2Array($_sql_rets, $_cnt=1);
				for($i=0;$i<sizeof($_allRet);$i++){
					$_retRest[$_allRet[$i]->language] = $_allRet[$i];
				}
				return $_retRest;
			}
			function servicegetlist($_pkparams, $_connection){         
			    $_sql = "	 SELECT * FROM ".$this->tableName." _table where _table.sys_del_flag='N'	";			
				$tableFields = $this->tablesField[$this->tableName];
				for($i=0; $i<sizeof($_pkparams); $i++){
					if($tableFields[$_pkparams[$i]]=="string")
						$_sql .="	AND _table.".$_pkparams[$i]." = '".($_REQUEST[$_pkparams[$i]])."' ";	
					else if($tableFields[$_pkparams[$i]]=="int")
						$_sql .="	AND _table.".$_pkparams[$i]." = ".($_REQUEST[$_pkparams[$i]]);	
				}
				
				if(sizeof($_REQUEST['field_order_by'])!=0){
						$_sql.=" order by	 ";
						for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
							if($k==0)
								$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
							else
								$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
						}
						$_sql.= "	".$_REQUEST['field_order_sort']."	";
				}else{
						$_sql.=" order by	 _table.order_no ";
				}
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql);
				return $this->fill2Array($_sql_rets);	
			}  
			
			function servicedeletes($_pkparams, $_connection){        
			    $_sql = "	 update ".$this->tableName." set sys_del_flag = 'Y' where sys_del_flag='N'	";				
				$tableFields = $this->tablesField[$this->tableName];
				for($i=0; $i<sizeof($_pkparams); $i++){
					if($tableFields[$_pkparams[$i]]=="string")
						$_sql .="	AND ".$_pkparams[$i]." = '".($_REQUEST[$_pkparams[$i]])."' ";	
					else if($tableFields[$_pkparams[$i]]=="int")
						$_sql .="	AND ".$_pkparams[$i]." = ".($_REQUEST[$_pkparams[$i]]);	
				} 
				
				//echo "$_sql<BR>";

				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql);
				if($_sql_rets!=0)
					return true;
				return false;
			}  
			
			function servicegetByFK($_fk, $_connection){         
			    $_sql = "	 SELECT * FROM ".$this->tableName." _table where _table.sys_del_flag='N' ";
				$_keys = array_keys($_fk);
				for($i=0;$i<sizeof($_keys);$i++){
					if(substr($_keys[$i], -3)=="_id")
						$_sql .= "	AND ".$_keys[$i]." = ".($_fk[$_keys[$i]]);			
					else
						$_sql .= "	AND ".$_keys[$i]." = ".$_fk[$_keys[$i]];			
				}
				//echo "$_sql<BR>";
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql);
				return $this->fill2Array($_sql_rets);	
			}  
			
			function html_lists($_name, $_id, $publish_flag = "Y", $_value, $_labels, $_conditions,$_connection){                   
	
						if(empty($this->tableName))
								return NULL;
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag = 'N' ";	
						$_sql.=" AND  _table.publish_flag = '$publish_flag' ";	

						for($i=0;$i<sizeof($_conditions);$i++){
							$_sql.= "{$_conditions[$i][0]}  _table.{$_conditions[$i][1]} = '{$_conditions[$i][2]}' ";
						}              
						
						//$_sql.=" AND _table.global_flag='Y' ";

						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.order_no ";
						}
		
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
						$_sql_rets = mysql_query($_sql);
						//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<br>";
						$_cnt_item=0;	
						
						ob_start();

						echo "<select name=\"$_name\" id=\"$_id\" title=\"$_name\">";
						echo "<option label=\"\" value=\"\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>";
						while ($row = mysql_fetch_array($_sql_rets)){  
								$_label  = "";
								for($k=0;$k<sizeof($_labels);$k++){
									if(!empty($row[$_labels[$k]])){
										if($k==0)
											$_label  .= trim($row[$_labels[$k]]);
										else
											$_label  .= " - ".trim($row[$_labels[$k]]);
									}
								}
								if(strpos($_value, "_id"))
									echo "<option label=\"{$row[$_label]}\" value=\"".($row[$_value])."\">$_label</option>";
								else               
									echo "<option label=\"$_label\" value=\"".$row[$_value]."\">$_label</option>";
						}
						
						echo "</select>";

						$out2 = ob_get_contents(); 
						ob_end_clean();

						return $out2;
			}  
            
			function isDuplicate($_field, $_value,$_connection){     
				if(empty($this->tableName))
								return NULL; 
				$_sql=" SELECT * FROM ".$this->tableName." _table ";	
				$_sql.=" WHERE  _table.sys_del_flag = 'N' ";
				$_sql.=" AND  _table.$_field = '$_value' ";	
				mysql_query("SET character_set_results=tis620");
				mysql_query("SET character_set_client=tis620");
				mysql_query("SET character_set_connection=tis620");
			 
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());			
				$_row = mysql_affected_rows();
				//echo "mysql_affected_rows >> $_row<BR>";
				if($_row>0)
					return true;
				return false;
			}
			function isDuplicates($_fields, $_values,$_connection){     
				if(empty($this->tableName))
								return NULL; 
				$_sql=" SELECT * FROM ".$this->tableName." _table ";	
				$_sql.=" WHERE  _table.sys_del_flag = 'N' ";
				for($i=0;$i<sizeof($_fields);$i++){
					$_sql.=" AND  _table.".$_fields[$i]." = '".$_values[$i]."' ";	
				}
				mysql_query("SET character_set_results=tis620");
				mysql_query("SET character_set_client=tis620");
				mysql_query("SET character_set_connection=tis620");
			 
				mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());			
				$_row = mysql_affected_rows();
				//echo "mysql_affected_rows >> $_row<BR>";
				if($_row>0)
					return true;
				return false;
			}


			function view_by_language($_connection){     
						
						if(empty($this->tableName))
								return NULL; 
						
						$_sql=" SELECT * FROM ".$this->tableName." _table ";	
						$_sql.=" WHERE  _table.sys_del_flag = 'N' ";
						$_sql.=" AND  _table.publish_flag = 'Y' ";	
						
						//$_sql.=" AND _table.global_flag='Y' "; 
						//echo "$_sql<BR>";
						$_keys = array_keys($this->tableFields);
						for($i=0;$i<sizeof($_keys);$i++){
							if(!in_array($_keys[$i], $this->search_not_array) && $_REQUEST["searchby_".$_keys[$i]]!=""){
								if(substr($_keys[$i], -3)=="_id")
									$_sql.=" AND  _table.{$_keys[$i]} like '%".($_REQUEST["searchby_".$_keys[$i]])."%' ";
								else
									$_sql.=" AND  _table.{$_keys[$i]} like '%".$_REQUEST["searchby_".$_keys[$i]]."%' ";
							} 
						}              
						
						///$_sql.=" AND _table.language ='".$GLOBALS["__language"]."' ";						

						if(sizeof($_REQUEST['field_order_by'])!=0){
								$_sql.=" order by	 ";
								for($k=0;$k<sizeof($_REQUEST['field_order_by']);$k++){
									if($k==0)
										$_sql.="	 _table.".$_REQUEST['field_order_by'][$k];
									else
										$_sql.="	 , _table.".$_REQUEST['field_order_by'][$k];
								}
								$_sql.= "	".$_REQUEST['field_order_sort']."	";
						}else{
								$_sql.=" order by	 _table.order_no ";
						}
						
						mysql_query("SET character_set_results=tis620");
						mysql_query("SET character_set_client=tis620");
						mysql_query("SET character_set_connection=tis620");
			 
						mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());
						$_sql_rets = mysql_query($_sql) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql]" . mysql_error());										
						$_rets = $this->fill2Array($_sql_rets, $pageNavigator->currentPageFirstRecordNo);	
						//echo "??????? >> ".$this->tableName."_language<br>";
						if(in_array($this->tableName."_language", $GLOBALS["__APP_TABLES"])){
									$_keyfield =  $this->tablePrimaryKey;
									for($j=0;$j<sizeof($_rets);$j++){
										$_sql2 = "select * from ".$this->tableName."_language where sys_del_flag = 'N'  and  ".$this->tablePrimaryKey." = ".$_rets[$j]->$_keyfield;
										mysql_query("SET character_set_results=tis620") or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql2]" . mysql_error());
										$_lang_rets = mysql_query( $_sql2) or die("Invalid query: [{$GLOBALS["__MYSQLDB"]["DB_NAME"]}][$_sql2]" . mysql_error());			
										$_cnt_item=0;	
										while ($_langrow = mysql_fetch_array($_lang_rets)){	 
											$_alllanguage[$_langrow['language']] = $_langrow;
											$_cnt_item++;
										}
										$_rets[$j]->languages = $_alllanguage;
									}
								}
								//echo "[".$GLOBALS["__MYSQLDB"]["DB_NAME"]."]\$_sql[$_sql]<br>";
								//echo "Found size =>".sizeof($_sql_rets)."[{$pageNavigator->currentPageFirstRecordNo}]<br>";
								return $_rets;
						                          
			} 

		function fill2Request($_sql_rets){					 
			
			if ($row = mysql_fetch_array($_sql_rets)){	        
				if(sizeof($this->tableFields)!=0){      
					$_keys = array_keys($this->tableFields);                   
					for($m=0;$m<sizeof($_keys);$m++){			 
										//echo "[".$_keys[$m]."]case ($m)<br>";
										if(substr($_keys[$m], -3)!="_id"  && substr($_keys[$m], -4)!="_psw"){  
										 				if(substr($_keys[$m], -9)=="_datetime"){
															//echo "[".$_keys[$m]."]case 1++(".$row[trim($_keys[$m])].")<br>";
															$_REQUEST[trim($_keys[$m])] = mysql2DateWithTime($row[trim($_keys[$m])]);      
														}else if(substr($_keys[$m], -3)=="_dt"){
															//echo "[".$_keys[$m]."]case 2++ (".mysql2Date($row[trim($_keys[$m])]).")<br>";
															$_REQUEST[trim($_keys[$m])] = mysql2Date($row[trim($_keys[$m])]);
														} else{
															$_REQUEST[trim($_keys[$m])] = ($row[trim($_keys[$m])]);      
														}
										}else  if(substr($_keys[$m], -4)!="_psw"){      
											$_REQUEST[trim($_keys[$m])] = ($row[trim($_keys[$m])]);      
										} 
					}      
				}     
			}     
			
		}     
		
		function getLists($_object,$_connection){     
					$_sql="	SELECT * FROM ".$this->tableName." _table ";	
					$_sql.="	WHERE  _table.sys_del_flag='N' ";	
					$_sql.="	AND  _table.".$this->tablePrimaryKey." > 0 ";
					if(sizeof($_object)>0){
						$_keys = array_keys($_object); 
						for($k=0;$k<sizeof($_keys);$k++){
							$_sql.=" AND  _table.".$_keys[$k]." = '".$_object[$_keys[$k]]."' ";						
						}
					}            
					//echo $_sql."<BR>";
					mysql_query($GLOBALS["__MYSQLDB"]["RESULT_CHARSET"]) or die("Invalid query: [$_sql]" . mysql_error());
					$_sql_rets = mysql_query($_sql);
					return $this->fill2Array($_sql_rets);	
		}
		
		function getListOrders($_keys, $_connection, $character_set = "utf8"){       
							$_sql=" SELECT * FROM ".$this->tableName."  WHERE  sys_del_flag = 'N' ";
							if(sizeof($_keys)>0 && is_array($_keys)){
								$_arrkeys = array_keys($_keys);
								for($i=0;$i<sizeof($_arrkeys);$i++){
									if(!empty($_keys[$_arrkeys[$i]])){
										//$_sql.="  AND ".$_arrkeys[$i]." = '".$_keys[$_arrkeys[$i]]."' ";
										if(substr($_arrkeys[$i], -9)=="_datetime"){
												//echo "[".$_keys[$m]."]case 1++<br>";
												//$_allRet[$_cnt_item][$_fields[$a]] = thai_short_date($row[$_fields[$a]]);
												$_sql.="  AND ".$_arrkeys[$i]." = ".InsertMysqlDate($_keys[$_arrkeys[$i]])." ";
										}else if(substr($_arrkeys[$i], -3)=="_dt"){
												//echo "[".$_keys[$m]."]case 2++<br>";
												//$_allRet[$_cnt_item][$_fields[$a]] = display_MySQL_Date($row[$_fields[$a]]);
												$_sql.="  AND ".$_arrkeys[$i]." = ".InsertMysqlShotDate($_keys[$_arrkeys[$i]])." ";
										}else{						  
											$_sql.="  AND ".$_arrkeys[$i]." = '".$_keys[$_arrkeys[$i]]."' ";
										}
												
									}
								}  
							}
							$_sql .=" AND ".$this->tablePrimaryKey."  > 0 ";					
							
							if(!empty($_order_keys) && is_array($_order_keys) && sizeof($_order_keys)>0){ 
								$_keys = array_keys($_order_keys);
								if(sizeof($_keys)==1)
									$_sql.="  order by ".$_keys[0]." ".$_order_keys[$_keys[0]];
							}else{
								$_sql.="  order by order_no ASC ";
							} 
							//echo "$_sql<BR>";
							mysql_query($GLOBALS["__MYSQLDB"]["RESULT_CHARSET"]) or die("Invalid query: [$_sql]" . mysql_error());
							$_sql_rets = mysql_query($_sql);
							return $this->fill2Array($_sql_rets);	

		}

		function getLists_utf8($_object,$_connection){     
					$_sql="	SELECT * FROM ".$this->tableName." _table ";	
					$_sql.="	WHERE  _table.sys_del_flag='N' ";	
					$_sql.="	AND  _table.".$this->tablePrimaryKey." > 0 ";
					if(sizeof($_object)>0){
						$_keys = array_keys($_object); 
						for($k=0;$k<sizeof($_keys);$k++){
							$_sql.=" AND  _table.".$_keys[$k]." = '".$_object[$_keys[$k]]."' ";						
						}
					}                                 
					//echo $_sql;
					mysql_query("SET character_set_results=utf8") or die("Invalid query: [$_sql]" . mysql_error());
					$_sql_rets = mysql_query($_sql);
					return $this->fill2Array($_sql_rets);	
		}
		

		function UTF8_getLists($_object,$_connection){     
				$_sql="SELECT * FROM ".$this->tableName." _table ";	
				$_sql.="WHERE  _table.sys_del_flag='N' ";	
				$_sql.="	AND  _table.".$this->tablePrimaryKey." > 0 ";
				if(sizeof($_object)>0){
					$_keys = array_keys($_object); 
					for($k=0;$k<sizeof($_keys);$k++){
						$_sql.=" AND  _table.".$_keys[$k]." = '".$_object[$_keys[$k]]."' ";						
					}
				}
				mysql_query("SET character_set_results=utf8") or die("Invalid query: [$_sql]" . mysql_error());
				$_sql_rets = mysql_query($_sql);
				return $this->fill2Array($_sql_rets);	
		}

		function fillToReportObject($_sql_rets){		
			$_fields = array();
			$i = 0;
			while ($i < mysql_num_fields($_sql_rets)) {
					$meta = mysql_fetch_field($_sql_rets, $i);
					$_fields[$i] = trim($meta->name);					
					$i++;
			}
			
			$_cnt_item=0;	
			while ($row = mysql_fetch_array($_sql_rets)){	 
					for($a=0;$a<sizeof($_fields);$a++){
						
						if(substr($_fields[$a], -9)=="_datetime"){
								//echo "[".$_keys[$m]."]case 1++<br>";
								$_allRet[$_cnt_item][$_fields[$a]] = thai_short_date($row[$_fields[$a]]);
						}else if(substr($_fields[$a], -3)=="_dt"){
								//echo "[".$_keys[$m]."]case 2++<br>";
								$_allRet[$_cnt_item][$_fields[$a]] = mysql2Date($row[$_fields[$a]]);
						}else{						  
							$_allRet[$_cnt_item][$_fields[$a]] = trim($row[$_fields[$a]]);
						}
						
					}
					$_cnt_item++;	
			}
			return $_allRet;
		}
		
		function fillToReport1Object($_sql_rets){		
			$_fields = array();
			$i = 0;
			while ($i < mysql_num_fields($_sql_rets)) {
					$meta = mysql_fetch_field($_sql_rets, $i);
					$_fields[$i] = trim($meta->name);					
					$i++;
			}
			
				
			if ($row = mysql_fetch_array($_sql_rets)){	 
					for($a=0;$a<sizeof($_fields);$a++){
						
						if(substr($_fields[$a], -9)=="_datetime"){
								//echo "[".$_keys[$m]."]case 1++<br>";
								$_allRet[$_fields[$a]] = thai_short_date($row[$_fields[$a]]);
						}else if(substr($_fields[$a], -3)=="_dt"){
								//echo "[".$_keys[$m]."]case 2++<br>";
								$_allRet[$_fields[$a]] = mysql2Date($row[$_fields[$a]]);
						}else{						  
							$_allRet[$_fields[$a]] = trim($row[$_fields[$a]]);
						}
						
					}
					$_cnt_item++;	
			}
			return $_allRet;
		}

		function META_fill2array($_sql_rets){					
				$_cnt_item=0;
				while ($row = mysql_fetch_array($_sql_rets)){	  
					$i=0;
					while ($i < mysql_num_fields($_sql_rets)) { 
							
							$meta = mysql_fetch_field($_sql_rets, $i);			
							$_field_name = trim($meta->name);
							if(substr($_field_name, -3)!="_id"  && substr($_field_name, -4)!="_psw"){    
															if(substr($_field_name, -9)=="_datetime"){
																//echo "[".$_field_name."]case 1++<br>";
																$_allRet[$_cnt_item][$_field_name] = thai_short_date($row[trim($_field_name)]);      
															}else if(substr($_field_name, -3)=="_dt"){
																//echo "[".$_field_name."]case 2++<br>";
																$_allRet[$_cnt_item][$_field_name] = mysql2Date($row[trim($_field_name)]);
															}else{     
																$_allRet[$_cnt_item][$_field_name] = $row[trim($_field_name)];      
															} 
							}else  if(substr($_field_name, -4)!="_psw"){       
								$_allRet[$_cnt_item][$_field_name] = ($row[trim($_field_name)]);      
							} 

							$i++;
					}      
					
					$_cnt_item++; 
					
				}   

				return $_allRet;
			}
			function META_fill2Object($_sql_rets){			 
				if ($row = mysql_fetch_array($_sql_rets)){	 
					$i=0;
					while ($i < mysql_num_fields($_sql_rets)) {
							
							$meta = mysql_fetch_field($_sql_rets, $i);			
							$_field_name = trim($meta->name);
							if(substr($_field_name, -3)!="_id"  && substr($_field_name, -4)!="_psw"){   
														if($row[trim($_field_name)]!="" && strlen($row[trim($_field_name)])==19){ 
															if(substr($_field_name, -9)!="_datetime"){
																//echo "[".$_field_name."]case 1++<br>";
																$_allRet[$_field_name] = mysql2Date($row[trim($_field_name)]);      
															}else if(substr($_field_name, -3)!="_dt"){
																//echo "[".$_field_name."]case 2++<br>";
																$_allRet[$_field_name] = thai_short_date($row[trim($_field_name)]);
															}
														}else{     
															$_allRet[$_field_name] = $row[trim($_field_name)];      
														} 
							}else  if(substr($_field_name, -4)!="_psw"){       
								$_allRet[$_field_name] = ($row[trim($_field_name)]);      
							} 

							$i++;
					}       
				}   

				return $_allRet;
			}
		

		// 03/01/2011 ทำเพื่อลด function
		function _sqlget($_sql, $_connection, $character_set = "tis620"){       
				//echo "$_sql<Br>";
				mysql_query("SET character_set_results=$character_set");
				mysql_query("SET character_set_client=$character_set");
				mysql_query("SET character_set_connection=$character_set"); 
				$_sql_rets = mysql_query($_sql);
				return $this->META_fill2Object($_sql_rets);				
		}	
		function _sqllists($_sql, $_connection, $character_set = "tis620"){                        
				//echo "$_sql<Br>";			
				mysql_query("SET character_set_results=$character_set");
				mysql_query("SET character_set_client=$character_set");
				mysql_query("SET character_set_connection=$character_set"); 
				$_sql_rets = mysql_query($_sql);
				return $this->META_fill2array($_sql_rets);	
		}

		  
	}

	$abstractBaseService = new AbstractBaseService();
	
?>
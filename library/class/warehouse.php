<?php

	class warehouse
	{
		public $id;
		public $code;
		public $name;
		public $role;
		public $allowUnderZero;
		public $isDefault;
		public $active;
		public $error;

		public function __construct($id='')
		{
			if( $id != '' )
			{
				$qs = dbQuery("SELECT * FROM tbl_warehouse WHERE id = '".$id."'");
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$this->id		= 	$rs->id;
					$this->code	= $rs->code;
					$this->name	= $rs->name;
					$this->role	= $rs->role;
					$this->allowUnderZero	= $rs->allow_under_zero == 1 ? TRUE : FALSE;
					$this->isDefault	= $rs->is_default == 1 ? TRUE : FALSE;
					$this->active	= $rs->active == 1 ? TRUE : FALSE;
				}
			}
		}




		public function add(array $ds = array())
		{
			$sc = FALSE;
			if(!empty($ds) )
			{
				$fields 	= "";
				$values 	= "";
				$i 				= 1;
				foreach( $ds as $field => $value )
				{
					$fields .= $i == 1 ? $field : ", ".$field;
					$values .= $i == 1 ? "'".$value."'" : ", '".$value."'";
					$i++;
				}

				$sc = dbQuery("INSERT INTO tbl_warehouse (".$fields.") VALUES (".$values.")");

				if( $sc === FALSE)
				{
					$this->error = dbError();
				}
				else
				{
					$sc = dbInsertId();
				}
			}

			return $sc;
		}




		public function update($id, array $ds = array())
		{
			$sc = FALSE;
			if( !empty($ds) )
			{
				$set = "";
				$i   = 1;
				foreach( $ds as $key => $val )
				{
					$set .= $i == 1 ? $key." = '".$val."'" : ", ".$key." = '".$val."'";
					$i++;
				}

				$sc = dbQuery("UPDATE tbl_warehouse SET ".$set." WHERE id = '".$id."'");

				if( $sc === FALSE)
				{
					$this->error = dbError();
				}
			}

			return $sc;
		}





		public function deleteWarehouse($id)
		{
			if( $this->isWarehouseEmpty($id) === TRUE )
			{
				return $this->actionDelete($id);
			}
			else
			{
				return FALSE;
			}
		}




		public function isWarehouseEmpty($id)
		{
			$sc = TRUE;
			$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE id_warehouse = '".$id."'");
			if( dbNumRows($qs) > 0 )
			{
				$sc = FALSE;
			}
			return $sc;
		}





		private function actionDelete($id)
		{
			return dbQuery("DELETE FROM tbl_warehouse WHERE id = '".$id."'");
		}





		public function isExists($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT code FROM tbl_warehouse WHERE id = '".$id."'");
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}
			return $sc;
		}



		public function isExistsWarehouseCode($code, $id = "")
		{
			$sc = FALSE;

			if( $id != "" )
			{
				$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE code = '".$code."' AND id != '".$id."'");
			}
			else
			{
				$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE code = '".$code."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}






		public function isExistsWarehouseName($name, $id="")
		{
			$sc = FALSE;
			if( $id != "" )
			{
				$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE warehouse_name = '".$name."' AND id != '".$id."'");
			}
			else
			{
				$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE warehouse_name = '".$name."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}






		public function getWarehouseDetail($id)
		{
			return dbQuery("SELECT * FROM tbl_warehouse WHERE id = '".$id."'");
		}





		public function isEmptyWarehouse($id)
		{
			$sc = TRUE;
			$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE id = '".$id."'");
			if( dbNumRows($qs) > 0 )
			{
				$sc = FALSE;
			}

			return $sc;
		}







		public function getDatas()
		{
			return dbQuery("SELECT * FROM tbl_warehouse ORDER BY code ASC");
		}






		public function getRoleDatas()
		{
			return dbQuery("SELECT * FROM tbl_warehouse_role");
		}





		public function getCode($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT code FROM tbl_warehouse WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}





		public function getName($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT name FROM tbl_warehouse WHERE id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}




		public function getId($code)
		{
			$sc = '0000';
			$qs = dbQuery("SELECT id FROM tbl_warehouse WHERE code = '".$code."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}




		public function getRoleName($id)
		{
			$sc = '';
			$qs = dbQuery("SELECT name FROM tbl_warehouse_role WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}




		//-------------- คลังนี้สามารถติดลบได้หรือไม่
		public function isAllowUnderZero($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT allow_under_zero FROM tbl_warehouse WHERE allow_under_zero = 1 AND id = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$sc = TRUE;
			}
			return $sc;
		}





		public function isAllowPrepare($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT prepare FROM tbl_warehouse WHERE id = '".$id."' AND prepare = 1 ");
			if( dbNumRows($qs) == 1 )
			{
				$sc = TRUE;
			}
			return $sc;
		}





		public function isAllowSell($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT sell FROM tbl_warehouse WHERE id = '".$id."' AND sell = 1");
			if( dbNumRows($qs) == 1 )
			{
				$sc = TRUE;
			}
			return $sc;
		}




		public function isWarehouseActive($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT active FROM tbl_warehouse WHERE id = '".$id."' AND active = 1");
			if( dbNumRows($qs) == 1 )
			{
				$sc = TRUE;
			}
			return $sc;
		}



		public function searchWarehouse($txt)
		{
			$qr  = "SELECT * FROM tbl_warehouse ";
			if( $txt != '*')
			{
				$qr .= "WHERE code LIKE '%".$txt."%' OR name LIKE '%".$txt."%' ";
			}

			return dbQuery($qr);
		}



		public function autocomplete($txt, $fields = "", $limit = 50)
		{
			if($fields == '')
			{
				if( $txt == '*')
				{
					$sc = dbQuery("SELECT * FROM tbl_warehouse ORDER BY code ASC LIMIT ".$limit);
				}
				else
				{
					$sc = dbQuery("SELECT * FROM tbl_warehouse WHERE name LIKE '%".$txt."%' OR code LIKE '%".$txt."%' ORDER BY code ASC LIMIT ".$limit);
				}
			}
			else
			{
				if( $txt != '*')
				{
					$sc = dbQuery("SELECT ".$fields." FROM tbl_warehouse WHERE name LIKE '%".$txt."%' OR code LIKE '%".$txt."%' ORDER BY code ASC LIMIT ".$limit);
				}
				else
				{
					$sc = dbQuery("SELECT ".$fields." FROM tbl_warehouse ORDER BY code ASC LIMIT ".$limit);
				}

			}

			return $sc;
		}



		public function getDefaultId()
		{
			$qr  = "SELECT id FROM tbl_warehouse WHERE is_default = 1 LIMIT 1";
			$qs = dbQuery($qr);
			if(dbNumRows($qs) == 1)
			{
				$rs = dbFetchObject($qs);
				return $rs->id;
			}

			return FALSE;
		}


	} 	//----- End class


?>

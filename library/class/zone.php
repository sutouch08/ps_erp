<?php

	class zone
	{
		public $id;
		public $id_zone;
		public $barcode;
		public $name;
		public $zone_name;
		public $id_warehouse;
		public $id_customer;
		public $allowUnderZero = FALSE;

		public function __construct($id = '')
		{
			if( $id )
			{
				$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = '".$id."'");
				if( dbNumRows($qs) == 1 )
				{
					$rs = dbFetchObject($qs);
					$this->id		= 	$rs->id_zone;
					$this->id_zone = $rs->id_zone;
					$this->barcode	= $rs->barcode_zone;
					$this->barcode_zone = $rs->barcode_zone;
					$this->name	= $rs->zone_name;
					$this->zone_name = $rs->zone_name;
					$this->id_warehouse = $rs->id_warehouse;
					//$this->id_customer = $rs->id_customer;
					$this->allowUnderZero = $this->isAllowUnderZero($rs->id_zone);
				}
			}
		}



		public function add(array $ds)
		{
			$fields 	= '';
			$values 	= '';
			$n 		= count($ds);
			$i 			= 1;
			foreach( $ds as $key => $val )
			{
				$fields .=	 $key;
				if( $i < $n ){ $fields .= ', '; }
				$values .= "'".$val."'";
				if( $i < $n ){ $values .= ', '; }
				$i++;
			}
			$qs = dbQuery("INSERT INTO tbl_zone (".$fields.") VALUES (".$values.")");
			if( $qs )
			{
				return dbInsertId();
			}
			else
			{
				return FALSE;
			}
		}


		public function update($id, array $ds)
		{
			$set = '';
			$n = count($ds);
			$i = 1;
			foreach( $ds as $key => $val )
			{
				$set .= $key." = '".$val."'";
				if( $i < $n ){ $set .= ", "; }
				$i++;
			}
			return dbQuery("UPDATE tbl_zone SET ".$set." WHERE id_zone = ".$id);
		}


		public function deleteZone($id)
		{
			if( $this->isZoneEmpty($id) === TRUE && $this->isExistsTransection($id) === FALSE )
			{
				return $this->actionDelete($id);
			}
			else
			{
				return FALSE;
			}
		}


		public function isZoneEmpty($id)
		{
			$sc = TRUE;
			$qs = dbQuery("SELECT id_stock FROM tbl_stock WHERE id_zone = ".$id);
			if( dbNumRows($qs) > 0 )
			{
				$sc = FALSE;
			}
			return $sc;
		}


		public function isExistsTransection($id)
		{
			list( $movement ) 		= dbFetchArray( dbQuery("SELECT COUNT(*) FROM tbl_stock_movement WHERE id_zone = ".$id) );

			return $movement == 0 ? FALSE : TRUE;
		}



		private function actionDelete($id)
		{
			return dbQuery("DELETE FROM tbl_zone WHERE id_zone = ".$id);
		}




		public function getWarehouseId($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT id_warehouse FROM tbl_zone WHERE id_zone = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}


		public function getZoneIdWithBranch($barcode, $id_branch)
		{
			$sc = FALSE;
			$qr  = "SELECT id_zone FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE z.barcode_zone = '".$barcode."' ";
			$qr .= "AND w.id_branch = '".$id_branch."' ";

			$qs = dbQuery($qr);
			if(dbNumRows($qs) == 1)
			{
				list($sc) = dbFetchArray($qs);
			}

			return $sc;
		}


		public function getId($barcode)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone ='".$barcode."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}



		public function getName($id)
		{
			$sc = "";
			$qs = dbQuery("SELECT zone_name FROM tbl_zone WHERE id_zone = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				list( $sc ) = dbFetchArray($qs);
			}
			return $sc;
		}





		public function isAllowUnderZero($id_zone)
		{
			$sc  = FALSE;
			$qr  = "SELECT id_zone FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE z.id_zone = ".$id_zone." ";
			$qr .= "AND w.allow_under_zero = 1";

			$qs = dbQuery($qr);

			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}
			
			return $sc;
		}




		public function isExistsZoneCode($code, $id = '')
		{
			$sc = FALSE;
			if( $id != '' )
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."' AND id_zone != ".$id);
			}
			else
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE barcode_zone = '".$code."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}





		public function isExistsZoneName($name, $id = '')
		{
			$sc = FALSE;
			if( $id != '' )
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."' AND id_zone != ".$id);
			}
			else
			{
				$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE zone_name = '".$name."'");
			}
			if( dbNumRows($qs) > 0 )
			{
				$sc = TRUE;
			}

			return $sc;
		}




		public function getZoneDetail($id)
		{
			$sc = FALSE;
			$qs = dbQuery("SELECT * FROM tbl_zone WHERE id_zone = '".$id."'");
			if( dbNumRows($qs) == 1 )
			{
				$sc = dbFetchObject($qs);
			}

			return $sc;
		}



		public function getZoneDetailByBarcode($barcode, $id_warehouse = '')
		{
			$sc = FALSE;



			if( $id_warehouse != '')
			{
				$qr  = "SELECT * FROM tbl_zone AS z ";
				$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
				$qr .= "WHERE barcode_zone = '".$barcode."' AND z.id_warehouse = '".$id_warehouse."' ";
				$qr .= "AND w.active = 1 ";

				$qs = dbQuery($qr);
			}
			else
			{
				$qr  = "SELECT * FROM tbl_zone AS z ";
				$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
				$qr .= "WHERE barcode_zone = '".$barcode."' ";
				$qr .= "AND w.active = 1 ";

				$qs = dbQuery($qr);
			}

			if( dbNumRows($qs) == 1)
			{
				$sc = dbFetchObject($qs);
			}

			return $sc;
		}




		public function searchId($txt)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT id_zone FROM tbl_zone WHERE zone_name LIKE '%".$txt."%'";

			return dbQuery($qr);
		}



		public function search($txt, $role = '')
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE ";
			$qr .= $role == '' ? '' : "w.role IN(".$role.") AND ";
			$qr .= "w.active = 1 AND zone_name LIKE '%".$txt."%' ";
			$qr .= "ORDER BY zone_name ASC";

			return dbQuery($qr);
		}



		//---	 ค้นหาโซนเฉพาะในคลังนี้เท่านั้น
		public function searchWarehouseZone($txt, $id_warehouse = '')
		{

			$qr  = "SELECT * FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			if( $id_warehouse != '')
			{
				$qr .= "WHERE w.id = '".$id_warehouse."' ";
				$qr .= "AND w.active = 1 ";
			}
			else
			{
				$qr .= "WHERE w.active = 1 ";
			}

			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}

			return dbQuery($qr);
		}





		//---	auto complete โซนรับสินค้า
		public function searchReceiveZone($txt, $limit = 50)
		{
			$role = getConfig('RECEIVE_WAREHOUSE');
			$role = $role == '' ? 5 : $role;
			//---	search role 1 คือคลังซื้อขาย, 5 คือ คลังรับสินค้า
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role IN(".$role.") AND w.active = 1 AND zone_name LIKE '%".$txt."%' ";
			$qr .= "LIMIT ".$limit;

			return dbQuery($qr);
		}


		public function searchConsignZoneId($txt)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT id_zone FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 2 AND w.active = 1 AND zone_name LIKE '%".$txt."%' ";

			return dbQuery($qr);
		}


		//---	auto complete โซนในคลังฝากขาย
		public function searchConsignZone($txt, $id_customer)
		{
			//---	role = 2 คือ คลังฝากขาย
			$qr  = "SELECT z.*, zc.id_customer FROM tbl_zone_customer AS zc ";
			$qr .= "JOIN tbl_zone AS z ON zc.id_zone = z.id_zone ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 2 ";
			$qr .= "AND w.active = 1 ";
			$qr .= "AND zc.id_customer = '".$id_customer."' ";

			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%' ";
			}

			return dbQuery($qr);
		}


		//---	auto complete โซนในคลังระหว่าทำ
		public function searchWIPZone($txt)
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 7 ";
			$qr .= "AND w.active = 1 ";
			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}

			return dbQuery($qr);
		}



		//---	auto complete โซนในคลังยืมสินค้า
		public function searchLendZone($txt)
		{
			$qr  = "SELECT z.* FROM tbl_zone AS z ";
			$qr .= "JOIN tbl_warehouse AS w ON z.id_warehouse = w.id ";
			$qr .= "WHERE w.role = 8 ";
			$qr .= "AND w.active = 1 ";
			if( $txt != '*')
			{
				$qr .= "AND z.zone_name LIKE '%".$txt."%'";
			}

			return dbQuery($qr);
		}


		public function countWarehouseZone($id_warehouse)
		{
			$qs = dbQuery("SELECT id_zone FROM tbl_zone WHERE id_warehouse = '".$id_warehouse."'");
			return dbNumRows($qs);
		}




		public function autocomplete($txt, $fields = "", $limit = 50)
		{
			if($fields == '')
			{
				if( $txt == '*')
				{
					$sc = dbQuery("SELECT * FROM tbl_zone LIMIT ".$limit);
				}
				else
				{
					$sc = dbQuery("SELECT * FROM tbl_zone WHERE zone_name LIKE '%".$txt."%' OR barcode_zone LIKE '%".$txt."%' LIMIT ".$limit);
				}
			}
			else
			{
				if( $txt != '*')
				{
					$sc = dbQuery("SELECT ".$fields." FROM tbl_zone WHERE zone_name LIKE '%".$txt."%' OR barcode_zone LIKE '%".$txt."%' LIMIT ".$limit);
				}
				else
				{
					$sc = dbQuery("SELECT ".$fields." FROM tbl_zone LIMIT ".$limit);
				}

			}

			return $sc;
		}



		public function countCustomer($id)
		{
			$qs = dbQuery("SELECT count(id) FROM tbl_zone_customer WHERE id_zone = '".$id."'");
			list($sc) = dbFetchArray($qs);

			return $sc;
		}

	} 	//----- End class


?>

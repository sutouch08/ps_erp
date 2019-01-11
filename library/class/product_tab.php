<?php
//-------------  Product Tab
//--------------- คือ แถบแสดงรายการสินค้า ในหน้าสั่งซื้อ ใช้สำหรับจัดหมวดหมู่ เพื่อให้ง่ายต่อการ สั่งซื้อ
class product_tab
{
	public $id;
	public $name;
	public $id_parent;

	public function __construct($id = "")
	{
		if( $id != "" )
		{
			$qs = dbQuery("SELECT * FROM tbl_product_tab WHERE id = ".$id);
			if( dbNumRows($qs) == 1 )
			{
				$rs = dbFetchObject($qs);
				$this->id				= $rs->id;
				$this->name			= $rs->name;
				$this->id_parent	= $rs->id_parent;
			}
		}
	}


		public function add(array $ds)
	{
		$sc = FALSE;
		if( count($ds) > 0 )
		{
			$fields	= "";
			$values	= "";
			$i			= 1;
			foreach( $ds as $field => $value )
			{
				$fields	.= $i == 1 ? $field : ", ".$field;
				$values	.= $i == 1 ? "'". $value ."'" : ", '". $value ."'";
				$i++;
			}
			$sc = dbQuery("INSERT INTO tbl_product_tab (".$fields.") VALUES (".$values.")");
		}
		return $sc;
	}


	public function update($id, array $ds)
	{
		$sc = FALSE;
		if( count( $ds ) > 0 )
		{
			$set 	= "";
			$i		= 1;
			foreach( $ds as $field => $value )
			{
				$set .= $i == 1 ? $field . " = '" . $value . "'" : ", ".$field . " = '" . $value . "'";
				$i++;
			}
			$sc = dbQuery("UPDATE tbl_product_tab SET " . $set . " WHERE id = '".$id."'");
		}
		return $sc;
	}



	public function updateChild($id, $id_parent)
	{
		return dbQuery("UPDATE tbl_product_tab SET id_parent = ".$id_parent." WHERE id_parent = ".$id);
	}



	public function delete($id)
	{
		return dbQuery("DELETE FROM tbl_product_tab WHERE id = '".$id."'");
	}



	public function updateTabsProduct($id_style, array $ds = array())
	{
		$sc = TRUE;

		if( !empty($ds))
		{
			startTransection();
			if( $this->dropTabsProduct($id_style) === TRUE )
			{
				foreach( $ds as $id )
				{
					if( $this->addTabsProduct($id_style, $id) === FALSE )
					{
						$sc = FALSE;
					}
				}
			}

			if( $sc === TRUE )
			{
				commitTransection();
			}
			else
			{
				dbRollback();
			}

			endTransection();
		}

		return $sc;
	}




	public function addTabsProduct($id_style, $id_tab)
	{
		return dbQuery("INSERT INTO tbl_tab_product (id_style, id_product_tab) VALUES ('".$id_style."', '".$id_tab."')");
	}




	public function dropTabsProduct($id_style)
	{
		return dbQuery("DELETE FROM tbl_tab_product WHERE id_style = '".$id_style."'");
	}



	public function isExists($field, $val, $id='')
	{
		$sc = FALSE;
		if( $id != '' )
		{
			$qs = dbQuery("SELECT id FROM tbl_product_tab WHERE ".$field." = '".$val."' AND id != ".$id);
		}
		else
		{
			$qs = dbQuery("SELECT id FROM tbl_product_tab WHERE ".$field." = '".$val."'");
		}

		if( dbNumRows($qs) > 0 )
		{
			$sc = TRUE;
		}
		return $sc;
	}



	public function getName($id)
	{
		$sc = "TOP LEVEL";
		$qs = dbQuery("SELECT name FROM tbl_product_tab WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}

		return $sc;
	}



	public function getParentId($id)
	{
		$sc = 0;
		$qs = dbQuery("SELECT id_parent FROM tbl_product_tab WHERE id = ".$id);
		if( dbNumRows($qs) == 1 )
		{
			list( $sc ) = dbFetchArray($qs);
		}
		return $sc;
	}


	public function getAllParent($id)
	{
		$sc = array();
		$id_parent = $this->getParentId($id);
		while( $id_parent > 0 )
		{
			$sc[$id_parent] = $id_parent;
			$id_parent = $this->getParentId($id_parent);
		}
		return $sc;
	}



	//-------- เอารายการใน tbl_tab_product มา
	public function getStyleTabsId($id_style)
	{
		$sc = array();
		$qs = dbQuery("SELECT id_product_tab FROM tbl_tab_product WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_product_tab] = $rs->id_product_tab;
			}
		}
		return $sc;
	}




	//-------- เอารายการใน tbl_tab_product มา
	public function getParentTabsId($id_style)
	{
		$sc = array();
		$ds = $this->getStyleTabsId($id_style);
		if( count( $ds ) > 0 )
		{
			foreach( $ds as $id )
			{
				$id_tab = $this->getParentId($id);
				while( $id_tab > 0 )
				{
					$sc[$id_tab] = $id_tab;
					$id_tab = $this->getParentId($id_tab);
				}
			}
			return $sc;
		}

		$qs = dbQuery("SELECT id_product_tab FROM tbl_tab_product WHERE id_style = '".$id_style."'");
		if( dbNumRows($qs) > 0 )
		{
			while( $rs = dbFetchObject($qs) )
			{
				$sc[$rs->id_product_tab] = $rs->id_product_tab;
			}
		}
		return $sc;
	}





	public function getParentList($id = 0)
	{
		//----- Parent cannot be yoursalfe
		return dbQuery("SELECT * FROM tbl_product_tab WHERE id != ".$id);
	}





	//-----------------  Search Result
	public function getSearchResult($txt)
	{
		return dbQuery("SELECT * FROM tbl_product_tab WHERE name LIKE '%".$txt."%'");

	}






	public function countMember($id)
	{
		$qs = dbQuery("SELECT * FROM tbl_tab_product WHERE id_product_tab = ".$id);
		return dbNumRows($qs);
	}





	public function getStyleInTab($id)
	{
		$qr = "SELECT t.id_style FROM tbl_tab_product AS t ";
		$qr .= "JOIN tbl_product_style AS p ON t.id_style = p.id ";
		$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND is_deleted = 0 ";
		$qr .= "AND id_product_tab = ".$id;

		return dbQuery($qr);
	}





	public function getStyleInSaleTab($id)
	{
		$qr = "SELECT t.id_style FROM tbl_tab_product AS t ";
		$qr .= "JOIN tbl_product_style AS p ON t.id_style = p.id ";
		$qr .= "WHERE p.active = 1 AND p.can_sell = 1 AND p.is_deleted = 0 AND p.show_in_sale = 1 ";
		$qr .= "AND id_product_tab = ".$id;

		return dbQuery($qr);
	}


}//--- end class

?>

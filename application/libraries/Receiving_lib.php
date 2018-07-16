<?php
class Receiving_lib
{
	var $CI;

  	function __construct()
	{
		$this->CI =& get_instance();
	}

	function get_cart()
	{
		if($this->CI->session->userdata('cartRecv') === false)
			$this->set_cart(array());

		return $this->CI->session->userdata('cartRecv');
	}

	function set_cart($cart_data)
	{
		$this->CI->session->set_userdata('cartRecv',$cart_data);
	}

	function get_supplier()
	{
		if(!$this->CI->session->userdata('supplier'))
			$this->set_supplier(-1);

		return $this->CI->session->userdata('supplier');
	}

	function set_supplier($supplier_id)
	{
		$this->CI->session->set_userdata('supplier',$supplier_id);
	}

	function get_location()
	{
		if(!$this->CI->session->userdata('location'))
			$this->set_location(-1);

		return $this->CI->session->userdata('location');
	}

	function set_location($location_id)
	{
		$this->CI->session->set_userdata('location',$location_id);
	}

	function get_mode()
	{
		if(!$this->CI->session->userdata('recv_mode'))
			$this->set_mode('receive');

		return $this->CI->session->userdata('recv_mode');
	}

	function get_items_in_cart()
	{
		$items_in_cart = 0;
		foreach($this->get_cart() as $item)
		{
		    $items_in_cart+=$item['quantity'];
		}
		
		return $items_in_cart;
	}
	
	function set_mode($mode)
	{
		$this->CI->session->set_userdata('recv_mode',$mode);
	}

	function add_item($item_id,$quantity=1,$discount=0,$price=null,$description=null,$serialnumber=null)
	{
		//make sure item exists in database.
		if(!$this->CI->Item->exists(is_numeric($item_id) ? (int)$item_id : -1))
		{
			//try to get item id given an item_number
			$item_id = $this->CI->Item->get_item_id($item_id);

			if(!$item_id)
				return false;
		}

		//Get items in the receiving so far.
		$items = $this->get_cart();

        //We need to loop through all items in the cart.
        //If the item is already there, get it's key($updatekey).
        //We also need to get the next key that we are going to use in case we need to add the
        //item to the list. Since items can be deleted, we can't use a count. we use the highest key + 1.

        $maxkey=0;                       //Highest key so far
        $itemalreadyinsale=FALSE;        //We did not find the item yet.
		$insertkey=0;                    //Key to use for new entry.
		$updatekey=0;                    //Key to use to update(quantity)

		foreach ($items as $item)
		{
            //We primed the loop so maxkey is 0 the first time.
            //Also, we have stored the key in the element itself so we can compare.
            //There is an array function to get the associated key for an element, but I like it better
            //like that!

			if($maxkey <= $item['line'])
			{
				$maxkey = $item['line'];
			}

			if($item['item_id']==$item_id)
			{
				$itemalreadyinsale=TRUE;
				$updatekey=$item['line'];
			}
		}

		$insertkey=$maxkey+1;

		$cur_item_info = $this->CI->Item->get_info($item_id);
		$cur_item_location_info = $this->CI->Item_location->get_info($item_id);
		
		$default_cost_price = ($cur_item_location_info && $cur_item_location_info->cost_price) ? $cur_item_location_info->cost_price : $cur_item_info->cost_price;

		//array records are identified by $insertkey and item_id is just another field.
		$item = array(($insertkey)=>
		array(
			'item_id'=>$item_id,
			'line'=>$insertkey,
			'name'=>$this->CI->Item->get_info($item_id)->name,
			'description'=>$description!=null ? $description: $this->CI->Item->get_info($item_id)->description,
			'serialnumber'=>$serialnumber!=null ? $serialnumber: '',
			'allow_alt_description'=>$this->CI->Item->get_info($item_id)->allow_alt_description,
			'is_serialized'=>$this->CI->Item->get_info($item_id)->is_serialized,
			'quantity'=>$quantity,
            'discount'=>$discount,
			'price'=>$price!=null ? $price: $default_cost_price
			)
		);

		//Item already exists
		if($itemalreadyinsale)
		{
			$items[$updatekey]['quantity']+=$quantity;
		}
		else
		{
			//add to existing array
			$items+=$item;
		}

		$this->set_cart($items);
		return true;

	}

	function edit_item($line,$description = FALSE,$serialnumber = FALSE,$quantity = FALSE,$discount = FALSE,$price = FALSE )
	{
		$items = $this->get_cart();
		if(isset($items[$line]))
		{
			if ($description !== FALSE ) {
				$items[$line]['description'] = $description;
			}
			if ($serialnumber !== FALSE ) {
				$items[$line]['serialnumber'] = $serialnumber;
			}
			if ($quantity !== FALSE ) {
				$items[$line]['quantity'] = $quantity;
			}
			if ($discount !== FALSE ) {
				$items[$line]['discount'] = $discount;
			}
			if ($price !== FALSE ) {
				$items[$line]['price'] = $price;
			}
			 
			$this->set_cart($items);
		}

		return false;
	}

	function is_valid_receipt($receipt_receiving_id)
	{
		//RECV #
		$pieces = explode(' ',$receipt_receiving_id);

		if(count($pieces)==2 && $pieces[0] == 'RECV')
		{
			return $this->CI->Receiving->exists($pieces[1]);
		}

		return false;
	}
	
	function is_valid_item_kit($item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$item_kit_id);

		if(count($pieces)==2 && $pieces[0] == 'KIT')
		{
			return $this->CI->Item_kit->exists($pieces[1]);
		}

		return false;
	}

	function return_entire_receiving($receipt_receiving_id)
	{
		//POS #
		$pieces = explode(' ',$receipt_receiving_id);
		$receiving_id = $pieces[1];

		$this->empty_cart();
		$this->delete_supplier();

		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
		{
			$this->add_item($row->item_id,-$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);
	}
	
	function add_item_kit($external_item_kit_id)
	{
		//KIT #
		$pieces = explode(' ',$external_item_kit_id);
		$item_kit_id = $pieces[1];
		
		foreach ($this->CI->Item_kit_items->get_info($item_kit_id) as $item_kit_item)
		{
			$this->add_item($item_kit_item->item_id, $item_kit_item->quantity);
		}
	}

	function copy_entire_receiving($receiving_id)
	{
		$this->empty_cart();
		$this->delete_supplier();

		foreach($this->CI->Receiving->get_receiving_items($receiving_id)->result() as $row)
		{
			$this->add_item($row->item_id,$row->quantity_purchased,$row->discount_percent,$row->item_unit_price,$row->description,$row->serialnumber);
		}
		$this->set_supplier($this->CI->Receiving->get_supplier($receiving_id)->person_id);

	}

	function delete_item($line)
	{
		$items=$this->get_cart();
		unset($items[$line]);
		$this->set_cart($items);
	}

	function empty_cart()
	{
		$this->CI->session->unset_userdata('cartRecv');
	}

	function delete_supplier()
	{
		$this->CI->session->unset_userdata('supplier');
	}

	function delete_location()
	{
		$this->CI->session->unset_userdata('location');
	}

	function clear_mode()
	{
		$this->CI->session->unset_userdata('recv_mode');
	}

	function clear_all()
	{
		$this->clear_mode();
		$this->empty_cart();
		$this->delete_supplier();
		$this->delete_location();
	}

	function get_total()
	{
		$total = 0;
		foreach($this->get_cart() as $item)
		{
            $total+=($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100);
		}
		
		return $total;
	}
}
?>
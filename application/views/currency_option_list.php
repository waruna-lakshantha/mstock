<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$result = $this->currency->read_currency();

foreach ($result as $row)
{
	echo "<option value=\"".$row->curcode."\">".$row->curcode." - ".$row->description."</option>";
}

?>
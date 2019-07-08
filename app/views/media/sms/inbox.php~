<?php
if($outbox->num_rows() >0 )
	echo tables::basic("SMS Outbox",$outbox->result(), array("sender", "phone","status","message","timestamp"));
else
	echo 'No messages in the outbox';
?>

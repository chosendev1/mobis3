<?php
if($outbox->num_rows() >0 )
	echo tables::basic("SMS Inbox",$outbox->result(), array("sender", "phone","status","message","timestamp"));
else
	echo 'No messages in the inbox';
?>

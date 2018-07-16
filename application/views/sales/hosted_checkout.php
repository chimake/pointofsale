<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>www.PhpSoftwares.com</title>
</head>
<body onload="document.getElementById('formCheckout').submit();">

<form id="formCheckout" method="post" action="<?php echo $hosted_checkout_url; ?>">
<input type="hidden" name="PaymentID" value="<?php echo $payment_id; ?>" />
<input type="hidden" name="ReturnMethod" value="GET" />
</form>
</body>
</html>
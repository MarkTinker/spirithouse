<?php

// jCart v1.3
// http://conceptlogic.com/jcart/

// By default, this file returns the $config array for use with PHP scripts
// If requested via Ajax, the array is encoded as JSON and echoed out to the browser

// Don't edit here, edit config.php
include_once('config.php');

// Use default values for any settings that have been left empty
if (!$config['currencyCode']) $config['currencyCode']                     = 'USD';
if (!$config['text']['cartTitle']) $config['text']['cartTitle']           = 'Shopping Cart';
if (!$config['text']['singleItem']) $config['text']['singleItem']         = 'Item';
if (!$config['text']['multipleItems']) $config['text']['multipleItems']   = 'Items';
if (!$config['text']['subtotal']) $config['text']['subtotal']             = 'Subtotal';
if (!$config['text']['update']) $config['text']['update']                 = 'update';
if (!$config['text']['checkout']) $config['text']['checkout']             = 'checkout';
if (!$config['text']['checkoutPaypal']) $config['text']['checkoutPaypal'] = 'Checkout with PayPal';
if (!$config['text']['removeLink']) $config['text']['removeLink']         = 'remove';
if (!$config['text']['emptyButton']) $config['text']['emptyButton']       = 'empty';
if (!$config['text']['emptyMessage']) $config['text']['emptyMessage']     = 'Your cart is empty!';
if (!$config['text']['itemAdded']) $config['text']['itemAdded']           = 'Item added!';
if (!$config['text']['priceError']) $config['text']['priceError']         = 'Invalid price format!';
if (!$config['text']['quantityError']) $config['text']['quantityError']   = 'Item quantities must be whole numbers!';
if (!$config['text']['checkoutError']) $config['text']['checkoutError']   = 'Your order could not be processed!';

if ($_GET['ajax'] == 'true') {
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($config);
}
?>
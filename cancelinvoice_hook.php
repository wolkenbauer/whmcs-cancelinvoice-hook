<?php

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

use WHMCS\Database\Capsule;

add_hook('DailyCronJob',1,function($vars) 
{
//
// Change $overduedays to whatever you need. Default is cancel all invoices after being overdue for 90 days.
//
$overduedays=90;    
$invoices=Capsule::table("tblinvoices")->where("status","Unpaid")->where("duedate","<",date("Y-m-d",strtotime("-$overduedays days")))->get();

foreach($invoices as $invoice)
{
  //use UpdateInvoice API Call for changing the status, otherwise the InvoiceCancelled hook will not trigger 
  //and may affect other modules/apps which rely on this hook.
  $results = localAPI("UpdateInvoice",array("invoiceid"=>$invoice->id,"status"=>"Cancelled"));
  if ($results["result"]=="success") 
   logactivity("[INVOICECANCELHOOK] has cancelled Invoice ID: $invoice->id automatically.");
}
 
}); //hook


//process long pending orders the same way as overdue invoices

add_hook('DailyCronJob',2,function($vars) 
{
    
// As requested by someone, we can use this hook also for cancelling long pending orders (dead orders)
//
// Change $overduedays to whatever you need. Default is cancel all orders after being overdue for 14 days.
//
$overduedays=14;    
$orders=Capsule::table("tblorders")->where("status","Pending")->where("date","<",date("Y-m-d",strtotime("-$overduedays days")))->get();

foreach($orders as $order)
{
  //use CancelOrder API Call for changing the status
  $results = localAPI("CancelOrder",array("id"=>$order->id,"status"=>"Cancelled"));
  if ($results["result"]=="success") 
   logactivity("[ORDERCANCELHOOK] has cancelled Order ID: $order->id automatically.");
}

}); //hook





<?php

if (!defined("WHMCS"))
    die("This file cannot be accessed directly");

use WHMCS\Database\Capsule;

add_hook('AfterCronJob',1,function($vars) 
{
//
// Change $overduedays to whatever you need. Default is cancel all invoices after being overdue for 90 days.
//
$overduedays=14;    
$invoices=Capsule::table("tblinvoices")->where("status","Unpaid")->where("duedate","<",date("Y-m-d",strtotime("-$overduedays days")))->get();

foreach($invoices as $invoice)
{
  //use UpdateInvoice API Call for changing the status, otherwise the InvoiceCancelled hook will not trigger 
  //and may affect other modules/apps which rely on this hook.
  $results = localAPI("UpdateInvoice",array("invoiceid"=>$invoice->id,"status"=>"Cancelled"));
  if ($results["result"]=="success") 
   logactivity("[INVOICECANCELHOOK] has cancelled Invoice ID: $invoice->id automatically." ]);
}

}); //hook


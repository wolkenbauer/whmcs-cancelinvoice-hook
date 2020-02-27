# whmcs-cancelinvoice-hook
Hook for WHMCS which will cancel overdue invoices automatically after x days

This has been requested by the community 7 years ago at requests.whmcs.com and is still being investigated :-)
I bet: this functionality will never come, because of the many different jurisdiction and legal requirements throughout the world.

Original request: https://requests.whmcs.com/topic/cancel-x-days-overdue-invoices

Just copy the hook file into /includes/hooks and adjust the $overduedays var in the hook. 

WHMCS 7.4 and above.

n-joy

Changes:

20200226 bugfix: removed "]" sign in line 22 (logactivity) and changed "AfterCronJob" hook to "DailyCronJob". 

Due to a confirmed Bug in WHMCS Core, the InvoiceCancelled Hook will not fire when changing invoice status by API call.
(internal case #CORE-14322). Until this has been fixed by WHMCS, you need to make sure no other module etc. need to be notified for status changes.

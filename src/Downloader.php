<?php
namespace RJM\RecurlyInvoiceDL;

use Recurly_Client, Recurly_Invoice, Recurly_InvoiceList, Recurly_NotFoundError;
use Dotenv\Dotenv;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv(__DIR__ . '/../');
$dotenv->load();

Recurly_Client::$subdomain = getenv('RECURLY_SUBDOMAIN');
Recurly_Client::$apiKey = getenv('RECURLY_APIKEY');

$invoices = Recurly_InvoiceList::get();
foreach ($invoices as $invoice) {
  print "Invoice: {$invoice->invoice_number}\n";

  try {
	  $pdf = Recurly_Invoice::getInvoicePdf($invoice->invoice_number);

	  file_put_contents(__DIR__ . '/../output/' . $invoice->invoice_number . '.pdf', $pdf);
	} catch (Recurly_NotFoundError $e) {
	  print "Invoice not found: $e";
	}
}
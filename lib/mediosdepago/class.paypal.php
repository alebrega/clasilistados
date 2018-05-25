<?php

require_once ("paypalfunctions.php");

class Paypal {
	private $errores=array();
	private $transaccion_id=null;
	
	public function __construct(){
	}
	public function getErrores(){
		return $this->errores;
	}
	public function validarDatos($data){
		if (empty($data['cardnumber'])){
			$this->errores[]="Introduzca el número de su tarjeta de crédito/débito.";
			return false;	
		}
		if (empty($data['cvmvalue'])){
			$this->errores[]="Introduzca el número de verificación de la tarjeta.";
			return false;
		}
		if ( (empty($data['cardexpmonth']) || ($data['cardexpmonth']=='Mes')) || ( empty($data['cardexpyear']) || ($data['cardexpyear']=='Ano')) ){
			$this->errores[]="La fecha de expiración de su tarjeta de crédito no es correcta.";	
			return false;
		}
		if (empty($data['name'])){
			$this->errores[]="Introduzca el nombre del titular de la tarjeta.";
			return false;
		}
		if (empty($data['address1'])){
			$this->errores[]="Introduzca la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['city'])){
			$this->errores[]="Introduzca la ciudad de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['state'])){
			$this->errores[]="Introduzca el estado de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['zip'])){
			$this->errores[]="Introduzca el Zip/Código postal de la dirección de facturación de su tarjeta.";
			return false;
		}
		if (empty($data['country'])){
			$this->errores[]="Introduzca el país de la dirección de facturación de su tarjeta.";
			return false;
		}
		return true;
	}
	public function process($data){
		//die(var_dump($data));
		//$creditCardType 		= "<<Visa/MasterCard/Amex/Discover>>"; //' Set this to one of the acceptable values (Visa/MasterCard/Amex/Discover) match it to what was selected on your Billing page
		$creditCardType =  $data["creditCardType"];
		//$expDate=> MMYY
		$expDate=$data["cardexpmonth"].$data["cardexpyear"];
		$resArray = DirectPayment( "Sale", $data["chargetotal"], $creditCardType, $data["cardnumber"],
							$expDate, $data["cvmvalue"], $data["name"], $data["apellido"], $data["address1"], $data["city"], $data["state"], $data["zip"], 
							$data["country"], "USD");
		$ack = strtoupper($resArray["ACK"]);
		if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
		{
			/*
			'********************************************************************************************************************
			'
			' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE 
			'                    transactionId & orderTime 
			'  IN THEIR OWN  DATABASE
			' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT 
			'
			'********************************************************************************************************************
			*/
	
			$transactionId		= $resArray["TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
			$transactionType 	= $resArray["TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
			$paymentType		= $resArray["PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
			$orderTime 			= $resArray["ORDERTIME"];  //' Time/date stamp of payment
			$amt				= $resArray["AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
			$currencyCode		= $resArray["CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
			$feeAmt				= $resArray["FEEAMT"];  //' PayPal fee amount charged for the transaction
			$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
			$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
			$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer�s account.
			
			/*
			' Status of the payment: 
					'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
					'Pending: The payment is pending. See the PendingReason element for more information. 
			*/
			
			$paymentStatus	= $resArray["PAYMENTSTATUS"]; 
	
			/*
			'The reason the payment is pending:
			'  none: No pending reason 
			'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 
			'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 
			'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		
			'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 
			'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 
			'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 
			*/
			
			$pendingReason	= $resArray["PENDINGREASON"];  
	
			/*
			'The reason for a reversal if TransactionType is reversal:
			'  none: No reason code 
			'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 
			'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 
			'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 
			'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 
			'  other: A reversal has occurred on this transaction due to a reason not listed above. 
			*/
			
			$reasonCode		= $resArray["REASONCODE"];   
			$this->transaccion_id=$transactionId;
			return true;
		}
		else  
		{
			//Display a user friendly Error on the page using any of the following error information returned by PayPal
			$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
			$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
			$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
			$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
			
			echo "GetExpressCheckoutDetails API call failed. ";
			echo "Detailed Error Message: " . $ErrorLongMsg;
			echo "Short Error Message: " . $ErrorShortMsg;
			echo "Error Code: " . $ErrorCode;
			echo "Error Severity Code: " . $ErrorSeverityCode;
			return $ErrorShortMsg;
		}
	}
	public function getTransaccionId(){
		return $this->transaccion_id;
	}
}
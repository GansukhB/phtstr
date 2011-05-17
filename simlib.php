<?

// DISCLAIMER:
//     This code is distributed in the hope that it will be useful, but without any warranty; 
//     without even the implied warranty of merchantability or fitness for a particular purpose.

// Main Interfaces:
//
// function InsertFP ($loginid, $x_tran_key, $amount, $sequence) - Insert HTML form elements required for SIM
// function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp) - Returns Fingerprint.

function hmac ($key, $data)

{
   // RFC 2104 HMAC implementation for php.
   // Creates an md5 HMAC.
   // Eliminates the need to install mhash to compute a HMAC
   // Hacked by Lance Rushing
   $b = 64; // byte length for md5
   if (strlen($key) > $b) {
       $key = pack("H*",md5($key));
   }
   $key  = str_pad($key, $b, chr(0x00));
   $ipad = str_pad('', $b, chr(0x36));
   $opad = str_pad('', $b, chr(0x5c));
   $k_ipad = $key ^ $ipad ;
   $k_opad = $key ^ $opad;
   return md5($k_opad  . pack("H*",md5($k_ipad . $data)));
}
// compute HMAC-MD5
// Uses PHP mhash extension. Pl sure to enable the extension
//function hmac ($key, $data)
//{
//return (bin2hex (mhash(MHASH_MD5, $data, $key)));
//}

// Calculate and return fingerprint
// Use when you need control on the HTML output
function CalculateFP ($loginid, $x_tran_key, $amount, $sequence, $tstamp, $currency = "")
{
return (hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency));
}


// Inserts the hidden variables in the HTML FORM required for SIM
// Invokes hmac function to calculate fingerprint.

function InsertFP ($loginid, $x_tran_key, $amount, $sequence, $currency = "")
{

$tstamp = time ();

$fingerprint = hmac ($x_tran_key, $loginid . "^" . $sequence . "^" . $tstamp . "^" . $amount . "^" . $currency);

echo ('<input type="hidden" name="x_fp_sequence" value="' . $sequence . '">' );
echo ('<input type="hidden" name="x_fp_timestamp" value="' . $tstamp . '">' );
echo ('<input type="hidden" name="x_fp_hash" value="' . $fingerprint . '">' );


return (0);

}

?>


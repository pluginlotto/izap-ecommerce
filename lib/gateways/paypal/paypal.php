<?php
/**************************************************
* iZAP Web Solutions                              *
* Copyrights (c) 2005-2009. iZAP Web Solutions.   *
* All rights reserved                             *
***************************************************
* @author iZAP Team "<support@izap.in>"
* @link http://www.izap.in/
* Under this agreement, No one has rights to sell this script further.
* For more information. Contact "Tarun Kumar<tarun@izap.in>"
 */
class paypal {

  var $last_error;                 // holds the last error encountered

  var $ipn_log;                    // bool: log IPN results to text file?
  var $ipn_log_file;               // filename of the IPN log
  var $ipn_response;               // holds the IPN response from paypal
  var $ipn_data = array();         // array contains the POST values for IPN

  var $fields = array();           // array holds the fields to submit to paypal
  var $debug = false;

  function paypal($debug=false) {
    global $CONFIG;
    // initialization constructor.  Called when class is created.

    if($debug) {
      $this->paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
      $this->debug = true;
    }
    else {
      $this->paypal_url = 'https://www.paypal.com/cgi-bin/webscr';
    }

    $this->last_error = '';

    $this->ipn_log_file = $CONFIG->pluginspath . 'izap-ecommerce/ipn_log.txt';
    $this->ipn_log = true;
    $this->ipn_response = '';
    $this->ipn_posted_vars = '';

    // populate $fields array with a few default values.  See the paypal
    // documentation for a list of fields and their data types. These default
    // values can be overwritten by the calling script.

    $this->add_field('rm','2');           // Return method = POST
    $this->add_field('cmd','_ext-enter');
    $this->add_field('redirect_cmd','_cart');
    $this->add_field('upload', 1);

  }

  function add_field($field, $value) {
    $this->fields["$field"] = $value;
  }

  function submit_paypal_post() {


    echo "<html>\n";
    echo "<head><title>Processing Payment...</title>";
    echo "</head>\n";
    echo "<body onLoad=\"document.form.submit();\">\n";
    echo "<p align=\"center\"><img src=\"http://cdn.iconfinder.net/data/icons/creditcarddebitcard/64/paypal-curved.png\"><br />
      <h3 align='center'>Processing... Please don't refresh or press back button.</h3>
      </p>";
    echo "<form method=\"post\" name=\"form\" action=\"".$this->paypal_url."\">\n";
    if($debug)
      $this->add_field("demo","Y");
    foreach ($this->fields as $name => $value) {
      echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
    }

    echo "</form>\n";
    echo "</body></html>\n";

  }

  function validate_ipn() {

    // parse the paypal URL
    $url_parsed=parse_url($this->paypal_url);
    $post_string = '';
    foreach ($_POST as $field=>$value) {
      $this->ipn_data["$field"] = $value;
      $post_string .= $field.'='.urlencode($value).'&';
    }
    $post_string.="cmd=_notify-validate"; // append ipn command

    // open the connection to paypal
    $fp = fsockopen($url_parsed[host],"80",$err_num,$err_str,30);
    if(!$fp) {

      // could not open the connection.  If loggin is on, the error message
      // will be in the log.
      $this->last_error = "fsockopen error no. $errnum: $errstr";
      $this->log_ipn_results(false);
      return false;

    } else {

      // Post the data back to paypal
      fputs($fp, "POST $url_parsed[path] HTTP/1.1\n");
      fputs($fp, "Host: $url_parsed[host]\n");
      fputs($fp, "Content-type: application/x-www-form-urlencoded\n");
      fputs($fp, "Content-length: ".strlen($post_string)."\n");
      fputs($fp, "Connection: close\n\n");
      fputs($fp, $post_string . "\n\n");

      // loop through the response from the server and append to variable
      while(!feof($fp)) {
        $this->ipn_response .= fgets($fp, 1024);
      }

      fclose($fp); // close connection
    }

    if (eregi("VERIFIED",$this->ipn_response)) {

      // Valid IPN transaction.
      $this->log_ipn_results(true);
      return true;

    } else {

      // Invalid IPN transaction.  Check the log for details.
      $this->last_error = 'IPN Validation Failed.';
      $this->log_ipn_results(false);
      return false;

    }

  }

  function log_ipn_results($success) {

    if (!$this->ipn_log) return;  // is logging turned off?

    // Timestamp
    $text = '['.date('m/d/Y g:i A').'] - ';

    // Success or failure being logged?
    if ($success) $text .= "SUCCESS!\n";
    else $text .= 'FAIL: '.$this->last_error."\n";

    // Log the POST variables
    $text .= "IPN POST Vars from Paypal:\n";


    foreach ($this->ipn_data as $key=>$value) {
      $text .= "$key=$value, ";
      $this->ipn_posted_vars .= $key.'='.$value."<br>";
    }



    // Log the response from the paypal server
    $text .= "\nIPN Response from Paypal Server:\n ".$this->ipn_response;

    // Write to log
    $fp=fopen($this->ipn_log_file,'a');
    fwrite($fp, $text . "<hr \>\n\n");

    fclose($fp);  // close file
  }

  function dump_fields() {

    // Used for debugging, this function will output all the field/value pairs
    // that are currently defined in the instance of the class using the
    // add_field() function.

    echo "<h3>paypal_class->dump_fields() Output:</h3>";
    echo "<table width=\"95%\" border=\"1\" cellpadding=\"2\" cellspacing=\"0\">
            <tr>
               <td bgcolor=\"black\"><b><font color=\"white\">Field Name</font></b></td>
               <td bgcolor=\"black\"><b><font color=\"white\">Value</font></b></td>
            </tr>";

    ksort($this->fields);
    foreach ($this->fields as $key => $value) {
      echo "<tr><td>$key</td><td>".urldecode($value)."&nbsp;</td></tr>";
    }

    echo "</table><br>";
  }
}
?>
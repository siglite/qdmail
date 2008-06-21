<?php
/**
 * QdSimpleMail ver 0.1.0a
 * E-Mail for multibyte charset
 *
 * PHP versions 4 and 5 (PHP4.3 upper)
 *
 * Copyright 2008, Spok in japan , tokyo
 * hal456.net/simple_qdmail    :  http://hal456.net/simple_qdmail/
 * & CPA-LAB/Technical  :  http://www.cpa-lab.com/tech/
 * Licensed under The MIT License License
 *
 * @copyright		Copyright 2008, Spok.
 * @link			http://hal456.net/simple_qdmail/
 * @version			0.1.0a
 * @lastmodified	2008-06-21
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * Qdmail is sending e-mail library for multibyte language ,
 * easy , quickly , usefull , and you can specify deeply the details.
 * Copyright (C) 2008   spok 
*/
if( !function_exists( 'qd_simple_mail' ) ){

	function qd_simple_mail( $toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null  , $additional = array() , $option = array() ,$mta_option = null){

		$mail = & new QdSimpleMail();
		$mail -> to($toaddr , $toname);
		$mail -> from($fromaddr , $fromname);
		foreach($additional as $header_name => $value){
			if(is_array($value)){
				$addr = array_shift($value);
				$name = array_shift($value);
			}else{
				$addr = $value;
				$name = null;
			}
			$mail->additional($header_name,$addr,$name);
		}

		$option = array_change_key_case($option, CASE_LOWER);
		foreach($option as $func_name => $value){
			$mail->{$func_name}($value);
		}
		$mail ->subject($subject);
		$mail ->content($content);
		return $mail->send($mta_option);
	}
}

class QdSimpleMail{
	var	$LFC				= "\r\n";// Notice: CRLF ,If you failed, change "\n"
	var $LFC_Qmail			= "\n";
	//----------
	// sysytem 
	//----------
	var	$version		= '0.1.0a';
	var	$xmailer		= 'QdSimplemail';
	var $license 		= 'The_MIT_License';
	//--------------------
	// charset , encoding
	//--------------------
	var $charset_header				= 'iso-2022-jp';
	var $charset_content			= 'iso-2022-jp';
	var $content_transfer_enc_text	= '7bit';
	var $org_charset		 		= null;
	// debug
	var $debug = 0;

	function QdSimpleMail(){
		$this->LFC = $this->isQmail() ? $this->LFC_Qmail:$this->LFC;
	}
	function isQmail(){
		$ret = ini_get ( 'sendmail_path' );
		return false!==strpos($ret,'qmail');
	}
	function clean( $content ){
		$LFC = "\n";
		return rtrim( preg_replace( '/\r?\n/' , $LFC , $content ));
	}
	function charset_header($param){
		$this->charset_header=$param;
	}
	function charset_content($param){
		$this->charset_content=$param;
	}
	function charset_org($param){
		$this->org_charset=$param;
	}
	function enc_content($param){
		$this->content_transfer_enc_text=$param;
	}
	function lf(){
		$this->LFC="\n";
	}
	function crlf(){
		$this->LFC="\r\n";
	}
	function debug($param){
		$this->debug=$param;
	}
	function mime_string( $subject , $charset ) {
		if( empty($subject) || ( 0 === preg_match( '/[^\w\s0-9\.]/' , $subject ) ) ){
			return trim(chunk_split($subject, 75, "\r\n "));
		}
		$enc = isset($this->org_charset) ? $this->org_charset:mb_detect_encoding($subject);
		$subject = mb_convert_encoding( $subject , $charset , $enc );
		$start = "=?" . $charset . "?B?";
		$end = "?=";
		$spacer = $end . $this->LFC . ' ' . $start;

		$length = 75 - strlen($start) - strlen($end);

		$pointer = 1;
		$cut_start = 0;
		$line = null;
		$_ret = array();
		$max = mb_strlen( $subject ,$charset );
		while( $pointer <= $max ){
			$line  = mb_substr( $subject , $cut_start , $pointer-$cut_start , $charset );
			$bs64len = strlen(bin2hex(base64_encode($line)))/2;
			if( $bs64len <= $length ){
				$pointer ++;
			}else{
				$_ret[] = base64_encode($line) ;
				$cut_start = $pointer;
			}
		}
		$_ret[] = base64_encode( $line );
		$ret = $start . implode( $spacer , $_ret ) . $end;
		return $ret ;
	}

	function addr($addr,$name=null){
		if(is_null($name)){
			return  $addr;
		}else{
			return $this->mime_string($name,$this->charset_header).' <'.$addr.'>';
		}
	}
	function content($content){
		$this->content = $content;
	}
	function subject($subject){
		if( 1 === preg_match( '/[^a-zA-Z0-9]/is' , $subject ) ){
			$subject = $this->mime_string($subject,$this->charset_header);
		}
		$this->subject = $subject;
	}
	function to($addr,$name=null){
		$this->to = $this->addr($addr,$name);
	}
	function from($addr,$name=null){
		$this->additional('From',$addr,$name);
	}
	function additional($header_name,$addr,$name=null){
		$this->additional[$header_name] = $this->addr($addr,$name);
	}
	function makeContent(){
		$enc = isset($this->org_charset) ? $this->org_charset:mb_detect_encoding($this->content);
		if(0!==strcasecmp ( $enc , $this->charset_content )){
			$this->content = mb_convert_encoding($this->content,$this->charset_content,$enc);
		}
		if('BASE64'===strtoupper($this->content_transfer_enc_text)){
			$this->content = chunk_split( base64_encode( $this->content ) );
		}
		$this->content = $this->clean($this->content);
	}

	function makeHeader(){
		$this->header = null;
		$header = array();
		foreach($this->additional as $name => $value){
			$header[]=$name.': '.$value;
		}
		$this->header = implode($this->LFC,$header);
	}

	function send($mta_option=null){
		if(!isset($this->additional['Content-Transfer-Encoding'])){
			$this->additional['Content-Transfer-Encoding']=$this->content_transfer_enc_text;
		}
		if(!isset($this->additional['Content-Type'])){
			$this->additional['Content-Type']='text/plain; charset='.$this->charset_content;
		}
		if(!isset($this->additional['MIME-Version'])){
			$this->additional['MIME-Version']='1.0';
		}
		$this->additional['X-mailer']=$this->xmailer.' '.$this->version;
		$this->makeContent();
		$this->makeHeader();

		if( 0 < $this->debug){
			$out = 'To: '.$this->to . $this->LFC
				. 'Subject: '.$this->subject . $this->LFC
				. $this->header . $this->LFC . $this->LFC
				. $this->content . $this->LFC
				. $mta_option;
			$out = mb_convert_encoding($out,'utf-8',mb_detect_encoding($out));
			$out = htmlspecialchars($out);
			echo "<pre>"
				. $out
				. "</pre>";
		}
		if( 2 > $this->debug){
			return mail( $this->to , $this->subject , $this->content , $this->header , $mta_option);
		}
	}
}

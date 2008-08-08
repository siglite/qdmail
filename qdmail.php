<?php
/**
 * Qdmail ver 1.0.2b
 * E-Mail for multibyte charset
 *
 * PHP versions 4 and 5 (PHP4.3 upper)
 *
 * Copyright 2008, Spok in japan , tokyo
 * hal456.net/qdmail    :  http://hal456.net/qdmail/
 * & CPA-LAB/Technical  :  http://www.cpa-lab.com/tech/
 * Licensed under The MIT License License
 *
 * @copyright		Copyright 2008, Spok.
 * @link			http://hal456.net/qdmail/
 * @version			1.0.2b
 * @lastmodified	2008-08-08
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 * 
 * Qdmail is sending e-mail library for multibyte language ,
 * easy , quickly , usefull , and you can specify deeply the details.
 * Copyright (C) 2008   spok 
*/
//-------------------------------------------
// For CakePHP , extended 'Object' Class ,
// if including in CakePHP Component .
// At normal PHP Script or another Framework ,
// 'QdmailBranch' means Dummy Base Class .
//-------------------------------------------
if ( defined('CAKE_CORE_INCLUDE_PATH') || defined('CAKE')) {
	class QdmailBranch extends Object{
	}
}else{
	class QdmailBranch{
	}
}
if( !function_exists( 'qd_send_mail' ) ){

	function qd_send_mail( $type , $to = null, $subject = null , $content = null , $other_header = array() , $attach = null, $debug = 0 ){
		$option = array();
		if( is_array( $type ) ){
			$type = array_change_key_case( $type , CASE_UPPER ) ;
			$option = (isset($type['OPTION']) && is_array($type['OPTION'])) ? $type['OPTION'] : array();
			if(isset($type['SMTP'])){
				$option = array_merge($option,array('SMTP'=>true,'smtpServer'=>$type['SMTP']));
			}
			$type = isset($type['TYPE']) ? $type['TYPE']:'text';
		}

		$_type=array('Text'=>true,'Html'=>true,'Deco'=>true );
		$type = ucfirst(strtolower($type));
		$easy_method = isset($_type[$type]) ? 'easy'.$type:false;
		$mail = & Qdmail::getInstance();
		$mail->debug = $debug;

		return $mail->{$easy_method}( $to , $subject , $content , $other_header , $attach , $option );

	}
}

class QdmailBase extends QdmailBranch{

	//----------------------------
	// Line Feed Character & kana
	//----------------------------
	var	$LFC				= "\r\n";// Notice: CRLF ,If you failed, change "\n"
	var $LFC_Qmail			= "\n";
	var $language			= 'ja';
	var $kana				=  false; // kana header
	//----------
	// sysytem 
	//----------
	var $kana_content_relation =  false;
	var	$name			= 'Qdmail';
	var	$version		= '1.0.2b';
	var	$xmailer		= 'PHP-Qdmail';
	var $license 		= 'The_MIT_License';
	//--------------------
	// charset , encoding
	//--------------------
	var $charset_header				= 'iso-2022-jp';
	var $charset_content			= 'iso-2022-jp';
	var $charset_attach_filename	= 'iso-2022-jp';
	var $content_transfer_enc_text	= '7bit';
	var $content_transfer_enc_html	= '7bit';
	var $detect_order				= false;
//	var $detect_order				= "iso-2022-jp,eucJP-win,UTF-8,SJIS-win,jis,ASCII";
	var $qdmail_system_charset 		= 'utf-8';
	var	$force_change_charset		= false;
	var $corres_charset				= array(
			'HEADER'=>'charset_header',
			'BODY'=>'charset_content',
			'ATTACH'=>'charset_attach_filename',
			'TEXT'=>'content_transfer_enc_text',
			'HTML'=>'content_transfer_enc_html',
			'DETECT'=>'detect_order',
			'SYSTEM'=>'qdmail_system_charset',
		) ;
	//--------------------------
	// for address 
	//--------------------------
	var	$addr_many = array(
		'TO'	=> true,
		'CC'	=> true,
		'BCC'	=> true,
		'FROM'	=> false,
		'REPLYTO'=> false
	);
	var	$addr_head_name = array(
		'TO'	=> 'To',
		'CC'	=> 'Cc',
		'BCC'	=> 'Bcc',
		'FROM'	=> 'From',
		'REPLYTO'=>'Reply-To'
	);
	var $header_must =array( 'TO' , 'FROM' , 'SUBJECT' );
	var $body_empty_allow = false;
	var $tokey		= array(
		'_ADDR' => 'mail',
		'_NAME' => 'name',
	);
	//--------------
	// content_id
	//--------------
	var	$content_id_include_attach_path = false ;
	var	$content_id_only_filename = true ;
	//---------------
	// Once mode
	//---------------
	var $body_structure			= array();
	var $body_build_once		= false;
	var $body_already_build		= false;
	var $attach_build_once		= true;
	var $attach_already_build	= false;
	//------------------------------
	// simple replace
	//------------------------------
	var $simple_replace	= false;
	var $replace		= array();
	var $replace_with_to_priority= true;
	var $replace_def	= array();
	// simple replace command prefix
	var	$rep_prefix		= null;
	//---------
	// wordwrap
	//---------
	var	$wordwrap_allow	= false;
	var	$wrap_prohibit_allow	= false;
	var $wordwrap_length= 45 ;
	// inteligent wordwrap
	// false is that the word exist in the line ,
	// true is that the word must be the beginning of a line 
	var	$wrap_except	= array(
		'http://'=>false,
		'code'=>true,
	);
	var $wrap_prohibit_top=',.;:–?!‼、。．)）]}｝〕〉》」』】〙〗〟’”»ヽヾーァィゥェォッャュョヮヵヶぁぃぅぇぉっゃゅょゎ‐〜？！';
	var $wrap_prohibit_end='（([{｛〔〈《「『【〘〖‘“« ';
	var $wrap_prohibit = array();
	// multibyte wordwrap , by wordcount or by wordwidth
	var	$wrap_width	= true;
	// multibyte wordwidth compare by ascii
	var	$mb_strwidth_magni = 2;
	//------------------
	// To Separate mode
	//------------------
	var	$to_separate	= false ;
	//----------------------------
	// html mail
	//----------------------------
	var $is_html		= null ;
	var	$auto_both		= true ; // text & html
	var	$inline_mode	= false;
	var	$deco_kind		= null ;
	var	$auto_deco_judge= false;
	var $no_inline_attach_structure = 0;
	var $deco_def_default = 0;
	var	$deco_def		=array(
		array(
			'OPTION_NAME'	=> array( 'MHTML' , 'INLINE' , 'PC' ),
			'STRUCTURE'		=> 1,
			'_CHARSET'		=> 'iso-2022-jp' ,
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> false,
			'DISPOSITION'	=> true,
		),
		array(
			'OPTION_NAME'	=> array( 'DC' , 'DOCOMO' ),
			'STRUCTURE'		=> 2,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> array('this','stripCrlf'),
			'DISPOSITION'	=> false,
		),
		array(
			'OPTION_NAME'	=> array( 'AU' ,'EZ', 'EZWEB'),
			'STRUCTURE'		=> 3,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> array('this','stripCrlf'),
			'DISPOSITION'	=> true,
		),
		array(
			'OPTION_NAME'	=> array( 'SB' , 'SOFTBANK' ),
			'STRUCTURE'		=> 4,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> array('this','stripCrlf') ,
			'DISPOSITION'	=> true ,
		),
		array(
			'OPTION_NAME'	=> array( 'EM','EMOBILE' ,'EMNET'),
			'STRUCTURE'		=> 2,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> array('this','stripCrlf') ,
			'DISPOSITION'	=> true ,
		),
		array(
			'OPTION_NAME'	=> array( 'WL','WILLCOM' ,'POCKET'),
			'STRUCTURE'		=> 2,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> 'QUOTED-PRINTABLE',
			'HTML_EXTERNAL'	=> array('this','stripCrlf') ,
			'DISPOSITION'	=> true ,
		),
	);

	var		$structure		=array(
		// no inline attachment
		0 => array(
			'multipart/mixed' => array(
				'multipart/alternative'=>array(
					'html'		=>	1,
					'plain'		=>	1,
					'OMIT'		=>	true,
				),
				'image'		=> 'BOTH', // Available Inline
				'OMIT'		=>	true,
			),
			'OMIT'		=>	true,
		),
		// PC inline HTML
		1 => array(
			'multipart/mixed' => array(
				'multipart/alternative'=>array(
					'multipart/related'	=>	array(
						'html'		=>	1,
						'image'		=>	'INLINE',
						'OMIT'		=>	true,
					),
					'plain'		=>	1,
					'OMIT'		=>	true,
				),
				'image'		=> 'NOT_INLINE', // not inline
				'OMIT'	=> true,
			),
			'OMIT'	=> true,
		),
		2 => array(
			'multipart/mixed' => array(
				'multipart/related'=>array(
					'multipart/alternative'	=>	array(
						'plain'		=>	1,
						'html'		=>	1,
						'OMIT'		=>	false,
					),
					'image'		=>	'INLINE',
					'OMIT'		=>	false,
				),
				'OMIT'		=>	false,
				'image'		=> 'NOT_INLINE',
			),
			'OMIT'	=> false,
		),
		3 => array(
			'multipart/mixed' => array(
				'multipart/alternative'	=>	array(
					'plain'		=>	1,
					'html'		=>	1,
					'OMIT'		=>	false,
				),
				'image'		=>	'BOTH',
				'OMIT'		=>	false,
			),
			'OMIT'	=> false,
		),
		4 => array(
			'multipart/related'=>array(
				'multipart/alternative'	=>	array(
					'plain'		=>	1,
					'html'		=>	1,
					'OMIT'		=>	false,
				),
				'image'		=>	'INLINE',
				'OMIT'		=>	false,
			),
			'image'		=> 'NOT_INLINE',
			'OMIT'		=>	false,
			),

	);
	var	$deco_judge		= array(
		'docomo.ne.jp'=>'DC',
		'softbank.ne.jp'=>'SB',
		'i.softbank.ne.jp'=>'SB',
		'disney.ne.jp'=>'SB',
		'vodafone.ne.jp'=>'SB',
		'ezweb.ne.jp'=>'AU',
		'emnet.ne.jp'=>'EM',
		'pdx.ne.jp'=>'WL',
		'gmail.com'=>'DC',
	);
	//------------------
	// using address and content
	//------------------
	var	$to				= array()	;
	var	$from			= array()	;
	var	$cc				= array()	;
	var	$bcc			= array()	;
	var	$done			= array()	;
	var	$undone			= array()	;
	var	$replyto		= array()	;
	var	$receipt		= array()	;
	var	$allways_bcc	= null ;
	var	$header			= array()	;
	var	$other_header	= array()	;
	var $header_content_type = array();
	var $content		= array(
		'TEXT'=>array(
			'CONTENT'		=> null,
			'LENGTH'		=> null,
			'_CHARSET'		=> null,
			'ENC'			=> null,
			'_ORG_CHARSET'	=> null,
		),
		'HTML'=>array(
			'CONTENT'		=> null,
			'LENGTH'		=> null,
			'_CHARSET'		=> null,
			'ENC'			=> null,
			'_ORG_CHARSET'	=> null,
		),
	);
	var	$header_for_mailfunction_to;
	var	$header_for_mailfunction_subject;
	var	$header_for_mailfunction_other;
	var	$content_for_mailfunction;
	var	$header_for_smtp;
	//--------------
	// attachament
	//--------------
	var	$attach			= array();
	var	$attach_path	= null;
	var	$auto_ext		= true ; // mimetypes
	//------------------------
	// SMTP
	//-------------------------
	var $smtp				= false ;
	var $smtp_object		= null;
	var $smtp_loglevel_link	= false;
	var $smtp_server = array(
	'host'		=> null ,
	'port'		=> 25 ,
	'from'		=> null,
	'user'		=> null,
	'pass' 		=> null,
	'protocol'	=> null,
	'pop_host'	=> null,
	'pop_user'	=> null,
	'pop_pass'	=> null,
	);
	//------------------------
	// etc
	//------------------------
	var $keep_parameter		= array(false);
	var	$mta_option			= null ;
	var	$is_create			= false;
	var	$validate_addr  	= array('this','validateAddr');
	var	$boundary_base_degit= 2 ;
	var	$stack_construct	= null ;
	var $start_time			= null;
	var $framework			= null;
	//-------------------------------
	// logs
	// 0 is nolog,
	// 1 is simple(Message 'Success' & recipt e-mail@address ),
	// 2 is including header data,
	// 3 is including fulldata,
	//------------------------------
	var	$log_level		= 0 ;
	var	$log_level_max  = 3 ;
	var	$log_path  		= './';
	var	$log_filename	= 'qdmail.log';
	var	$log_append		= 'a' ;
	var	$log_dateformat	= 'Y-m-d H:i:s';
	var	$log_LFC	= "\n";
	// -------------------------------
	// error & error logs
	// 0 is nolog,
	// 1 is simple,
	// 2 is including header data,
	// 3 is inc fulldata
	//--------------------------------
	var $error			= array();
	var $error_stack	= array();
	var $error_display	= true;
	var	$errorlog_level	= 0 ; 
	var	$errorlog_level_max = 3 ;
	var	$errorlog_path  = './';
	var	$errorlog_filename= 'qbmail_error.log';
	var	$errorlog_append= 'a' ;
	var	$ignore_error	= false ;
	//----------------
	// debug 
	// 0 is no debug mode & really sending ,
	// 1 is showing header&body & really sending ,
	// 2 is no sending & showing header&body and some vars
	//----------------
	var	$debug			= 0 ;
	var	$debug_report	= false;
	var	$debug_report_path = './';
	var	$debug_echo_charset= true;

//****************************************************
//  Methods
//****************************************************
	//--------------------------------
	// constructor   set error display 
	// $charset_def = null,
	// $error_display = true
	// $mail -> (&) new Qdmail( Charset , Encoding , DetectOrder , error_display );
	//--------------------------------

	function __construct( $param = null ){
		$this->stack_construct = $param ;
		if( !empty( $param[0] ) && !empty( $param[1] ) ){
			$this->charset( $param[0] , $param[1] );
		}elseif( !empty( $param[0] ) ){
			$this->charset( $param[0] );
		}
		if( !empty( $param[2] ) ){
			$this->detect_order = $param[1];
		}
		if( false !== $this->detect_order ){
			mb_detect_order( $this->detect_order );
		}
		if( !empty( $param[3] ) ){
			$this->error_display = $param[2];
		}
		if($this->is_qmail()){
			$this->LFC = $this->LFC_Qmail;
		}
		$this->optionNameLink();
		$this->wordwrapProhibitConstruct();
	}
	function & getInstance(){
		static $instance = array();

		if( isset($instance[0]) && is_object($instance[0]) ){
			$keep = $instance[0]->keep_parameter;

			if(  is_string($keep[0]) ){
				$stack = array();
				foreach($keep as $method){
					if( !is_string( $method ) || !method_exists( $instance[0] , $method ) ){
						continue;
					}
					$stack[$method] = $instance[0]->{$method}();
				}
				$instance[0] -> reset();
				foreach($stack as $method => $value){
					$instance[0]->{$method}($value);
				}
			}elseif( true !== $keep[0] ){
				$instance[0] -> reset();
			}

			return $instance[0];
		}
		$instance[0] = & new Qdmail();
		return  $instance[0];
	}
	//-------------------
	// Easy Base
	//-------------------
	function easy( $type , $to , $subject , $content , $other_header = array() , $attach = null ){
		if(is_null($other_header)){
			$other_header=array();
		}
		$option_return = array();
		if( is_array($type) ){
			$type = array_change_key_case( $type , CASE_UPPER );
			if( isset( $type['SMTP'] ) ){
				$this->smtp( true );
				$this->smtpServer( $type['SMTP'] );
			}
			if(isset( $type['OPTION'] )){
				$type['OPTION'] = array_change_key_case( $type['OPTION'] , CASE_UPPER );
				foreach($type['OPTION'] as $method => $param ){
					if(method_exists($this,$method)){
						$option_return[$method] = $this->{$method}($param);
					}
				}
			}
			$type = isset( $type['TYPE'] ) ? $type['TYPE'] : 'TEXT' ;
		}

		if( (empty($to) && ( !empty($subject) || !empty($content) ))){
			return $this->errorGather('Parameter Specified Error',__LINE__);
		}elseif( empty($to) ){
			return $option_return;
		}

		if( 'TEXT' == strtoupper( $type ) || 'HTML' == strtoupper( $type ) ){
			$type=strtolower( $type );
		}else{
			$this->error[]='Illegal spcify \'type\' in '.$type.' .'.__LINE__;
			return $this->errorGather();
		}
		$to = is_string($to) ? array($to) : $to ;
		$other_header = is_string($other_header) ? array('From' => $other_header) : $other_header ;

		list($other_header_temp , $link ) = $this->keyUpper( $other_header );

		if(!isset($other_header_temp['FROM'])){
			$fromAddr = null;
			if( isset($other_header[0]) ){
				$fromAddr = $other_header[0];
				unset($other_header[0]);
			}
			$fromName = null;
			if(isset($other_header[1])){
				$fromName =  $other_header[1];
				unset($other_header[1]);
			}
			$other_header = array_merge( $other_header,array('FROM'=>array( $fromAddr , $fromName )));
		}

		$other_header = array_merge(array('TO'=>$to),$other_header);
		$section = array('TO'=>'to','CC'=>'cc','BCC'=>'bcc','REPLY-TO'=>'replyto','FROM'=>'from');
		list($other_header_temp , $link ) = $this->keyUpper( $other_header );

		foreach($other_header_temp as $key => $other_head){
			if(isset($section[$key])){
				$method = $section[$key];
				$this -> {$method}( $other_head , null );
			}else{
				$this -> addHeader( $link[$key] ,  $other_head );
			}
		}

		$this->subject( $subject );
		$this->{$type}( $content );
		if( isset( $attach ) ){
			$this->attach( $attach , $add = false , $this->inline_mode );
		}

		return $this->send();
	}

	function easyText( $to , $subject , $content , $other_header = array() , $attach = null , $option = array() ){
		return $this->easy( array('TYPE'=>'text','OPTION'=>$option) , $to , $subject , $content , $other_header ,  $attach );
	}

	function easyHtml( $to , $subject , $content , $other_header = array() , $attach = null , $option = array() ){
		return $this->easy( array('TYPE'=>'html','OPTION'=>$option) , $to , $subject , $content , $other_header ,  $attach );
	}

	function easyReplace( $to , $subject , $content , $other_header = array() , $attach = null , $option = array() ){
		$this->simpleReplace( true );
		$type = 'text';
		if(0!==count($option)){
			$option = array_change_key_case( $option , CASE_UPPER );
			$type = ( 'HTML' === strtoupper( $option['TYPE']) ) ? 'html' : $type ;
		}
		$this->easy( array('TYPE'=>$type,'OPTION'=>$option) , $to , $subject , $content , $other_header ,  $attach );
	}

	function easyDeco( $to , $subject , $content , $other_header = array() , $attach = null , $option = array() ){
		if( isset( $attach ) ){
			$this->inline_mode=true;
		}
		$this->autoDecoJudge( true );
		$this->toSeparate( true );
		return $this->easy( array('TYPE'=>'html','OPTION'=>$option) , $to , $subject , $content , $other_header ,  $attach );
	}

	function easyDecoRep( $to , $subject , $content , $other_header = array() , $attach = null , $option = array() ){
		$this->simpleReplace( true );
		return $this->easyDeco($to , $subject , $content , $attach , $option , $option);
	}

	function easyOption( $to , $subject = null , $content = null , $other_header = array() , $attach = null , $option = array() ){

		if(!is_array($to)){
			$option = array( $to => $subject );
		}else{
			$option = $to;
		}
		return $this->easy( array('TYPE'=>'option','OPTION'=>$option) , $to , $subject , $content , $other_header ,  $attach );
	}

/*
 * Notice: Before use $this->optionNameLink(); by Constractor
*/
	//
	//---------------------------------------
	// something change mode
	//---------------------------------------
	// Keys must lowercase , because of PHP4's 
	var	$property_type = array(
		'auto_both'			=> 'bool' ,
		'to_separate'		=> 'bool' ,
		'simple_replace'	=> 'bool' ,
		'auto_deco_judge'	=> 'bool' ,
		'auto_ext'			=> 'bool' ,
		'body_empty_allow'	=> 'bool' ,
		'ignore_error'		=> 'bool' ,
		'wrap_width'		=> 'bool' ,
		'wordwrap_allow'	=> 'bool' ,
		'wrap_prohibit_allow'=> 'bool' ,
		'force_change_charset'	=> 'bool' ,
		'error_display'		=> 'bool' ,
		'smtp'				=> 'bool' ,
		'smtp_loglevel_link'=> 'bool' ,
		'inline_mode'		=> 'bool' ,
		'replace_with_to_priority'=> 'bool' ,
		'attach_build_once'	=> 'bool' ,
		'body_build_once'	=> 'bool' ,
		'kana'				=> 'bool' ,
		'keep_parameter'	=> 'array' ,
		'attach_path'		=> 'string' ,
		'mta_option'		=> 'string' ,
		'rep_prefix'		=> 'string' ,
		'log_path'			=> 'string' ,
		'errorlog_path'		=> 'string' ,
		'log_filename'		=> 'string' ,
		'errorlog_filename'	=> 'string' ,
		'allways_bcc'		=> 'string' ,
		'wrap_prohibit_top'	=> 'string' ,
		'wrap_prohibit_end'	=> 'string' ,
		'framework'			=> 'string' ,
		'mb_strwidth_magni'	=> 'numeric' ,
		'log_dateformat'	=> 'numeric' ,
		'log_level'			=> 'numeric' ,
		'errorlog_level'	=> 'numeric' ,
		'smtp_server'			=> 'array' ,
	);
	var	$method_property	= array();

	function optionNameLink(){
		foreach($this->property_type as $prop => $type ){
			$method_low = strtolower( str_replace( '_' , '' , $prop ) );
			$this->method_property[$method_low] = $prop;
		}
	}
	function option( $option , $line = null , $min = null , $max = null ){
		$ret = array();
		if( !is_null( $line ) ){
			$line = '-' . $line ;
		}
		if(!is_array($option)){
			return $this->errorSpecify( __FUNCTION__, __LINE__ );
		}
		foreach( $option as $key => $value ){
			if( !isset( $this->method_property[strtolower($key)] ) ){
				return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
			}
			$property_name = $this->method_property[strtolower($key)];
			if( is_null( $value ) ){
				$ret[] = $this->{$property_name} ;
				continue ;
			}
			$err = false;
			switch( $this->property_type[$property_name] ){
				case 'bool':
					if( is_bool( $value ) ){
						$this->{$property_name} = $value ;
						$ret[0] = true ;
					}else{
						return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
					}
				break;
				case 'string':
					if( '' === $value ){
						$this->{$property_name} = null ;
						$ret[0] = true ;
						break ;
					}
					if( is_string( $value ) ){
						$this->{$property_name} = $value ;
						$ret[0] = true ;
					}else{
						return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
					}
				break;
				case 'numeric':
					if( !is_numeric( $value ) || ( isset( $min ) && ( $value < $min ) ) || ( isset( $max ) && ( $value > $max ) ) ){
						return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
					}else{
						$this->{$property_name} = $value ;

						$ret[0] = true ;
					}
				break;
				case 'array':
					if( !is_array( $value ) ){
						$value = array( $value );
					}
					if( true===$min ){
						$this->{$property_name} = array_merge( $this->{$property_name} , $value );
					}else{
						$this->{$property_name} = $value ;
					}
					$ret[0] = true ;
					if( true === $max ){
						$this->{$property_name} = array_change_key_case( $this->{$property_name} , CASE_UPPER );
					}
				break;

				default:
					return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
				break;
			}
		}
		if( 0 === count( $ret ) ){
			return $this->errorSpecify( __FUNCTION__ , __LINE__ );
		}elseif( 1 === count( $ret ) ){
			return array_shift( $ret );
		}else{
			return $ret ;
		}
	}

	function autoBoth( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function toSeparate( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function simpleReplace( $bool = null ){
		$this->toSeparate( $bool );
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function autoDecoJudge( $bool = null ){
		$this->attachBuildOnce( !$bool );
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function autoExt( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function bodyEmptyAllow( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function ignoreError( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function forceChangeCharset( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function errorDisplay( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function smtp( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function smtpLoglevelLink( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function inlineMode( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function replaceWithToPriority( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function attachBuildOnce( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function bodyBuildOnce( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function kana( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function keepParameter( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function is_qmail(){
		$ret = ini_get ( 'sendmail_path' );
		return false!==strpos($ret,'qmail');
	}
	function lineFeed( $LFC = null ){
		if(is_null($LFC)){
			return $this->LFC;
		}
		if(preg_match('/[\r|\n|\r\n]/is',$LFC)){
			$this->LFC = $LFC;
			return true;
		}else{
			return false;
		}
	}
	//---------------------------------------
	// something change mode 
	//---------------------------------------
	function whichTextHtml( $which ){
		$which = strtoupper( $which );
		if( 'TEXT' == $which ){
			$this->is_html='TEXT';
		}elseif( 'HTML' == $which ){
			$this->is_html='HTML';
		}elseif( 'BOTH' == $which ){
			$this->is_html='BOTH';
		}
	}

	function allwaysBcc( $option = null ){
		if( is_null( $option ) ){
			return $this->allways_bcc ; 
		}
		if( $this->option( array( __FUNCTION__ => $option ) ,__LINE__) ){
			$fg = $this->extractAddr( $this->allways_bcc ) ;
		 }
		if( $this->errorGather() && $fg && !empty($this->allways_bcc) ){
			return true ;
		}else{
			$this->allways_bcc = array();
			return false ; 
		}
	}
	function framework( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function attachPath( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function mtaOption( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function logPath( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function errorlogPath( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function logDateFormat( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function logFilename( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function errorlogFilename( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__);
	}
	function logLevel( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__, 0 , $this->log_level_max );
	}
	function errorlogLevel( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__, 0 , $this->errorlog_level_max );
	}
	function smtpServer( $array = null ){
		return $this->option( array( __FUNCTION__ => $array ) ,__LINE__, true , true );
	}
	//-----------------------------------------
	// Address and Name Keys change Opiton
	//-----------------------------------------
	function addressField( $addr = null , $name = null ){
		if( is_null($addr) && is_null($name) ){
			return array( $this->tokey['_ADDR'] , $this->tokey['_NAME'] );
		}
		if( !is_null($addr) && is_array($addr) && 1 < count($addr) ){
			$_addr = array_shift( $addr ) ;
			$name = array_shift( $addr ) ;
			$addr = $_addr;
		}
		if( (!is_null($addr) && !is_string( $addr )) || !is_null($name) && !is_string($name) ){
			return $this->errorGather('Specify Error in addressField',__LINE__);
		}
		$addr = is_null( $addr ) ? $this->tokey['_ADDR'] : $addr ;
		$name = is_null( $name ) ? $this->tokey['_NAME'] : $name ;
		$this->tokey = array(
			'_ADDR' => $addr,
			'_NAME' => $name,
		);
		return true;
	}
	//-----------------------------------------------------------
	// Wordwrap Opiton
	// array( 'except word' => beginning flag ) 
	// if beginning flag is true , beginning of a line is target
	// if beginning flag is true , the word in line is target
	//-----------------------------------------------------------
	function wordwrapProhibitConstruct(){
		$ret = $this->strToArrayKey( $this->wrap_prohibit_top , true );
		$ret2 = $this->strToArrayKey( $this->wrap_prohibit_end , false );
		$this->wrap_prohibit = array_merge( $ret , $ret2 );
	}
	function strToArrayKey( $word , $value ){
		$ret = array();
		$enc = mb_detect_encoding( $word );
		$length = mb_strlen( $word , $enc );
		for( $i=0 ; $i < $length ; $i++ ){
			$ret[ mb_substr( $word , $i , 1 , $enc ) ] = $value;
		}
		return $ret;
	}
	function wordwrapAllow( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function wrapProhibitAllow( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function wrapProhibitEnd( $option = null ){
		$this->option( array( __FUNCTION__ => $option ) ,__LINE__);
		$this->wordwrapProhibitConstruct();
		return  $this->errorGather();
	}
	function wrapProhibitTop( $option = null ){
		$this->option( array( __FUNCTION__ => $option ) ,__LINE__);
		$this->wordwrapProhibitConstruct();
		return  $this->errorGather();
	}
	function wrapWidth( $bool = null ){
		return $this->option( array( __FUNCTION__ => $bool ) ,__LINE__);
	}
	function wordwrapLength( $length = null ){
		if( is_null( $length ) ){
			return $this->wordwrap_length;
		}
		if( !is_numeric( $length ) || ( is_numeric( $length ) &&  $length < 1 ) ){
			return $this->errorGather('Wordwrap error , length is illegal' ,__LINE__) ;
		}
		$this->wordwrap_length = $length;
		return $this->errorGather() ;
	}
	function mbStrwidthMagni( $option = null ){
		return $this->option( array( __FUNCTION__ => $option ) ,__LINE__, 0 , 10 );
	}
	function wrapExcept( $array = null ){
		if( null === $array ){
			return $this->wrap_except;
		}
		if( is_string( $array ) || is_numeric( $array ) ){
			$this->wrap_except = array( $array =>false );//default false
		}
		if( is_array( $array ) ){
			if( 0 === count( $array ) ){
				$this->wrap_except = array();
				return $this->errorGather() ;
			}
			foreach( $array as $key => $value){
				if( !is_bool( $value ) ){
					$array[$key] = empty( $value ) ? false:true;
				}else{
					$array[$key] = $value ;
				}
			}
			$this->wrap_except = $array;
			return $this->errorGather() ;
		}
		return $this->errorSpecify(__FUNCTION__,__LINE__);
	}
	//------------------------------------------
	// Charset Option
	//------------------------------------------
	function charsetHeader( $charset = null ){
		if(is_null($charset)){
			return $this->charset_header ;
		}
		$stack = $this->charset();
		$stack['HEADER'] = $charset;
		return $this->charset( $stack );
	}
	function charsetAttach( $charset = null ){
		if(is_null($charset)){
			return $this->charset_attach_filename ;
		}
		$stack = $this->charset();
		$stack['HEADER'] = $charset;
		return $this->charset( $stack );
	}
	function charsetBody( $charset = null , $enc = null ){
		if( is_null($charset) && is_null($enc) ){
			return array($this->charset_content , $this->content_transfer_enc_text , content_transfer_enc_html) ;
		}
		if(is_array($charset)){
			$enc = isset($charset[1]) ? $charset[1]:null;
			$charset = $charset[0];
		}
		$stack = $this->charset();
		if( !is_null($charset) ){
			$stack['BODY'] = $charset;
		}
		if( !is_null($enc) ){
			$stack['HTML'] = $stack['TEXT'] = $enc;
		}
		return $this->charset( $stack );
	}
	function charset( $array = null , $op = null ){
		if( is_null( $array ) && is_null( $op ) ){
			foreach( $this->corres_charset as $key => $value ){
				$ret[$key] = $this->{$value} ;
			}
			return $ret;
		}
		if( !is_null($op) && is_string($op) ){

			$this->content_transfer_enc_text = $op ;
			$this->content_transfer_enc_html = $op ;

			return $this->charset( $array );
		}elseif(!is_null($op) && !is_string($op)){
			return $this->errorSpecify( __FUNCTION__ , __LINE__ );
		}
		if( is_array( $array ) ){
			if( 2===count($array) && isset($array[0]) && isset($array[1])){
				return $this->charset($array[0],$array[1]);
			}
			$array = array_change_key_case( $array , CASE_UPPER );
			foreach( $array as $key => $value ){
				if(isset($this->corres_charset[$key])){
					if( is_string($this->corres_charset[$key]) ){
						$this->{$this->corres_charset[$key]} = $value;
					}else{
						return $this->errorSpecify( __FUNCTION__ , __LINE__ );
					}
				}
			}
		}elseif( is_string($array) ){
			$this->charset_header = $this->charset_content = $this->charset_attach_filename = $array;
		}else{
			return $this->errorSpecify( __FUNCTION__ , __LINE__ );
		}
	return $this->errorGather() ;
	}
	//--------------------------------
	// Decorationable HTML Mail Opiton 
	// ( Inline HTML , MHTML )
	// See $this->deco_def Property
	//--------------------------------
	// Change decoration default along to each career
	function decoDef( $value = null ){
		if( is_null( $value ) ){
			return $this->deco_def;
		}
			$this->deco_def = $value ;
		return $this->errorGather() ;
	}
	// fix Decoration Pattern by STRING means CareerName
	function decoFix( $deco_kind = null ){
		if( is_null( $deco_kind ) ){
			return $this->deco_def_default;//$this->deco_judge
		}
		$deco_kind = strtoupper( $deco_kind );
		$ret = false ;
		foreach( $this->deco_def as $key => $def ){
			if( in_array( $deco_kind , $def['OPTION_NAME'] ) ){
				$ret = $key ;
				$this->inline_mode = true;
			}
		}
		return $ret;
	}

	// Change Decoration Pattern by E-mail Address
	function decoJudge( $addr_array ){
		$addr=$addr_array[$this->tokey['_ADDR']];
		$start = strrpos( $addr , '@');
		if(empty($start)){
			return $this->deco_def_default;
		}
		$right = trim(substr($addr , $start+1));
		$parts = explode('.',$right);
		$ct = count($parts);
		if( 2 > $ct ){
			return $this->deco_def_default;
		}
		$domains = array();
		$domains[] =  $parts[$ct-2] . '.' . $parts[$ct-1];
		if( isset($parts[$ct-3]) ){
			$domains[] =  $parts[$ct-3] .'.'.$parts[$ct-2] . '.' . $parts[$ct-1];
		}
		if( isset($parts[$ct-3]) && isset($parts[$ct-4]) ){
			$domains[] =  $parts[$ct-4] .'.'. $parts[$ct-3] .'.'.$parts[$ct-2] . '.' . $parts[$ct-1];
		}
		$ct = count($domains);
		$domain = reset($domains);
		while( $ct-- > 0){
			if(isset( $this->deco_judge[$domains[$ct]])){
				$domain = $domains[$ct];
				break;
			}
		}
		return $this->decoFix(isset( $this->deco_judge[$domain] ) ? $this->deco_judge[$domain]:null);
	}
	//------------------------------------
	//
	// Word Replace
	//
	// You can add prefix by $this->rep_prefix proparty
	// OR $this->repPrefix() Method (Recommended)
	// notice: this functino need on utf-8
	// OR $this->qdmail_system_charset need utf-8
	//------------------------------------
	function replaceWord( $array = null , $add = false ){
		if( is_null( $array ) ){
			return $this->replace ;
		}
		if( !is_array( $array ) ){
			$array = array( $array );
		}
		foreach($array as $key => $arr){
			if( !is_array( $arr ) ){
				$array[$key] = array( $arr );
			}
		}
		if( $add ){
			$this->replace = array_merge( $this->replace , $array ); 
		}else{
			$this->replace = $array ; 
		}
		return $this->errorGather() ;
	}
	function replaceDef( $array = null ){
		if(is_null($array)){
			return $this->replace_def;
		}
		if(is_array($array)){
			$this->replace_def = $array;
		}else{
			return false;
		}
	}

	function repPrefix( $option = null ){
		return $this->stringOption( __FUNCTION__ , $option , __LINE__ );
	}
	function replace( $cont , $rep ){
		foreach($rep as $serch => $replace ){
			if( '_' == mb_substr( $serch , 0 , 1 , mb_detect_encoding($serch) ) ){
				continue;
			}
			if( empty($replace) && !empty($this->replace_def[$serch]) ){
				$replace = $this->replace_def[$serch];
			}
			$reg = '/%' . $this->rep_prefix . (string) $serch . '%/is' ;
			$cont = $this->qdmail_preg_replace( $reg , $replace , $cont );
		}
		return $cont;
	}
	function qdmail_preg_replace( $reg , $rep , $cont ){
		$enc = mb_detect_encoding( $cont );
		$_reg = mb_convert_encoding( $reg , $this->qdmail_system_charset , mb_detect_encoding( $reg ) );		$_rep = mb_convert_encoding( $rep , $this->qdmail_system_charset , mb_detect_encoding( $rep ) );		$_cont = mb_convert_encoding( $cont , $this->qdmail_system_charset , $enc );
		$cont = preg_replace( $_reg , $_rep , $_cont );
		return mb_convert_encoding($cont , $enc , $this->qdmail_system_charset );
	}
	//------------------------------------
	// OOP User Interface (Recommended)
	//------------------------------------
	function to( $addr = null , $name = null , $add = false ){
		return $this->addrs( 'TO' , $addr , $name , $add );
	}
	function cc( $addr = null  , $name = null , $add = false ){
		return $this->addrs( 'CC' , $addr , $name , $add );
	}
	function bcc( $addr = null  , $name = null , $add = false ){
		return $this->addrs( 'BCC' , $addr , $name , $add );
	}

	function from( $addr = null  , $name = null ){
		return $this->addrs( 'FROM' , $addr , $name , false );
	}
	function replyto( $addr = null  , $name = null ){
		return $this->addrs( 'REPLYTO' , $addr , $name , false );
	}
	function addHeader( $header_name = null , $value = null){
		if('REPLY-TO'==strtoupper($header_name)){
			$header_name = 'REPLYTO' ;
		}
		if(isset($this->addr_head_name[strtoupper($header_name)])){
			return $this->{strtolower($header_name)}( $value , null , true );
		}		if(is_null($header_name)){
			return $this->other_header;
		}
		if('clear'===strtolower($header_name) && is_null($value)){
			$this->other_header=array();
			return ;
		}
		$this->other_header[$header_name] = $value ;
	}
	function reset( $debugErase = false ){

		if( !$debugErase ){
			$stack_debug = $this->debug ;
		}

		$stack = $this->stack_construct;
		$array = get_class_vars( $this->name ) ;
		foreach($array as $key => $value){
			$this->{$key} = $value ;
		}

		$this->__construct( $stack );
		if( !$debugErase ){
			$this->debug = $stack_debug ; 
		}
	}

	function _gatherFromArray( $array , $key ){
		$ret = array();
		foreach( $array as $ar ){
			$ret[] = $ar[$key] ;
		}
		return $ret;
	}
	function done(){
		return $this->_gatherFromArray( $this->done , $this->tokey['_ADDR'] );
	}
	function undone(){
		return $this->_gatherFromArray( $this->undone , $this->tokey['_ADDR'] );
	}

	function subject( $subj = null ){
		if( is_null($subj) ){
			return $this->subject;
		}
		
		if( is_string( $subj ) || is_numeric( $subj ) ){
			$this->subject['CONTENT'] = (string) $subj;
			return $this->errorGather() ;
		}elseif( is_array($subj) ){
			$subj = array_change_key_case( $subj , CASE_UPPER );
			if(isset($subj['CONTENT'])){
				$this->subject = $subj;
			}else{
				$this->subject['CONTENT'] = (string) $subj[0];
				$this->subject['_CHARSET'] = isset($subj[1]) ? $subj[1] : null ;
				$this->subject['_ORG_CHARSET'] = isset($subj[2]) ? $subj[2] : null ;
			}
			return $this->errorGather() ;
		}else{
			return $this->errorSpecify(__FUNCTION__,__LINE__);
		}
	}

	function body( $type , $cont , $length = null , $charset = null , $enc = null , $org_charset = null ){
		$type = strtolower( $type );
		if( 'text'!==$type && 'html'!==$type ){
			return $this->errorGather('You must use \'text\' or \'html\'' ,__LINE__) ;
		}
		if( is_array( $cont ) ){
			$def = array(
				'CONTENT'=>null,
				'LENGTH'=>null,
				'_CHARSET'=>null,
				'ENC'=>null,
				'_ORG_CHARSET'=>null,
			);
			$temp = array_change_key_case( array_merge($def,$cont) , CASE_UPPER);
		}else{
			$temp = array(
				'CONTENT'=>$cont,
				'LENGTH'=>$length,
				'_CHARSET'=>$charset,
				'ENC'=>$enc,
				'_ORG_CHARSET'=>$org_charset,
			);
		}
		$this->content[strtoupper($type)] = array_merge( $this->content[strtoupper($type)] , $temp );
		return $this->errorGather() ;
	}

	function text( $cont , $length = null , $charset = null , $enc = null , $org_charset = null ){
		return $this->body('text', $cont , $length , $charset , $enc , $org_charset );
	}

	function html( $cont , $charset = null , $enc = null , $org_charset = null ){
		return $this->body('html', $cont , null , $charset , $enc , $org_charset );
	}

	//--------------------------
	// assist User Interface
	//--------------------------
	function addrs( $section , $addr = null  , $name = null , $add = false ){
		$section = strtolower( $section );
		$ck = array('to'=>true,'from'=>true,'cc'=>true,'bcc'=>true,'replyto'=>true);
		if(empty($ck[$section])){
			return $this->errorGather('Illegal Section Name \''.$section.'\'' ,__LINE__) ;
		}
		if( is_null( $addr ) && is_null( $name )){
			return  $this->{$section} ;
		}
		if( false === $addr ){
			$this->{$section} = array();
			return $this->errorGather() ;
		}
		$addr = $this->analyzeAddr( $addr , $name );

		if( !$add ){
			$this->{$section} = $addr;
		}else{
			$this->{$section} = array_merge( $this->{$section} , $addr );
		}

		return ( 0 != count( $addr ) );
	}

	function analyzeAddr( $addr , $name ){
		if( is_string( $addr ) ){
			if( empty( $name ) ){
				list( $name , $addr ) = $this->_extractNameAddr( $addr );
			}else{
				$addr = $this->extractAddr( $addr );
			}
			return array(array( $this->tokey['_ADDR'] => $addr , $this->tokey['_NAME'] => $name ));
		}
		// $addr is array
##		list( $addr , $void ) = $this->keyUpper( $addr );
		$ret = array();
		if( empty( $name ) || !is_array( $name ) ){
			if(isset($addr[$this->tokey['_ADDR']])){
				$addr[$this->tokey['_NAME']] = isset($addr[$this->tokey['_NAME']]) ? $addr[$this->tokey['_NAME']]:null;
				if( !empty($addr) ){
					return array( $addr );//ver 0.7.3a
				}else{
					return array();
				}
			}elseif( isset( $addr[0] ) && is_array( $addr[0] ) ){
				foreach($addr as $ad){
##					list( $ad , $void ) = $this->keyUpper( $ad );
					$_addr = isset( $ad[$this->tokey['_ADDR']] ) ?  $this->extractAddr( $ad[$this->tokey['_ADDR']] ) : $this->extractAddr( $ad[0] ) ;
					if(isset( $ad[$this->tokey['_NAME']] ) ){
						$_name = $ad[$this->tokey['_NAME']];
					}elseif( isset( $ad[1] ) ){
						$_name = $ad[1];
					}else{
						$_name = null;
					}
					if( empty($_addr) ){
						continue;
					}else{
						$ret[] = array_merge( $ad , array( $this->tokey['_ADDR'] => $_addr , $this->tokey['_NAME'] => $_name ) );
					}
				}
				return $ret;
			}else{
				$_addr = $addr[0];
				$_name = isset($addr[1]) ? $addr[1]:null;
				$ret[] = array($this->tokey['_ADDR'] => $_addr , $this->tokey['_NAME'] => $_name);
			}
			return $ret; //fool proof
		}else{
			foreach( $addr as $key => $value ){
				$_addr = $this->extractAddr( $value );
				$_name = $name[$key] ;
				if( empty( $_addr ) ){
					continue;
				}else{
					$ret[] = array( $this->tokey['_ADDR'] => $_addr , $this->tokey['_NAME'] => $_name );
				}
			}
			return $ret;
		}
	return $ret; // fool proof
	}

	//--------------------------------------------------------
	// From MutibyteName<example@example.com> To MutibyteName
	//--------------------------------------------------------
	function _extractNameAddr( $addr ){
		$formed_addr = $this->extractAddr( $addr );
		if( empty( $formed_addr ) ){
			return false;
		}
		$addr = trim($addr);
		$addr = str_replace(array('<','>'),'',$addr);
		$temp=strpos($addr,$formed_addr);
		if( false === $temp ){
			return null;
		}
		return array( substr( $addr , 0 , strpos( $addr , $formed_addr )) , $formed_addr );
	}

	function setContentArgs( $type , $param ){
		$method_name = 'text';
		if('HTML' == $type ){
			$method_name = 'html';
		}
		$cont = null;
		if(isset($param[$type])){
			$cont = $param[$type];
		}elseif(isset($param['CONTENT'])){
			$cont = $param['CONTENT'];
		}

		return $this->{$method_name}(
			$cont ,
			isset($param['_CHARSET']) ? $param['_CHARSET']:null,
			isset($param['ENC']) ? $param['ENC']:null,
			isset($param['_ORG_CHARSET']) ? $param['_ORG_CHARSET']:null
		);

	}
	//-------------------------------------------
	// Main Routine Send()
	//   Option analyize
	//   Is To-Separate Mode ?
	//     loop:sendbase
	//       Already Created Mail?
	//       Create mail
	//         Additional Parameter(From User) Analyize (e.g. charset , subject etc...)
	//           (Not OOP MODE)
	//         Build Header(except Content-type etc) and Must Header Checking
	//           Both mode ? text only or html only or both ? or auto both
	//           Addition Attachment will do
	//           Select Body Structure by Decoration Pattern or else
	//         Build Body ( Recursive ) 
	//         Render Body with 'Content-type' Header and Boundary etc..
	//           +  finalize( Recursive )
	//              Pass to the Header,first Content-type etc. that needs by Header Render Routine
	//         Set Default Header, MIME 1.0 etc
	//         Render Header and Render for SMTP Sender Text(Future)
	//   Debug Echo & log & error log will do if you want 
	//If error exsist , no sender(except ignore_error Property)
	//-------------------------------------------
	function headerDefault(){
		$this->header['MIME-Version'] = '1.0';
		$this->header['X-'.$this->xmailer] = 'version-'.$this->version . ' ' . $this->license .' http://hal456.net/qdmail';
		$sendby = $this->smtp ? 'SMTP':'mailfunction';
		$this->header['X-'.$this->xmailer] .= $this->LFC.' send-by '.$sendby;
	}
	function send( $option = null ){
		if( is_null( $this->start_time )){
			$this->start_time = microtime();
		}
		if( is_object( $option ) ){
			$this->smtp_object = & $option;
			$this->smtp = true;
			$option = null ;
		}

		$this->debugEchoLine('debug: '.$this->debug().', log: '.$this->logLevel().', errorlog: '.$this->errorlogLevel());

		$fg = true;
		if( true === $this->toSeparate() ){
			$stack_tos = array( $this->to , $this->cc , $this->bcc );
			$tos = $this->to ;
			$this->cc( false ) ;
			$this->bcc( false ) ;
			if( empty( $tos ) ){
				$fg = $this->errorGather('Receipt Header is not exsit line' ,__LINE__) ;
			}else{
				// To Separate mode
				foreach($tos as $key => $to){
					if( $this->simple_replace ){
						if($this->replace_with_to_priority){
							$to = array_merge( $this->selectReplace( $to , $key ) ,$to );
						}else{
							$to = array_merge( $to , $this->selectReplace( $to , $key )  );
						}
					}
					$this->to( $to , null , false );
					if( $this->auto_deco_judge ){
						$this->deco_kind = $this->decoJudge( $this->to[0] );
					}
					if( $this->sendBase() ){
						$this->is_create = false; // for next to
						continue ;
					}else{
						$this->is_create = false; // for next to
						$fg = $this->errorGather('Error \'TO\' Separate mode in Sendbase function , the Address is -> '.$this->to[0][$this->tokey['_ADDR']] ,__LINE__) ;
					}
				}
			}
			list( $this->to , $this->cc , $this->bcc ) = $stack_tos ;

		}else{
			// normal mode the end
			$fg = $this->sendBase() ;
			$this->is_create = false;
		}
		$this->log();
		//debug
		$this->debugEcho('END');
		if( $fg ){
			return $this->errorGather();
		}else{
			return $this->errorGather('Send Error' ,__LINE__) ;
		}
	}

	function selectReplace( $to , $key ){
		$ret = array();
		if( isset( $this->replace[$to[$this->tokey['_ADDR']]] ) ){
			$ret = $this->replace[$to[$this->tokey['_ADDR']]];
		}elseif( isset( $this->replace[$key] ) ){
			$ret = $this->replace[$key];
		}
		return $ret ;
	}

	function sendBase(){
		// stack bcc for allways bcc
		unset( $stack_bcc ) ;
		if( 0 != count( $this->allways_bcc ) ){
			$stack_bcc = $this->bcc ;
			$this->bcc( $this->allways_bcc , null , true );
		}
		if( !$this->is_create ){
			$this->body = null;
			$this->after_id = null;
			$this->createMail();
		}
		if( isset($option) && !empty($option) ){
			list( $option , $void ) = $this->keyUpper( $option );
		}

		// for smtp
		$this->extractReceipt() ;
		$fg = true;
		$fg_debug = ( 2 > $this->debug ) ;
		if( $fg_debug && (  ( 0 === count( $this->error ) ) && ( 0 === count( $this->error_stack ) ) ) || $this->ignore_error ) {
			//
			//  mail or SMTP(FUTURE)
			//
			if( $this->smtp ){
				$fg = $this->sendBySmtp();
			}else{
				$fg = mail( 
					  trim( $this->header_for_mailfunction_to )
					, trim( $this->header_for_mailfunction_subject )
					, $this->content_for_mailfunction
					, trim( $this->header_for_mailfunction_other )
					, trim( $this->mta_option )
				);
			}

			if( $fg ){
				$this->done = array_merge( $this->done , $this->to , $this->cc , $this->bcc ) ;
			}else{
				$this->undone = array_merge( $this->undone , $this->to , $this->cc , $this->bcc ) ;
				$err_mes = $this->smtp ? 'SMTP mail method':'PHP mail function';
				$fg =$this->errorGather('No send . Because '.$err_mes.' replied error' ,__LINE__);
			}
		}elseif( $fg_debug ){
			$this->undone = array_merge( $this->undone , $this->to , $this->cc , $this->bcc ) ;
			$err_mes = $this->smtp ? 'SMTP mail method':'PHP mail function';
			$fg = $this->errorGather('No send . Because '.$err_mes.' replied error' ,__LINE__);;
		}else{
			$this->undone = array_merge( $this->undone , $this->to , $this->cc , $this->bcc ) ;
			$fg = true ;
		}
		//debug
		$bcc = null;
		if( !empty($this->header_for_smtp_bcc )){
			$bcc = '('.$this->header_for_smtp_bcc.')';
		}
		$this->debugEchoLf(
			$bcc ,
			$this->header_for_smtp,
			$this->content_for_mailfunction,
			$this->LFC.$this->LFC ,
			date('Y-m-d H:i:s')
		);
		if($this->debug_report){
			$this->debugReport('FILE');
		}

		if( isset( $stack_bcc ) ){
			 $this->bcc = $stack_bcc ;
		}
		return $this->errorGather() && $fg;
	}
	//-----------------
	// checking
	//-----------------
	function mustCheck(){
		if( 0 == count( $this->header_must ) ){
			return $this->errorGather() ;
		}
		$must = true;
		foreach( $this->header_must as $hdn ){
			$header_upp = array_change_key_case( $this->header , CASE_UPPER );
			if( ( !$this->smtp && empty( $header_upp[strtoupper($hdn)] ) ) || ( $this->smtp && empty( $header_upp[strtoupper($hdn)] ) && !isset( $this->smtp_server['FROM'] ) ) ){
				$must = $this->errorGather('Must Header is not exist \''.$hdn.'\'' ,__LINE__) ;
			}
		}
		return $must;
	}
	//-----------------------------------------------
	//  Create one mail
	//-----------------------------------------------
	function createMail(){

		$this->_charsetDefFix();
		//
		// content(body) force convert to utf-8 , 
		// because some system function can't do collectlly whitout utf-8,ex preg_replace,striptags
		//
		$this->content = $this->convertCharsetRecursive( $this->content , $this->qdmail_system_charset );
		$this->buildHeader();
		if(!$this->mustCheck()){
			return false;
		};

		// Text only or Html Only or both ?
		if( empty( $this->is_html ) ){
			if( $this->issetContent( $this->content['HTML'] ) && $this->issetCOntent( $this->content['TEXT'] ) ){
				$this->is_html = 'BOTH' ;
			}elseif( $this->issetContent( $this->content['HTML'] ) && $this->auto_both ){
				$this->content['TEXT'] = array(
					'content'=>$this->htmlToText( $this->content['HTML']['CONTENT'] )
				);
				$this->is_html = 'BOTH';
			}elseif( $this->issetContent( $this->content['HTML'] ) && !$this->auto_both ){
				$this->is_html = 'HTML';
			}else{
				$this->is_html = 'TEXT';
			}
		}
		$this->debugEcholine( __FUNCTION__,__LINE__, 'this->deco_kind' , $this->deco_kind );
		// Select Body Structure
		if( empty( $this->attach ) || !isset( $this->deco_kind ) ){
			$structure_no = 0 ;
		}else{
			$structure_no = $this->deco_def[$this->deco_kind]['STRUCTURE'];
		}

		// Short cut on many recipients , samebody
		if( !$this->body_build_once && $this->attach_build_once && $this->attach_already_build){
			//only text and html making
			$this->replaceBodyStructure('TEXT');
			$this->replaceBodyStructure('HTML');

$this->debugEcholine(1,__LINE__);

		}elseif(
			($this->body_build_once && !$this->body_already_build)
			||
			( !$this->body_build_once && ( !$this->attach_build_once || ( $this->attach_build_once && !$this->attach_already_build ) ) ) 
				){
			$this->body_structure = $this->buildBody( $this->structure[$structure_no] );

$this->debugEcholine(2,__LINE__);

		}
		if( !$this->body_build_once || ($this->body_build_once && !$this->body_already_build) ){
			$this->renderBody();//including Content-type Header
$this->debugEcholine(3,__LINE__);

		}

		$this->header = array_merge($this->header , $this->header_content_type);
		// user added header
		$this->headerDefault();
		$this->renderHeader();
		$this->is_create=true;
	}

	function replaceBodyStructure( $kind ){
		$content_type = ( 'TEXT' === $kind ) ? 'text/plain':'text/html';
		$false = false;
		$rep = & $this->serchBodyStructure( $content_type , $this->body_structure , $false );
		if( false === $rep ){
			return false;
		}
		list( $content , $charset , $enc ) = $this->makeContentText( $this->content[$kind] , $kind );
		$rep['CONTENT'] = $content;
		$rep['HEADER']['Content-Type'] = $content_type.'; charset="' . $charset . '"';
		$rep['HEADER']['Content-Transfer-Encoding'] = $enc ;
	}

	function & serchBodyStructure( $content_type , & $bbs , & $false ){
		foreach($bbs as $fkey => $bs){
			if( isset( $bs['HEADER']) && ( 0 < count($bs['HEADER']))) {
				$len = strlen($content_type);
				foreach( $bs['HEADER'] as $key => $cont ){
					if( ('CONTENT-TYPE' === strtoupper($key)) && ($content_type === substr($cont,0,$len)) ){
					return $bbs[$fkey];
					}
				}
			}
			if( !isset( $bs['CONTENT']) || ( isset( $bs['CONTENT']) && !is_array( $bs['CONTENT']))){
				continue;
			}
			$ret = & $this->serchBodyStructure( $content_type , $bbs[$fkey]['CONTENT'] , $false );
			return $ret;
		}
		return $false;
	}
	//except Content-type,user option
	function buildHeader(){
		$header = array();
		foreach( $this->addr_many as $section => $many ){
			if( 0 == count( $this->{strtolower( $section )} ) ){
				continue;
			}
			foreach( $this->{strtolower($section)} as $one ){
				$mime=$this->mime_string(
					$one[$this->tokey['_NAME']],
					isset($one['_CHARSET']) ? $one['_CHARSET'] : $this->charset_header,
					isset($one['_ORG_CHARSET']) ? $one['_ORG_CHARSET'] : null
				);
				// bcc header is not allowed MimeName
				if( empty( $mime ) || 'BCC'===strtoupper( $section ) ){
					$header[$this->addr_head_name[$section]][] = $one[$this->tokey['_ADDR']];
				}else{
					$header[$this->addr_head_name[$section]][] = $mime.' <'.$one[$this->tokey['_ADDR']].'>';
				}
			}
			if( !$many ){
			$header[$this->addr_head_name[$section]] = array( array_pop( $header[$this->addr_head_name[$section]] ) );
			}
		}
		if( !empty( $this->subject ) ){
			//replace
			if( $this->simple_replace ){
				$subj = $this->replace( $this->subject['CONTENT'] , $this->to[0] );
			}else{
				$subj = $this->subject['CONTENT'] ;
			}
			$header['Subject']=$this->mime_string(
				$subj ,
				isset($this->subject['_CHARSET']) ? $this->subject['_CHARSET']:$this->charset_header,
				isset($this->subject['_ORG_CHARSET']) ? $this->subject['_ORG_CHARSET'] : null
			);
		}
	$this->header = array_merge( $this->other_header , $header ) ;
	}
	function renderHeader(){
		$this->header_for_mailfunction_to = implode( ','.$this->LFC.' ' , $this->header['To'] );
		unset( $this->header['To'] ) ;
		$this->header_for_mailfunction_subject = $this->header['Subject'];
		unset( $this->header['Subject'] ) ;

		$this->header_for_mailfunction_other = null;
		$header_for_smtp = null;
		$this->header_for_smtp_bcc = null;

		foreach( $this->header as $key => $value ){
			if( is_array( $value ) ){
				$add = implode( ',' . $this->LFC . ' ' , $value );
			}else{
				$add = $value;
			}
			if( 'BCC' !== strtoupper($key) ){
				$header_for_smtp .= $key . ': ' . $add . $this->LFC ;
			}else{
				$this->header_for_smtp_bcc = $key . ': ' . $add . $this->LFC ;
			}
			$this->header_for_mailfunction_other .= $key . ': ' . $add . $this->LFC;
			unset( $this->header[$key] );
		}
		$this->header_for_smtp = 'To: '
			. $this->header_for_mailfunction_to . $this->LFC
			. 'Subject: '.$this->header_for_mailfunction_subject . $this->LFC
			. trim( $header_for_smtp ) . $this->LFC
		;
	}

	//-------------------------
	// $ret = array(
	//     'BOUNDARY' =>
	//     'HEADER' =>
	//     'CONTENT' =>array(
	//			(Recursive)
	//		)
	//	)
	//-------------------------
	function buildBody( $structure , $boundary = null , $rel = false){
		$ret = array();
		$one = array() ;
		if( is_null( $boundary ) ){
			$boundary = $this->makeBoundary();
		}
		$ret_boundary = $boundary ;
		foreach( $structure as $key => $value ){

			$ret_header = array();
			$ret_cont = array();
			if( is_array( $value ) ){
				$next_boundary = $this->makeBoundary();
				$ret_header['Content-Type'] = strtolower($key).';' . $this->LFC
					. ' boundary="' . $next_boundary . '"' ;
				$rel = false;
				$ret_cont = $this->buildBody( $value , $next_boundary , $rel);
				if( 0 == count($ret_cont) && $structure['OMIT']){
					continue;
				}elseif( 1 == count($ret_cont) && $structure['OMIT']){
					$one = null;
					$ret_cont[0]['BOUNDARY'] = '--'.$boundary ;
					$ret[] = $ret_cont[0];
					continue;
				}else{
					$one = null;
					$ret_cont[] = array( 'BOUNDARY' => null ,'HEADER' => array() ,'CONTENT' => '--' . $next_boundary . '--' );
					$ret[] = array( 'BOUNDARY' => '--' . $ret_boundary , 'HEADER' => $ret_header , 'CONTENT' => $ret_cont );
					continue;
				}
			}else{
				switch( strtolower($key) ){
					case 'image':
						foreach( $this->attach as $att){
							if( ( 'INLINE' === $value ) && $this->isSetCid( $att ) ){
								$ret_cont[]= $this->buildAttach( $att , $boundary , true ) ;
							}elseif( ( 'NOT_INLINE' === $value ) && !$this->isSetCid( $att )){
								$ret_cont[] = $this->buildAttach( $att , $boundary , false ) ;
							}if(  'BOTH' === $value  ){
								$ret_cont[] = $this->buildAttach( $att , $boundary , $this->isSetCid( $att ) ) ;
							}
						}
					break;
					case 'html':
						list( $content , $charset , $enc ) = $this->makeContentText( $this->content['HTML'] , 'HTML' );
						$ret_header['Content-Type'] = 'text/html; charset="' . $charset . '"';
						$ret_header['Content-Transfer-Encoding'] = $enc ;
						$ret_cont = $content ;

					break;
					case 'plain':
						list( $content , $charset , $enc ) = $this->makeContentText( $this->content['TEXT'] , 'TEXT' );
						$ret_header['Content-Type'] = 'text/plain; charset="' . $charset . '"';
						$ret_header['Content-Transfer-Encoding'] = $enc ;
						$ret_cont = $content ;
					break;
					case 'omit':
						$one = null;
					break;
				}
				if( !empty($ret_cont) ){
					$ret[] = array( 'BOUNDARY' => '--' . $boundary , 'HEADER' => $ret_header , 'CONTENT' => $ret_cont );
				}
			}
		}
		return $ret ;
	}

	function renderBody(){

		if( ( 0 === count( $this->body_structure ) ) && !$this->body_empty_allow ){
			return $this->errorGather('Empty Body do not allowed. If you want to send empty Mail , use method -> bodyEmptyAllow(true)' ,__LINE__) ;

		}elseif( 0 === count( $this->body_structure ) ){
			$this->body_structure[0]['HEADER'] = array();
		}
		foreach( $this->body_structure[0]['HEADER'] as $key => $value){
			$this->header_content_type[$key]=$value;
		}
		$this->body_structure[0]['BOUNDARY'] = null;
		$this->body_structure[0]['HEADER'] = array();

		$this->content_for_mailfunction = rtrim($this->finalize( $this->body_structure )) ;
		$this->body_already_build = true ;
	}

	function finalize( $array ){
		foreach( $array as $ar ){
			$header = $this->expandHeader( $ar['HEADER'] );
			$bd = isset($ar['BOUNDARY']) ? trim($ar['BOUNDARY']) . $this->LFC : null ;
			if(is_array($ar['CONTENT'])){
				if( !empty( $header ) ){
					$this->body =  $this->body . $bd . $header . $this->LFC . $this->LFC ;
				}
				$this->finalize( $ar['CONTENT'] );
			}else{
				if( !empty( $header ) ){
					$header .= $this->LFC . $this->LFC ; 
				}
				$add = $bd . $header .  $ar['CONTENT']  ;
				$this->body =  $this->body . $add . $this->LFC . $this->LFC ;
			}
		}
	return $this->body;
	}
	function expandHeader( $hds ){
		if(empty($hds)){
			return null;
		}
		$header = null;
		foreach( $hds as $key => $value ){
			if( isset( $value ) ){
				$header .= $key . ': ' . $value . $this->LFC;
			}
		}
		return trim($header);
	}
	function makeBoundary(){
		static $rec = 0 ;
		$boundary = '__Next-' . $rec . '-' . $this->qdmail_md( null , 65 , 90 ) . 'UWRtYWlsIEFHUEx2Mw==' . base64_encode( $this->qdmail_md() ) . '__';
		$rec ++ ;
		return $boundary;
	}
	function makeContentText( $content , $is_text = 'TEXT' ){
		$flag_wrp = ( 'TEXT' == $is_text ) ? true:false;
		$enc = ( 'HTML' == $is_text ) ? $this->content_transfer_enc_html : $this->content_transfer_enc_text ;
		if( is_array( $content ) ){
			$content = array_change_key_case( $content , CASE_UPPER );
			$_content = $content['CONTENT'];
			$org_char = $this->qdmail_system_charset ; //already converted to system charaset
			$target_char = isset($content['_CHARSET']) 
				? $content['_CHARSET'] : $this->charset_content;
			$length = isset($content['LENGTH']) 
				? $content['LENGTH'] : $this->wordwrap_length;
			$content_transfer_enc = isset($content['ENC'])
				? $content['ENC'] : $enc;
			$content = $_content;
		}else{
			$org_char = $this->qdmail_system_charset ;
			$target_char = $this->charset_content;
			$length = $this->wordwrap_length;
			$content_transfer_enc = $enc;
		}
		// fix crlf
		list($content,$void) = $this->clean($content);
		// Content_replace
		if( $this->simple_replace ){
			$content = $this->replace( $content , $this->to[0] );
		}
		// Content-id replace
		$content = $this->replaceCid( $content );

		// content modify by external function at HTML
		if( 'HTML' == $is_text && isset($this->deco_kind) && isset($this->deco_def[$this->deco_kind]['HTML_EXTERNAL']) ){
			$temp = $this->deco_def[$this->deco_kind]['HTML_EXTERNAL'];
			if( is_array( $temp ) && 'this'==$temp[0]){
				$content = $this->{$temp[1]}($content);
			}elseif( !empty( $temp ) ){
				$content = call_user_func( array($temp[0],$temp[1]) , $content);
			}
		}
		if( $this->wordwrap_allow && $flag_wrp && false !== $length ){
			$content = $this->mbWordwrap( $content , $length );
		}

		$enc_upp = strtoupper($content_transfer_enc);
		if( $this->kana && 'ja'===$this->language && (('BASE64' === $enc_upp && $this->kana_content_relation) || 'BASE64' !== $enc_upp )){
			$content = mb_convert_kana( $content , 'KV' , $org_char );
		}

		if( $org_char != $target_char ){
			$content = mb_convert_encoding( $content , $target_char , $org_char );
		}
		if( 'BASE64' == $enc_upp && !empty( $content ) ){
			$content = chunk_split( base64_encode( $content ) );
		}elseif( ( 'QUOTED-PRINTABLE' == $enc_upp || 'QP' == $enc_upp ) && !empty( $content ) ){
			$content_transfer_enc = 'quoted-printable';
			$content = $this->quotedPrintableEncode( $content );
		}
		return array( $content , $target_char , $content_transfer_enc );
	}
	//--------------
	// html => text
	// must utf-8 because of preg_replace & strip_tags function 
	//--------------
	function htmlToText( $html ){
		$_content = str_replace( array( "\r" , "\n" ) , '' , $html );
		$_content = preg_replace( array( '/<br>/i','/<\/p>/i' , '/<br\s*\/>/i' , '/<\/div>/i' , '/<\/h[1-9]>/i' , '/<\/ol>/i' , '/<\/dl>/i' , '/<\/ul>/i' , '/<li>/i' , '/<\/li>/i' , '/<\/dd>/i' , '/<\/blockquote>/i' , '/<hr\s*\/?>/i' , '/<\/tr>/i' , '/<\/caption>/i' ), $this->LFC , $_content );
		$_content = preg_replace( array( '/<\/td>/i' , '/<\/th>/i' ), ' ' , $_content );
		$_content = preg_replace( "/\\r?\\n/", "\n" , $_content );
		$_content = preg_replace( "/[\\n]+/", "\n" , $_content );
		$_content = preg_replace( "/\\n/", $this->LFC , $_content );
		return trim(strip_tags($_content));
	}

	function mime_string( $subject , $charset , $org_charset = null  ) {

		$enc = isset($org_charset) ? $org_charset:mb_detect_encoding($subject);
		if( empty($subject) || ( strlen(bin2hex($subject))/2 == mb_strlen($subject,$enc) ) ){
			return trim(chunk_split($subject, 75, "\r\n "));
		}
		if($this->kana && 'ja'===$this->language){
			$subject = mb_convert_kana( $subject , 'KV' , $enc );
		}
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

	function _mime_addr( $addr , $add_addr = true ){
		$formed_addr = $this->extractAddr( $addr );
		if( empty( $formed_addr ) ){
			return false;
		}
		$addr = trim($addr);
		$addr = str_replace(array('<','>'),'',$addr);
		$temp=strpos($addr,$formed_addr);
		if(empty($temp)){
			return $formed_addr;
		}
		$for_mime = substr( $addr , 0 , strpos( $addr , $formed_addr ));

		$ret = $this->mime_string( $for_mime , $this->charset_header );
		if( $add_addr ){
			return $ret . ' <' . $formed_addr . '>' ;
		}else{
			return $ret;
		}
	}

	function extractAddr($addr_including_sclub){
		if( preg_match( '/([^<>\s]*@[^<>\s]+)/' , $addr_including_sclub , $match ) == 0){
			return $this->errorGather('Illegal Mail Address "'.$addr_including_sclub.'"' ,__LINE__) ;
		}

		$temp = $this->validate_addr;
		if( is_array( $temp ) && 'this'==$temp[0]){
			$fg = $this->{$temp[1]}($match[1]);
		}elseif( !empty( $temp ) ){
			$fg = call_user_func( array($temp[0],$temp[1]) , $match[1]);
		}

		if( $fg ){
			return $match[1];
		}else{
			return $this->errorGather('User function area error' ,__LINE__) ;
		}
	}

	function makeAddrLine( $one , $hdn ){
		if( is_array($one) ){
##			$one = array_change_key_case( $one , CASE_UPPER );
			if( !empty($one[$this->tokey['_NAME']]) && !empty( $one[$this->tokey['_ADDR']] ) ){
				if(isset($one['_CHARSET'])){
					$charset = $one['_CHARSET'];
				}else{
					$charset = $this->charset_header;
				}
				$ret = $this->mime_string( $one[$this->tokey['_NAME']] , $charset ) . ' <' . $this->extractAddr($one[$this->tokey['_ADDR']]) . '>';
			}elseif( !empty( $one[$this->tokey['_ADDR']] ) ){
				$ret = $this->_mime_addr( $one[$this->tokey['_ADDR']] );
			}else{
				$this->error[]="Data nothing Error in '" . $hdn . "' Header line->".__LINE__;
				$ret = false;
			}
			
		}else{
			$ret = $this->_mime_addr( $one );
		}
		return $ret;
	}

	//----------------------------------------------------------------
	// Charset ReDecear - if Decoration Pattern needs anather charset
	//  (Overload)
	//----------------------------------------------------------------
	function _charsetDefFix(){
		if( !isset( $this->deco_kind ) ){
			return ;
		}
		if(isset($this->deco_def[$this->deco_kind]['_CHARSET'])){
			$this->charset_content = $this->deco_def[$this->deco_kind]['_CHARSET'];
			}
		if(isset($this->deco_def[$this->deco_kind]['ENC_TEXT'])){
			$this->content_transfer_enc_text = $this->deco_def[$this->deco_kind]['ENC_TEXT'];
		}
		if(isset($this->deco_def[$this->deco_kind]['ENC_HTML'])){
			$this->content_transfer_enc_html = $this->deco_def[$this->deco_kind]['ENC_HTML'];
		}
	}

	//------------------------------------------------------------
	// Addition Header(in send( param ) ) set to $this->{to} etc.
	// and return UpperCase keys
	//------------------------------------------------------------
	function setAddr( $header ){

		if( empty( $header ) ){
			return array( $header , array() );
		}
		list( $header , $link_hd )= $this->keyUpper( $header );
		foreach( $this->addr_many as $section => $void){
			if( !isset( $header[strtoupper($section)] ) ){
				continue;
			}
			$this->{strtolower( $section )}( $header[strtoupper($section)] , null , true );
			unset( $header[strtoupper( $section )] );
		}
		// TO Separate mode?
		if( true === $this->toSeparate() ){
			$this->cc(false);
			$this->bcc(false);
		}
		return array( $header , $link_hd );
	}

	function convertCharsetRecursive( $array , $target_enc ){

		if( is_array( $array ) && !empty( $array['_ORG_CHARSET'] ) ){
			foreach($array as $key => $value){
				if( false === strpos( $key , '_CHARSET' ) ){
					$array[$key] = mb_convert_encoding($value , $target_enc ,$array['_ORG_CHARSET']  );
				}
			}
		}elseif( is_string( $array ) || is_numeric( $array ) ){
			$enc = mb_detect_encoding( $array );
			$array = mb_convert_encoding($array , $target_enc , $enc );
		}elseif( is_array( $array ) ){
			foreach( $array as $key => $value ){
				$ret[$key] = $this->convertCharsetRecursive( $value , $target_enc );
			}
			$array = $ret ;
		}elseif( empty( $array ) ){
			$array = null ;
		}else{
			$this->error[]='Error convertCharsetRecursive, invalid type ,line->'.__LINE__;
		}
		return $array ;
	}
	function extractReceipt(){
		$hd = array('to','cc','bcc') ;
		$ret = array();
		foreach( $hd as $hdn ){
			foreach($this->{$hdn} as $addr ){
				$ret[] = $addr[$this->tokey['_ADDR']] ; 
			}
		}
		if( 0 === count( $ret ) ){
			return $this->errorGather('No Receipt' ,__LINE__) ;
		}else{
			$this->receipt = $ret ;
			return $this->errorGather();
		}
	}
	//------------------------------------------------------------------------
	// Attachment Routine
	//     attach - set to $this->attach array
	//        attach OneArray(1 array pattern array('path','attacheName'))  
	//        attach Singe (2 string pattern  ('path','attacheName') )
	//           attachFull - Base Routine  allattch routine call him
	// buildAttach - called buildBody method
	//------------------------------------------------------------------------

	//
	//
	//array('path_filename','attach_name','mime_type','target_charset','org_charset', );
	//
	//
	//

	function attach( $param , $add = false , $inline = null ){
		list( $stack , $this->attach ) = array( $this->attach , array() );
		if( !isset($inline) ){// ver0.7.2a
			$inline = $this->inline_mode ;
		}
		if(is_string($param)){
			$param = array($param);
		}
		$test = reset($param);
		if(!is_array($test)){
			$param = array( $param );
		}
		foreach($param as $par){
			$path_filename = isset($par[0]) ? $par[0] : null;
			$attach_name = isset($par[1]) ? $par[1] : null;
			$mime_type = isset($par[2]) ? $par[2] : null;
			$target_charset = isset($par[3]) ? $par[3] : null;
			$charset_org = isset($par[4]) ? $par[4] : null;
			$this->attachSingle( $path_filename , $attach_name , $mime_type , $inline , $target_charset , $charset_org );
		}

		if($add){
			$this->attach = array_merge( $stack , $this->attach );
		}
		return $this->errorGather() ;
	}

	// $path_filename is string
	function attachSingle( $path_filename , $attach_name = null , $mime_type = null , $inline = false , $target_charset = null , $charset_org = null ){

		$content_id = null;
		if( true === $inline  ){
			$content_id =$this->selectContentId(
				$attach_name
				,
				$path_filename
			);
		}

		if( is_string( $path_filename ) && empty( $attach_name ) ) {
			$attach_name = basename( $path_filename ) ;
		}
		$this->attachFull( 
			$path_filename , 
			$attach_name , 
			$content_id , 
			$mime_type , 
			$inline , 
			$target_charset , 
			$charset_org 
		);

	}

	function attachFull( $path_filename , $attach_name = null , $content_id = null , $mime_type = null , $inline = false , $target_charset = null , $charset_org = null ){

		$_att = array(array(
			'PATH'=>$path_filename,
			'NAME'=>$attach_name,
			'CONTENT-TYPE'=> $mime_type,
			'CONTENT-ID'=> $content_id,
			'_CHARSET'=> $target_charset,
			'_ORG_CHARSET'	=> $charset_org,
		));
		$this->attach = array_merge( $this->attach , $_att );
		$this->attach_already_build = false ;
		return $this->errorGather() ;
	}
	//--------------------------------------------------------
	// Build attachment one file , called by buildBody method
	// $one is array , no recursive ,must ['PATH'] element
	//--------------------------------------------------------
	function buildAttach( $one , $boundary , $inline){
		$ret_boundary = null;
		$ret_header = array();
		$ret_content = null;
		$one = array_change_key_case( $one , CASE_UPPER);
		if( !isset($one['NAME'] )){
			$one['NAME'] = basename( $one['PATH'] );
		}
		//Content-Type
		if( isset( $one['CONTENT-TYPE'] )){
			$type = $one['CONTENT-TYPE'];
		}elseif( 0 != preg_match( '/\.([^\.]+)$/' , $one['NAME'] , $matches )){
			$type = isset( $this->attach_ctype[strtolower($matches[1])] ) 
				? $this->attach_ctype[strtolower($matches[1])] : 'unkown';
		}elseif(0 != preg_match( '/\.([^\.]+)$/' , $one['PATH'] , $matches )){
			$type = isset( $this->attach_ctype[strtolower($matches[1])])
				? $this->attach_ctype[strtolower($matches[1])] : 'unkown';

			if( $this->auto_ext && 'unkown' != $type ){
				$one['NAME'] .= '.'.$matches[1];
			}

		}else{
			$type = 'unkown';
		}

		if( isset( $one['_CHARSET'] ) ){
			$charset = $one['_CHARSET'];
		}else{
			$charset = $this->charset_attach_filename;
		}
		if( isset( $one['_ORG_CHARSET'] ) ){
			$org_charset = $one['_ORG_CHARSET'];
		}else{
			$org_charset = null;
		}

		$filename = $this->mime_string( $one['NAME'] , $charset , $org_charset );

		//is Inline ?
		if( $inline ){
			$id = $this->makeContentId($one['CONTENT-ID']);
			$content_id =  '<' . $id . '>' ;
			$content_disposition = 'inline';//attachment for au?
		}else{
			$content_id =  null ;
			$content_disposition = 'attachement';
		}

		// do it need Disposition Heaer ?
		if( isset( $this->deco_kind ) && false===$this->deco_def[$this->deco_kind]['DISPOSITION']){
			$disposition = null;
		}else{
			$disposition = $content_disposition.';'.$this->LFC
				.' filename="'.$filename.'"'
			;
		}
			$ret_boundary = '--'.$boundary ; 
			$ret_header['Content-Type'] = $type.';'.$this->LFC
				. ' name="'.$filename.'"'
			;
			$ret_header['Content-Transfer-Encoding'] = 'base64' ;
			$ret_header['Content-Id'] = isset($content_id) ? trim( $content_id ) : null ;
			if(!empty($disposition)){
				$ret_header['Content-Disposition'] = $disposition;
			}
		if( !empty( $one['DIRECT'] ) ){
			$cont=$one['DATA'];
		}else{
			$path_filename = $this->attachPathFix( $one['PATH'] );
			if( !file_exists ( $path_filename )){
				$this->error[]='No attach file \''.$path_filename.'\' line->'.__LINE__;
				return false;
			}else{
				$cont=file_get_contents( $path_filename );
			}
		}
		$ret_content = trim(chunk_split(base64_encode($cont)));
		$this->attach_already_build = true;
		return array(
			'BOUNDARY' =>$ret_boundary ,
			'HEADER' =>$ret_header ,
			'CONTENT' =>$ret_content
		);

	}
	function selectContentId( $name = null , $path = null ){
			if(isset($name)){
				$content_id = $name;
			}elseif( isset($path) ){
				$content_id = basename($path);
			}else{
				$content_id = null ;
			}
		$fg = preg_match('/[^\x01-\x7E]/', $content_id);
		if( $fg && !empty($content_id) ){
			$this->errorGather('Content-id error , need Ascii',__LINE__);
		}
		return $content_id ;
	}
	function isSetCid( $array ){
		return  isset( $array['CONTENT-ID'] ) && ( '' !== $array['CONTENT-ID']  ) ;
	}
	function makeContentId( $id ){
		if( is_null( $this->after_id ) ){
			$fromaddr = isset($this->header['From'][0]) ? $this->extractAddr($this->header['From'][0]):null;
			$this->after_id = mt_rand() . '_'. str_replace(array('@','-','/','.'),'', $fromaddr .'_'. $this->xmailer.'_'.$this->version );
		}
		return str_replace(array('@','-','/','.'),'', 'id_'.$id ).'_@_'. $this->after_id ;
	}
	function replaceCid( $content ){
		if( !isset( $this->deco_kind ) ){
			return $content;
		}
		foreach($this->attach as $att){
			if( $this->isSetCid( $att ) ){
				$orig = preg_quote($att['CONTENT-ID'] ,'/');
				$rep = $this->makeContentId(  $att['CONTENT-ID'] );
				$content = preg_replace('/(<\s*img[^>]+src\s*=\s*"cid:)' . $orig . '("[^>]*>)/is','${1}'.$rep.'${2}' ,$content);
			}
		}
	return $content ;
	}
	function attachDirect( $attach_name , $data , $add = false , $mime_type = null , $content_id = null , $target_charset = null , $charset_org = null){
		$_att=array();
		$_att[0]['DIRECT'] = true;
		$_att[0]['DATA'] = $data;
		$_att[0]['PATH'] = null;
		$_att[0]['NAME'] = $attach_name ;
		$_att[0]['MIME_TYPE'] = $mime_type ;
		$_att[0]['CONTENT-ID'] = $content_id ;
		$_att[0]['_CHARSET'] = $target_charset;
		$_att[0]['_ORG_CHARSET'] = $charset_org;
		if( $add ){
			$this->attach = array_merge( $this->attach , $_att );
		}else{
			$this->attach = $_att ;
		}
		return $this->errorGather() ;
	}
	function attachPathFix( $path_filename ){
		$temp = substr($path_filename,0,1);
		if( '/' != $temp && '\\' != $temp ){
			return  $this->attach_path . $path_filename;
		}
		return $path_filename;
	}

	//---------------------------------------
	//
	// Inteligent Multibyte Wordwrap
	//
	//---------------------------------------
	function mbWordwrap( $word , $length ){
		if( !is_numeric( $length ) ){
			$length = $this->wordwrap_length;
		}
		if( 1 > $length ){
			$this->error[]='Wordwrap length illegal , need more than 1 line->'.__LINE__;
		}
		$ret = array();
		list( $word , $LFC ) = $this->clean( $word );
		$lines = explode( $LFC , $word ) ;
		foreach($lines as $line){
			$ret []= $this->mbWordwrapLine( $line , $length );
		}
		return implode( $this->LFC , $ret );
	}
	function mbWordwrapLine( $line , $length ){
		$skip = false;
		if( 0 != count( $this->wrap_except ) ){
			foreach($this->wrap_except as $word => $begin_flag ){
				$fg = strpos( $line , $word );
				if( ( ( 0 === $fg ) && $begin_flag) || ( ( false !== $fg ) && !$begin_flag) ){
					$skip = true;
				}
			}
		}
		$enc = mb_detect_encoding( $line );
		$len = mb_strlen( $line , $enc );
		if ( ( $len <= $length )  || $skip ) {
			return $line;
		}

		if( $this->wrap_width ){
			$method = 'widthSubStr';
		}else{
			$method = 'defMbSubStr';
		}

		$ret = array();
		$ln = $length;
		$j = 0;
		for( $i=0; $i < $len ; $i += $ln ){
			list( $r , $ln ) = $this->{$method}( $line , $i , $length , $enc );
			if( 0 !== $j ){
				list( $r , $no_top , $flag )=$this->mbProhibitTop( $r , $enc );
				if( $flag ){
					$ret[$j-1] .= $no_top ;
					$i += mb_strlen( $no_top , $enc );
					list( $r , $ln ) = $this->{$method}( $line , $i , $length , $enc );
				}
			}
			if( ( $i + $ln ) < $len  ){
				list( $_r , $ret_count , $flag )=$this->mbProhibitEnd( $r , $enc );
				if( $flag && ( $ret_count < ($length-1) ) ){
					$i -=  $ret_count;
					$r = $_r;
				}
			}
			$ret [$j++]= $r ;
		}
		return implode( $this->LFC , $ret ) ;
	}

	function defMbSubStr( $line , $start , $length , $enc ){
		return array( mb_substr( $line , $start , $length , $enc ) ,$length );
	}
	function widthSubStr( $line , $start , $length , $enc ){
		$ret = array();
		$max = mb_strlen( $line , $enc ) ;
		$target = mb_substr( $line , $start , $length , $enc ) ;
		$point = $start + $length;
			// mb_strwidth's lengh means ascii width
		while( ( mb_strwidth( $target , $enc ) <= ( $length-1 ) * $this->mb_strwidth_magni ) && ( $point < $max ) ){
			$target .= mb_substr( $line , $point++ , 1 , $enc ) ;
		}
		return array( $target , mb_strlen( $target , $enc ) ) ;
	}

	function mbProhibitTop(  $line , $enc ){
		$flag = false;
		$ret = null;
		$len = mb_strlen( $line , $enc );
		$count = 0 ;
		do{
			$top = mb_substr( $line , $count++ , 1 , $enc );
		}while( isset($this->wrap_prohibit[$top]) && $this->wrap_prohibit[$top] && ( abs($count) < $len  ) );
		-- $count ;
		if( 0 < $count ){
			$ret = mb_substr( $line , 0 , $count , $enc );
			$line = mb_substr( $line , $count , $len - $count , $enc );
			$flag = true;
		}
		return array( $line , $ret , $flag );
	}
	function mbProhibitEnd( $line , $enc ){
		$flag = false;
		$len = mb_strlen( $line , $enc );
		$count = 0 ;
		do{
			$end = mb_substr( $line , --$count , 1 , $enc );
		}while( isset($this->wrap_prohibit[$end]) && !$this->wrap_prohibit[$end] && ( abs($count) < $len  ) );
		$count = abs( ++$count );
		if( 0 < $count ){
			$line = mb_substr( $line , 0 , $len - $count , $enc );
			$flag = true;
		}
		return array( $line , $count , $flag );
	}
	//------------------------
	// utility
	//------------------------
	function issetContent( $array ){
		if( !isset( $array ) ){
			return false ;
		}
		if( isset( $array['CONTENT'] ) ){
			return true ;
		}
		if( isset( $array ) && is_string( $array ) ){
			return true ;
		}
		return false ;
	}
	function keyUpper( $array ){
		$up_array = array_change_key_case( $array , CASE_UPPER );
		$link = $this->qdmail_array_combine( array_keys( $up_array ) , array_keys( $array ));
		return array( $up_array , $link );
	}
	function qdmail_array_combine( $keys , $values ){//for php4
		if( !is_array( $keys ) || !is_array( $values ) ){
			$this->error[]='array_conbine needs array line =>'.__LINE__;
		}
		reset( $values );
		foreach( $keys as $key ){
			$ret[$key] = array_shift( $values ) ;
		}
		return $ret;
	}
	function qdmail_md( $col = null , $start = 33 , $end = 126 ){
		if( is_null( $col ) ){
			$col = $this->boundary_base_degit ;
		}
		$ret = null;
		for( $i = 0 ; $i < $col ; $i++){
			$ret .= chr( mt_rand( $start , $end ) ) ;
		}
		return $ret;
	}

	function clean( $content ){
		if($this->smtp){
			$LFC = $this->LFC;
		}else{
			$LFC = "\n";
		}
		return array(rtrim( preg_replace( '/\r?\n/' , $LFC , $content ) ),$LFC);
	}
	function quotedPrintableEncode( $word ){
		if(empty($word)){
			return $word;
		}
		$lines = preg_split("/\r?\n/", $word);
		$out = array() ;
		foreach ($lines as $line){
			$one_line = null ;
			for ($i = 0; $i <= strlen($line) - 1; $i++){
				$char = substr ( $line, $i, 1 );
				$ascii = ord ( $char ); 
				if ( (32 > $ascii) || (61 == $ascii) || (126 < $ascii) ){
					$char = '=' . strtoupper ( dechex( $ascii ) );
				}
				if ( ( strlen ( $one_line ) + strlen ( $char ) ) >= 76 ){
					$out[]= $one_line . '=' ;
					$one_line = null ;
				}
				$one_line .= $char;
			}
			$out[]= $one_line  ;
		}
	return implode( $this->LFC , $out );
	}
	function time(){
		list($start_usec, $start_sec) = explode(' ', $this->start_time );
		list($end_usec, $end_sec) = explode(' ', microtime() );
		return ($end_sec - $start_sec) + (float) ($end_usec - $start_usec);
	}
	//------------------
	// error
	//------------------
	function errorStatment( $type = false , $lf = null ){
		if( $type ){
			return $this->errorRender( $this->error_stack , $lf , false );
		}else{
			return $this->error_stack;
		}
	}

	function errorRender( $error = null , $lf = null , $display = true ){
		if( is_null( $error ) ){
			$error = $this->error;
		}
		if( is_null( $lf ) ){
			$lf = $this->log_LFC ;
		}
		if( !is_array( $error ) ){
			$error = array( $error );
		}
		$out = null ;
		foreach($error as $mes){
			$out .= $this->name . ' error: ' . trim( $mes ) . $lf ;
		}
		if( $this->error_display && $display ){
			$_out = str_replace( $lf ,'<br>' . $lf , $out );
			echo  $_out ;
		}
		return $out ;
	}

	function errorGather( $message = null , $line = null){

		if( !is_null( $message ) ){
			if( !is_null( $line ) ){
				$message .= ' line -> '.$line;
			}
			if(0 === count( $this->error_stack )){
				$this->error[] = 'Version '.$this->version;
			}
			$this->error[] = $message ;
		}elseif( 0 === count( $this->error ) ){
			return true;
		}

		$er = $this->errorRender();
		$this->error_stack = array_merge( $this->error_stack , $this->error );
		$this->error = array();

		if( !$this->logWrite( 'error' ,  $er ) ){
			$this->error_stack = array_merge( $this->error_stack , $this->error );
		}
		return false;
	}

	function log( $mes = null ){
		if( is_null( $mes )){
			$addrs = $this->done() ;
			$this->done = array();
			$spacer = null;
			if( 0 != count( $addrs ) ){
				$mes .= 'Send Success: '.implode(' ',$addrs) ;
				$spacer =  $this->log_LFC ;
			}
			$addrs = $this->undone() ;
			$this->undone = array();
			if( 0 != count( $addrs ) ){
				$mes .=  $spacer . 'Send failure: '.implode(' ',$addrs);
			}
		}
		return $this->logWrite( null , trim( $mes ) ) ;
	}

	function logWrite( $type , $message ){

		$tp = ('error' == $type) ? false:true;
		$level		=	$tp ? $this->log_level:$this->errorlog_level;
		if( 0 == $level ){
			return true;
		}

		$filename	=	$tp ? $this->log_filename:$this->errorlog_filename;
		$path		=	$tp ? $this->log_path:$this->errorlog_path;
		$ap			=	$tp ? $this->log_append:$this->errorlog_append;

		$fp = fopen( $path.$filename , $ap );
		if( !is_resource( $fp ) ){
			$this->error[]='file open error at logWrite() line->'.__LINE__;
			return false;
		}
		$spacer = $tp ? ' ' : $this->log_LFC ;
		fwrite( $fp , 
			date( $this->log_dateformat )
			. $spacer
			. trim( $message )
			. $this->log_LFC
		);
		if( $level > 1 ){
			fwrite( $fp , trim( $this->header_for_smtp ) . $this->log_LFC );
		}elseif( $level > 2 ){
			fwrite( $fp , $this->log_LFC .  $this->content_for_mailfunction  . $this->log_LFC );
		}
		fclose( $fp ) ;
		return true ;
	}

	function errorSpecify( $func , $line , $add_message = null){
		return $this->errorGather($add_message.'User Specify Error in Method of \''.$func.'\'' , $line ) ;
	}
	//-------------------------------------------
	// MIME Content-type def
	//-------------------------------------------
	var $attach_ctype=array(
		'txt'=>'text/plain',
		'csv'=>'text/csv',
		'xml'=>'text/xml',
		'htm'=>'text/html',
		'html'=>'text/html',
		'gif'=>'image/gif',
		'jpg'=>'image/jpeg',
		'jpeg'=>'image/jpeg',
		'png'=>'image/png',
		'tif'=>'image/tiff',
		'tiff'=>'image/tiff',
		'bmp'=>'image/x-bmp',
		'ps'=>'appilcation/postscript',
		'eps'=>'appilcation/postscript',
		'epsf'=>'appilcation/postscript',
		'ai'=>'application/postscript',
		'zip'=>'application/zip',
		'lzh'=>'application/x-lzh',
		'tar'=>'application/x-tar',
		'gzip'=>'application/x-tar',
		'cpt'=>'application/mac-compactpro',
		'doc'=>'application/msword',
		'xls'=>'application/vnd.ms-excel',
		'ppt'=>'application/vnd.ms-powerpoint',
		'rtf'=>'application/rtf',
		'pdf'=>'application/pdf',
		'css'=>'application/css',
		'au'=>'audio/basic',
		'rpm'=>'audio/x-pn-realaudio-plugin',
		'swa'=>'application/x-director',
		'mp3'=>'audio/mpeg',
		'mp4'=>'audio/mp4',
		'wav'=>'audio/x-wav',
		'midi'=>'audio/midi',
		'avi'=>'vide/x-msvideo',
		'mpeg'=>'video/mpeg',
		'mpg'=>'video/mpeg',
		'wmv'=>'video/x-ms-wmv',
		'flash'=>'application/x-shockwave-flash',
		'mmf'=>'application/x-smaf ',	//softbank chakumero
		'smaf'=>'application/x-smaf',	//softbank chakumero
		'hdml'=>'text/x-hdml',			// HDML au,docomo
		'3gpp2'=>'video/3gpp2',			// au chaku-uta,ez-movie
		'3g2'=>'video/3gpp2',			// au chaku-uta,ez-movie
		'amc'=>'video/3gpp2',			// au chaku-uta,ez-movie
		'kjx'=>'application/x-kjx',		// au ez-apri
		'3gpp'=>'video/3gpp',			// docomo chaku-uta,movie
		'3gp'=>'video/3gpp',			// docomo chaku-uta,movie
		'amr'=>'video/3gpp', 			// docomo chaku-uta,movie
		'asf'=>'video/3gpp',			// docomo chaku-uta,movie
		'jam'=>'application/x-jam',		// docomo i-apri
		'jar'=>'application/java-archive',	// java apri
		'jad'=>'text/vnd.sun.j2me.app-descriptor',	// java apri
		'exe'=>'application/octet-stream',
		'khm'=>'application/x-kddi-htmlmail',// au decoration mail template
		'dmt'=>'application/x-decomail-template',// nttdocomo decoration mail template
		'hmt'=>'application/x-htmlmail-template',// softbank decoration mail template
	);

	//-------------------------------
	// Debug
	//-------------------------------
	function debug( $level=null ){
		if( is_null( $level ) || !is_numeric($level) ){
			return $this->debug;
		}
		$this->debug = $level ;
		return true;
	}
	function debugEchoLine(){
		$vars = func_get_args();
		$this->debugEcho( false , $vars );
	}
	function debugEchoLf(){
		$vars = func_get_args();
		$this->debugEcho( true , $vars );
	}
	function debugEcho( $lf , $vars = null ){
		static $already_header = false;
		static $already_footer = false;
		if( 1 > $this->debug ){
			return;
		}

		if( true === $this->debug_echo_charset ){
			$this->debug_echo_charset = $this->charset_content ;
		}

		if( !$already_header ){
			$head='<html><head><meta http-equiv="content-type" content="text/html; charset='.$this->debug_echo_charset.'"></head><body>';
			echo $head ;
			$already_header = true ;
		}
		if( $already_header && ( 'END' === $lf ) && !$already_footer){
			$foot ='</body></html>';
			echo $foot;
			$already_footer = true;
			return ;
		}
		$out = null;
		if( !is_array( $vars ) ){
			$vars =array( $vars );
		}
		foreach($vars as $var){
			$_out = print_r( $var , true ) ;
			$enc = mb_detect_encoding( $_out );
			if( strtoupper( $this->qdmail_system_charset ) !== strtoupper( $enc ) ){
				$_out = mb_convert_encoding( $_out , $this->qdmail_system_charset , $enc );
			}
			$out .=  $_out  . $this->LFC;
		}
		$spacer = $this->log_LFC ;
		if( !$lf ){
			$out = preg_replace("/\\r?\\n/",' ',$out);
			$spacer = null ;
		}

		echo "<pre>";
		$out = $this->name . ' Debug: ' . $spacer . trim( $out );
		$enc = mb_detect_encoding( $out );
		if( strtoupper(mb_internal_encoding()) !== strtoupper( $enc ) ){
			$out = mb_convert_encoding($out,mb_internal_encoding(),$enc);
		}
		$out = htmlspecialchars( $out , ENT_QUOTES , mb_internal_encoding() );
		echo  mb_convert_encoding( $out , $this->debug_echo_charset , mb_internal_encoding() );
		echo "</pre>";

	}
	function debugReport( $var = null ){
		if( is_null( $var ) ){
			return $this->debug_report;
		}
		if( is_bool( $var ) ){
			$this->debug_report = $var;
			return true;
		}
		if( 'FILE' !== $var ){
			return $this->errorSpecify(__FUNCTION__,__LINE__);
		}

		$fg= true;
		$cont = print_r( $this , true );
		$cont .= print_r( $_SERVER , true);
		$date = date("Y_m_d_H_i_s");
		$out = <<<EOF
Debug Report
date: {$date}
name: {$this->name}
version: {$this->version}

{$cont}
EOF;

			$filename = $this->debug_report_path . $this->name.'_debug_report_'.date("Y_m_d_H_i_s") . '.txt';
		if($fp = fopen( $filename , 'w' )){
			fwrite( $fp , $out );
			fclose( $fp );
		}else{
			$this->error[] = 'Can not open file \'' . $filename . '\' line-> ' . __LINE__ ;
			$fg = false;
		}
		return $fg;
	}
	//--
	// this path like this, /home/foo/bar/  or c\:htdocs\foo\bar\ or ./foo/bar/
	// do not forget the last '/' or '\'
	//--
	function debugReportPath( $path = null ){
		if( is_null( $path ) ){
			return $path;
		}
		if( empty( $path ) ){
			$this->debug_report_path = './';
			return true;
		}
		if( is_string( $path ) ){
			$this->debug_report_path = $path;
			return true ;
		}
		return $this->errorSpecify(__FUNCTION__,__LINE__);
	}

	function sendBySmtp( $obj = null ){
		if( !is_null( $obj ) ){
			 $this->smtp_object = $obj;
		}
		if( !isset( $this->smtp_object ) || !is_object( $this->smtp_object ) ){
			if( false === ( $this->smtp_object = $this->smtpObject() ) ){
				return $this->errorGather('SMTP Object make \'new\' error',__LINE__);
			}
		}
		$this->smtp_server = array_change_key_case( $this->smtp_server , CASE_UPPER );

		if( !isset( $this->smtp_server['HOST'] ) ){
			$this->smtp_server = array_merge( $this->smtp_server , $this->smtp_object->server() );
		}
		if( !isset( $this->smtp_server['HOST'] ) ){
			return $this->errorGather('No exist SMTP\'s Settings',__LINE__);
		}
		$this->smtp_server['CONTINUE'] = true;

		if( !$this->smtp_object -> server( $this->smtp_server )){
			return $this->errorGather('SMTP Object initialize error',__LINE__);
		}
		if( $this->smtp_loglevel_link ){
			$this->smtp_object -> logLevel( $this->log_level );
			$this->smtp_object -> errorlogLevel( $this->errorlog_level );
		}
		$this->smtp_object -> to( $this->receipt );
		$this->smtp_object -> data( $this->header_for_smtp . $this->LFC . $this->content_for_mailfunction );
		return $this -> smtp_object -> send();
	}
	//------------------------------------------
	// expecting Override on the other FrameWork
	//------------------------------------------
	function & smtpObject( $null = false ){
		if(is_null($null)){
			$this->smtp_object = null;
			return true;
		}
		if( isset( $this->smtp_object ) && is_object( $this->smtp_object ) ){
			return $this->smtp_object;
		}
		if( !class_exists ( 'Qdsmtp' ) && file_exists( 'qdsmtp.php' ) ){
			require_once( 'qdsmtp.php' );
		}elseif( !class_exists ( 'Qdsmtp' ) && !file_exists( 'qdsmtp.php' )){
			return $this->errorGather('Plese load SMTP Program - Qdsmtp http://hal456.net/qdsmtp',__LINE__);
		}
		$this->smtp_object = & new Qdsmtp();
		return $this->smtp_object;
	}
	function setSmtpObject( & $obj ){
		if(is_object($obj)){
			$this->smtp_object = & $obj;
			return true;
		}else{
			return false;
		}
	}

}//the QdmailBase

class QdmailUserFunc extends QdmailBase{

	function __construct( $param = null ){
		parent::__construct( $param );
	}

	function validateAddr( $addr ){
		// validate mail-addrress routine
		// if error then $this->error[]='error message';
		return true;
	}

	function stripCrlf( $word ){
		if( $this->force_change_charset ){
			$enc = mb_detect_encoding( $word ) ;
			$word = mb_convert_encoding( $word , $this->qdmail_system_charset , $enc );
		}
		$word = preg_replace( '/\r?\n/i' , '' , $word );
		if( $this->force_change_charset ){
			$word = mb_convert_encoding( $word , $enc , $this->qdmail_system_charset );
		}
		return $word ;
	}

}

class Qdmail extends QdmailUserFunc{

	var $name ='Qdmail';

	function Qdmail( $param = null ){
		if( !is_null($param)){
			$param = func_get_args();
		}
		parent::__construct( $param );
	}
}

//-------------------------------------------
// CakePHP Component
//-------------------------------------------
class QdmailComponent extends QdmailUserFunc{

	var $view_dir	= 'email';
	var $layout_dir	= 'email';
	var $layout		= 'default';
	var $template	= 'default';
	var $view		= null;

	function QdmailComponent( $param = null ){
		if( !is_null($param)){
			$param = func_get_args();
		}
		parent::__construct( $param );
	}

	function startup(&$controller) {
		$this->Controller =& $controller;
		if( defined( 'COMPONENTS' ) ){
			$this->logPath(COMPONENTS);
			$this->errorlogPath(COMPONENTS);
		}
		return;
	}
	//----------------------------
	// Override Parent Method
	//----------------------------
	function & smtpObject(){
		if( isset( $this->Qdsmtp ) && is_object( $this->Qdsmtp ) ){
			return $this->Qdsmtp;
		}

		if( !class_exists ( 'QdsmtpComponent' ) ){
			if( !$this->import( 'Component' , 'Qdsmtp' ) ){
				return $this->errorGather('Qdmail<->CakePHP Component Load Error , the name is Qdsmtp',__LINE__);
			}
		}
		$this->Qdsmtp = & new QdsmtpComponent();
		if( !is_object( $this->Qdsmtp ) ){
				return $this->errorGather('Qdmail<->CakePHP Component making Instance Error , the name is QdsmtpComponent',__LINE__);
		}
		$this->Qdsmtp -> startup( $this->Controller );
		return $this->Qdsmtp;
	}
	//----------------------------
	// Cake Interface
	//----------------------------
	function import( $kind , $name ){
		if( 1.2 > (float) substr(Configure::version(),0,3) ){
			$function_name = 'load' . $kind ;
			if( function_exists( $function_name ) ){
					return $function_name( $name ) ;
			}else{
					return $this->errorGather('Qdmail<->CakePHP ' .$kind .' Load Error , the name is \'' . $name . '\'',__LINE__);
			}
		}else{
			return App::import( $kind , $name ) ;
		}
	}
	function cakeText( $content , $template = null , $layout = null , $org_charset = null , $target_charset = null , $enc = null , $wordwrap_length = null ){

		$this->template = is_null( $template ) ?  $this->template : $template ;
		$this->layout   = is_null( $layout )   ?  $this->layout : $layout ;

		list( $cont , $target_charset , $org_charset ) = $this->cakeRender( $content , 'TEXT' , $org_charset = null , $target_charset );
		return $this->text(  $cont , $wordwrap_length , $target_charset , $enc , $org_charset );
	}
	function cakeHtml( $content , $template = null , $layout = null , $org_charset = null , $target_charset = null , $enc = null ){

		$this->template = is_null( $template ) ?  $this->template : $template ;
		$this->layout   = is_null( $layout )   ?  $this->layout : $layout ;

		list( $cont , $target_charset , $org_charset ) = $this->cakeRender( $content , 'HTML' , $org_charset = null , $target_charset );
		return $this->html(  $cont , null , $target_charset , $enc , $org_charset  );
	}
	function cakeRender( $content , $type , $org_charset = null , $target_charset = null){

		if( is_null( $target_charset ) ){
			$target_charset = $this->qdmail_system_charset;
		}
		if( !class_exists ( $this->Controller->view ) ){
			if( !$this->import( 'View' , $this->view ) ){
				return $this->errorGather('Qdmail<->CakePHP View Load Error , the name is \''.$this->view.'\'',__LINE__);
			}
		}
		$type = strtolower( $type );
		$view = & new $this->Controller->view( $this->Controller , false );
		$view->layout = $this->layout;
		$mess = null;
		$content = $view->renderElement( $this->view_dir . DS . $type . DS . $this->template , array('content' => $content ) , true );
		if( 1.2 > (float) substr(Configure::version(),0,3) ){
			$view->subDir = $this->layout_dir . DS . $type . DS ;
		}else{
			$view->layoutPath = $this->layout_dir . DS . $type;
		}
		$mess .= $view->renderLayout( $content ) ;

		if( is_null( $org_charset ) ){
			$org_charset = mb_detect_encoding( $mess );
		}
		if( $org_charset !== $target_charset ){
			$mess = mb_convert_encoding( $mess , $target_charset , $org_charset );
		}
		return array( $mess , $target_charset , $org_charset );
	}
	function CakePHP( $param ){
		$param = array_change_key_case( $param , CASE_LOWER );
		extract($param);
		if( isset($type) || 'HTML' == $type ){
			$type ='cakeHtml';
		}else{
			$type = 'cakeText';
		}
		return $this->{$type}( isset($content) ?  $content:null, isset($template) ?  $template:null , isset($layout) ?  $layout:null , isset($org_charset) ?  $org_charset: null , isset($target_charset) ? $target_charset:null , isset($enc) ?  $enc:null , isset($wordwrap_length) ? $wordwrap_length:null );
	}
}?>
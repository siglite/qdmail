<?php
/**
 * Qdmail ver 0.7.4a
 * E-Mail for multibyte charset
 *
 * PHP versions 4 and 5 (PHP4.3 upper)
 *
 * Copyright 2008, Spok in japan , tokyo
 * hal456.net/qdmail    :  http://hal456.net/qdmail/
 * & CPA-LAB/Technical  :  http://www.cpa-lab.com/tech/
 * Licensed under AGPL3v License
 *
 * @copyright		Copyright 2008, Spok.
 * @link			http://hal456.net/qdmail/
 * @version			0.7.4a
 * @lastmodified	2008-04-23
 * @license			http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * 
 * Qdmail is sending e-mail library for multibyte language ,
 * easy , quickly , usefull , and you can specify deeply the details.
 * Copyright (C) 2008   spok 
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/
class QdmailBase{

	//----------
	// sysytem 
	//----------
	var	$name			= 'Qdmail';
	var	$version		= '0.7.4a';
	var	$xmailer		= 'PHP-Qdmail';
	var $license 		= 'AGPLv3';
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
		'_ADDR' => 'ADDR',
		'_NAME' => 'NAME',
	);
	//--------------
	// content_id
	//--------------
	var	$content_id_include_attach_path = false ;
	var	$content_id_only_filename = true ;
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
	var	$wordwrap_allow	= true;
	var	$wrap_prohibit_allow	= true;
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
	var	$deco_def		=array(
		array(
			'OPTION_NAME'	=> array( 'MHTML' , 'INLINE' , 'PC' ),
			'STRUCTURE'		=> 1,
			'_CHARSET'		=> null ,
			'ENC_TEXT'		=> null,
			'ENC_HTML'		=> null,
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
			'ENC_HTML'		=> '7bit',
			'HTML_EXTERNAL'	=> array('this','stripCrlf') ,
			'DISPOSITION'	=> true ,
		),
		array(
			'OPTION_NAME'	=> array( 'EM','EMOBILE' ,'EMNET'),
			'STRUCTURE'		=> 2,
			'_CHARSET'		=> 'iso-2022-jp',
			'ENC_TEXT'		=> '7bit',
			'ENC_HTML'		=> '7bit',
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
				'image'		=> 1,
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
						'image'		=>	1,
						'OMIT'		=>	true,
					),
					'plain'		=>	1,
					'OMIT'		=>	true,
				),
				'image'		=> 0,
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
					'image'		=>	1,
					'OMIT'		=>	false,
				),
				'OMIT'		=>	false,
				'image'		=> 0,
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
				'image'		=>	1,
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
				'image'		=>	1,
				'OMIT'		=>	false,
			),
			'image'		=> 0,
			'OMIT'		=>	false,
			),

	);
	var	$deco_judge		= array(
		'docomo.ne.jp'=>'DC',
		'softbank.ne.jp'=>'SB',
		'vodafone.ne.jp'=>'SB',
		'ezweb.ne.jp'=>'AU',
		'emnet.ne.jp'=>'EM',
//		'pdx.ne.jp'=>'WL',
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
	var $content		= null;
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
	var $smtp_param = array(
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
	var	$LFC				= "\r\n";
	var	$mta_option			= null ;
	var	$is_create			= false;
	var	$validate_addr  	= array('this','validateAddr');
	var	$boundary_base_degit= 2 ;
	var	$stack_construct	= null ;
	var $start_time			= null;
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
		$this->optionNameLink();
		$this->wordwrapProhibitConstruct();
	}
	//-------------------
	// Easy Base
	//-------------------
	function easy( $type , $toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null  , $filename = null , $attach_name = null , $deco_kind = null , $separate = false ){

		$this->toSeparate( $separate );
		if( 'TEXT' == strtoupper( $type ) || 'HTML' == strtoupper( $type ) ){
			$type=strtolower( $type );
		}else{
			$this->error[]='Illegal spcify \'type\' line-> '.__LINE__;
			return $this->errorGather();
		}
		if( !is_null( $deco_kind ) ){
			$this->deco_kind = $this->decoFix($deco_kind);
			$this->auto_deco_judge = false;
		}
		if( !is_array( $toaddr ) ){
			$this->to( $toaddr , $toname );
		}else{
			$toaddr = array_change_key_case( $toaddr , CASE_UPPER );
			if( !isset( $toaddr['TO'] ) ){
				$this->to( $toaddr , $toname );
			}else{
				$section = array('TO','CC','BCC');
				foreach( $section as $sec ){
					if( isset( $toaddr[$sec] ) ){
						$this->{strtolower( $sec )}( $toaddr[$sec] , null );
					}
				}
			}
		}
		if( !is_array( $fromaddr )){
			$this->from( $fromaddr , $fromname );
		}else{
			$fromaddr = array_change_key_case( $fromaddr , CASE_UPPER );
			if( !isset( $fromaddr['FROM'] ) ){
				$this->from( array( $fromaddr ) , null );
			}else{
				$this->from( array( $fromaddr['FROM'] ) , null );
				if( isset( $fromaddr['REPLY-TO'] ) ){
					$this->replyto( array( $fromaddr['REPLY-TO'] ) , null );
				}
			}
		}

		$this->subject( $subject );
		$this->{$type}( $content );

		if( isset( $filename ) ){
			$this->attach( $filename , $cid = $attach_name  , $add = false , $mime = null , $this->inline_mode );
		}

		return $this->send();
	}

	function easyText( $toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null , $filename = null , $attach_name = null){
		return $this->easy( 'text' ,$toaddr , $toname , $subject , $content , $fromaddr , $fromname  , $filename , $attach_name );
	}

	function easyHtml( $toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null  , $filename = null , $attach_name = null , $deco_kind = null ){
		return $this->easy( 'html' ,$toaddr , $toname , $subject , $content , $fromaddr , $fromname  , $filename , $attach_name  , $deco_kind );
	}

	function easyReplace( $toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null , $replace = null ){
		if( !is_null($replace) ){
			$this->replaceWord( $replace );
		}
		$this->simpleReplace( true );
		$this->easy( 'TEXT' , $toaddr , $toname , $subject , $content , $fromaddr , $fromname );
	}

	function easyDeco($toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null  ,  $filename = null , $deco_kind = null , $separate = true ){
		if(isset($filename)){
			$this->inline_mode=true;
		}
		$this->autoDecoJudge( true );
		return $this->easy( 'html' , $toaddr , $toname , $subject , $content , $fromaddr , $fromname  , $filename  , $attach_name = null , $deco_kind  , $separate );
	}

	function easyDecoRep($toaddr , $toname , $subject , $content , $fromaddr = null , $fromname = null  ,  $filename = null , $replace = null , $deco_kind = null , $separate = true ){
		if( !is_null( $replace )){
			$this->replaceWord( $replace );
		}
		$this->simpleReplace( true );
		return $this->easyDeco($toaddr , $toname , $subject , $content , $fromaddr , $fromname  ,  $filename  , $deco_kind  , $separate );
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
		'mb_strwidth_magni'	=> 'numeric' ,
		'log_dateformat'	=> 'numeric' ,
		'log_level'			=> 'numeric' ,
		'errorlog_level'	=> 'numeric' ,
		'smtp_param'			=> 'array' ,
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
						return $this->errorSpecify( __FUNCTION__ . '-' .$key , __LINE__ . $line );
					}elseif( true===$min ){
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
	function smtpParam( $array = null ){
		return $this->option( array( __FUNCTION__ => $array ) ,__LINE__, true , true );
	}
	//-----------------------------------------
	// Address and Name Keys change Opiton
	//-----------------------------------------
	function fieldName( $addr = null , $name = null ){
		if( is_null($addr) && is_null($name) ){
			return array( $this->tokey['_ADDR'] , $this->tokey['_NAME'] );
		}
		if( !is_null($addr) && is_array($addr) && 1 < count($addr) ){
			$_addr = array_shift( $addr ) ;
			$name = array_shift( $addr ) ;
			$addr = $_addr;
		}
		if( (!is_null($addr) && !is_string( $addr )) || !is_null($name) && !is_string($name) ){
			return $this->errorGather('Specify Error in fieldName',__LINE__);
		}
		$addr = is_null( $addr ) ? $this->tokey['_ADDR'] : $addr ;
		$name = is_null( $name ) ? $this->tokey['_NAME'] : $name ;
		$this->tokey = array(
			'_ADDR' => $addr,
			'_NAME' => $name,
		);
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
			$this->content_transfer_enc_text = $this->content_transfer_enc_html = $op ;
			return $this->charset( $array );
		}elseif(!is_null($op) && !is_string($op)){
			return $this->errorSpecify( __FUNCTION__ , __LINE__ );
		}

		if( is_array( $array ) ){
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
			if( isset( $this->deco_def[$this->deco_kind] ) ){
				return $this->deco_def[$this->deco_kind]['OPTION_NAME']['0'];
			}else{
				return $this->errorGather('Illegal Decoration Name' ,__LINE__) ;
			}
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
			return null;
		}
		$right = substr($addr , $start+1);
		$parts = explode('.',$right);
		$ct = count($parts);
		if( 3 > $ct ){
			return null;
		}
		$domain = $parts[$ct-3] . '.' . $parts[$ct-2] . '.' . $parts[$ct-1];

		if( isset( $this->deco_judge[$domain] ) ){
			return $this->decoFix($this->deco_judge[$domain]);
		}else{
			return null;
		}
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
	function addHeader( $header_name , $value ){
		if('REPLY-TO'==strtoupper($header_name)){
			$header_name = 'REPLYTO' ;
		}
		if(isset($this->addr_head_name[strtoupper($header_name)])){
			return $this->{strtolower($header_name)}( $value , null , true );
		}
		$this->header[$header_name] = $value ;
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
		if( !is_string( $cont ) ){
			return $this->errorGather('Body need String type' ,__LINE__) ;
		}
		$this->content[strtoupper($type)] = array(
			'CONTENT'=>$cont,
			'LENGTH'=>$length,
			'_CHARSET'=>$charset,
			'ENC'=>$enc,
			'_ORG_CHARSET'=>$org_charset,
		);
		return $this->errorGather() ;
	}

	function text( $cont , $length = null , $charset = null , $enc = null , $org_charset = null ){
		return $this->body('text', $cont , $length = null , $charset = null , $enc = null , $org_charset = null );
	}

	function html( $cont , $charset = null , $enc = null , $org_charset = null ){
		return $this->body('html', $cont , $length = null , $charset = null , $enc = null , $org_charset = null );
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
				foreach($addr as $ad){
						list( $_name , $_addr )=$this->_extractNameAddr( $ad );
						$ret[] = array( $this->tokey['_ADDR'] => $_addr , $this->tokey['_NAME'] => $_name );
				}

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
		$this->header['X-Mailer'] = $this->xmailer . ' ' . $this->version ;
		$this->header['license'] = $this->license ;
	}
	function send( $header = array() , $content = array() , $attach  = array() , $option = array() ){

$this->debugEchoLf($this->to);
		if( is_null( $this->start_time )){
			$this->start_time = microtime();
		}
		//----------------
		// analysis Option
		//----------------
		$option = array_change_key_case( $option , CASE_UPPER );
		// TO Separate ?
		if( isset( $option['TOSEPARATE'] ) ){
			$this->toSeparate( $option['TOSEPARATE'] );
		}
		// auto deco judge ?
		if( isset( $option['AUTO_DECO_JUDGE'] ) ){
			$this->autoDecoJudge( $option['AUTO_DECO_JUDGE'] );
		}
		// replace
		if( isset( $option['PEPLACE'] ) ){
			$this->replaceWord( $option['PEPLACE'] );
		}
		// deco mode
		if( isset( $option['DECO_KIND'] ) ){
			$this->deco_kind = $this->decoFix( $option['DECO_KIND'] );
		}elseif( !isset( $this->deco_kind ) && $this->auto_deco_judge ){
			$this->deco_kind = $this->decoJudge($this->to[0]);
		}
		list( $header , $link_hd )= $this->setAddr( $header ); // must need for toSeparate mode
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
					if( $this->sendBase( $header , $content , $attach , $option ) ){
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
			$fg = $this->sendBase( $header , $content , $attach , $option ) ;
			$this->is_create = false; // for next ver 0.7.4a
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

	function sendBase( $header = array(), $content = array() , $attach  = array() , $option = array() ){		// stack bcc for allways bcc
		unset( $stack_bcc ) ;
		if( 0 != count( $this->allways_bcc ) ){
			$stack_bcc = $this->bcc ;
			$this->bcc( $this->allways_bcc , null , true );
		}
		if( !$this->is_create ){
			$this->body = null;
			$this->after_id = null;
			$this->createMail( $header , $content , $attach , $option );
		}
		if( isset($option) && !empty($option) ){
			list( $option , $void ) = $this->keyUpper( $option );
		}

		$sendmail_option = isset($option['SENDMAIL']) ? $option['SENDMAIL'] : $this->mta_option ;
		$sendmail_option = isset($option['MTA']) ? $option['MTA'] : $this->mta_option ;
		if( empty( $sendmail_option ) ){
			$sendmail_option = null;
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
					, trim( $sendmail_option )
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
			if( ( !$this->smtp && empty( $header_upp[strtoupper($hdn)] ) ) || ( $this->smtp && empty( $header_upp[strtoupper($hdn)] ) && !isset( $this->smtp_param['FROM'] ) ) ){
				$must = $this->errorGather('Must Header is not exist \''.$hdn.'\'' ,__LINE__) ;
			}
		}
		return $must;
	}
	//-----------------------------------------------
	//  Create one mail
	//-----------------------------------------------
	function createMail( $header = array() , $content = array() , $attach  = array() , $option = array() ){
		$this->_charsetDefFix();

		if( 0 != count($header) ){
			list( $header , $link_hd )= $this->setAddr( $header );
			if( isset( $header[ 'SUBJECT' ] ) ){
				$this->subject( $header[ 'SUBJECT' ] );
				unset($header['SUBJECT']);
			}
		}
		if( !empty($content) ){

			if( !is_array($content) ){
				$temp = $content;
				$content= array();
				$content['TEXT']['CONTENT'] = $temp;
			}
			list( $content , $void )= $this->keyUpper( $content );
			if( isset($content['TEXT']) && is_string($content['TEXT'])){
				$this->text(
					$content['TEXT'],
					isset($content['LENGTH']) ? $content['LENGTH']:null,
					isset($content['_CHARSET']) ? $content['_CHARSET']:null,
					isset($content['ENC']) ? $content['ENC']:null,
					isset($content['_ORG_CHARSET']) ? $content['_ORG_CHARSET']:null
				);
			}elseif(isset($content['TEXT']) && is_array($content['TEXT'])){
				list( $content['TEXT'] , $void )= $this->keyUpper( $content['TEXT'] );
				$this->setContentArgs( 'TEXT' ,  $content['TEXT'] );
			}

			if( isset($content['HTML']) && is_string($content['HTML'])){
				$this->html(
					$content['HTML'],
					isset($content['_CHARSET']) ? $content['_CHARSET']:null,
					isset($content['ENC']) ? $content['ENC']:null,
					isset($content['_ORG_CHARSET']) ? $content['_ORG_CHARSET']:null
				);
			}elseif( isset($content['HTML']) && is_array($content['HTML'])){
				list( $content['HTML'] , $void )= $this->keyUpper( $content['HTML'] );
				$this->setContentArgs( 'HTML' ,  $content['HTML'] );
			}
		}
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
		if( empty($this->is_html) ){
			if( isset($this->content['HTML'] ) && isset( $this->content['TEXT'] )){
				$this->is_html = 'BOTH' ;
			}elseif( isset( $this->content['HTML'] ) && $this->auto_both ){
				$this->content['TEXT'] = array(
					'content'=>$this->htmlToText($this->content['HTML']['CONTENT'])
				);
				$this->is_html = 'BOTH';
			}elseif(isset( $this->content['HTML'] ) && !$this->auto_both ){
				$this->is_html = 'HTML';
			}else{
				$this->is_html = 'TEXT';
			}
		}
		// Addition Attachment
		if( 0 != count($attach)){
			$this->attach( $attach , null , true );
		}
		$this->debugEcholine( __FUNCTION__,__LINE__, 'this->deco_kind' , $this->deco_kind );
		// Select Body Structure
		if( empty( $this->attach ) || !isset( $this->deco_kind ) ){
			$structure_no = 0 ;
		}else{
			$structure_no = $this->deco_def[$this->deco_kind]['STRUCTURE'];
		}
		$body_structure = $this->buildBody( $this->structure[$structure_no] );

		$this->renderBody( $body_structure );//including Content-type Header

		// user added header
		if(!empty($header) && is_array($header)){
			foreach($header as $key => $value){
				$_header[$link_hd[$key]] = $value;
			}
			$this->header = array_merge($this->header,$_header);
		}
		$this->headerDefault();
		$this->renderHeader();
		$this->is_create=true;
	}
	//except Content-type,user option
	function buildHeader(){
		$this->header = array();
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
					$this->header[$this->addr_head_name[$section]][] = $one[$this->tokey['_ADDR']];
				}else{
					$this->header[$this->addr_head_name[$section]][] = $mime.' <'.$one[$this->tokey['_ADDR']].'>';
				}
			}
			if( !$many ){
			$this->header[$this->addr_head_name[$section]] = array( array_pop( $this->header[$this->addr_head_name[$section]] ) );
			}
		}
		if( !empty( $this->subject ) ){
			//replace
			if( $this->simple_replace ){
				$subj = $this->replace( $this->subject['CONTENT'] , $this->to[0] );
			}else{
				$subj = $this->subject['CONTENT'] ;
			}
			$this->header['Subject']=$this->mime_string(
				$subj ,
				isset($this->subject['_CHARSET']) ? $this->subject['_CHARSET']:$this->charset_header,
				isset($this->subject['_ORG_CHARSET']) ? $this->subject['_ORG_CHARSET'] : null
			);
		}
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
							if( ( 1 === $value ) && $this->isSetCid( $att ) ){
								$ret_cont[]= $this->buildAttach( $att , $boundary , true ) ;
							}elseif( ( 1 === $value ) ){
								$ret_cont[] = $this->buildAttach( $att , $boundary , false ) ;
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

	function renderBody( $body_structure ){

		if( ( 0 === count( $body_structure ) ) && !$this->body_empty_allow ){
			return $this->errorGather('Empty Body do not allowed. If you want to send empty Mail , use method -> bodyEmptyAllow(true)' ,__LINE__) ;

		}elseif( 0 === count( $body_structure ) ){
			$body_structure[0]['HEADER'] = array();
		}
		foreach( $body_structure[0]['HEADER'] as $key => $value){
			$this->header[$key]=$value;
			$body_structure[0]['BOUNDARY'] = null;
			$body_structure[0]['HEADER'] = array();
		}
		$this->content_for_mailfunction = preg_replace('/\\'.$this->LFC.'\\'.$this->LFC.'$/','',$this->finalize( $body_structure ));
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

		if(is_array($content)){
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
		}
		// fix crlf
		$content = $this->clean($content);
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
		if($org_char != $target_char){
			$content = mb_convert_encoding( $content , $target_char , $org_char );
		}
		$enc_upp = strtoupper($content_transfer_enc);
		if( 'BASE64' == $enc_upp ){
			$content = chunk_split( base64_encode( $content ));
		}elseif('QUOTED-PRINTABLE' == $enc_upp || 'QP' == $enc_upp ){
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
		if( ! preg_match( '/[^\w\s0-9\.]/' , $subject ) ){
			return trim(chunk_split($subject, 75, "\r\n "));
		}
		$enc = isset($org_charset) ? $org_charset:mb_detect_encoding($subject);
		$subject = mb_convert_encoding( $subject , $charset , $enc );
		$start = "=?" . $charset . "?B?";
		$end = "?=";
		$spacer = $end . "\r\n " . $start;

		$length = 75 - strlen($start) - strlen($end);
		$length = $length - ($length % 4);// base64 is each 4char convert
		$subject = base64_encode($subject);
		$subject = chunk_split($subject, $length, $spacer);
		$spacer = preg_quote($spacer);
		$subject = preg_replace("/" . $spacer . "$/", "", $subject);
		$subject = $start . $subject . $end;
		return $subject;
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
			return $this->errorGather('Illegal Mail Address' ,__LINE__) ;
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
					$array[$key] = mb_convert_encoding($value , $target_enc ,isset( $array['_ORG_CHARSET'] ) );
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
	function attach( $path_filename , $attach_name = null , $add = false , $mime_type = null , $inline = null , $target_charset = null , $charset_org = null ){

		list( $stack , $this->attach ) = array( $this->attach , array() );
		if( !isset($inline) ){// ver0.7.2a
			$inline = $this->inline_mode ;
		}
		if(is_string($path_filename)){
			$this->attachSingle( $path_filename , $attach_name , $mime_type , $inline , $target_charset , $charset_org );

		}elseif( is_array($path_filename) && isset( $path_filename['PATH']) ){
			$this->attachOnearray( $path_filename , $attach_name , $mime_type , $inline , $target_charset , $charset_org );

		}elseif( is_array($path_filename) && !is_array($attach_name) && !is_array($path_filename[0])){

			foreach( $path_filename as $name ){
				$content_id = $attache_name = basename($name);
				$this->attachSingle( $name , $attach_name , $mime_type , $inline , $target_charset , $charset_org );
			}

		}elseif( is_array($path_filename) && is_array($attach_name) ){
			foreach( $path_filename as $key => $path){
				$this->attachFull(
					$path ,
					$attach_name[$key] ,
					$inline ?  $attach_name[$key] : null,
					$mime_type , 
					$inline , 
					$target_charset, 
					$charset_org
				);
			}
		}elseif( is_array($path_filename) && is_array($path_filename[0])){
			foreach($path_filename as $name ){
				$this->attachOnearray( $name , $attach_name , $mime_type , $inline , $target_charset , $charset_org );
			}
		}
		if($add){
			$this->sttach = array_merge( $stack , $this->attach );
		}
		return $this->errorGather() ;
	}

	function attachOnearray( $path_filename , $attach_name = null , $mime_type = null , $inline = false , $target_charset = null , $charset_org = null ){

			list( $path_filename, $void ) = $this->keyUpper($path_filename);
			if( !isset( $path_filename['PATH'] ) ){
				$path_filename['PATH'] = $path_filename[0];
				$path_filename[$this->tokey['_NAME']] = isset($path_filename[1]) ? $path_filename[1]:$path_filename[0];
			}
			$content_id = null;
			if( true === $inline ){
				$content_id =$this->selectContentId(
					isset($path_filename[$this->tokey['_NAME']]) ? $path_filename[$this->tokey['_NAME']]:null ,
					isset($path_filename['PATH']) ? $path_filename['PATH']:null
				);
			}
				$this->attachFull(
					$path_filename['PATH'] , 
					isset($path_filename[$this->tokey['_NAME']]) ? $path_filename[$this->tokey['_NAME']]:basename($path_filename['PATH']) , 
					$content_id , 
					isset($path_filename['MIME_TYPE']) ? $path_filename['MIME_TYPE']:$mime_type , 
					$inline , 
					isset($path_filename['_CHARSET']) ? $path_filename['_CHARSET']:$target_charset , 
					isset($path_filename['_ORG_CHARSET']) ? $path_filename['_ORG_CHARSET']:$charset_org 
				);
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
			$this->tokey['_NAME']=>$attach_name,
			'MIME_TYPE'=> $mime_type,
			'CONTENT-ID'=> $content_id,
			'_CHARSET'=> $target_charset,
			'_ORG_CHARSET'	=> $charset_org,
		));
		$this->attach = array_merge( $this->attach , $_att );
		return $this->errorGather() ;
	}
	//--------------------------------------------------------
	// Build attachment one file , called by buildBody method
	// $one is array , no recursive ,must ['PATH'] element
	//--------------------------------------------------------
	function buildAttach( $one , $boundary , $inline = false ){ 
		$ret_boundary = null;
		$ret_header = array();
		$ret_content = null;

		$one = array_change_key_case( $one , CASE_UPPER);
		if( !isset($one[$this->tokey['_NAME']] )){
			$one[$this->tokey['_NAME']] = basename( $one['PATH'] );
		}
		//Content-Type
		if( isset( $one['CONTENT-TYPE'] )){
			$type = $one['CONTENT-TYPE'];
		}elseif( 0 != preg_match( '/\.([^\.]+)$/' , $one[$this->tokey['_NAME']] , $matches )){
			$type = isset( $this->attach_ctype[strtolower($matches[1])] ) 
				? $this->attach_ctype[strtolower($matches[1])] : 'unkown';
		}elseif(0 != preg_match( '/\.([^\.]+)$/' , $one['PATH'] , $matches )){
			$type = isset( $this->attach_ctype[strtolower($matches[1])])
				? $this->attach_ctype[strtolower($matches[1])] : 'unkown';

			if( $this->auto_ext && 'unkown' != $type ){
				$one[$this->tokey['_NAME']] .= '.'.$matches[1];
			}

		}else{
			$type = 'unkown';
		}

		if( isset( $one['_CHARSET'] ) ){
			$charset = $one['_CHARSET'];
		}else{
			$charset = $this->charset_attach_filename;
		}
		$filename = $this->mime_string( $one[$this->tokey['_NAME']] , $charset );

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
		$_att[0][$this->tokey['_NAME']] = $attach_name ;
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
		$word = $this->clean( $word );
		$lines = explode( $this->LFC , $word ) ;
		foreach($lines as $line){
			$ret []= $this->mbWordwrapLine( $line , $length );
		}
		return implode( $this->LFC , $ret );;
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
		return preg_replace('/\r?\n/',"\r\n",$content);
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
		$out = htmlspecialchars( $this->name . ' Debug: ' . $spacer . trim( $out ) , ENT_QUOTES , $this->qdmail_system_charset );
		echo  mb_convert_encoding( $out , $this->debug_echo_charset , $this->qdmail_system_charset );
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
		if( !class_exists ( 'Qdsmtp' ) && file_exists( 'qdsmtp.php' ) ){
			require_once( 'qdsmtp.php' );
		}elseif( !class_exists ( 'Qdsmtp' ) && !file_exists( 'qdmail.php' )){
			return $this->errorGather('Plese load SMTP Program - Qdsmtp http://hal456.net/qdsmtp',__LINE__);
		}
		$this->smtp_param = array_change_key_case( $this->smtp_param , CASE_UPPER );
		if( !isset( $this->smtp_param['HOST'] ) ){
			return $this->errorGather('No SMTP Settings',__LINE__);
		}
		if( !isset( $this->smtp_object ) || !is_object( $this->smtp_object ) ){
			$this->smtp_param['CONTINUE'] = true;
			$this->smtp_object = & new Qdsmtp( $this->smtp_param );
		}
		if( $this->smtp_loglevel_link ){
			$this->smtp_object -> logLevel( $this->log_level );
			$this->smtp_object -> errorlogLevel( $this->errorlog_level );
		}

		$this->smtp_object -> to( $this->receipt );
		$this->smtp_object -> data( $this->header_for_smtp . $this->LFC . $this->content_for_mailfunction );
		return $this -> smtp_object -> send();
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

class QdmailComponendt extends QdmailUserFunc{

	var $name = 'Qdmail';
	// comming soon !? for CakePHP
	// now construction 
	// if you want to use in CakePHP , change like follows
	// "class QdmailBase{" => "class QdmailBase extends Object{"
}?>
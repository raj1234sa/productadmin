<?php
/**
 * @package RxCore.Exception
 *
 * @version $Id: RInvalidPropertyMapException.php,v 1.1 2008-07-04 13:51:26 nitin Exp $ 
 *
 * Revision Histry:-
 * $Log: RInvalidPropertyMapException.php,v $
 * Revision 1.1  2008-07-04 13:51:26  nitin
 * *** empty log message ***
 *
 * Revision 1.1  2008-07-04 05:40:50  kruti
 * *** empty log message ***
 *
 * Revision 1.1  2007-06-16 11:07:35  kruti
 * *** empty log message ***
 *
 * Revision 1.2  2006-08-11 14:34:38  chintan
 * *** empty log message ***
 *
 * Revision 1.1  2005/11/07 15:14:40  nitin
 * no message
 *
 *
 */ 

/**
 * Invalid attributes map specified in control
 */
class RInvalidPropertyMapException extends Exception
{
	function __construct($Method)
	{
		parent::__construct("Invalid attributes map specified in control '$Method'");
	}
}
?>
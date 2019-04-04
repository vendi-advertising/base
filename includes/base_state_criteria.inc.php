<?php

use Vendi\BASE\Criteria\AlertGroupCriteria;
use Vendi\Shared\utils as vendi_utils;

/*******************************************************************************
** Basic Analysis and Security Engine (BASE)
** Copyright (C) 2004 BASE Project Team
** Copyright (C) 2000 Carnegie Mellon University
**
** (see the file 'base_main.php' for license details)
**
** Project Lead: Kevin Johnson <kjohnson@secureideas.net>
**                Sean Muller <samwise_diver@users.sourceforge.net>
** Built upon work by Roman Danyliw <rdd@cert.org>, <roman@danyliw.com>
**
** Purpose: routines to manipulate shared state (session information)
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/
/** The below check is to make sure that the conf file has been loaded before this one....
 **  This should prevent someone from accessing the page directly. -- Kevin
 **/
defined( '_BASE_INC' ) or die( 'Accessing this file directly is not allowed.' );

include_once("$BASE_path/includes/base_state_common.inc.php");




/* ***********************************************************************
 * Function: PopHistory()
 *
 * @doc Remove and restore the last entry of the history list (i.e.,
 *      hit the back button in the browser)
 *
 * @see PushHistory PrintBackButton
 *
 ************************************************************************/
function PopHistory()
{
   if ( $_SESSION['back_list_cnt'] >= 0 )
   {
      /* Remove the state of the page from which the back button was
       * just hit
       */
      unset($_SESSION['back_list'][$_SESSION['back_list_cnt']]);

      /*
       * save a copy of the $back_list because session_destroy()/session_decode() will
       * overwrite it.
       */
      $save_back_list = $_SESSION['back_list'];
      $save_back_list_cnt = $_SESSION['back_list_cnt']-1;

      /* Restore the session
       *   - destroy all variables in the current session
       *   - restore proper back_list history entry into the current variables (session)
       *       - but, first delete the currently restored entry and
       *              decremement the history stack
       *   - push saved back_list back into session
       */
      session_unset();

      if ( $GLOBALS['debug_mode'] > 2 )
         ErrorMessage("Popping a History Entry from #".$save_back_list_cnt);

      session_decode($save_back_list[$save_back_list_cnt]["session"]);
      unset($save_back_list[$save_back_list_cnt]);
      --$save_back_list_cnt;

      $_SESSION['back_list'] = $save_back_list;
      $_SESSION['back_list_cnt'] = $save_back_list_cnt;
   }
}

/* ***********************************************************************
 * Function: PushHistory()
 *
 * @doc Save the current criteria into the history list ($back_list,
 *      $back_list_cnt) in order to support the BASE back button.
 *
 * @see PopHistory PrintBackButton
 *
 ************************************************************************/
function PushHistory()
{
   if ( $GLOBALS['debug_mode'] > 1 )
   {
      ErrorMessage("Saving state (into ".$_SESSION['back_list_cnt'].")");
   }

   /* save the current session without the $back_list into the history
    *   - make a temporary copy of the $back_list
    *   - NULL-out the $back_list in $_SESSION (so that
    *       the current session is serialized without these variables)
    *   - serialize the current session
    *   - fix-up the QUERY_STRING
    *       - make a new QUERY_STRING that includes the temporary QueryState variables
    *       - remove &back=1 from any QUERY_STRING
    *   - add the current session into the $back_list (history)
    */
   if (isset($_SESSION['back_list'])) {
       $tmp_back_list = $_SESSION['back_list'];
   } else {
       $tmp_back_list = '';
   }

   if (isset($_SESSION['back_list_cnt'])) {
       $tmp_back_list_cnt = $_SESSION['back_list_cnt'];
   } else {
       $tmp_back_list_cnt = '';
   }

   $_SESSION['back_list'] = NULL;
   $_SESSION['back_list_cnt'] = -1;

   $full_session = session_encode();
   $_SESSION['back_list'] = $tmp_back_list;
   $_SESSION['back_list_cnt'] = $tmp_back_list_cnt;

   $query_string = CleanVariable(vendi_utils::get_server_value("QUERY_STRING"), VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER);
   if(vendi_utils::get_post_value('caller')){
      $query_string .= "&amp;caller=".vendi_utils::get_post_value('caller');
   }
   if(vendi_utils::get_post_value('num_result_rows')){
      $query_string .= "&amp;num_result_rows=".vendi_utils::get_post_value('num_result_rows');
   }
   if(vendi_utils::get_post_value('sort_order')){
      $query_string .= "&amp;sort_order=".vendi_utils::get_post_value('sort_order');
   }
   if(vendi_utils::get_post_value('current_view')){
      $query_string .= "&amp;current_view=".vendi_utils::get_post_value('current_view');
   }
   if(vendi_utils::get_post_value('submit')){
      $query_string .= "&amp;submit=".vendi_utils::get_post_value('submit');
   }

   //TODO: Validate this conversion from ereg_replace
   $query_string = preg_replace("/back=1&/", "", CleanVariable($query_string, VAR_PERIOD | VAR_DIGIT | VAR_PUNC | VAR_LETTER));

   ++$_SESSION['back_list_cnt'];
   $_SESSION['back_list'][$_SESSION['back_list_cnt']] =
          array ("SCRIPT_NAME"     => $_SERVER["SCRIPT_NAME"],
                 "QUERY_STRING" => $query_string,
                 "session"      => $full_session );

  if ( $GLOBALS['debug_mode'] > 1 )
  {
      ErrorMessage("Insert session into slot #".$_SESSION['back_list_cnt']);

      echo "Back List (Cnt = ".$_SESSION['back_list_cnt'].") <PRE>";
      print_r($_SESSION['back_list']);
      echo "</PRE>";
  }
}

/* ***********************************************************************
 * Function: PrintBackButton()
 *
 * @doc Returns a string with the URL of the previously viewed
 *      page.  Clicking this link is equivalent to using the browser
 *      back-button, but all the associated BASE meta-information
 *      propogates correctly.
 *
 * @see PushHistory PopHistory
 *
 ************************************************************************/
function PrintBackButton()
{
   if ( $GLOBALS['maintain_history'] == 0 )
      return "&nbsp;";

   $criteria_num = $_SESSION['back_list_cnt'] - 1;

   if ( isset($_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"]) )
     return "[&nbsp;<FONT><A HREF=\"".$_SESSION['back_list'][$criteria_num]["SCRIPT_NAME"].
            "?back=1&".
            $_SESSION['back_list'][$criteria_num]["QUERY_STRING"]."\">"._BACK."</A></FONT>&nbsp;]";
   else
     return "&nbsp;";
}

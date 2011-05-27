<?php
/**
 * FormExpress : Build forms for Zikula through a web interface
 *
 * @copyright (c) 2002 Stutchbury Limited, 2010 Chris Candreva
 * @Version $Id$
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package FormExpress
 *
 *
 * Origianally written by Philip Fletcher for PostNuke
 * Updated for Zikula API by Christopher X. Candreva
 *
 * LICENSE
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WIthOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * To read the license please visit http: *www.gnu.org/copyleft/gpl.html
 * ----------------------------------------------------------------------
 * Purpose of file:  FormExpress user display functions
 * ----------------------------------------------------------------------
 */
 
include_once( 'pnclass/FXSession.php' );
include_once( 'pnclass/FXCache.php' );

/**
 * The main function either displays the default form,
 * or the list of forms if no defauls is defined.
 */
function FormExpress_user_main()
{

    // Security check - important to do this as early as possible to avoid
    // potential security holes or just too much wasted processing.  For the
    // main function we want to check that the user has at least overview
    // privilege for some item within this component, or else they won't be
    // able to see anything and so we refuse access altogether.  The lowest
    // level of access for administration depends on the particular module, but
    // it is generally either 'overview' or 'read'
    if (!SecurityUtil::checkPermission('FormExpresss::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $default_form_id = pnModGetVar('FormExpress', 'default_form_id');
    if ( $default_form_id >0 ) {
        return ( pnModFunc ( 'FormExpress'
                           , 'user'
                           , 'display_form'
                           , array( 'form_id' => $default_form_id
			          , 'admin_mode' => false
                                  )
                           )
               );
    } else {
        return  pnModFunc ( 'FormExpress'
                           , 'user'
                           , 'view'
               );
    }
}

/**
 * view items
 * Show a user view of all defined forms.
 */
function FormExpress_user_view()
{
    // Security check - important to do this as early as possible.
    if (!SecurityUtil::checkPermission('FormExpresss::', '::', ACCESS_OVERVIEW)) {
        return LogUtil::registerPermissionError();
    }

    /* Get all forms via API */
    $items = pnModAPIFunc( 'FormExpress', 'user', 'getall' );

    if ($items == false) {
        $dom = ZLanguage::getModuleDomain('FormExpress');
        return LogUtil::registerError( __("No forms found."), 500) ;
    }

    /* Check permissions on each form, so we only show forms
     * for which the user has permission.
     */
    $forms = array();
    foreach ($items as $item) {
        if (SecurityUtil::checkPermission('FormExpresss::', "$item[form_name]::$item[form_id]", ACCESS_READ)) {
            $forms[] = $item;
        }
    }

    $render = pnRender::getInstance('FormExpress');
    $render->assign('forms', $forms);
    // Return the output that has been generated by this function
    return $render->fetch('formexpress_user_view.html');
}


/**
 * Display form with a header
 * Calls display_form with an option set
 */
function FormExpress_user_display($args)
{
    return FormExpress_user_display_form(array('showheaders' => true) );
}

/**
 * display the form without any menu/header/footer etc.
 */
function FormExpress_user_display_form($args)
{
    if (!SecurityUtil::checkPermission('FormExpresss::', '::', ACCESS_READ)) {
        return LogUtil::registerPermissionError();
    }

    $form_id = FormUtil::getPassedValue('form_id');
    if (!$form_id) $form_id = $args['form_id'];

    $render = pnRender::getInstance('FormExpress');
    if (isset($args['showheaders'])) {
        $render->assign('showheaders', $args['showheaders']);
    }
    //Get the form from the cache
    $fxCache = new FXCache();
    $form = $fxCache->getForm($form_id);

    $fxSession = new FXSession();
    // $form['user_data'] = $fxSession->getForm($form_id);
    $user_data = $fxSession->getForm($form_id);

    /**
     *  We need to know the relative position of the 'next' item, which 
     *  is not available in the format originally developed for the table format.
     *  We add it here.
     */
    $max = count($form['items']) - 1;
    /* Set first item position to be 'below' */
    $form['items'][0]['relative_position'] = 'below';
    $form['items'][0]['isfirst'] = true;
    $form['items'][$max]['islast'] = true;
    for ($i=0; $i<$max; $i++) {
      $item = &$form['items'][$i];
      $item['user_data'] = $user_data[$item['item_name']];
      $item['next_position'] = $form['items'][$i+1]['relative_position'];
      $item['Num'] = $i;
    }
    $render->assign('form', $form);

    // Return the output that has been generated by this function
    return $render->fetch('formexpress_user_display.html');

}


/** *****************************************************************************
 * This function is called for _every_ (user) form submit
 * It manages the session vars and calls to other functions.
 */
function FormExpress_user_submit_form() {

    // Confirm authorisation code.
    if (!pnSecConfirmAuthKey()) {
	$render = new pnRender('FormExpress');
	return $render->fetch('formexpress_user_submit_form_badkey.html');
    }

    $form_id = FormUtil::getPassedValue('form_id');

    //// Get all the form, item and input data ////

    //Get the form from the cache
    $fxCache = new FXCache();
    $form = $fxCache->getForm($form_id);

    $missing_value_list = false;
    $validation_result_list = false;

    //An array to hold the form results
    $user_data = array();
    //Load the array
    foreach($form['items'] AS $item) {
        if ( fe_itemHasValue($item['item_type'])) {

            //Get the input name
            $input_name = $item['item_name'].$form['input_name_suffix'];
            //!TODO Need to do something for SelectMultple when 
            //pnVarCleanFromInput supports it...
            //$form['items'][$item['form_item_id']]['user_data'] = FormUtil::getPassedValue(($input_name);
            $user_data[$item['item_name']] = FormUtil::getPassedValue($input_name);
            //$user_data[$item['item_name']] = FormUtil::getPassedValue((&$input_name);
            //Check the required fields
            if ( ($item['required'])
               //&&(empty($form['items'][$item['form_item_id']]['user_data']))
               &&(trim($user_data[$item['item_name']]) == '')
               ) {
                $missing_value_list = $missing_value_list
                                      .(($missing_value_list)?', ':'')
                                      .$item['prompt']
                                      ._FORMEXPRESSVALUEREQUIRED;
            }
            //Do other validation (function calls and regexp)
            //Note this is reverse logic - if the call returns anything other 
            //void or false then validation is deemed to have failed
            //TODO! revisit this when pnException is available
            if ( ( $item['validation_rule'] ) 
               &&($validation_result 
                   = FormExpress_parseandexecute( $item['validation_rule']
                                                , $user_data[$item['item_name']]
                                                )
                 )
               ) {
                $validation_result_list = $validation_result_list
                                          .(($validation_result_list)?', ':'')
                                          .$item['prompt']
                                          .$validation_result;
            }
            //
        }
    }

    //We now have all the data, so create the session vars
    //Moving towards using session vars to store data
    //This will enable multiple forms
    $fxSession = new FXSession();
    $fxSession->setForm($form_id, $user_data, true);


    //Check there are no missing required values
    if ($missing_value_list||$validation_result_list) {
        pnSessionSetVar('errormsg', _FORMEXPRESSVALIDATIONFAILED.$missing_value_list.$validation_result_list);
        return pnModFunc('FormExpress', 'user', 'display_form'
                              , array('form_id' => $form_id
                                     //, 'user_data' => $user_data //Will use session data
                                     )
                        );
    }

    //Call the submit_action
    $result = FormExpress_parseandexecute($form['submit_action'], false);

    // Check for parse or execution error
    if ( ( strpos($result, _FORMEXPRESSFUNCPARSEERROR) === 0 )
       ||( strpos($result, _FORMEXPRESSFUNCVOIDRESULT) === 0 )
       ) {
        //Note we don't use failure message for parse or void return errors.
        return FormExpress_user_display_message( array('message' => $result) );
    }

    //Note that if the submit action returns true or false then we call
    // success or failure, otherwise return the output stream

    //Check for submit result
    if ( $result === true ) {
        $result = FormExpress_parseandexecute($form['success_action'], false);
        return $result;
//        return FormExpress_user_display_message($result);
    } elseif ( $result === false ) {
        $result = FormExpress_parseandexecute($form['failure_action'], false);
//        return FormExpress_user_display_message($result);
//    } else {
//        return FormExpress_user_display_message($result);
    }

    return FormExpress_user_display_message( array('message' => $result) );


}

/** *****************************************************************************
 * This function creates an anonymous (lambda-style) function and
 * then runs it.
 * Returns: the value of the anonymous function
 *      or  a parse error message
 *      or  VOID if the parse was successful but the call to sub functions failed.
 *
 * @param action        The text to parse (and therefore action to execute)
 * @param fx_value      A value passed from the FormExpress call.  These are:
 *                        For ereg/preg_match: the value to be checked
 *                        For pnModFunc call: the form_id (?)
 * @param check_config Check FormExpress config to see if execute allowed. 
 *                     (not yet implemented)
 *                   
 */
function FormExpress_parseandexecute($action, $fx_value='', $check_config=true) {

     $dom = ZLanguage::getModuleDomain('FormExpress');

    //First check if there are '{}' at each end
    //if ( !ereg("(^[[:space:]]*[\{]+).+([\}]+[[:space:]]*$)", $action ) ) {
    // Check if action starts with '{'
    if ( !ereg("(^[[:space:]]*[\{]+).+([[:space:]]*$)", $action ) ) {
        return $action;
    } elseif ( strpos(ereg_replace("([[:space:]])", "", $action), 'ereg') === 1 ) {
        //Next check for ereg
        $validation_failed = ereg_replace( "}+[[:space:]]*$", "", substr($action, strrpos($action, '&')+1));
        $action = substr(substr($action, 0, strrpos($action, '&')-1), strpos($action, ':')+1);
        $func_text = "((ereg('".$action."', '".$fx_value."'))?false:'".$validation_failed."')";
    } elseif ( strpos(ereg_replace("([[:space:]])", "", $action), 'preg') === 1 ) {
        //Then check for preg
        $validation_failed = ereg_replace( "}+[[:space:]]*$", "", substr($action, strrpos($action, '&')+1));
        $action = substr(substr($action, 0, strrpos($action, '&')-1), strpos($action, ':')+1);
        $func_text = "((preg_match('".$action."', '".$fx_value."'))?false:'".$validation_failed."')";
    } else {
        //Must be calling a function
        $func_text = '';
        $modstoload = array();
        //while ( $chr = substr($action, 0, 1)) {
        while ( strlen($action) ) {
            $chr = substr($action, 0, 1);
            if ( ($prev_chr == "'") && ($chr != "'") ) {
                //Ignore every thing between quotes
                $func_text .= $chr;
                $action = substr($action, 1);
            } else {
                switch ($chr) {
                    case "'":
                        $func_text .= $chr;
                        //Set/unset prev_chr
                        $action = substr($action, 1);
                        $prev_chr = (($prev_chr == "'")? '' : "'");
                    break;
                    case '{':
                        //$func_text = rtrim($func_text)."pnModAPIFunc('";
                        $func_text = rtrim($func_text)."pnModFunc('";
                        $action = ltrim(substr($action, 1));
                        $prev_chr = $chr;
                    break;
                    case ':':
                        $func_text = rtrim($func_text)."' , 'user', '";
                        $action = ltrim(substr($action, 1));
                        $prev_chr = $chr;
                        $modstoload[] = $modtoload;
                        $modtoload = '';
                    break;
                    case '&':
                        //If any parameters are passed, then we also pass fx_action
                        //Don't want to pass it if no args 'cos function may fail
                        $func_text = rtrim($func_text)
                                    .(($prev_chr == ':') 
                                       ? "' , array ( 'fx_value'=>'".$fx_value."', '" 
                                       : " , '"
                                     );
                        $action = ltrim(substr($action, 1));
                        $prev_chr = $chr;
                    break;
                    case '=':
                        $func_text = rtrim($func_text)."' => ";
                        $action = ltrim(substr($action, 1));
                        $prev_chr = $chr;
                    break;
                    case '}':
                        if ( $prev_chr == ':' ) {
                            $func_text = rtrim($func_text) . "' )";
                        } elseif ( $prev_chr == '{' ) {
                            $func_text = rtrim($func_text) . "' )";
                            $modstoload[] = $modtoload;
                            $modtoload = '';
                        } else {
                            $func_text = rtrim($func_text)."))";
                        }
                        $action = ltrim(substr($action, 1));
                        $prev_chr = $chr;
                    break;
                    default:
                        $func_text .= $chr;
                        $action = substr($action, 1);
                        //Don't set prev_char
                        //Might as well build the module list here too...
                        if ( $prev_chr == '{' ) {
                           $modtoload .= $chr;
                        }
                    break;
                }
            }
        }

        //If no mods to load, then return 'cos something's not right
        if (count($modstoload) < 1) {
            return $action;
        }

        $modstoload = array_unique($modstoload);


        foreach ($modstoload as $mod) {
           //Don't need to worry about multiple loads as there is a 
           //check in pnModAPILoad()
           if (!pnModLoad($mod, 'user')) {
               return LogUtil::registerError( __("Load of module failed: ") . $mod, 500) ;
           }
        }
    }

    //Add 'return' and ';' to function
    $func_text = 'return '.$func_text.';';

    //Create the function - don't display parse errors.
    $func = @create_function('', $func_text);

    if (empty($func)) {
        //There must have been a parse error...
        //Implement exception handling when available
        return (_FORMEXPRESSFUNCPARSEERROR . $action );
    } else {
        //Call the function
        $func_result = $func();
        if ( empty($func_result) && !($func_result === false) ) {
           //This means the function parsed OK but the call to
           //the function did not return a value (true, false or any value)
           //Implement exception handling when available
           return ( _FORMEXPRESSFUNCVOIDRESULT . $action );
        } else {
            return $func_result;
        }
    }
}

function FormExpress_user_configGetVar($args) {
    extract($args);
    return pnConfigGetVar($name);
}

function FormExpress_user_userGetVar($args) {
    extract($args);
    $result = pnUserGetVar($name);
    if ($result) {
        return $result;
    } else {
        return false;
    }
}

function FormExpress_user_showPage($args) {
    extract($args);
    if (!$page_url) {
        return _MODARGSERROR;
    }
    return pnRedirect($page_url);
}


/** *****************************************************************************
 * Sample implementation of a Formexpress backend (experimental)
 */
function FormExpress_user_sendmail($args) {

    $dom = ZLanguage::getModuleDomain('FormExpress');
    
    if (isset($args['email_address']) || empty($args['email_address']) ) {
        $email_address = $args['email_address'];
    } else {
        pnSessionSetVar('errormsg', __('No email address found. Please check your Form action syntax.', $dom));
        return false;
    }
    if (isset($args['replymail'])) $replymail = $args['replymail'];
    
    $fxSession = new FXSession();
    $form_id = $fxSession->getSubmittedFormID();
    $user_data = $fxSession->getForm($form_id);

    $fxCache = new FXCache();
    $form = $fxCache->getForm($form_id);

    $dup_list = array();
    $mailData = array();
    // Load field names and values into an array
    // To pass to the Smarty template to format the email.
    foreach($form['items'] AS $item) {
        //TODO! Tidy up ifs
        if ( fe_ItemHasValue($item['item_type'])) {
            $item_name = $item['item_name'];

            //TODO! Need to add some logic for multiple selects (when supported)
            if (!in_array($item_name, $dup_list) && $user_data[$item_name] ) {
                $mailData[] = array('name' => $item_name, 'value' => $user_data[$item_name]);
                $dup_list[] = $item['item_name'];
            }
            if ( $item['item_name']  == 'replyto_email' ) {
                $replymail = $user_data[$item['item_name']];
            }
        }
    }

    $message_id = time();  //current time in seconds as a simple mail ID
    $adminmail = pnConfigGetVar('adminmail');

    $render = pnRender::getInstance('FormExpress');
    $render->assign('message_id', $message_id);
    $render->assign('mailData', $mailData);
    $message = $render->fetch('formexpress_user_sendmail.txt');

    // Actually send the mail.
    $result = pnModAPIFunc('Mailer', 'user', 'sendmessage', array(
                'toaddress' => $email_address,
                'replytoaddress' => $replay_mail,
                'sendmail_from' => $adminmail,
                'subject' => __('Results from form: ', $dom) . $form['form_name'],
                'body' => $message,
                'html' => false
            ) );

    if ( $result ) {
        pnSessionSetVar('FORMEXPRESSSUBMITID',  $message_id);

        /* Clear the user session data */
        $fxSession->delForm($form_id);
        return true;

    } else {
        pnSessionSetVar('errormsg', 
            __('Your form could not be mailed. You may want to wait a few minutes and try again, in case this was a temporary problem.',$dom));
        return false;
    }
}

/**
 * Display a formatted message.
 * This is the function called by the submit handler
 */
function FormExpress_user_display_message($args)
{
   $render = pnRender::getInstance('FormExpress');
   $render->assign('message', $args['message'] );
   $render->assign('submitid', pnSessionGetVar('FORMEXPRESSSUBMITID') );
   pnSessionDelVar('FORMEXPRESSSUBMITID');
   return $render->fetch('formexpress_user_display_message.html');;   
}

/**
 *  Check if a form item is a type that has a value
 * @param type $ItemName - the name of the item type
 * @return type bool
 */
function fe_ItemHasValue($ItemName) {
    return !preg_match('/boilerplate|groupstart|groupend|reset/', $ItemName);
}

?>

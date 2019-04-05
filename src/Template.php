<?php

namespace Vendi\BASE;

//TODO: I think this class isn't used. cjh - 2019-04-05
class Template {

  // Returns a 'non-standard' template, ie: one which is not needed on all pages.
  function get($file, $vars=null)
  {
    return Template::parseTemplate($file, $vars);
  }

  // Returns the raw 'non-standard' template that is passed.
  function getRaw($file, $vars=null)
  {
    return file_get_contents($TPATH."/$file");
  }

  // Parses the template as a string, and returns
  // the string result.
  function parseTemplate($file, $vars=null)
  {
    global $TPATH;
    // get contents of the $template file
    $template = file_get_contents($TPATH."/".$file);
    if (is_array($vars))
    {
      $vars = array_merge($vars, $_SERVER);
    } else {
      $vars = $_SERVER;
    }
    $start = 0;
    $end = 0;
    $retstring = "";
    $varname = "";
    $found = false;
    //echo htmlspecialchars($template);
    $size = strlen($template);
    for ($i=0; $i<=$size; $i++)
    {
      $char = substr($template, $i, 1);
      if ($found == false)
      {
        if ($char === '{')
        {
          $found = true;
          $varname = "";
        } else {
          $retstring .= $char;
        }
      } else {
        if ($char === '}')
        {
          $found = false;
          // now determine if we should call a function, or
          // use a variable name
          if (preg_match("/^TL:/i", $varname))
          {
            // If matched, this should use the contents of a function
            $fcall = preg_replace("/^TL:/i", "", $varname);
            eval("\$tmp = $fcall");
            $retstring .= $tmp;
          } else {
            $retstring .= $vars[$varname];
          }
        } else {
          $varname .= $char;
        }
      }
    }
    return $retstring;
  }
}

<?php

namespace Vendi\BASE;

/*******************************************************************************
** Purpose: generates timing information
********************************************************************************
** Authors:
********************************************************************************
** Kevin Johnson <kjohnson@secureideas.net
**
********************************************************************************
*/

class EventTiming
{
  var $start_time;
  var $num_events;
  var $event_log;
  var $verbose;

  function __construct($verbose)
  {
    $this->num_events = 0;
    $this->verbose = $verbose;
    $this->start_time = time();
    $this->Mark("Page Load");
  }


  function Mark($desc)
  {
    $this->event_log[$this->num_events++] = array ( time(), $desc );
  }

  function PrintTiming()
  {
    if ( $this->verbose > 0 )
    {
       echo "\n\n<!-- Timing Information -->\n".
            "<div class='systemdebug'>["._LOADEDIN." ".(time()- ($this->start_time) )." "._SECONDS."]</div>\n";
    }

    if ( $this->verbose > 1 )
    {
       for ( $i = 1; $i < $this->num_events; $i++ )
          echo "<LI>".$this->event_log[$i][1]." [".
               ($this->event_log[$i][0] - ($this->event_log[$i-1][0])).
               " "._SECONDS."]\n";
    }
  }
}

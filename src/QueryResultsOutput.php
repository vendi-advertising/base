<?php

declare(strict_types=1);

namespace Vendi\BASE;

class QueryResultsOutput
{
    public $qroHeader;
    public $url;

    public function __construct($uri)
    {
        $this->url = $uri;
    }

    public function AddTitle($title, $asc_sort = ' ', $asc_sort_sql1 = '', $asc_sort_sql2 = '',
                            $desc_sort = ' ', $desc_sort_sql1 = '', $desc_sort_sql2 = '')
    {
        $this->qroHeader[$title] = [$asc_sort => [$asc_sort_sql1, $asc_sort_sql2],
                                     $desc_sort => [$desc_sort_sql1, $desc_sort_sql2], ];
    }

    public function GetSortSQL($sort, $sort_order)
    {
        reset($this->qroHeader);

        //TODO: This loop convert from each() to foreach()
        foreach ($this->qroHeader as $title) {
            if (array_key_exists('value', $title)) {
                if (in_array($sort, array_keys($title['value']))) {
                    $tmp_sort = $title['value'][$sort];

                    return $tmp_sort;
                }
            }
        }
        /*
        while( $title = each($this->qroHeader) )
        {
          if ( in_array($sort, array_keys($title["value"])) )
          {
             $tmp_sort = $title["value"][$sort];
             return $tmp_sort;
          }
        }
        */
        /* $sort is not a valid sort type of any header */
        return null;
    }

    public function PrintHeader($text = '')
    {
        /* Client-side Javascript to select all the check-boxes on the screen
         *   - Bill Marque (wlmarque@hewitt.com) */
        echo '
          <SCRIPT type="text/javascript">
            function SelectAll()
            {
               for(var i=0;i<document.PacketForm.elements.length;i++)
               {
                  if(document.PacketForm.elements[i].type == "checkbox")
                  {
                    document.PacketForm.elements[i].checked = true;
                  }
               }
            }

            function UnselectAll()
            {
                for(var i=0;i<document.PacketForm.elements.length;i++)
                {
                    if(document.PacketForm.elements[i].type == "checkbox")
                    {
                      document.PacketForm.elements[i].checked = false;
                    }
                }
            }
           </SCRIPT>';

        if ('' != $text) {
            echo $text;
        }

        echo '<TABLE CELLSPACING=0 CELLPADDING=2 BORDER=0 WIDTH="100%" BGCOLOR="#000000">' . "\n" .
          "<TR><TD>\n" .
          '<TABLE CELLSPACING=0 CELLPADDING=0 BORDER=0 WIDTH="100%" BGCOLOR="#FFFFFF">' . "\n" .
          "\n\n<!-- Query Results Title Bar -->\n   <TR>\n";

        reset($this->qroHeader);

        //TODO: This loop was converted from each() to foreach()
        foreach ($this->qroHeader as $title) {
            $print_title = '';

            $sort_keys = array_key_exists('value', $title) ? array_keys($title['value']) : [];
            if (2 == count($sort_keys)) {
                $print_title = '<A HREF="' . $this->url . '&amp;sort_order=' . $sort_keys[0] . '">&lt;</A>' .
                         '&nbsp;' . $title['key'] . '&nbsp;' .
                         '<A HREF="' . $this->url . '&amp;sort_order=' . $sort_keys[1] . '">&gt;</A>';
            } else {
                $print_title = array_key_exists('key', $title) ? $title['key'] : '';
            }

            echo '    <TD CLASS="plfieldhdr">&nbsp;' . $print_title . '&nbsp;</TD>' . "\n";
        }
        /*
        while( $title = each($this->qroHeader) )
        {
          $print_title = "";

          $sort_keys = array_keys($title["value"]);
          if ( count($sort_keys) == 2 )
          {
             $print_title = "<A HREF=\"".$this->url."&amp;sort_order=".$sort_keys[0]."\">&lt;</A>".
                            "&nbsp;".$title["key"]."&nbsp;".
                            "<A HREF=\"".$this->url."&amp;sort_order=".$sort_keys[1]."\">&gt;</A>";
          }
          else
          {
             $print_title = $title["key"];
          }

          echo '    <TD CLASS="plfieldhdr">&nbsp;'.$print_title.'&nbsp;</TD>'."\n";
        }
        */

        echo "   </TR>\n";
    }

    public function PrintFooter()
    {
        echo "  </TABLE>\n
           </TD></TR>\n
          </TABLE>\n";
    }

    public function DumpQROHeader()
    {
        echo '<B>' . _QUERYRESULTSHEADER . '</B>
          <PRE>';
        print_r($this->qroHeader);
        echo '</PRE>';
    }
}

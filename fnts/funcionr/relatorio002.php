printf("<tr><td>Moradia:</td><td>");
$sql=pg_query("SELECT cplogradouro,txnomelogradouro FROM logradouros ORDER by txnomelogradouro");
printf("<select name='celogradouro'>\n");
    while ( $sel=pg_fetch_array($sql) )
    {
    $selected=( $reg['celogradouromoradia']==$sel['cplogradouro'] ) ? " SELECTED" : "" ;
    printf("<option value='$sel[cplogradouro]'$selected>$sel[txnomelogradouro] - ($sel[cplogradouro])</option>\n");
    }
    printf("</select>\n");
printf("</td></tr>\n");
<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicoslistar.php
# Descricao...: Montar do formulÃ¡rio para escolher a ordenaÃ§Ã£o dos dados. Depois emite a listagem no frame e oferece a escolha para emitir em aba separada.
# Autor.......: JoÃ£o MaurÃ­cio HypÃ³lito - Copie mas diga quem fez
# Objetivo....: Mostrar uma lista de campos para escolha da forma de ordenaÃ§Ã£o dos dados do relatÃ³rio. Depois de escolhida a ordem, emite uma listagem com os
#               dados da tabela medicos relacionados com logradouros (por duas chaves estrangeiras), instituicaoensino e especmedicas. Depois de emitida a
#               listagem oferece a escolha para emitir a listagem em aba separada (fundo branco).
#               Se escolhida emite a listagem com a mesma ordem em aba separada.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-25 - Ajustes e testes gerais.
#               2017-10-26 - RevisÃ£o e descarta de linhas desnecessÃ¡rias.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregar o ToolsKit (e executar as funÃ§Ãµes Gerais disponÃ­veis no grupo de funÃ§Ãµes)
require_once("../toolskit.php");
# Carregar o arquivo com as funÃ§Ãµes locais da tabela medicos
require_once("funcionrfuncoes.php");
# Atribuindo o valor de $acao, $passo, $salto, $corfundo e $corfonte
$acao = "Listar";
$passo= ( ISSET($_POST['passo']) ) ? $passo=$_POST['passo'] : '1';
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');   // $salto recebe $_POST['salto']+1 (se houver), senÃ£o 1
$corfonte="#000000"; # black
$cordefundo = ($passo==3) ? "white" : "navajowhite" ;
# Iniciando a pÃ¡gina - nÃ£o se usa aqui o iniciapagina (programa do toolskit)
printf("<html xml:lang='pt-BR' lang='pt-BR' dir='ltr'>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n</head>\n");
printf("<body bgcolor='$cordefundo'>\n<dir>\n");
printf("<font face='tahoma' size=3 color='$corfonte'>\n");
printf("<font color=red><b>Listagem</b></font>\n");
# SWITCH CASE com a variÃ¡vel $passo
SWITCH (TRUE)
{ # 1 -------------------------------------------------------------------------------------------------------------------------------------------
    case ($passo==1):
    { # 1.1 Vamos montar o formulario para escolha da ordenaÃ§Ã£o dos dados no relatÃ³rio ------------------------------------------------------------
        printf("<form action='./medicoslistar.php' method='post'>\n");
        printf("<input type='hidden' name='acao'  value='$acao'>\n");
        printf("<input type='hidden' name='passo' value='2'>\n");
        printf("<input type='hidden' name='salto' value='$salto'>\n");
        printf("Escolha a ordena&ccedil;&atilde;o dos dados do relat&oacute;rio marcando um dos campos<br>\n");
        printf("<table>\n");
        printf("<tr><td>C&oacute;digo</td>             <td><INPUT TYPE=RADIO NAME='ordem' VALUE='M.cpmedico' CHECKED></td></tr>\n");
        printf("<tr><td>Nome</td>                      <td><INPUT TYPE=RADIO NAME='ordem' VALUE='M.txnomemedico'></td></tr>\n");
        printf("<tr><td>Logradouro Moradia</td>        <td><INPUT TYPE=RADIO NAME='ordem' VALUE='L1.txnomelogrmora'></td></tr>\n");
        printf("<tr><td>Logradouro Cl&iacute;nica</td> <td><INPUT TYPE=RADIO NAME='ordem' VALUE='L2.txnomelogrclin'></td></tr>\n");
        printf("<tr><td>Data de Cadastro</td>          <td><INPUT TYPE=RADIO NAME='ordem' VALUE='M.dtcadmedico'></td></tr>\n");
        printf("</table>\n");
        # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
        # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
        botoes(TRUE,TRUE,TRUE,TRUE,"Gerar Listagem",$salto);
        printf("</form>\n");
        break;
    } # 1.1 ---------------------------------------------------------------------------------------------------------------------------------------
    case ($passo==2 or $passo==3):
    { # 1.2 - pegando o valor da variavel $ordena do formulario anterior --------------------------------------------------------------------------
        $ordem=$_POST['ordem'];
        # O proximo comando le a tabela de medicos ordenando os dados pela escolha indicada na variavel $ordem
        $sql = pg_query("SELECT M.*, L1.txnomelogradouro AS txnomelogrmora, L2.txnomelogradouro AS txnomelogrclin, txnomeespecialidade, txnomeinstituicao
                            FROM medicos AS M,
                                 logradouros AS L1, 
                                 logradouros AS L2, 
                                 especmedicas AS E, 
                                 instituicoesensino AS I
                            WHERE M.celogradouromoradia=L1.cplogradouro AND 
                                  M.celogradouroclinica=L2.cplogradouro AND
                                  M.ceespecialidade=E.cpespecialidade AND
                                  M.ceinstituicao=I.cpinstituicao
                            ORDER BY $ordem");
        printf("<table border=1>\n");
        printf("<tr bgcolor='lightblue'><td>M&eacute;dico</td>
                                    <td>CRM</td>
                                    <td>Logradouro-Moradia</td>
                                    <td>Logradouro-Cl&iacute;nica</td>
                                    <td>Especialidade</td>
                                    <td>Institui&ccedil;&atilde;o de Ensino</td>
                                    <td>Situa&ccedil;&atilde;o</td>
                                    <td>Cadastro</td> </tr>\n");
        $cor="WHITE";
        while ($le = pg_fetch_array($sql))
        { # 1.2.1 -----------------------------------------------------------------------------------------------------------------------------------
            $dtcad=explode("-",$le['dtcadmedico']);
            printf("<tr bgcolor='$cor'><td>$le[cpmedico] - $le[txnomemedico]</td>
                                  <td>$le[nucrm]</td>
                                  <td>$le[celogradouromoradia]-$le[txnomelogrmora]-$le[txcomplementomoradia]</td>
                                  <td>$le[celogradouroclinica]-$le[txnomelogrclin]-$le[txcomplementoclinica]</td>
                                  <td>$le[ceespecialidade]-$le[txnomeespecialidade]</td>
                                  <td>$le[ceinstituicao]-$le[txnomeinstituicao]</td>
                                  <td>".($le['aoativo']=="A" ? "Ativo":"Bloqueado")."</td>
                                  <td>$dtcad[2]/$dtcad[1]/$dtcad[0]</td> </tr>\n");
            $cor=( $cor == "WHITE" ) ? "LIGHTGREEN" : "WHITE";
        } # 1.2.1 -----------------------------------------------------------------------------------------------------------------------------------
        printf("</table>\n");
        if ( $passo==2 )
        { # 1.2.2 vamos montar o botÃ£o para impressÃ£o -----------------------------------------------------------------------------------------------
            printf("<form action='./medicoslistar.php' method='POST' target='_NEW'>\n");
            printf("<input type='hidden' name='acao'  value='$acao'>\n");
            printf("<input type='hidden' name='passo' value='3'>\n");
            printf("<input type='hidden' name='ordem' value='$ordem'>\n");
            printf("<input type='hidden' name='salto' value='$salto'>\n");
            # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
            # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
            botoes(TRUE,TRUE,TRUE,FALSE,"Gerar para Impress&atilde;o",$salto);
            printf("O mesmo relat&oacute;rio ser&aacute; montado em uma janela!<br>Depois voc&ecirc; pode escolher a impress&atilde;o pelo navegador.\n");
            printf("</form>\n");
        } # 1.2.2 -----------------------------------------------------------------------------------------------------------------------------------
        else
        { # 1.2.3 - O fluxo passa por aqui quando o $passo valer 3 ----------------------------------------------------------------------------------
            printf("<hr>\nDepois de Imprimir rasgue na linha acima<br>\n");
            printf("<input type='submit' value='Imprimir' onclick='javascript:window.print();'>");
            # Aqui montamos o final de pÃ¡gina quando o relatÃ³rio vai para a impressÃ£o ($passo valendo 3)
            $ano=date('Y');
            printf("</dir>\n <hr> \n");
            printf("<font size=2 color='gray'>&copy; Copyright $ano, FATEC Ourinhos - Copie, divulgue, mas indique sempre quem fez!\n</font>\n");
        } # 1.2.3 -----------------------------------------------------------------------------------------------------------------------------------
        break;
    } # 1.2 ---------------------------------------------------------------------------------------------------------------------------------------
} # 1 -------------------------------------------------------------------------------------------------------------------------------------------
# o comando que emite as TAGs de fim de pÃ¡gina acontecem SEMPRE (qualquer valor de $passo).
# Por isso o printf() estÃ¡ FORA do SWITCH-CASE
printf("<br><br><br><br><br><br><br><br><br>\n");
printf("</body>\n</html>\n");
?>
<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosmenu.php
# Descricao...: Definicao dos Menus de Gerenciamento de Dados de medicos
# Autor.......: Joao Mauricio Hypolito - Copie mas diga quem fez.
# Objetivo....: Montar Uma Tabela contendo em suas celulas: Identificacao (medicos), Itens para escolha das operacoes ICAEL.
#               Cada celula do ICAEL deve ser um formulario disparando os programas inc1, con01, alt01, exc01 e lst01 para cada operacao
#               respectivamente. A execucao dos programas devem acontecer no quadro Q2.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-24 - Ajustes gerais. Adequação dos alvos dos programas à estrutura dos frames.
# Modificação.: 2017-11-11 - Adaptado para funcionários. Felipe Bertanha
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once("../../toolskit.php");
iniciapagina("#FFDEAD","#000000","Menu"); # $cordefundo,$corfonte);
printf("<table><tr>");
printf("<td valign=top><font color=red size=4><b>Funcionários:</b></font></td>");
printf("<td><form action='funcionrincluir.php'   method='POST' target='Q2'><input type='submit' value='Incluir'></form></td>");
printf("<td><form action='funcionrconsultar.php' method='POST' target='Q2'><input type='submit' value='Consultar'></form></td>");
printf("<td><form action='funcionralterar.php'   method='POST' target='Q2'><input type='submit' value='Alterar'></form></td>");
printf("<td><form action='funcionrexcluir.php'   method='POST' target='Q2'><input type='submit' value='Excluir'></form></td>");
printf("<td><form action='funcionrlistar.php'    method='POST' target='Q2'><input type='submit' value='Listar'></form></td>");
printf("<td><form action='relatorio001.php'    method='POST' target='Q2'><input type='submit' value='Relat1'></form></td>");
printf("<td><form action='relatorio002.php'    method='POST' target='Q2'><input type='submit' value='Relat2'></form></td>");
printf("</tr></table>\n");
# não se usa a função que termina a página, porque a linha de autoria NÃO aparece na página que monta o menu.
printf("</body>\n</html>\n");
?>
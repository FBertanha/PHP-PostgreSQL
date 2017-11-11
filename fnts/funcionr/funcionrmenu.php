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
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
require_once("../toolskit.php");
iniciapagina("#FFDEAD","#000000","Menu"); # $cordefundo,$corfonte);
printf("<table><tr>");
printf("<td valign=top><font color=red size=4><b>M&eacute;dicos:</b></font></td>");
printf("<td><form action='./medicosincluir.php'   method='POST' target='Q2'><input type='submit' value='Incluir'></form></td>");
printf("<td><form action='./medicosconsultar.php' method='POST' target='Q2'><input type='submit' value='Consultar'></form></td>");
printf("<td><form action='./medicosalterar.php'   method='POST' target='Q2'><input type='submit' value='Alterar'></form></td>");
printf("<td><form action='./medicosexcluir.php'   method='POST' target='Q2'><input type='submit' value='Excluir'></form></td>");
printf("<td><form action='./medicoslistar.php'    method='POST' target='Q2'><input type='submit' value='Listar'></form></td>");
printf("<td><form action='./tstrelatorio1.php'    method='POST' target='Q2'><input type='submit' value='Relat1'></form></td>");
printf("<td><form action='./tstrelatorio2.php'    method='POST' target='Q2'><input type='submit' value='Relat2'></form></td>");
printf("</tr></table>\n");
# não se usa a função que termina a página, porque a linha de autoria NÃO aparece na página que monta o menu.
printf("</body>\n</html>\n");
?>
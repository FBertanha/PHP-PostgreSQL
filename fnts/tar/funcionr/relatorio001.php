<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: tstrelatorio1.php
# Descricao...: Relatório do TESTESQL
# Autor.......: João Maurício Hypólito - Copie mas diga quem fez.
# Objetivo....: Especificar e desenvolver um dos relatórios apresentados no texto do TESTESQL.
#               Este programa somente monta a ESTRUTURA Fundamental de como deve ser o relatório.
# Criacao.....: 2016-10-15
# Atualizacao.: 2016-10-15 - Primeira montagem.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Algoritmo do programa
# Inicia as variáveis $passo e $salto
# Inicia a variavel $cordefundo com navajowhite se a $passo valer 1 ou 2 e WHITE quando $passo == 3
# Inicia a página com alinhamento padrão.
# Inicia SWITCH com valor da variável $passo.
#   Para $passo=='1'
#     monta um form recursivo com escolha da ordenação dos dados (variavel $ordem)
#     $passo='2' hidden
#   Para $passo=='2' ou $passo=='3'
#     Le os dados de usuários, tiposusuários e cidades em uma junção de 3 tabelas
#     monta o relatório lendo os dados com a ordenação escolhida no form anterior
#     Se $passo==2
#       entao
#         monta um form recursivo para escolha de versão a imprimir abrindo a emissao em uma nova aba do navegador.
#         $passo='3' hidden (escolhendo emitir para impressao a cor de fundo fica WHITE)
# FIM_DO_CASE_$PASSO
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregando o ToolsKit (e executando as funções Gerais disponíveis no grupo de funções)
# fazendo a conexão com o banco de dados e recebendo as variáveis globalizadas da conex.
require_once("../../toolskit.phpp");
# Atrinbuindo valores em $passo e $salto.
$passo=(isset($_POST['passo']) ? $_POST['passo'] : '1');  // $passo recebe $_POST['passo'] (se houver), senão 1
$salto=(isset($_POST['salto'])? $_POST['salto']+1:'1');   // $salto recebe $_POST['salto']+1 (se houver), senão 1
# printf("Acao: $acao<br>Passo: $passo<br>Salto: $salto<br>");
$cordefundo = ($passo==3) ? "white" : "navajowhite" ;
# Iniciando a página
printf("<html xml:lang='pt-BR' lang='pt-BR' dir='ltr'>\n<head>\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n</head>\n");
printf("<body bgcolor='$cordefundo'>\n");
printf("<font face='tahoma' color=red><b>Relat&oacute;rio NN do TESTESQL</b></font>\n");
# SWITCH CASE com a variável $passo
SWITCH (TRUE)
{ # 1.6.1
    #------------------------------------------------------------------------------------------------------------------------------------------------------------
    case ($passo==1):
    { # 1.6.1.1 Vamos montar o formulario para escolha da ordenação dos dados no relatório
        printf("<form action='relatorio001.php' method='post'>\n");
        printf("<input type='hidden' name='passo' value='2'>\n");
        printf("<input type='hidden' name='salto' value='$salto'>\n");
        printf("Esta tela deve apresentar alguma forma de ESCOLHA de dados para SELE&Ccedil;&Atilde;O<br>\n");
        printf("Ou ordena&ccedil;&atilde;o dos dados do relat&oacute;rio<br>\n");
        printf("<table>\n");
        printf("<tr><td>Use de Tabelas para</td>      <td><INPUT TYPE=RADIO NAME='ordem' VALUE='' CHECKED></td></tr>\n");
        printf("<tr><td>Apresentar os dados</td>      <td><INPUT TYPE=RADIO NAME='ordem' VALUE=''></td></tr>\n");
        printf("<tr><td colspan=2>Use campos de caixa de sele&ccedil;&atilde;o se tiver que escolher valores de chaves estrangeiras.</td></tr>\n");
        printf("<tr><td colspan=2><select name=''>\n<option value=''>Escolha1</option>\n<option value=''>Escolha2</option>\n</select></td></tr>\n");
        printf("<tr><td>Ou se precisar escolher data:</td><td>De: <input type='text' name='diaini' size=2 maxlength=2>/<input type='text' name='mesini' size=2 maxlength=2>/<input type='text' name='anoini' size=4 maxlength=4> at&eacute;:<input type='text' name='diafim' size=2 maxlength=2>/<input type='text' name='mesfim' size=2 maxlength=2>/<input type='text' name='anofim' size=4 maxlength=4></td></tr>\n");
        # Montar o botão para Gerar a Listagem
        printf("<tr><td colspan=2>");
        # montar o botão de voltar UMA página, voltar para página de ABERTURA, "limpar" (RESET dos campos do form) e Gerar O Relatório
        printf("<input type='button' value='< P&aacute;gina' onclick='history.go(-1)'><input type='button' value='< Menu' onclick='history.go(-$salto)'><input type='button' value='< Sa&iacute;da' onclick='history.go(-($salto+1))'><input type='reset' value='Limpar'><input type=submit value='Gerar Listagem'>");
        printf("</td></tr>\n");
        printf("</table>\n");
        printf("</form>\n");
        # Fechamos a Página - Emitimos os comandos que finalizam a página em HTML
        $ano=date('Y');
        printf("<hr>\n");
        printf("<font size=2 color='gray'>Relat&oacute;rio de medicos - Resolu&ccedil;&atilde;o m&iacute;nima de 1280x720 &copy; Copyright $ano, FATEC Ourinhos - Copie, divulgue, mas indique sempre quem fez! - medicosrel01.php</font>\n");
        break;
    } # 1.6.1.1
    #------------------------------------------------------------------------------------------------------------------------------------------------------------
    case ($passo==2 or $passo==3):
    { # 1.6.1.2 - pegando o valor da variavel $ordena do formulario anterior
        $ordem=$_POST['ordem'];
        # O proximo comando le a tabela de medicos ordenando os dados pela escolha indicada na variavel $ordem
        printf("Aqui desenvolva o SQL que consulta o banco e monta o conjunto de dados para rodar o relat&oacute;rio.");
        printf("Use a estrutura do LST para montar a emiss&atilde;o do Relat&oacute;rio na Tela e depois em uma nova Aba para impress&atilde;o.");
        break;
    } # 1.6.1.2
    #------------------------------------------------------------------------------------------------------------------------------------------------------------
} # 1.6.1
# o comando que emite as TAGs de fim de página acontecem SEMPRE (qualquer valor de $passo).
# Por isso o printf() está FORA do SWITCH-CASE
printf("</body>\n</html>\n");
?>
<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcionrconsultar.php
# Descricao...: Montar uma caixa de seleÃ§Ã£o (escolha de registro) e depois exibir o registro escolhido
# Autor.......: JoÃ£o MaurÃ­cio HypÃ³lito - Copie mas diga quem fez
# Objetivo....: Usar a funÃ§Ã£o local FUN01 e montar uma caixa de seleÃ§Ã£o para escolha de um registro.
#               Depois de ler o valor da CP, usar a funÃ§Ã£o local FUN02 e exibir os valores dos campos do registro escolhido.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-25 - Ajustes e testes gerais.
#               2017-10-26 - RevisÃ£o e descarta de linhas desnecessÃ¡rias.
# Modificação.: 2017-11-11 - Adaptado para funcionários. Felipe Bertanha
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregar o ToolsKit (e executar as funÃ§Ãµes Gerais disponÃ­veis no grupo de funÃ§Ãµes)
require_once("../../toolskit.php");
# Carregar o arquivo com as funÃ§Ãµes locais da tabela funcionários
require_once("funcionrfuncoes.php");
# Atribuindo o valor de $acao, $passo, $salto, $corfundo e $corfonte
$acao = "Consultar";
$passo= ( ISSET($_POST['passo']) ) ? $passo=$_POST['passo'] : '1';
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');   // $salto recebe $_POST['salto']+1 (se houver), senÃ£o 1
$corfundo="#FFDEAD"; # navajowhite
$corfonte="#000000"; # black
# executar a funÃ§Ã£o que inicia a pÃ¡gina (define cor de fundo, cor de fonte, alinhamento e textos iniciais)
iniciapagina($corfundo,$corfonte,$acao);
# Desvio de Blocos Principais baseado em $passo. --------------------------------------------------------------------------------------------------------------
SWITCH (TRUE)
{ # 1-montando a tela de form para digitaÃ§Ã£o dos dados para inclusÃ£o ------------------------------------------------------------------------------------------
    case ( $passo==1 ):
    { # 1.1 - executa a funÃ§Ã£o funcionrfun01() - monta a picklist escolhendo o registro de consulta --------------------------------------------------------------
        funcionrfun01($acao,$passo,$salto);
        break;
    } # 1.1 -----------------------------------------------------------------------------------------------------------------------------------------------------
    case ( $passo==2 ):
    { # 1.2 - mostrando o registro escolhido --------------------------------------------------------------------------------------------------------------------
        funcionrfun02("$_POST[cpfuncionario]");
        # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
        # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
        botoes(TRUE,TRUE,TRUE,FALSE,NULL,$salto);
        printf("<br>\n");
        break;
    } # 1.2 -----------------------------------------------------------------------------------------------------------------------------------------------------
} # 1-Fim do divisor de blocos principal ----------------------------------------------------------------------------------------------------------------------
terminapagina($acao,"funcionrconsultar.php",FALSE);
?>
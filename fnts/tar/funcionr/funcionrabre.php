<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosabre.php
# Descricao...: Montagem da página de abertura dos programas de Gerenciamento dos Dados de medicos
# Autor.......: João Maurício Hypólito - Copie mas diga quem fez.
# Objetivo....: Montar uma página com texto explicativo saobre como usar os programas de gerenciamento dos dados da tabela medicos.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-26 - Exclusão de linhas de código desnecessárias.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregar o ToolsKit (e executar as funções Gerais disponíveis no grupo de funções)
require_once("../../toolskit.php");# Atribuindo o valor de $passo e $salto.
# Atribuir valores às variáveis (usadas na função geral iniciapagina - escrita no toolskit):
$acao     = "Abertura";
$corfundo = "#FFDEAD"; # navajowhite
$corfonte = "#000000"; # black
iniciapagina($corfundo,$corfonte,$acao);
printf("Este &eacute; o sistema de programas de Gerenciamento de m&eacute;dicos<br><br>\n");
printf("Use o Menu acima para escolher as a&ccedil;&otilde;es que deseja realizar sobre os dados da tabela.<br>\n");
printf("Para cada a&ccedil;&atilde;o disparada uma nova tela se abre neste painel (inferior).<br>\n");
printf("Nesta nova tela, na &uacute;ltima linha no lado esquerdo surge a fun&ccedil;&atilde;o executada e no lado direito o c&oacute;digo do programa em execu&ccedil;&atilde;o.<br>\n");
printf("Isso ajuda muito na hora de localizar eventuais erros no programa.<br><br>\n");
printf("Se um erro ocorrer no uso do Programa entre em contato com o Suporte t&eacute;cnico informando a mensagem de erro e o c&oacute;digo do programa.<br><br>\n");
?>
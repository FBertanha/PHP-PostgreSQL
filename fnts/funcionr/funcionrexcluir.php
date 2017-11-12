<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcionrexcluir.php
# Descricao...: Montar do formulÃ¡rio escolher e exibir os dados de um registro. Montar um form de confirmaÃ§Ã£o da exclusÃ£o.
#               Executar o controle da transaÃ§Ã£o de DELETE.
# Autor.......: JoÃ£o MaurÃ­cio HypÃ³lito - Copie mas diga quem fez
# Objetivo....: Usar a funÃ§Ã£o local FUN01 e montar uma caixa de seleÃ§Ã£o para escolha de um registro. Usar a funÃ§Ã£o local FUN02 e exibir o registro.
#               Montar um form para confirmaÃ§Ã£o da aÃ§Ã£o de exclusÃ£o do registro. Se confirmado, executa o controle da transaÃ§Ã£o de DELETE.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-25 - Ajustes e testes gerais.
#               2017-10-26 - RevisÃ£o e descarta de linhas desnecessÃ¡rias.
# Modificação.: 2017-11-11 - Adaptado para funcionários. Felipe Bertanha
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregar o ToolsKit (e executar as funÃ§Ãµes Gerais disponÃ­veis no grupo de funÃ§Ãµes)
require_once("../toolskit.php");
# Carregar o arquivo com as funÃ§Ãµes locais da tabela funcionários
require_once("funcionrfuncoes.php");
# Atribuindo o valor de $acao, $passo, $salto, $corfundo e $corfonte
$acao = "Excluir";
$passo= ( ISSET($_POST['passo']) ) ? $passo=$_POST['passo'] : '1';
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');   // $salto recebe $_POST['salto']+1 (se houver), senÃ£o 1
$corfundo="#FFDEAD"; # navajowhite
$corfonte="#000000"; # black
# executar a funÃ§Ã£o que inicia a pÃ¡gina (define cor de fundo, cor de fonte, alinhamento e textos iniciais)
iniciapagina($corfundo,$corfonte,$acao);
# Desvio de Blocos Principais baseado em $passo. ------------------------------------------------------------------------------------------------
SWITCH (TRUE)
{ # 1-montando a tela de form para digitaÃ§Ã£o dos dados para inclusÃ£o ----------------------------------------------------------------------------
    case ( $passo==1 ):
    { # 1.1 - executa a funÃ§Ã£o funcionrfun01() - monta a picklist escolhendo o registro de consulta ------------------------------------------------
        funcionrfun01($acao,$passo,$salto);
        break;
    } # 1.1 ---------------------------------------------------------------------------------------------------------------------------------------
    case ( $passo==2 ):
    { # 1.2 - mostrando o registro escolhido ------------------------------------------------------------------------------------------------------
        funcionrfun02("$_POST[cpfuncionario]");
        printf("<form action='funcionrexcluir.php'  method='POST'>\n");
        printf("<input type='hidden' name='acao'  value='$acao'>\n");
        printf("<input type='hidden' name='passo' value=3>\n");
        printf("<input type='hidden' name='salto' value='$salto'>\n");
        printf("<input type='hidden' name='cpfuncionario' value='$_POST[cpfuncionario]'>\n");
        # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
        # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
        botoes(TRUE,TRUE,TRUE,FALSE,"Confirma a Exclus&atilde;o",$salto);
        printf("</form>\n");
        break;
    } # 1.2 ---------------------------------------------------------------------------------------------------------------------------------------
    case ( $passo==3 ):
    { # 1.3-Bloco para Tratamento da TransaÃ§Ã£o
        # Montando o comando de DELETE
        $cmd="DELETE FROM funcionarios WHERE cpfuncionario='$_POST[cpfuncionario]'";
        # Ajustando a tabela de simbolos recebidos/enviados para o BD para UTF8
        pg_query("SET NAMES 'utf8'");
        pg_query("SET CLIENT_ENCODING TO 'utf8'");
        pg_set_client_encoding('utf8');
        # exibindo mensagem de orientaÃ§Ã£o
        printf("Excluindo o Registro...<br>\n");
        #--------------------------------------------------------------------------------------------------------------------------------------------
        # Executando o case que remove (DELETE) os dados na tabela funcionários.
        # Tratamento da TransaÃ§Ã£o
        # Inicio da transaÃ§Ã£o - No PostgreSQL se inica com o comando BEGIN. Colocamos dentro de um WHILE para poder
        # controlar o reinicio da transaÃ§Ã£o caso aconteÃ§a um DEADLOCK.
        $tentativa=TRUE;
        while ( $tentativa )
        { # 1.3.1-LaÃ§o de repetiÃ§Ã£o para tratar a transaÃ§Ã£o -----------------------------------------------------------------------------------------
            $query = pg_send_query($dbp,"BEGIN");
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            # Depois que se inicia uma transaÃ§Ã£o o comando enviado para o BD deve ser atravÃ©s da funÃ§Ã£o pg_send_query().
            # Esta funÃ§Ã£o avisa ao PostgreSQL que devem ser usados os LOGs de transaÃ§Ã£o para acessar os dados.
            # A cada send_query o PostgreSQL responde com um sinal de status (erro ou nÃ£o erro).
            # Por conta disso deve-se "ler" este status com as funÃ§Ãµes pg_getr_result() e pg_result_error().
            # Executando o comando (montado FORA do laÃ§o de tentativa) e capturando um eventual erro.
            $comando=pg_send_query($dbp,$cmd);
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            $volta=pg_fetch_array($result);
            # O PrÃ³ximo SWITCH trata as situaÃ§Ãµes de erro. A funÃ§Ã£o pg_get_result($dbp) retorna o nÃºmero do erro do PostgreSQL.
            switch (TRUE)
            { # 1.3.1.1 - AvaliaÃ§Ã£o da situaÃ§Ã£o de erro (se existir). ---------------------------------------------------------------------------------
                case $erro == "" :
                { # 1.3.1.1.1 - Nao tem erro! Concluir a transacao e Avisar o usuario. ------------------------------------------------------------------
                    # Comando que foi EXECUTADO no BD  SEM ERRO  podemos COMMITAR a transaÃ§Ã£o.
                    $query=pg_send_query($dbp,"COMMIT"); # A captura do erro fica fora do SWITCH CASE
                    printf("Registro <b>Exclu&iacute;do</b> com sucesso!<br>\n");
                    $tentativa=FALSE;
                    break;
                } # 1.3.1.1.1 ---------------------------------------------------------------------------------------------------------------------------
                case $erro == "deadlock_detected" :
                { # 1.3.1.1.2 - Erro de DeadLock - Cancelar e Reiniciar a transacao
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=TRUE;
                    break;
                } # 1.3.1.1.2 ---------------------------------------------------------------------------------------------------------------------------
                case $erro != '' AND  $erro!= 'deadlock_detected' :
                { # 1.3.1.1.3 - Erro! NÃƒO por deadlock. AVISAR o usuario. CANCELAR A transacao ----------------------------------------------------------
                    printf("<b>Erro na tentativa de Inserir!</b><br>\n");
                    $mens=$result." : ".$erro;
                    printf("Mensagem: $mens<br>\n");
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=FALSE;
                    break;
                } # 1.3.1.1.3 ---------------------------------------------------------------------------------------------------------------------------
            } # 1.3.1.1 - Fim do SWITCH tratando os status da transaÃ§Ã£o -------------------------------------------------------------------------------
            $resultfinal=pg_get_result($dbp);
            $errofinal=pg_result_error($resultfinal);
        } # 1.3.1-Fim do LaÃ§o de repetiÃ§Ã£o para tratar a transaÃ§Ã£o ----------------------------------------------------------------------------------
        # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
        # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
        botoes(FALSE,TRUE,TRUE,FALSE,NULL,$salto);
        printf("<br>\n");
        break;
    } # 1.3-Fim do Bloco de Tratamento da TransaÃ§Ã£o -----------------------------------------------------------------------------------------------
} # 1-Fim do divisor de blocos principal --------------------------------------------------------------------------------------------------------
terminapagina($acao,"funcionrconsultar.php",FALSE);
?>
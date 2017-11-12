<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcionralterar.php
# Descricao...: Montar do formulÃ¡rio escolher e editar os dados de um registro e executar o controle da transaÃ§Ã£o de UPDATE
# Autor.......: JoÃ£o MaurÃ­cio HypÃ³lito - Copie mas diga quem fez
# Objetivo....: Usar a funÃ§Ã£o local FUN01 e montar uma caixa de seleÃ§Ã£o para escolha de um registro.
#               Montar um form usando a funÃ§Ã£o local fun03 com parÃ¢metro ALTERAR, depois lÃª os dados e executa o controle da transaÃ§Ã£o de UPDATE.
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
$acao = "Alterar";
$passo= ( ISSET($_POST['passo']) ) ? $passo=$_POST['passo'] : '1';
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');   // $salto recebe $_POST['salto']+1 (se houver), senÃ£o 1
$corfundo="#FFDEAD"; # navajowhite
$corfonte="#000000"; # black
# executar a funÃ§Ã£o que inicia a pÃ¡gina (define cor de fundo, cor de fonte, alinhamento e textos iniciais)
iniciapagina($corfundo,$corfonte,$acao);
# Desvio de Blocos Principais baseado em $passo.
SWITCH (TRUE)
{ # 1 - Este Ã© o comando de desvio principal do programa. -----------------------------------------------------------------------------------
    case ( $passo==1 ):
    { # 1.1 - executa a funÃ§Ã£o funcionrfun01() - monta a picklist escolhendo o registro de alteraÃ§Ã£o ------------------------------------------
        funcionrfun01($acao,$passo,$salto);
        break;
    } # 1.1 -----------------------------------------------------------------------------------------------------------------------------------
    case ( $passo==2 ):
    { # 1.2 - ---------------------------------------------------------------------------------------------------------------------------------
        $passo=$passo+1;
        funcionrfun03($acao,$passo,$salto);
        break;
    } # 1.2-Fim do Bloco de exibir registro ---------------------------------------------------------------------------------------------------
    case ( $passo==3 ):
    { # 1.3-Bloco para Tratamento da TransaÃ§Ã£o ------------------------------------------------------------------------------------------------
        # Alguns campos podem ter conteÃºdo indevido para a construÃ§Ã£o do comando UPDATE. Pode ser um SQL injection ou um simples caractere que rompe
        # a cadeia de caracteres que montam o comando de atualizaÃ§Ã£o no Banco. Podemos usar o PHP e fazer uma substituiÃ§Ã£o de caracteres ou atÃ© mesmo
        # bloquear a execuÃ§Ã£o dos comandos que seguem este trecho.
        #
        # Neste ponto do programa podemos usar funÃ§Ãµes do PHP para trocar caracteres indevidos para o UPDATE.
        $_POST['txprenomes']=str_replace("'", "''", $_POST['txprenomes']);
        $_POST['txsobrenome']=str_replace("'", "''", $_POST['txsobrenome']);
        # Montando o comando de UPDATE
        # (EstÃ¡ quebrado em mais de uma linha mas isso nÃ£o faz diferenÃ§a para o PostgreSQL, porÃ©m fica mais fÃ¡cil de achar algum eventual erro)
        # Montando em uma variavel a data de cadastro no formato do BD
        $dtcadfuncionario=$_POST['anocad'].'-'.$_POST['mescad'].'-'.$_POST['diacad'];
        $dtcontrfuncionario=$_POST['anocontr'].'-'.$_POST['mescontr'].'-'.$_POST['diacontr'];
        $dtnascfuncionario=$_POST['anonasc'].'-'.$_POST['mesnasc'].'-'.$_POST['dianasc'];
        $cmd="UPDATE funcionarios SET txprenomes         = '$_POST[txprenomes]',
                             txsobrenome                = '$_POST[txsobrenome]',
                             cedepto      = '$_POST[cedepto]',
                             cefuncao        = '$_POST[cefuncao]',
                             nuramal  = '$_POST[nuramal]',
                             celogradouro = '$_POST[celogradouro]',
                             txcomplemento  = '$_POST[txcomplemento]',
                             dtcontratacao = '$dtcontrfuncionario',
                             ceniveleducacao              = '$_POST[ceniveleducacao]',
                             aosexo              = '$_POST[aosexo]',
                             dtnascimento              = '$dtnascfuncionario',
                             txresenha              = '$_POST[txresenha]',
                             vlsalario              = '$_POST[vlsalario]',
                             vlbonus              = '$_POST[vlbonus]',
                             vlcomissao              = '$_POST[vlcomissao]',
                             nucep              = '$_POST[nucep]',
                             dtcadfuncionario          = '$dtcadfuncionario'
                          WHERE cpfuncionario='$_POST[cpfuncionario]'";
        # Ajustando a tabela de simbolos recebidos/enviados para o BD para UTF8
        pg_query("SET NAMES 'utf8'");
        pg_query("SET CLIENT_ENCODING TO 'utf8'");
        pg_set_client_encoding('utf8');
        # exibindo mensagem de orientaÃ§Ã£o
        printf("Alterando o Registro...<br>\n");
        # Executando o case que grava (UPDATE) os dados na tabela funcionários.
        # Tratamento da TransaÃ§Ã£o
        # Inicio da transaÃ§Ã£o - No PostgreSQL se inica com o comando BEGIN. Colocamos dentro de um WHILE para poder
        # controlar o reinicio da transaÃ§Ã£o caso aconteça um DEADLOCK.
        $tentativa=TRUE;
        while ( $tentativa )
        { # 1.3.1-LaÃ§o de repetiÃ§Ã£o para tratar a transaÃ§Ã£o -------------------------------------------------------------------------------------
            $query = pg_send_query($dbp,"BEGIN");
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            # Depois que se inicia uma transaÃ§Ã£o o comando enviado para o BD deve ser atravÃ©s da funÃ§Ã£o pg_send_query().
            # Esta funÃ§Ã£o avisa ao PostgreSQL que devem ser usados os LOGs de transaÃ§Ã£o para acessar os dados.
            # A cada send_query o PostgreSQL responde com um sinal de status (erro ou nÃ£o erro).
            # Por conta disso deve-se "ler" este status com as funÃ§Ãµes pg_getr_result() e pg_result_error().
            # Executando o comando (montado for ado laÃ§o de repetiÃ§Ã£o da tentativa) e capturando um eventual erro.
            $comando=pg_send_query($dbp,$cmd);
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            $volta=pg_fetch_array($result);
            # O PrÃ³ximo SWITCH trata as situaÃ§Ãµes de erro. A funÃ§Ã£o pg_get_result($dbp) retorna o nÃºmero do erro do PostgreSQL.
            switch (TRUE)
            { # 1.3.1.1 - AvaliaÃ§Ã£o da situaÃ§Ã£o de erro (se existir) --------------------------------------------------------------------------------
                case $erro == "" :
                { # 1.3.1.1.1 - Nao tem erro! Concluir a transacao e Avisar o usuario. ----------------------------------------------------------------
                    # Comando que foi EXECUTADO no BD  SEM ERRO  podemos COMMITAR a transaÃ§Ã£o.
                    $query=pg_send_query($dbp,"COMMIT");
                    printf("Registro <b>Alterado</b> com sucesso!<br>\n");
                    $tentativa=FALSE;
                    $mostra=TRUE;
                    break;
                } # 1.3.1.1.1 -------------------------------------------------------------------------------------------------------------------------
                case $erro == "deadlock_detected" :
                { # 1.3.1.1.2 - Erro de DeadLock - Cancelar e Reiniciar a transacao -------------------------------------------------------------------
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=TRUE;
                    break;
                } # 1.3.1.1.2 -------------------------------------------------------------------------------------------------------------------------
                case $erro != '' AND  $erro!= 'deadlock_detected' :
                { # 1.3.1.1.3 - Erro! NÃƒO por deadlock. AVISAR o usuario. CANCELAR A transacao --------------------------------------------------------
                    printf("<b>Erro na tentativa de Inserir!</b><br>\n");
                    $mens=$result." : ".$erro;
                    printf("Mensagem: $mens<br>\n");
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=FALSE;
                    $mostra=FALSE;
                    break;
                } # 1.3.1.1.3 --------------------------------------------------------------------------------------------------------------------------
            } # 1.3.1.1 - Fim do SWITCH tratando os status da transaÃ§Ã£o ------------------------------------------------------------------------------
            $resultfinal=pg_get_result($dbp);
            $errofinal=pg_result_error($resultfinal);
        } # 1.3.1-Fim do LaÃ§o de repetiÃ§Ã£o para tratar a transaÃ§Ã£o -------------------------------------------------------------------------------
        if ( $mostra )
        { # Executando a funÃ§Ã£o do subprograma com o valor de $CP como PK.
            funcionrfun02("$_POST[cpfuncionario]");
        } # -----------------------------------------------------------------------------------------------------------------------------------------
        # montando os botÃµes do form com a funÃ§Ã£o botoes e os parÃ¢metros:
        # (PÃ¡gina,Menu,SaÃ­da,Reset,AÃ§Ã£o,$salto) TRUE | FALSE para os 4 parÃ¢metros esq-dir.
        botoes(FALSE,TRUE,TRUE,FALSE,NULL,$salto);
        printf("<br>\n");
        break;
    } # 1.3-Fim do Bloco de Tratamento da TransaÃ§Ã£o -------------------------------------------------------------------------------------------
} # 1-Fim do divisor de blocos principAl ----------------------------------------------------------------------------------------------------
terminapagina($acao,"funcionrincluir.php",FALSE);
?>
<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcionrincluir.php
# Descricao...: Montar do formulário para entrada de dados e executar o controle da transação de INSERT
# Autor.......: João Maurício Hypólito - Copie mas diga quem fez
# Objetivo....: Montar um form usando a função local fun03 com parâmetro INCLUIR, depois lê os dados e executa o controle da transação de INSERT.
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-25 - Ajustes e testes gerais.
#               2017-10-26 - Revisão e descarta de linhas desnecessárias.
# Modificação.: 2017-11-11 - Adaptado para funcionários. Felipe Bertanha
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Carregar o ToolsKit (e executar as funÃ§Ãµes Gerais disponÃ­veis no grupo de funÃ§Ãµes)
require_once("../../toolskit.php");
# Carregar o arquivo com as funÃ§Ãµes locais da tabela funcionarios
require_once("funcionrfuncoes.php");
# Atribuindo o valor de $acao, $passo, $salto, $corfundo e $corfonte
$acao = "Incluir";
$passo= ( ISSET($_POST['passo']) ) ? $passo=$_POST['passo'] : '1';
$salto= ( ISSET($_POST['salto']  ) ? $_POST['salto']+1 : '1');   // $salto recebe $_POST['salto']+1 (se houver), senão 1
$corfundo="#FFDEAD"; # navajowhite
$corfonte="#000000"; # black
# executar a função que inicia a página (define cor de fundo, cor de fonte, alinhamento e textos iniciais)
iniciapagina($corfundo,$corfonte,$acao);
# Desvio de Blocos Principais baseado em $passo. ------------------------------------------------------------------------------------------------
SWITCH (TRUE)
{ # 1-Bloco divisor principal do programa -------------------------------------------------------------------------------------------------------
    case ( $passo==1 ):
    { # 1.1-Montagem do Formulário para entrada de dados. -----------------------------------------------------------------------------------------
        # Montando o form de leitura dos dados dos campos da tabela (os campos FORM terão os mesmos NOMES dos campos da tabela.
        # Aqui vamos montar um form 'passando' o valor $passo para 2.
        $passo=$passo+1;
        funcionrfun03($acao,$passo,$salto);
        break;
    } # 1.1-Fim do Bloco que monta o form de entrada de dados -------------------------------------------------------------------------------------
    case ( $passo==2 ):
    { # 1.2-Bloco para Tratamento da Transação ----------------------------------------------------------------------------------------------------
        # Executando o case que grava (INSERT) os dados na tabela funcionarios.
        # Alguns campos podem ter conteúdo indevido para a construção do comando INSERT. Pode ser um SQL injection ou um simples caractere que rompe
        # a cadeia de caracteres que montam o comando de atualização no Banco. Podemos usar o PHP e fazer uma substituição de caracteres ou até mesmo
        # bloquear a execução dos comandos que seguem este trecho.
        #
        # Neste ponto do programa podemos usar funções do PHP para trocar caracteres indevidos para o INSERT.
        $_POST['txprenomes']=str_replace("'", "''", $_POST['txprenomes']);
        # Ajustando a tabela de simbolos recebidos/enviados para o BD para UTF8
        pg_query("SET NAMES 'utf8'");
        pg_query("SET CLIENT_ENCODING TO 'utf8'");
        pg_set_client_encoding('utf8');
        # exibindo mensagem de orientação
        printf("Incluindo o Registro...<br>\n");
        #--------------------------------------------------------------------------------------------------------------------------------------------
        # Tratamento da Transação
        # Inicio da transação - No PostgreSQL se inica com o comando BEGIN. Colocamos dentro de um WHILE para poder
        # controlar o reinicio da transação caso aconteça um DEADLOCK.
        $tentativa=TRUE;
        while ( $tentativa )
        { # 1.2.1-Laço de repetição para tratar a transação -----------------------------------------------------------------------------------------
            $query = pg_send_query($dbp,"BEGIN");
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            # Depois que se inicia uma transação o comando enviado para o BD deve ser através da função pg_send_query().
            # Esta função avisa ao PostgreSQL que devem ser usados os LOGs de transação para acessar os dados.
            # A cada send_query o PostgreSQL responde com um sinal de status (erro ou não erro).
            # Por conta disso deve-se "ler" este status com as funções pg_getr_result() e pg_result_error().
            # Montando em uma variavel a data de cadastro no formato do BD
            $dtcadfuncionario=$_POST['anocad'].'-'.$_POST['mescad'].'-'.$_POST['diacad'];
            $dtcontrfuncionario=$_POST['anocontr'].'-'.$_POST['mescontr'].'-'.$_POST['diacontr'];
            $dtnascfuncionario=$_POST['anonasc'].'-'.$_POST['mesnasc'].'-'.$_POST['dianasc'];
            # Vamos pegar o último código gravado na tabela funcionarios. Este trecho fica 'dentro' da transação para gerar
            # o bloqueio na página de dados que vai gravar o próximo registro.
            # Estamos gerando o valor da PK e NÃO usando campos autoincrementados PORQUE este recurso não está disponível em todos os SGBDs
            # e SE UM DIA um ilustre aluno trabalhar com um destes SGBD vai se lembrar que um professor ensinou a trabalhar a determinação
            # do próximo valor de uma chave primária DENTRO da aplicação. Para 'brincar' com o conceito...
            # SUPONDO que o passo de incremento seja 1 (um)... escrevemos.
            $proxpk=pg_result(pg_query("SELECT max(cpfuncionario)+1 as CMAX FROM funcionarios"),0,'CMAX');
            # A tabela pode estar vazia, neste caso o CMAX é nulo e $proxpk NÃO recebe valor. Então a proxima PK deve ser 1.
            $cp=( isset($proxpk) ) ? $proxpk : 1;
            # Montando o comando de INSERT (Dentro do laço de repatição das tentativas porque o valor da PK depende da leitura da tabela 'dentro' da transação)
            $cmd="INSERT INTO funcionarios VALUES ('$cp',
                                        '$_POST[txprenomes]',
                                        '$_POST[txsobrenome]',
                                        '$_POST[cedepto]',
                                        '$_POST[cefuncao]',
                                        '$_POST[nuramal]',
                                        '$_POST[celogradouro]',
                                        '$_POST[txcomplemento]',
                                        '$dtcontrfuncionario',
                                        '$_POST[ceniveleducacao]',
                                        '$_POST[aosexo]',
                                        '$dtnascfuncionario',
                                        '$_POST[txresenha]',
                                        '$_POST[vlsalario]',
                                        '$_POST[vlbonus]',
                                        '$_POST[vlcomissao]',
                                        '$_POST[nucep]',
                                        '$dtcadfuncionario') RETURNING cpfuncionario";
            # O comando INSERT pode ser escrito em uma só linha (mais extenso), o que pode dificultar encontrar um erro eventual.
            # Na forma 'quebrada' fica mais fácil entender o comando.
            # Para o SGBD os sinais de enter e os espaços em branco não afeta o comando INSERT.
            # printf("$cmd<br>\n"); # Se quiser ver o comando na fase de teste, tire o comentário no início da linha.
            $comando=pg_send_query($dbp,$cmd);
            $result=pg_get_result($dbp);
            $erro=pg_result_error($result);
            $volta=pg_fetch_array($result);
            $PK=$volta['cpfuncionario'];
            # O Próximo SWITCH trata as situações de erro. A função pg_get_result($dbp) retorna o número do erro do PostgreSQL.
            # Dentro deste SwitchCase atribui-se o valor de $mostra.
            # $mostra vale FALSE se acontecer algum erro na execução e TRUE se a transação terminar SEM erro.
            switch (TRUE)
            { # 1.2.1.1 - Avaliação da situação de erro (se existir).
                case $erro == "" :
                { # 1.2.1.1.1 - Nao tem erro! Concluir a transacao e Avisar o usuario. --------------------------------------------------------------------
                    # Comando que foi EXECUTADO no BD podemos MOSTRAR o comando na tela para suporte ao usuário.
                    #printf("$cmd<br>\n");
                    #printf("$PK<br>\n");
                    $query=pg_send_query($dbp,"COMMIT");
                    printf("Registro <b>Inserido</b> com sucesso!<br>\n");
                    $tentativa=FALSE;
                    $mostra=TRUE;
                    break;
                } # 1.2.1.1.1 -------------------------------------------------------------------------------------------------------------------------
                case $erro == "deadlock_detected" :
                { # 1.2.1.1.2 - Erro de DeadLock - Cancelar e Reiniciar a transacao -----------------------------------------------------------------------
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=TRUE;
                    break;
                } # 1.2.1.1.2 -------------------------------------------------------------------------------------------------------------------------
                case $erro != '' AND  $erro!= 'deadlock_detected' :
                { # 1.2.1.1.3 - Erro! NÃO por deadlock. AVISAR o usuario. CANCELAR A transacao --------------------------------------------------------
                    printf("<b>Erro na tentativa de Inserir! + $cmd</b><br>\n");
                    $mens=$result." : ".$erro;
                    printf("Mensagem: $mens<br>\n");
                    $query=pg_send_query($dbp,"ROLLBACK");
                    $tentativa=FALSE;
                    $mostra=FALSE;
                    break;
                } # 1.2.1.1.3 -------------------------------------------------------------------------------------------------------------------------
            } # 1.2.1.1 - Fim do SWITCH tratando os status da transação -----------------------------------------------------------------------------
            $resultfinal=pg_get_result($dbp);
            $errofinal=pg_result_error($resultfinal);
        } # 1.2.1 - Fim do Laço de repetição para tratar a transação ----------------------------------------------------------------------------
        if ( $mostra )
        { # Executando a função do subprograma com o valor de $CP como PK. --------------------------------------------------------------------------
            funcionrfun02("$PK");
        } # -----------------------------------------------------------------------------------------------------------------------------------------
        # montando os botões do form com a função botoes e os parâmetros:
        # (Página,Menu,Saída,Reset,Ação,$salto) TRUE | FALSE para os 4 parâmetros esq-dir.
        botoes(FALSE,TRUE,TRUE,FALSE,NULL,$salto);
        printf("<br>\n");
        break;
    } # 1.2-Fim do Bloco de Tratamento da Transação -------------------------------------------------------------------------------------------
} # 1-Fim do divisor de blocos principal ----------------------------------------------------------------------------------------------------
terminapagina($acao,"funcionrincluir.php",FALSE);
?>
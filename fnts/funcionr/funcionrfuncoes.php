<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: funcionrfuncoes.php
# Descricao...: Segmentos de códigos que implementam funções usados nos programas de gerenciamento da tabela: funcionários.
# Autor.......: Joao Mauricio Hypolito - Copie mas diga quem fez.
# Objetivo....: Programas (código-fonte comentado) que executam as funções:
#               - Montar uma caixa de seleção para escolha de um registro (fun01) - usado na consulta, alteração e exclusão
#               - Mostrar os valores dos campos de um registro escolhido (fun03) - usado na consulta e exclusão
#               - Montar o formulário para entrada de dados (fun03) - usado na inclusão e alteração
# Criacao.....: 2017-10-24
# Atualizacao.: 2015-10-25 - Ajustes gerais nas três funções
# Modificação.: 2017-11-11 - Adaptado para funcionários. Felipe Bertanha
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function funcionrfun01($acao,$passo,$salto)
{ # Esta função monta uma caixa de seleção para escolha de um registro para Consulta, Alteração ou Exclusão.
    # determinando qual programa será chamado pela função
    $prg=( $acao=='Consultar' ) ? "funcionrconsultar.php" : (( $acao=='Alterar' ) ? "funcionralterar.php" : "funcionrexcluir.php");
    $passo=$passo+1;
    # Executando comandos em SQL nas tabelas da base que foi acessada. Usamos a função pg_query.
    # Esta função RETORNA: O NOME da tabela acessada, os CAMPOS e os ENDEREÇOS de Registros lidos no comando
    $sql=pg_query("SELECT cpfuncionario,txprenomes FROM funcionarios ORDER by txprenomes");
    # Podemos montar repetidas vezes os valores de UM VETOR com os dados lidos.
    # Aqui vamos montar um form 'passando' o valor $ passo para 2 e repetindo o valor de $salto (sempre criado no programa principal)
    printf("<form action='$prg' method='POST'>\n");
    printf("<input type='hidden' name='acao'  value='$acao'>\n");
    printf("<input type='hidden' name='passo' value='$passo'>\n");
    printf("<input type='hidden' name='salto' value='$salto'>\n");
    # A caixa de seleção DEVE ter um nome para ser identificado no vetor $_POST[] do programa que recebe os dados dos campos do form
    printf("<select name='cpfuncionario'>\n");
    while ( $reg=pg_fetch_array($sql) )
    {
        printf("<option value='$reg[cpfuncionario]'>$reg[txprenomes] - ($reg[cpfuncionario])</option>\n");
    }
    printf("</select>\n");
    # montando os botões do form com a função botoes e os parâmetros:
    # (Página,Menu,Saída,Reset,Ação,$salto) TRUE | FALSE para os 4 parâmetros esq-dir.
    botoes(FALSE,TRUE,TRUE,TRUE,$acao,$salto);
    # Esta função é geral e está escrita no toolskit.
    printf("</form>\n");
}
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function funcionrfun02($PK)
{ # Esta função recebe um valor da PK, consulta o registro e mostra o registro em uma tabela.
    # Este próximo comando é uma junção de funcionários, departamentos, funções, logradouros e niveleducacao
    $sql   = pg_query("select * from funcionarios func
                                    inner join departamentos dep
                                      on func.cedepto = dep.cpdepto
                                    inner join funcoes funco
                                      on func.cefuncao = funco.cpfuncao
                                    inner join logradouros log
                                      on func.celogradouro = log.cplogradouro
                                    inner join niveisdeeducacao niv
                                      on func.ceniveleducacao = niv.cpniveleducacao
                                    where func.cpfuncionario = '$PK'");
    # O resultado do comando SQL acima é um registro com todos os dados do médico consultado e mais os campos das tabelas que tem CE em médicos.
    # o comando a seguir coloca os valores em um Vetor.
    $reg   = pg_fetch_array($sql);
    $dtcad = explode("-",$reg['dtcadfuncionario']);
    $dtcontr = explode("-",$reg['dtcontratacao']);
    $dtnasc = explode("-",$reg['dtnascimento']);
    printf("<table>\n");
    printf("<tr><td>C&oacute;digo</td>         <td>$reg[cpfuncionario]</td></tr>\n");
    printf("<tr><td>Nome</td>                  <td>$reg[txprenomes]</td></tr>\n");
    printf("<tr><td>Sobrenome</td>                  <td>$reg[txsobrenome]</td></tr>\n");
    printf("<tr><td>Departamento</td>                   <td>$reg[txnomedepto] - ($reg[cedepto])</td></tr>\n");
    printf("<tr><td>Função</td>    <td>$reg[txnomefuncao] - ($reg[cefuncao])</td></tr>\n");
    printf("<tr><td>Ramal</td>    <td>$reg[nuramal]</td></tr>\n");
    printf("<tr><td>Logradouro</td>    <td>$reg[txnomelogradouro] - ($reg[celogradouro])</td></tr>\n");
    printf("<tr><td>Complemento</td>           <td>$reg[txcomplemento]</td></tr>\n");
    printf("<tr><td>Contratado em</td>         <td>$dtcontr[2]/$dtcontr[1]/$dtcontr[0]</td></tr>\n");
    printf("<tr><td>Nível Educação</td>    <td>$reg[txnomecomum] - ($reg[ceniveleducacao])</td></tr>\n");
    printf("<tr><td>Sexo</td><td>%s</td></tr>\n",($reg['aosexo']=='F') ? "Feminino" : "Masculino");
    printf("<tr><td>Data Nascimento</td>         <td>$dtnasc[2]/$dtnasc[1]/$dtnasc[0]</td></tr>\n");
    printf("<tr><td>Resenha</td>                  <td>$reg[txresenha]</td></tr>\n");
    printf("<tr><td>Salário</td>                  <td>$reg[vlsalario]</td></tr>\n");
    printf("<tr><td>Bônus</td>                  <td>$reg[vlbonus]</td></tr>\n");
    printf("<tr><td>Comissão</td>                  <td>$reg[vlcomissao]</td></tr>\n");
    printf("<tr><td>CEP</td>                  <td>$reg[nucep]</td></tr>\n");

    # O comando a seguir executa um operador ternário dentro de um printf().

    printf("<tr><td>Cadastrado em</td>         <td>$dtcad[2]/$dtcad[1]/$dtcad[0]</td></tr>\n");
    printf("</table>\n");
}
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
function funcionrfun03($acao,$passo,$salto)
{ # Esta função monta um formulário para inclusão ou alteração dos dados de um recebe um valor de PK,
    # monta a tela com os campos para digitação de valores nos campos.
    # Esta função pode ser usada no caso de programa onde o valor da pk será gerado pelo sistema.
    # Caso o valor da PK seja informado pelo usuário esta função deve ser ligeriamente alterada.
    $prg=( $acao=='Incluir' ) ? "funcionrincluir.php" : "funcionralterar.php" ;
    # Montando o form de leitura dos dados dos camposque devem ser alterados na tabela (os campos FORM terao os mesmos NOMES dos campos da tabela.
    # Podemos montar repetidas vezes os valores de UM VETOR com os dados lidos.
    # Aqui vamos montar um form 'passando' o valor $ passo para 3.
    printf("<form action='$prg'  method='POST'>\n");
    printf("<input type='hidden' name='acao'  value='$acao'>\n");
    printf("<input type='hidden' name='passo' value='$passo'>\n");
    printf("<input type='hidden' name='salto' value='$salto'>\n");
    # Agora se monta uma tabela com os campos para entrada de dados.
    # SE a acao for INCLUIR, então um vetor $reg sem conteúdo deve ser montado,
    # SE a acao for ALTERAR, então um vetor $reg é montado com campos da tabelas funcionários (e com os valores para a PK escolhida)
    printf("$acao<br>\n");
    if ( $acao=='Incluir' )
    {
        $reg['cpfuncionario']='';
        $reg['txprenomes']='';
        $reg['txsobrenome']='';
        $reg['cedepto']='';
        $reg['cefuncao']='';
        $reg['nuramal']='';
        $reg['celogradouro']='';
        $reg['txcomplemento']='';
        $reg['diacontr']='';
        $reg['mescontr']='';
        $reg['anocontr']='';
        $reg['ceniveleducacao']='';
        $reg['aosexo']='';
        $reg['dianasc']='';
        $reg['mesnasc']='';
        $reg['anonasc']='';
        $reg['txresenha']='';
        $reg['vlsalario']='';
        $reg['vlbonus']='';
        $reg['vlcomissao']='';
        $reg['nucep']='';

        $reg['diacad']='';
        $reg['mescad']='';
        $reg['anocad']='';
        $prg='funcionrincluir.php';
        $mens="Ser&aacute; gerado pelo sistema";
    }
    else
    {
        # Criar um vetor com valores dos campos do registro lido na tabela.
        $reg=pg_fetch_array(pg_query("SELECT * FROM funcionarios WHERE cpfuncionario='$_POST[cpfuncionario]'"));
        # Este vetor PODE ser manipulado normalmente
        $dtcad=explode("-",$reg['dtcadfuncionario']);
        $reg['diacad']=$dtcad[2];
        $reg['mescad']=$dtcad[1];
        $reg['anocad']=$dtcad[0];
        $dtcontr=explode("-",$reg['dtcontratacao']);
        $reg['diacontr']=$dtcontr[2];
        $reg['mescontr']=$dtcontr[1];
        $reg['anocontr']=$dtcontr[0];
        $dtnasc=explode("-",$reg['dtnascimento']);
        $reg['dianasc']=$dtnasc[2];
        $reg['mesnasc']=$dtnasc[1];
        $reg['anonasc']=$dtnasc[0];
        $prg='funcionralt01.php';
        printf("<input type='hidden' name='cpfuncionario' value=$_POST[cpfuncionario]>\n");
        $mens=$reg['cpfuncionario']." - N&Atilde;O Ser&aacute; Alterado pelo sistema";
    }
    printf("<table>\n");
    printf("<tr><td>C&oacute;digo:</td><td>$mens</td></tr>\n");
    printf("<tr><td>Nome:</td><td><input type='text' name='txprenomes' value=\"$reg[txprenomes]\" size=60 maxlength=250></td></tr>\n");
    printf("<tr><td>Sobrenome:</td><td><input type='text' name='txsobrenome' value=\"$reg[txsobrenome]\" size=60 maxlength=250></td></tr>\n");
    printf("<tr><td>Departamento:</td><td>");
    $sql=pg_query("SELECT cpdepto,txnomedepto FROM departamentos ORDER by txnomedepto");
    printf("<select name='cedepto'>\n");
    while ( $sel=pg_fetch_array($sql) )
    {
        $selected=( $reg['cedepto']==$sel['cpdepto'] ) ? " SELECTED" : "" ;
        printf("<option value='$sel[cpdepto]'$selected>$sel[txnomedepto] - ($sel[cpdepto])</option>\n");
    }
    printf("</select>\n");
    printf("</td></tr>\n");

    printf("<tr><td>Função:</td><td>");
    $sql=pg_query("SELECT cpfuncao,txnomefuncao FROM funcoes ORDER by txnomefuncao");
    printf("<select name='cefuncao'>\n");
    while ( $sel=pg_fetch_array($sql) )
    {
        $selected=( $reg['cefuncao']==$sel['cpfuncao'] ) ? " SELECTED" : "" ;
        printf("<option value='$sel[cpfuncao]'$selected>$sel[txnomefuncao] - ($sel[cpfuncao])</option>\n");
    }
    printf("</select>\n");
    printf("</td></tr>\n");

    printf("<tr><td>Ramal:</td><td><input type='text' name='nuramal' value='$reg[nuramal]' size=25 maxlength=25></td></tr>\n");

    printf("<tr><td>Moradia:</td><td>");
    $sql=pg_query("SELECT cplogradouro,txnomelogradouro FROM logradouros ORDER by txnomelogradouro");
    printf("<select name='celogradouro'>\n");
    while ( $sel=pg_fetch_array($sql) )
    {
        $selected=( $reg['celogradouro']==$sel['cplogradouro'] ) ? " SELECTED" : "" ;
        printf("<option value='$sel[cplogradouro]'$selected>$sel[txnomelogradouro] - ($sel[cplogradouro])</option>\n");
    }
    printf("</select>\n");
    printf("</td></tr>\n");
    printf("<tr><td>Complemento:</td><td><input type='text' name='txcomplemento' value='$reg[txcomplemento]' size=25 maxlength=25></td></tr>\n");

    printf("<tr><td>Data de Contracação</td><td>");
    printf("<input type='text' name='diacontr' value='$reg[diacontr]' size=2 maxlength=2>/");
    printf("<input type='text' name='mescontr' value='$reg[mescontr]' size=2 maxlength=2>/");
    printf("<input type='text' name='anocontr' value='$reg[anocontr]' size=4 maxlength=4></td></tr>\n");


    printf("<tr><td>Nível Educação:</td><td>");
    $sql=pg_query("SELECT cpniveleducacao,txnomecomum FROM niveisdeeducacao ORDER by txnomecomum");
    printf("<select name='ceniveleducacao'>\n");
    while ( $sel=pg_fetch_array($sql) )
    {
        $selected=( $reg['ceniveleducacao']==$sel['cpniveleducacao'] ) ? " SELECTED" : "" ;
        printf("<option value='$sel[cpniveleducacao]'$selected>$sel[txnomecomum] - ($sel[cpniveleducacao])</option>\n");
    }
    printf("</select>\n");
    printf("</td></tr>\n");

    $check_m=($reg['aosexo']=='M') ? " checked" : "";
    $check_f=($reg['aosexo']=='F') ? " checked" : "";
    printf("<tr><td>Sexo:</td><td><input type='radio' name='aosexo' value='M'$check_m> Masculino <input type='radio' name='aosexo' value='F'$check_f>Feminino</td></tr>\n");

    printf("<tr><td>Data de Nascimento</td><td>");
    printf("<input type='text' name='dianasc' value='$reg[dianasc]' size=2 maxlength=2>/");
    printf("<input type='text' name='mesnasc' value='$reg[mesnasc]' size=2 maxlength=2>/");
    printf("<input type='text' name='anonasc' value='$reg[anonasc]' size=4 maxlength=4></td></tr>\n");

    printf("<tr><td>Resenha:</td><td><textarea name='txresenha' cols='60' rows='4'>$reg[txresenha]</textarea></td></tr>\n");

    printf("<tr><td>Salário:</td><td><input type='text' name='vlsalario' value='$reg[vlsalario]' size=25 maxlength=25></td></tr>\n");

    printf("<tr><td>Bônus:</td><td><input type='text' name='vlbonus' value='$reg[vlbonus]' size=25 maxlength=25></td></tr>\n");

    printf("<tr><td>Comissão:</td><td><input type='text' name='vlcomissao' value='$reg[vlcomissao]' size=25 maxlength=25></td></tr>\n");

    printf("<tr><td>CEP:</td><td><input type='text' name='nucep' value='$reg[nucep]' size=25 maxlength=25></td></tr>\n");



    printf("<tr><td>Data de Cadastro</td><td>");
    printf("<input type='text' name='diacad' value='$reg[diacad]' size=2 maxlength=2>/");
    printf("<input type='text' name='mescad' value='$reg[mescad]' size=2 maxlength=2>/");
    printf("<input type='text' name='anocad' value='$reg[anocad]' size=4 maxlength=4></td></tr>\n");
    printf("<tr><td>&nbsp;</td><td>");
    # montando os botões do form com a função botoes e os parâmetros:
    # (Página,Menu,Saída,Reset,Ação,$salto) TRUE | FALSE para os 4 parâmetros esq-dir.
    botoes(TRUE,TRUE,TRUE,TRUE,$acao,$salto); # função geral do toolskit
    printf("</td></tr>\n");
    printf("</table>\n");
    printf("</form>\n");
}
## Aqui termina a declaração das três funções de tratamento da tabela funcionários. As outras funções (sistêmicas) estão escritas no toolskit.
?>
<?php
###################################################################################################################################################################################
# Programa...: toolskit
# Descrição..: Conjunto com as funções desenvolvidas para facilitar a construção de programas. Todas as funções são mantidas em arquivo para facilitar a edição e estrutura.
#              Este arquivio DEVE estar localizado no diretório FNTS (um nível acima do diretório onde devem estar os arquivos dos programas de manutenção de dados de uma tabela).
# Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
# Criação....: 2014-11-10
# Atualização: 2017-05-10 - Reorganização das função com mudança em parâmetros nas funções
###################################################################################################################################################################################
# Trecho de declaração das funções. Para cada uma apresentamos um cabeçalho curto com nome/parâmetros/descrição/histórico de atualizações e objetivo
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function iniciapagina($cordefundo,$corfonte,$acao)
{ # Esta função recebe a cor de fundo e texto e um texto com a ação escolhida no menu | monta as tagsa de iniciação da tela
    ###################################################################################################################################################################################
    # Função....: iniciapagina
    # Parametros: Esta Função recebe a cor de fundo da pag. na var. $cordefundo
    # Descrição.: Esta Função emite as TAGS que iniciam uma tela com a cor de fundo padrao do projeto, alinha o texto com um TAB para a direita e a determina o fonte do projeto.
    ###################################################################################################################################################################################
    # Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
    # Criação....: 2009-09-23
    # Atualização: 2009-09-23
    ###################################################################################################################################################################################
    printf("<html xml:lang='pt-BR' lang='pt-BR' dir='ltr'>\n");
    # declara o conjunto de caracteres universais (UTF-8)
    printf("<head>\n");
    printf("  <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n"); # ISO-8859-1
    printf("</head>\n");
    # inicia o corpo da pagina com a cor indicada no parametro
    printf("<body bgcolor='$cordefundo'>\n");
    # A função recebe como parâmetro a cor do fonte usado nos textos das telas (exceção dos destaques)
    # determina a fonte TAHOMA com tamanho 3
    printf("<font face='tahoma' size=3 color='$corfonte'>\n");
    # posiciona os textos com um TAB para a direita. Este alinhamento melhora a visibilidade da tela.
    printf("<dir>\n");
    $titulo = ( $acao=="Abertura")  ? "Abertura<br>" :
        (( $acao=="Incluir")   ? "Inclus&atilde;o" :
            (( $acao=="Consultar") ? "Consulta" :
                (( $acao=="Alterar")   ? "Altera&ccedil;&atilde;o" :
                    (( $acao=="Excluir")   ? "Exclus&atilde;o" :
                        (( $acao=="Listar")    ? "Listagem:" : "" ) ) ) ) );
    printf("<font color=red><b>$titulo</b></font>\n");

    ################################ Fim da Função IniciaPagina ################################
}
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function botoes($p,$m,$s,$r,$acao,$salto)
{ # Esta função recebe TRUE | FALSE para montar as tags que exibem os botões de navegação do sistema.
    ###################################################################################################################################################################################
    # Função....: botoes
    # Parametros: Esta Função recebe TRUE|FALSE para os parâmetros que apontam para montar as tags de exibição dos botões de navegação
    # Descrição.: Esta Função emite as TAGS para "< 1 Pag.", "< Menu","Saída","Limpar" e "Ação"
    ###################################################################################################################################################################################
    # Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
    # Criação....: 2017-05-31
    # Atualização: 2017-05-31 - Todo desenvolvimento e teste da função.
    ###################################################################################################################################################################################
    $barra=($p) ? "<input type='button' value='< 1 Pag.' onclick='history.go(-1)'>" : "";
    $barra=($m) ? $barra."<input type='button' value='< Menu' onclick='history.go(-$salto)'>" : $barra;
    $barra=($s) ? $barra."<input type='button' value='< Sa&iacute;da' onclick='history.go(-($salto+1))'>" : $barra;
    $barra=($r) ? $barra."<input type='reset'  value='Limpar'>" : $barra;
    $barra=( ISSET($acao) ) ? $barra."<input type='submit' value='$acao'>" : $barra;
    printf("$barra<br>\n");
}
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function terminapagina($texto,$prg,$center)
{
    ###################################################################################################################################################################################
    # Função.....: terminapagina
    # Parametros.: Esta Função recebe os parâmetros: $texto - descreve a ação (apresentado no lado esquerdo da linha de rodapé),
    #                                                $prg - código do programa (apresentado lado direito da linha de rodapé) e
    #                                                $center - TRUE/FALSE para colocar a linha de rodapé centralizada ou não.
    # Descrição..: Esta Função emite uma linha no final da página e coloca uma mensagem de Autoria.
    ###################################################################################################################################################################################
    # Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
    # Criação....: 2009-03-27
    # Atualização: 2009-09-17
    ###################################################################################################################################################################################
    $ano=date('Y');
    printf("%s",($center) ? "<center>" : "" ); # Este comando combina um operador ternário DENTRO print().
    printf("<font size=2 color='gray'>$texto - Resolu&ccedil;&atilde;o m&iacute;nima de 1280x720 &copy; Copyright $ano, FATEC Ourinhos - $prg</font>\n");
    printf("</dir>\n</font>\n"); # Estas duas TAGS fecham TAGS aberta no iniciapágina.
    printf("%s</body>\n</html>\n",($center) ? "</center>" : "" );
    ################################ Fim da Função terminapagina ################################
}
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function mostracampos($campo,$entidade,$pk,$valor)
{
    ###################################################################################################################################################################################
    # Função.....: mostracampos
    # Parametros.: Esta Função recebe os parâmetros: $campo - nome do campo que deve ter seu valor retornado,
    #                                                $entidade - nome da entidade onde o campo está,
    #                                                $pk - nome da chave primária da tabela e
    #                                                $valor - valor assumido na $pk
    # Descrição..: Esta Função retorna no ponto de chamada o valor do campo da tabela que foi projetado.
    ###################################################################################################################################################################################
    # Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
    # Criação....: 2009-03-27
    # Atualização: 2009-09-17
    ###################################################################################################################################################################################
    global $dbp;
    # em um só comando Projeta e retorna o valor de um campo como resposta da função.
    return pg_result(pg_query($dbp,"SELECT $campo FROM $entidade WHERE $pk='$valor'"),0,"$campo");
    ################################ Fim da Função mostracampos ################################
}
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
function conecta_pg($host,$porta,$dbname,$user,$senha)
{
    ###################################################################################################################################################################################
    # Função.....: conecta_pg
    # Descrição..: Esta função faz monte a conexão com o SGBD PostgreSQL
    # Observação.: Recebe 4 parâmetros: $host   - Nome do Host que executa o serviço do SGBD (localhost, para o servidor local)
    #                                   $dbname - Nome da Base de Dados que será acessada
    #                                   $user   - Nome do usuário que tem acesso permitido na Base (ver permissões com o DBA do SGBD)
    #                                   $senha  - Senha de conexão do usuário na base (e no SGBD).
    # Autor......: João Maurício Hypólito - Use! Mas fale quem fez!
    # Criação....: 2013-05-02
    # Alteração..: 2014-10-15
    #              2017-01-20 - inclui o parâmetro $porta para receber o número da porta para o caso de alguém ter mudado o número na instalação do PostgreSQL.
    ###################################################################################################################################################################################
    $con_string = "host='".$host."' port=".$porta." dbname='".$dbname."' user='".$user."' password='".$senha."'";
    # Conectando o PostgreSQL. O Ponteiro que retorna na conexão DEVE SER armazenado em uma variavel GLOBAL.
    global $dbp;
    # Fazendo a conexão com o banco de dados.
    $dbp = pg_connect($con_string) or die ("Problemas para Conectar no Banco de Dados PostgreSQL: <br>$con_string");
    # Agora vamos 'ajustar' os caracteres acentuados
    pg_query("SET NAMES 'utf8'");
    pg_query("SET CLIENT_ENCODING TO 'utf8'");
    pg_set_client_encoding('utf8'); # para a conexão com o PostgreSQL
    # Fim da função conecta_pg
    #################################################################################################################################################################################
}
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
#----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
###################################################################################################################################################################################
# Aqui termina a declaração das Funções.
# EXECUTANDO a função de CONEXÃO
################################################### Fim das Funções ###################################################
# Aqui começa o bloco principal do programa ToolsKit
header('content-type: text/html; charset=utf-8');
###################################################################################################################################################################################
# Para fazer a conexão com o PostgreSQL executamos a função conecta_pg com os 4 parâmetros: hostname, database name, username e password
conecta_pg("localhost",5432,"testesql","postgres","postgres"); # Com esta linha comentada NÃO se executa a conexão.
?>
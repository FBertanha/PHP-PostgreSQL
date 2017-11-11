<?php
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
# Programa....: medicosframe.php
# Descricao...: Montagem de Quadros dos programas de Gerenciamento dos Dados de medicos
# Autor.......: João Maurício Hypólito - Copie mas diga quem fez.
# Objetivo....: Montar uma página com dois quadros (Q1 e Q2) dividindo a tela horizontalmente em 75 pixels (Q1).
#               No frame Q1 carrega o menu (medicosmenu.php) e no Q2 carrega a página de abertura do site (medicosabre.php).
# Criacao.....: 2017-10-24
# Atualizacao.: 2017-10-24 - Ajustes nos parâmetros CSS que customizam os frames.
#--------------------------------------------------------------------------------------------------------------------------------------------------------------
printf("<html>\n");
printf("<iframe name='Q1' src='./medicosmenu.php' style=\"position:fixed;
                                                            top:0px;
                                                            left:0px;
                                                            bottom:0px;
                                                            right:0px;
                                                            width:100%%;
                                                            height:75;
                                                            border:none;
                                                            margin:0;
                                                            padding:0;
                                                            overflow:hidden;
                                                            z-index:999999;\" scrolling=no></iframe>\n");
printf("<iframe name='Q2' src='./medicosabre.php' style=\"position:fixed;
                                                            top:75px;
                                                            left:0px;
                                                            bottom:0px;
                                                            right:0px;
                                                            width:100%%;
                                                            height:100%%;
                                                            border:none;
                                                            margin:0;
                                                            padding:0;
                                                            overflow:hidden;
                                                            z-index:999999;\"></iframe>\n");
printf("</html>\n");
?>
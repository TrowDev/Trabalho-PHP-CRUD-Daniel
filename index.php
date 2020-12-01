<!DOCTYPE html>
<html>

<head>
    <title>Minha Agenda</title>
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script> -->
    <meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="robots" content="noindex, nofollow" />
    <link rel="stylesheet" href="style.css">
	<link rel="shortcut icon" href="https://i.imgur.com/bDsFDG2.png" />
    <!-- Latest compiled and minified CSS --> 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="card-header text-center" id="header">
                    Adicionar Evento
                </div>
                <div class="card-body">
                    <div class="row">
                        <input type="hidden" id="eventoUpdateID" value="0">
                        <div class="col-md-6 col-sm-3 col-xs-12">
                            <div class="form-group">
                                <label>Evento</label>
                                <input type="text" autocomplete="off" class="form-control" placeholder="Qual evento?" 
                                    id="evento" name="evento" required="" />
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Data</label>
                                <input type="text" autocomplete="off" class="form-control" placeholder="dd/mm/yyyy" 
                                    onkeyup="this.value = atualizaData(this.value)" id="data" name="data" required=""
                                    maxlength="10" />
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label>Descrição</label>
                                <input type="text" autocomplete="off" class="form-control" placeholder="Descrição" 
                                    id="descricao" name="descricao" required="" />
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button class="btn btn-success" id="insereEvento">Agendar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
            <table class="table">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Evento</th>
                        <th scope="col">Descrição</th>
                        <th scope="col">Data</th>
                        <th scope="col">Ação</th>
                    </tr>
                </thead>
                <tbody id="agendaData">
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.2/axios.js"></script>
    <script>
        function atualizaData(value){
            var v = value;
            if (v.match(/^\d{2}$/) !== null) {
                value = v + '/';
            } else if (v.match(/^\d{2}\/\d{2}$/) !== null) {
                value = v + '/';
            }
            return value;
        }

        // cria uma linha direta de conexão com o backend para fazer requisições
        const back = axios.create('http://localhost');
        async function listarEventos(){
            await back.get('/acao.php?acao=listar').then(ret => {
                $("#agendaData").empty();
                const data = ret.data.retorno;
                data.forEach(el => {
                    let html = '<tr>';
                    html += `
                    <th scope="row">${el.id}</th>
                        <td id="lstEvento${el.id}">${el.evento}</td>
                        <td id="lstDescricao${el.id}">${el.descricao}</td>
                        <td id="lstdataEvento${el.id}">${el.dataEvento}</td>
                        <td>
                            <button class="btn btn-danger btn-xs" onclick="delEvento(${el.id})">Deletar</button>
                            <button class="btn btn-info btn-xs" onclick="updateEvento(${el.id})">Atualizar</button>
                        </td>
                    </tr>`;
                    $("#agendaData").append(html);
                })
            });
        }

        async function insereEvento() {
            const evento            = $("#evento").val();
            const descricao         = $("#descricao").val();
            const data              = $("#data").val();
            const update            = $("#eventoUpdateID").val() == 0 ? false : true;
            const obj               = {
                evento,
                descricao,
                data
            };
            if(update){
                obj.eventoID        = $("#eventoUpdateID").val(); 
                await back.put('/acao.php?acao=atualizar', obj).then(async (resp) => {
                    await finalizaInsereUpdateDados(resp);
                    $("#eventoUpdateID").val(0);
                    $("#insereEvento").html('Agendar');
                    $("#header").html('Adicionar Evento');
                });
            } else {
                await back.post('/acao.php?acao=inserir', obj).then(async (resp) => {
                    await finalizaInsereUpdateDados(resp);
                });
            }
        }

        async function finalizaInsereUpdateDados(resp){
            if(resp.data.erro){
                alert(resp.data.retorno);
            } else {
                alert(resp.data.retorno);
                $("#evento").val('');
                $("#descricao").val('');
                $("#data").val('');
                await listarEventos();
            }
        }

        async function updateEvento(id){
            await back.get('/acao.php?acao=buscar&eventoID='+id).then(ret => {
                const data = ret.data.retorno;
                data.forEach(el => {
                    $("#evento").val(el.evento);
                    $("#descricao").val(el.descricao);
                    $("#data").val(el.dataEvento);
                    $("#eventoUpdateID").val(el.id);
                    $("#insereEvento").html('Atualizar');
                    $("#header").html('Atualizar Agenda');
                })
            });
        }

        async function delEvento(eventoID) {
            await back.delete('/acao.php?acao=remover&eventoID='+eventoID, {
                eventoID
            }).then(async (resp) => {
                if(resp.data.erro){
                    alert(resp.data.retorno);
                } else {
                    alert(resp.data.retorno);
                    await listarEventos();
                }
            });
        }

        listarEventos();

        $("#insereEvento").click(()=>{
            insereEvento();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>

</html>
  //Auto Close Timer
    function sucesso(){
        $('body').removeClass('timer-alert');
        Swal.fire({
            title: 'Salvo com Sucesso!',
            text: 'Fecharei em 1 segundo.',
            icon: "success",
            timer: 1000,
            customClass: {
            container: 'swal-whatsapp-container'
        }
        })?.then(
            function () {
            },
            // lidando com a rejeição da promessa
            function (dismiss) {
                if (dismiss === 'timer') {
                    console.log('Eu estava fechado pelo cronômetro')
                }
            }
        )
    }


        //Auto Close Timer
    function excluido(){
        $('body').removeClass('timer-alert');
        Swal.fire({
            title: 'Excluido com Sucesso!',
            text: 'Fecharei em 1 segundo.',
            icon: "success",
            timer: 1000,
            customClass: {
            container: 'swal-whatsapp-container'
        }
        })?.then(
            function () {
            },
            // lidando com a rejeição da promessa
            function (dismiss) {
                if (dismiss === 'timer') {
                    console.log('Eu estava fechado pelo cronômetro')
                }
            }
        )
    }
    



        //Auto Close Timer
    function alertcobrar(){
        $('body').removeClass('timer-alert');
        Swal.fire({
            title: 'Cobrança Efetuada!',
            text: 'Fecharei em 1 segundo.',
            icon: "success",
            timer: 1000,
            customClass: {
            container: 'swal-whatsapp-container'
        }
        })?.then(
            function () {
            },
            // lidando com a rejeição da promessa
            function (dismiss) {
                if (dismiss === 'timer') {
                    console.log('Eu estava fechado pelo cronômetro')
                }
            }
        )
    }



function baixado() {
    $('body').removeClass('timer-alert');
    Swal.fire({
        title: 'Baixa Efeturada com Sucesso!',
        text: 'Fecharei em 1 segundo.',
        icon: "success",
        timer: 2000,
        customClass: {
            container: 'swal-whatsapp-container'
        }
    })?.then(
        function () {
        },
        // lidando com a rejeição da promessa
        function (dismiss) {
            if (dismiss === 'timer') {
                console.log('Eu estava fechado pelo cronômetro')
            }
        }
    )
}


function alertWarning(mensagem) {
    $('body').removeClass('timer-alert');
    Swal.fire({
        title: 'Informação',
        text: mensagem,
        icon: "warning",
        confirmButtonText: 'OK',
         customClass: {
            container: 'swal-whatsapp-container'
        }
    })
}




// ALERT EXCLUIR #######################################
function excluirAlert(id) {
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success", // Adiciona margem à direita do botão "Sim, Excluir!"
            cancelButton: "btn btn-danger me-1",
            container: 'swal-whatsapp-container'
        },
        buttonsStyling: false
    });

    swalWithBootstrapButtons.fire({
        title: "Deseja Excluir?",
        text: "Você não conseguirá recuperá-lo novamente!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sim, Excluir!",
        cancelButtonText: "Não, Cancelar!",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza a requisição AJAX para excluir o item
            $.ajax({
                url: 'paginas/' + pag + "/excluir.php",
                method: 'POST',
                data: { id },
                dataType: "html",
                success: function (mensagem) {
                    if (mensagem.trim() == "Excluído com Sucesso") {
                        // Exibe mensagem de sucesso após a exclusão
                        swalWithBootstrapButtons.fire({
                            title: mensagem,
                            text: 'Fecharei em 1 segundo.',
                            icon: "success",
                            timer: 1000,
                            timerProgressBar: true,
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                        listar();
                        limparCampos()
                    } else {
                        // Exibe mensagem de erro se a requisição falhar
                        swalWithBootstrapButtons.fire({
                            title: "Opss!",
                            text: mensagem,
                            icon: "error",
                            confirmButtonText: 'OK',
                            customClass: {
                             container: 'swal-whatsapp-container'
                             }
                        });
                    }
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            swalWithBootstrapButtons.fire({
                title: "Cancelado",
                text: "Fecharei em 1 segundo.",
                icon: "error",
                timer: 1000,
                timerProgressBar: true,
            });
        }
    });
}



function inserido(){
        $('body').removeClass('timer-alert');
        Swal.fire({
            title: 'Produto Inserido',
            text: '',
            icon: "success",
            timer: 700,
            customClass: {
            container: 'swal-whatsapp-container'
        }
        })?.then(
            function () {
            },
            // lidando com a rejeição da promessa
            function (dismiss) {
                if (dismiss === 'timer') {
                    console.log('')
                }
            }
        )
    }




    function alertSucesso(mensagem){
        $('body').removeClass('timer-alert');
        Swal.fire({
            title: mensagem,
            text: 'Fecharei em 1 segundo.',
            icon: "success",
            timer: 1000,
            customClass: {
            container: 'swal-whatsapp-container'
        }
        })?.then(
            function () {
            },
            // lidando com a rejeição da promessa
            function (dismiss) {
                if (dismiss === 'timer') {
                    console.log('Eu estava fechado pelo cronômetro')
                }
            }
        )
    }





// ALERT SALVAR FINAL ##############
function alertInformativo(mensagem) {
    $('body').removeClass('timer-alert');
    Swal.fire({
        title: mensagem,
        text: 'Fecharei em 3 segundo.',
        icon: "error",
        timer: 3000,
        timerProgressBar: true,
        confirmButtonText: 'OK',
         customClass: {
            container: 'swal-whatsapp-container'
        }
    })
}


function alertWarning(mensagem) {
    $('body').removeClass('timer-alert');
    Swal.fire({
        title: 'Informação',
        text: mensagem,
        icon: "warning",
        confirmButtonText: 'OK',
         customClass: {
            container: 'swal-whatsapp-container'
        }
    })
}



function alertaPersonalizado(mensagem) {
    $('body').removeClass('timer-alert');
    Swal.fire({
        title: 'Atenção',
        text: mensagem,
        icon: "warning",
        timer: 3000,
        timerProgressBar: true,
        confirmButtonText: 'OK',
         customClass: {
            container: 'swal-whatsapp-container'
        }
    })
}
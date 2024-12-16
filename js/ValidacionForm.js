
    $(function () {
        $('#jobProposalForm').on('submit', function (event) {
            event.preventDefault(); 

            
            const idEmpleador = $('#id_empleador').val();
            const titulo = $('#titulo').val().trim();
            const descripcion = $('#descripcion').val().trim();
            const categoria = $('#categoria').val().trim();
            const tipoContrato = $('#tipo_contrato').val().trim();
            const rangoSalarial = $('#rango_salarial').val().trim();
            const fechaPublicacion = new Date($('#fecha_publicacion').val());
            const hoy = new Date();
            hoy.setHours(0, 0, 0, 0);

            if (idEmpleador <= 0) {
                alert('Tiene que Autenticarse');
                return;
            }

            if (titulo === '' || descripcion === '' || categoria === '' || tipoContrato === '' || rangoSalarial === '') {
                alert('Todos los campos son obligatorios.');
                return;
            }


            if (fechaPublicacion < hoy) {
                alert('La fecha de publicación no puede ser anterior a hoy.');
                return;
            }

            
            alert('Oferta válida. Enviando...');
            this.submit();
        });
    });

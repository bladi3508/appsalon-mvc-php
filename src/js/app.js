let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    hora: '',
    fecha: '',
    servicios: [
        
    ]
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

function iniciarApp(){
    
    mostrarSeccion(); //Muestra y oculta las secciones selecionadas
    tabs(); //Cambia la sección cuando se precionen los botones del menu corresponiente
    botonesPaginador(); //agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); //Consulta la API en el backend de PHP

    idCliente(); //Agregar el ID del ciente al objeto cita
    nombreCliente(); //Agrega el nombre del usuiario al objeto cita
    seleccionarFecha(); //Agregar la fecha al objeto cita
    seleccionarHora(); //Agrega la hora al objeto de cita

    mostrarResumen(); //Muestra los datos de la cita al usuario antes de agendarla
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////Botones para cambiar secciones/////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function mostrarSeccion(){

    //Ocultar la seccion que tenga la clase mostrar
    const seccionAnterior = document.querySelector('.mostrar');

    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }

    //Seleccionar la seción deacuerdo al paso
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);

    seccion.classList.add('mostrar');

    //Quitar la clase actual al tab anterior
    const tabAnterior = document.querySelector('.actual');
    if(tabAnterior){
        tabAnterior.classList.remove('actual');
    }

    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => {
        boton.addEventListener('click', function(e){
            paso = parseInt(e.target.dataset.paso);

            mostrarSeccion();
            botonesPaginador();
        })
    })
}

function botonesPaginador(){
    const botonAnterior = document.querySelector('#anterior');
    const botonSuguiente = document.querySelector('#siguiente');

    if(paso === 1){
        botonAnterior.classList.add('ocultar');
        botonSuguiente.classList.remove('ocultar');
    }else if(paso === 3){
        botonAnterior.classList.remove('ocultar');
        botonSuguiente.classList.add('ocultar');

        mostrarResumen();
    }else{
        botonAnterior.classList.remove('ocultar');
        botonSuguiente.classList.remove('ocultar');
    }

    mostrarSeccion();

}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');

    paginaAnterior.addEventListener('click', function(){
        
        if(paso <= pasoInicial) return;
        paso--;

        botonesPaginador();
    })
}

function paginaSiguiente(){
    const paginaSiguente = document.querySelector('#siguiente');

    paginaSiguente.addEventListener('click', function(){
        
        if(paso >= pasoFinal) return;
        paso++;

        botonesPaginador();
    })
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////Mostrar los servicios en pantalla/////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

async function consultarAPI()
{
    try {
        //Agregar la url que se va a consumir
        const url = '/api/servicios';

        const resultado = await fetch(url);

        const servicios = await resultado.json();

        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);
    }
}

function mostrarServicios(servicios)
{
    //Iterar sobre el arreglo de los servicios consultados
    servicios.forEach( servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        const servicioDIV = document.createElement('DIV');
        servicioDIV.classList.add('servicio');
        servicioDIV.dataset.idServicio = id;
        servicioDIV.onclick = function(){
            selecionarServicio(servicio);
        }

        servicioDIV.appendChild(nombreServicio);
        servicioDIV.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDIV);
    })
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////Agregar datos añ objeto cita //////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function selecionarServicio(servicio)
{
    const { id } = servicio;
    const { servicios } = cita;

    //Identificar el elemento al que se da click 
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue seleccionado
    if( servicios.some( agregado => agregado.id === id)){
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');

    }else{
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}



function idCliente(){
    cita.id = document.querySelector('#id').value;
}


function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;
}

function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){

        const dia = new Date(e.target.value).getUTCDay();

        if( [0, 6].includes(dia) ){
            e.target.value = '';
            mostrarAlerta('No se abre los fines de semanas', 'error', '.formulario');
        }else{
            cita.fecha = e.target.value;
        }
    })

}

function seleccionarHora(){
    const inputHora = document.querySelector('#hora');

    inputHora.addEventListener('input', function(e){
        
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if(hora < 10 || hora > 18){

            e.target.value = '';
            mostrarAlerta('El establecimiento abre de 10:00 am a 6:00 pm favor de seleccionar una hora valida.', 'error', '.formulario');

        }else{
            cita.hora = e.target.value;
        }

    })
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){

    //Previene que se genere mas de una alerta cuando el usuario seleciona un dia del fin de semana
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    //Se crea el elemento de alerta que se mostrara en el formulario en caso de error
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const formulario = document.querySelector(elemento);
    formulario.appendChild(alerta);

    if(desaparece){
        //Remueve la alerta despues de cierto tiempo en pmatalla
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');

    //Limpiar el contenido de resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    //Validar si no falta algun dato en el objeto resumen
    if( Object.values(cita).includes('') || cita.servicios.length === 0 ){
        mostrarAlerta('Hacen falta datos para agendar la cita, favor de verificar la información', 'error', 
                        '.contenido-resumen', false);
            
        return;
    }

    //Heading de los servicios
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen la cita';
    resumen.appendChild(headingCita);

    //Formatear el DIV de resumen
    const { nombre, fecha, hora, servicios} = cita;

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span>${nombre}`;

    //Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const año = fechaObj.getFullYear();

    const fechUTC = new Date( Date.UTC(año, mes, dia ));

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }
    const fechaFormateada = fechUTC.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span>${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = ` <span>Fecha: </span>${hora} horas`;


    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Servicios solicitados'
    resumen.appendChild(headingServicios);

    //Iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, nombre, precio } = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio: </span>$${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });


    //Boton para registrar la cita
    const botonRegistrar = document.createElement('BUTTON');
    botonRegistrar.classList.add('boton');
    botonRegistrar.textContent = 'Registrar Cita';
    botonRegistrar.onclick = recervarCita;

    resumen.appendChild(botonRegistrar);
}

async function recervarCita(){
    //Extraer los datos del objeto cota
    const { nombre, fecha, hora, servicios, id } = cita;

    //Iterar sobre los servicios
    const idServicio = servicios.map( servicio => servicio.id );

    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('id_usuario', id);
    datos.append('servicios', idServicio);

    //Validar si no hay un error en el servidor
    try {
         //Petición hacia la API
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        console.log(resultado);

        if(resultado.resultado){
            Swal.fire({
                icon: "success",
                title: "Felicidades",
                text: "Tu cita ha sido registrada correctamente!",
                button: 'Entendido'
            }).then( () => {
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Parece que hay un problema con el servidor!",
            button: 'Entendido'
          }).then( () => {
            window.location.reload();
        });
    }

   
}

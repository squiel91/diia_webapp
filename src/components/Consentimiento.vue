<template>
    <v-app>
        <header class="centered">
            <div class="float left"><v-icon>keyboard_arrow_left</v-icon> <a href="../">Volver a Inicio</a></div>
        </header>
        <v-card class="centered">
            <template v-if="administrador">
                <v-card-text>
                    <h1>Notas de consentimiento</h1>
                    <v-data-table
                        :headers="headers"
                        :items="consentimientos"
                        hide-actions
                        no-data-text="No hay datos que mostrar."
                        class="elevation-1"
                        :loading="actualizandoLista"
                    >
                        <template slot="items" slot-scope="props">
                        <td>{{ props.item.nombre }}</td>
                        <td class="text-xs-right">{{ props.item.ci }}</td>
                        <td class="text-xs-right">{{ props.item.email }}</td>
                        <td class="text-xs-right">{{ props.item.fbId }}</td>
                        <td class="text-xs-right">{{ props.item.rol }}</td>
                        <td class="text-xs-right">{{ props.item.autoriza }}</td>
                        </template>
                    </v-data-table>
                    <v-btn flat class="ml-0" @click="actualizar()"><v-icon>refresh</v-icon>Actualizar</v-btn>
                    <v-btn style="float: right" class="mr-0" flat @click="descargarCsv()"><v-icon>file_download</v-icon>Descargar CSV</v-btn>
                </v-card-text>
            </template>
            <template v-else>
                <v-card-text v-if="!enviado" class="card-body">
                    <h1>
                        Nota de consentimiento
                    </h1>
                    <p>
                        Mediante la aceptación de las condiciones de este formulario estarás colaborando con el Proyecto DIIA. 
                    </p>
                    <p>
                        Utilizaremos los datos que generes durante el año lectivo en la Plataforma CREA2 y en el Grupo de Facebook para enriquecer la detección de patrones y desarrollar una herramienta que mejore la experiencia de aprendizaje de muchos estudiantes.
                    </p>
                    <v-form ref="form">
                        <v-text-field
                            class="narrow"                            
                            name="nombre"
                            required
                            label="Nombre completo"	
                            v-model="nombre"
                            :validate-on-blur="true"
                            :rules="[(v) => !validarNombre(v)? 'Ingresa tu nombre y apellido' : true ]"
                        ></v-text-field>
                        <v-text-field
                            class="narrow"
                            name="ci"
                            mask="#.###.###-#"
                            label="Cédula de identidad"	
                            :validate-on-blur="true"
                            required
                            v-model="ci"
                            :rules="[(v) => v && v.length != 8? 'Ingresa tu cedula con guión y dígito verificador' : true ]"
                        ></v-text-field>
                        <v-text-field
                            class="narrow"
                            name="email"
                            required
                            :validate-on-blur="true"
                            label="Email"	
                            v-model="email"
                            :rules="[(v) => !validarEmail(v)? 'Ingresa un email válido' : true ]"
                        ></v-text-field>
                        <v-select
                            class="narrow"                            
                            :items="roles"
                            required
                            append-icon="help_outline"
                            :append-icon-cb="() => queRol = true"
                            v-model="rol"
                            label="Rol"
                            item-value="text"
                        ></v-select>
                        <v-dialog v-model="queRol" max-width="800px">
                            <v-card>
                                <v-card-title class="strong">
                                    ¿Cuál rol me corresponde?
                                </v-card-title>
                                <v-card-text>
                                    <p>En el proyecto DIIA tenemos dos roles bien diferenciados.</p>
                                    <h3>Alumno</h3>
                                    <p>Si eres un alumno elige esta opcion.</p>
                                    <h3>Docente</h3>
                                    <p>Si eres docente y tu clase participa de la experiencia elije esta opción.</p>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn color="primary" flat @click.stop="queRol=false">Cerrar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <v-text-field
                            name="facebook"
                            class="narrow"
                            label="ID de Facebook"
                            append-icon="help_outline"
                            :append-icon-cb="() => queFbId = true"
                            v-model="fbId"
                        ></v-text-field>
                        <v-dialog v-model="queFbId" max-width="800px">
                            <v-card>
                                <v-card-title class="strong">
                                    ¿Cómo conozco mi ID de Facebook?
                                </v-card-title>
                                <v-card-text>
                                    <p>El identificador de usuario de Facebook es un número que se te asigna al crear tu cuenta de Facebook. Para utilizar los datos que generes en el Grupo de Facebook necesitamos que nos proporciones ese número.</p>
                                    <p>Para obtenerlo puedes seguir alguno de los siguientes procedimientos:</p>
                                    1) Usando la siguiente aplicación web: <a target="_blank" href="https://es.piliapp.com/facebook/id/">¿Cuál es mi ID de Facebook?</a><br>
                                    2) En forma manual como se explica en la página: <a target="_blank" href="https://tecnologia.uncomo.com/articulo/como-encontrar-mi-id-de-facebook-304.html">¿Cómo encuentro mi identificador de facebook?</a>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn color="primary" flat @click.stop="queFbId=false">Cerrar</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                        <div>Seleccionando a continuación 'Si autorizo' concientes expresamente que Centro Ceibal transfiera los datos que se detallan en el documento de <a target="_blank" href="consentimiento-y-proteccion-de-datos.pdf">Consentimiento y Protección de Datos</a>.</div>
                        <v-radio-group v-model="autoriza">
                            <v-radio
                                label="Si autorizo."
                                color="primary"
                                value="si"
                            >
                            </v-radio>
                            <v-radio
                                color="primary"                    
                                label="No autorizo."
                                value="no"
                            ></v-radio>
                        </v-radio-group>
                        <template v-if="autoriza == 'no'">
                            <p>Agradeceriamos que nos cuentes porque prefieres no participar de la experiencia.</p>
                            <v-checkbox v-for="(razon, i) in razones" :key="i" 
                            :label="razon" class="ma-0 pa-0" color="primary" v-model="razonesElegidas" :value="razon"></v-checkbox>
                            <v-text-field
                                name="razonTexto"
                                v-model="razonTexto"
                                label="Escribe un comentario opcional"
                                textarea
                                :rules="[(v) => v && v.length <= 200 || '200 caracteres maximos']"
                                :counter="200"

                            ></v-text-field>
                        </template>
                        <v-btn class="ma-0 mb-2" :loading="cargando" @click="validar()" color="primary">Enviar</v-btn>
                        <p class="nota">
                            Esta nota se subscribe el día de hoy, {{ fechaHoy }}.
                        </p>
                    </v-form>
                </v-card-text>
                <v-card-text v-else class="card-body">
                    <template v-if="conciente">
                        <h1>Muchas gracias!</h1>
                        <p>Te agradecemos que te hayas tomado unos minutos para llenar el formulario y de participar de esta experiencia piloto. Muchos estudiantes te lo agradecerán.</p>
                    </template>
                    <template v-else>
                        <h1>Que lástima que no participes en la experiencia :(</h1>
                        <p>No aceptaste que utilizemos tus datos. Te entendemos y respetamos tu decisión y agradecemos que hayas completado el formulario.</p>
                        <p>Si por alguna razón cambias de parecer puedes volver a llenar el formulario. Nos quedaremos con tu ultima participación.</p>
                    </template>
                </v-card-text> 
            </template>               
        </v-card>
        <footer class="mt-5 mb-1">
            <p>
                Por mas información comunicate con <a src="mailto:proyecto@diia.edu.uy">proyecto@diia.edu.uy</a>
            </p>
        </footer>
    </v-app>
</template>

<script>
export default {
    methods: {
        exportCSVFile(titles, headers, items, fileTitle) {

            var csv = titles? titles.join(', ') + '\r\n' : '';

            for (var item of items) {
                var line = '';
                for (var header of headers) {
                    if (line != '') line += '\t'
                    if (item[header]) line += item[header]
                }
                csv += line + '\r\n';
            }

            var exportedFilenmae = fileTitle + '.csv' || 'export.csv';

            var blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
            if (navigator.msSaveBlob) { // IE 10+
                navigator.msSaveBlob(blob, exportedFilenmae);
            } else {
                var link = document.createElement("a");
                if (link.download !== undefined) { // feature detection
                    // Browsers that support HTML5 download attribute
                    var url = URL.createObjectURL(blob);
                    link.setAttribute("href", url);
                    link.setAttribute("download", exportedFilenmae);
                    link.style.visibility = 'hidden';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
        },
        actualizar() {
            this.listar()
            this.actualizandoLista = true
        },
        descargarCsv() {
            var headers = [
                'nombre',
                'ci',
                'email',
                'rol',
                'fecha',
                'fbId',
                'autoriza',
                'razonesElegidas',
                'razonTexto'
            ]
            var titles = [
                'Nombre Completo',
                'Cedula de Identidad',
                'Email',
                'Rol',
                'Fecha',
                'Facebook ID',
                'Consiente',
                'Razones',
                'Comentario'
            ]
            this.exportCSVFile(titles, headers, this.consentimientos, 'consentimientos');
        },
        validarEmail(email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },
        validarNombre(nombre) {
            return typeof nombre == 'string' && nombre.split(' ').length > 1
        },
        validar() {
            if (this.$refs.form.validate()) {
                var datos = {}

                datos['nombre'] = this.nombre
                datos['ci'] = this.ci
                datos['email'] = this.email
                datos['rol'] = this.rol
                datos['fecha'] = new Date()
                if (this.fbId) datos['fbId'] = this.fbId
                datos['autoriza'] = this.conciente
                
                if (this.autoriza == 'no') {
                    if (this.razonesElegidas) datos['razonesElegidas'] = this.razonesElegidas
                    if (this.razonTexto) datos['razonTexto'] = this.razonTexto
                }

                this.cargando = true
                this.$http.post('api/consentimiento.php', { d: JSON.stringify(datos) }, { emulateJSON: true })
                    .then(data => { 
                        var data_json = data.body
                        if (data_json.success) {
                            console.log('Todo OK')
                            this.cargando = false
                            this.enviado =  true
                        } else {
                            console.log(data_json.error)
                            this.cargando = false
                        }
                    }, error => {
                        this.cargando = false
                        console.log(error)
                    })
			}
        },
        listar() {
            this.$http.get('api/consentimiento.php')
                .then(data => {
                    this.actualizandoLista = false
                    if (!data.body.error) {
                        this.consentimientos = data.body
                    } else {
                        console.log(data_json.error)
                    }
                }, error => {
                    this.actualizandoLista = false
                    console.log(error)
                })
        }
    },
    watch: {
        administrador(nuevoValor) {
            if(nuevoValor) {
                this.listar()
            }
        }
    },
    computed: {
        conciente() {
            if(this.autoriza == 'si') {
                return true
            }
            if(this.autoriza == 'no') {
                return false
            }
            return undefined
        },
        fechaHoy() {
            var meses = [
                'enero','febrero','marzo','abril', 'mayo', 'junio',
                'julio', 'agosto', 'setiembre', 'octubre', 'noviembre', 'diciembre'
            ]
            var hoy = new Date()
            return `${hoy.getDate()} de ${meses[hoy.getMonth()]} del ${hoy.getFullYear()}`
        }
    },
    data() {
        return {
            administrador: false,
            actualizandoLista: true,
            headers: [
                { text: 'Nombre', value: 'name' },
                { text: 'CI', value: 'ci' },
                { text: 'Email', value: 'email' },
                { text: 'Facebook', value: 'fbId' },
                { text: 'Rol', value: 'rol' },
                { text: 'Autoriza', value: 'autoriza' }
            ],
            consentimientos: [],

            queRol: false,
            queFbId: false,

            nombre: undefined,
            ci: undefined,
            email: undefined,
            roles: ['alumno', 'docente', 'alumno y docente'],
            rol: undefined,
            autoriza: undefined,
            fbId: undefined,
            cargando: false,
            razones: [
                'No me siento seguro compartiendo mis datos.',
                'No le veo sentido al proyecto.',
                'Preferiria que se contacten conmigo antes.'
            ],
            razonesElegidas: [],
            razonTexto: undefined,
            enviado: false
        }
    }
}
</script>

<style>
    header {
        background-image: url(../../img/diia.png);
        background-size: 100pt;
        height: 130pt;
        font-size: large;
        padding-top: 10pt;
        background-position: center;
        width: 100%;
    }

    .centered {
        max-width: 800px;
        width: 100%;
        margin: 0pt auto;
    }

    .card-title {
        background-color: #8e44ad;
        color: white;
        font-size: large;
        padding: 5pt 15pt;
        text-align: center !important;
    }

    .card-body {
        padding: 15pt;
    }

    h1 {
        margin-bottom: 15pt;
    }

    .nota {
        color: gray;
        font-style: italic;
    }

    .float {
        display: block
    }

    .right {
        float: right;
    }

    .left {
        float: left;
    }

    footer {
        text-align: center
    }

    .strong {
        font-weight: bold;
        font-size: large
    }

</style>

<template>
	<div>
		<header class="centered">
			<div  class="float left"><v-icon>keyboard_arrow_left</v-icon><a href="../">Volver a Inicio</a></div>
        </header>
		<v-card class="main-card">
			<div class="column">
				<v-form ref="form">
					<v-text-field
					name="email"
					label="Email"	
					@keyup.enter="signIn()"
					v-model="inputEmail"
					:rules="[(v) => !validateEmail(v)? 'Ingresa un email valido' : true ]"
					></v-text-field>
					<v-text-field
					name="password"					
					label="Contraseña"
					v-model="inputPass"
					@keyup.enter="signIn()"
					:rules="[(v) => v && v.length >= 6? true : 'Tiene un minimo de 6 caracteres']"				
					:append-icon="visible ? 'visibility' : 'visibility_off'"
					@click:append="() => (visible = !visible)"
					:type="visible ? 'password' : 'text'"
					></v-text-field>
					<v-btn class="wide ma-0 mb-2" :loading="loading" full-width color="primary" @click="signIn()">
						<span>Iniciar sesión</span></v-btn>
					<a @click.stop="privacy = true" href="#">Políticas de privacidad</a><br>
				</v-form>
			</div>
			<div class="column">
				<p style="margin-top: 15pt;">Se ha restringido el ingreso de nuevos usuarios por el momento. Para solicitar un usuario escriba un email a <a href="mailto:proyecto@diia.edu.uy">proyecto@diia.edu.uy</a>.</p>
			</div>
		</v-card>
		<v-dialog v-model="privacy">
        <v-card>
          <v-card-title>
            <h1>Políticas de privacidad</h1>
          </v-card-title>
		  <v-card-text>
			  	<h2>Sobre el Proyecto DIIA</h2>
				<p>El Proyecto DIIA (Detección de Interacciones que Impactan en el Aprendizaje) es
				un proyecto de investigación financiado por la ANII que pretende brindar un ambiente de
				software a los docentes para descubrir patrones semánticos de interacción dentro de sus
				propios cursos y facilitando así la toma de decisiones. En ese sentido se procesarán los
				datos de aulas virtuales y redes sociales de los participantes reorganizándolos para facilitar
				la visualización y aplicando algoritmos de detección de patrones para conocer más sobre los
				estudiantes, los procesos de aprendizaje y las trayectorias educativas.</p>

				<p>En este documento se establecen los términos en que DIIA usa protege la información
				que es proporcionada por sus usuarios. El Proyecto DIIA se encuentra comprometido con la
				seguridad de los datos de sus usuarios. Cuando le solicitamos llenar los campos de
				información personal con la cual usted pueda ser identificado, lo hacemos asegurando que
				sólo se empleará de acuerdo con los términos de este documento.</p>

				<h2>Información que es recogida</h2>

				<p>En nuestro sitio web es posible que se recoja información personal de los estudiantes y
				docentes que consienten el uso de los datos de su curso, como por ejemplo: Nombre,
				información de contacto (como su dirección de correo electrónica e información
				demográfica), las interacciones estudiantes-materiales, estudiantes-actividades y también
				interacciones estudiantes-estudiantes y estudiantes-docentes, relevando específicamente
				los patrones que impactan en el aprendizaje. Por ejemplo, la cantidad de clics o descargas
				de los materiales, sus participaciones en los foros o grupo de facebook, las tareas
				presentadas, las devoluciones efectuadas a esas tareas, sus perfiles de usuario, entre otros
				datos útiles para detectar patrones. Esta información es la misma que podría ver el docente
				a cargo del curso, pero es sistematizada y proporcionada de forma gráfica permitiéndole ver
				las interacciones desde otro ángulo.</p>

				<h2>Uso de la información recogida</h2>

				<p>Los datos recogidos a través del sitio del proyecto se utilizarán con el fin exclusivo de
				creación de un ambiente de software para descubrir patrones semánticos de interacción.

				<p>Es posible que sean enviados correos electrónicos periódicamente a través de nuestro
				sitio con información que consideremos relevante para usted o para el desarrollo del
				proyecto, estos correos electrónicos serán enviados a la dirección que usted proporcione y
				podrán ser cancelados en cualquier momento.</p>

				<p>No venderemos, cederemos, ni distribuiremos la información personal que recopilamos,
				salvo que sea requerido por un juez con un orden judicial.</p>

				<p>DIIA está altamente comprometido para cumplir con el compromiso de mantener su
				información segura. Los datos se alojarán en un servidor dedicado al que tendrán acceso
				únicamente los investigadores que forman parte del equipo del proyecto. Una vez terminado
				el proceso de disociación de datos, todas las bases de datos que contengan nombres reales
				serán borrados y, una vez finalizado el proyecto, todas las bases que contengan datos
				personales que puedan identificar indirectamente a los estudiantes serán eliminadas.</p>

				<p>Ante la eventualidad de que docentes/instituciones decidan utilizar con sus estudiantes la
				herramienta de analítica resultante del proyecto DIIA, hemos puesto a disposición un
				documento con recomendaciones para lograr intervenciones eficaces tomando en cuenta la
				preservación de privacidad y el respeto a la dignidad del estudiante: ​ <a target="_blank" href="docs/DIIA_recomendaciones_para_instituciones_educativas.pdf">Recomendaciones para
				el uso de herramientas de analíticas de aprendizaje en Instituciones Educativas</a>.</p>

				<h2>Cookies</h2>

				<p>Una cookie se refiere a un fichero que es enviado con la finalidad de solicitar permiso
				para almacenarse en su ordenador, al aceptar dicho fichero se crea y la cookie sirve
				entonces para tener información respecto al tráfico web, y también facilita las futuras visitas
				a una web recurrente. Otra función que tienen las cookies es que con ellas las web pueden
				reconocer a los usuarios de forma individual y por tanto brindarles un servicio personalizado.</p>

				<p>Nuestro sitio web emplea las cookies para poder identificar las páginas que son visitadas
				y su frecuencia. Esta información es empleada únicamente para análisis estadístico y
				después la información se elimina de forma permanente. Usted puede eliminar las cookies
				en cualquier momento desde su ordenador. Sin embargo las cookies ayudan a proporcionar
				un mejor servicio de los sitios web, estas no dan acceso a información de su ordenador ni de
				usted. Usted puede aceptar o negar el uso de cookies, sin embargo la mayoría de
				navegadores aceptan cookies automáticamente pues sirve para tener un mejor servicio web.
				También usted puede cambiar la configuración de su ordenador para declinar las cookies. Si
				se declinan es posible que no pueda utilizar algunos de nuestros servicios.</p>

				<h2>Control de su información personal y ejercicio de sus derechos</h2>

				<p>En cualquier momento usted puede restringir la recopilación o el uso de la información personal que es proporcionada a nuestro sitio web.  Cada vez que se le solicite rellenar un formulario, como el de alta de usuario, puede marcar o desmarcar la opción de recibir información por correo electrónico.  En caso de que haya marcado la opción de recibir nuestro boletín o publicidad usted puede cancelarla en cualquier momento.</p>
				<p>Esta compañía no venderá, cederá ni distribuirá la información personal que es recopilada sin su consentimiento, salvo que sea requerido por un juez con un orden judicial.</p>
				<p>DIIA Se reserva el derecho de cambiar los términos de la presente Política de Privacidad en cualquier momento.</p>

				<h2>Cookies</h2>
				<p>En cualquier momento usted podrá solicitar la restricción o el cese de recopilación de la
				información personal que es proporcionada a nuestro sitio web.</p>
				<p>DIIA es un Proyecto del Instituto de Computación de la Facultad de Ingeniería /UdelaR
				(Ave Julio Herrera y Reissig 565, 11300 Montevideo). No dude en contactarnos si tiene
				alguna duda o inquietud sobre el tratamiento de sus datos personales. El email del proyecto es <a href="mailto:proyecto@diia.edu.uy">proyecto@diia.edu.uy</a>.</p>

		  </v-card-text>
          <v-card-actions>
            <v-btn color="primary" flat @click.stop="privacy=false">Volver</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
	  <v-snackbar
	  top
      color="error"
      v-model="error"
    >
    	{{ errorMessage }}
    	<v-btn dark flat @click.native="error = false">Close</v-btn>
    </v-snackbar>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				inputEmail: undefined,
				inputPass: undefined,
				visible: true,
				privacy: false,
				loading: false,
				error: false,
				errorMessage: undefined
			}
		},
		created() {
			if (this.$store.state.email) {
            	this.$router.push('/home')
			}
		},
		methods: {
			validateEmail(email) {
				var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(String(email).toLowerCase());
			},
			hasError(message) {
				this.loading = false
				this.error = true
				this.errorMessage = message
			},
			signIn() {
        		if (this.$refs.form.validate()) {
					this.loading = true
					var fixedEmail = this.inputEmail
					var fixedPass = this.inputPass
					this.$http.post('api/users.php', {
						email: fixedEmail, 
						pass: fixedPass, 
						accion: 'login'
					}, { emulateJSON: true })
						.then(data => {
							var data_json = data.body
							if (!data_json.error) {
								if (data_json.rol == 'docente') {
									// The professor can be autenticated already but I need to bring the courses before
									var id_docente = data_json.id_docente
									this.$http.get(`http://179.27.71.27/consulta/docente/${id_docente}/cursos`)
									.then(data => {
										var courses = data.body.cursos
										if (!courses) {
											this.hasError('Aun no tienes ningun curso asignado')
										} else {
											this.$store.commit('authenticate', {
												email: fixedEmail, 
												pass: fixedPass, 
												name: data_json.nombre,
												surname: data_json.apellido,
												role: data_json.rol,
												id_docente,
												courses
											})
								            this.$router.push('/home')
										}
									}, error => {
										this.hasError('No podemos encontrarte en la plataforma :(')
										console.log(error)
									})
								} else {
									this.hasError('Solo los docentes pueden iniciar sesión en la plataforma DIIA')
								}
							} else {
								this.errorMessage = data_json.error
								this.error = true
								this.loading = false
							}
						}, error => {
							this.loading = false
							console.log(error)
						})
				}
			}
		}
	}
</script>

<style scoped>
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

	.float {
        display: block
    }

    .right {
        float: right;
    }

    .left {
        float: left;
    }

	h1 {
		text-align: center;
	}

	.main-card {
        max-width: 350pt;
        width: 100%;
        margin: 0pt auto;
		padding: 30pt;
		padding-bottom: 20pt;
		padding-top: 30pt;
	}

	.column {
        margin: 0pt auto;
		wmax-width: 250pt;
		vertical-align: top;
	}

	.registro {
		border-left: 3pt dotted lightgray;
		margin-left: 15pt;
		padding-left: 15pt;
	}

	button.wide {
		width: 100%;
	}

	body {
		background-color: lightgrey;
	}
</style>


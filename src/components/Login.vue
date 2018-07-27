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
					label="Constaseña"
					v-model="inputPass"
					@keyup.enter="signIn()"
					:rules="[(v) => v && v.length >= 6? true : 'Tiene un minimo de 6 caracteres']"				
					:append-icon="visible ? 'visibility' : 'visibility_off'"
					@click:append="() => (visible = !visible)"
					:type="visible ? 'password' : 'text'"
					></v-text-field>
					<v-btn class="wide ma-0 mb-2" :loading="loading" full-width color="primary" @click="signIn()">
						<span>Iniciar sesión</span></v-btn>
					<a href="privacy.html" @click.stop.prevent="privacy = true">Politicas de privacidad</a>
				</v-form>
			</div>
			<div class="column">
				<p style="margin-top: 15pt;">Se ha restringido el ingreso de nuevos usuarios por el momento. Para solicitar un usuario escriba un email a <a href="mailto:proyecto@diia.edu.uy">proyecto@diia.edu.uy</a>.</p>
			</div>
		</v-card>
		<v-dialog v-model="privacy">
        <v-card>
          <v-card-title>
            <h1>Politicas de privacidad</h1>
          </v-card-title>
		  <v-card-text>
				<p>En este documento se establecen los términos en que DIIA usa y como protege la información que es proporcionada por sus usuarios. DIIA está comprometida con la seguridad de los datos de sus usuarios. Cuando le pedimos llenar los campos de información personal con la cual usted pueda ser identificado, lo hacemos asegurando que sólo se empleará de acuerdo con los términos de este documento. Sin embargo esta Política de Privacidad puede cambiar con el tiempo o ser actualizada por lo que le recomendamos y enfatizamos revisar continuamente esta página para asegurarse que está de acuerdo con dichos cambios. Esta version corresponde a la fecha 1 de febrero del 2018.</p>

				<h2>Información que es recogida</h2>

				<p>Nuestro sitio web podrá recoger información personal por ejemplo: Nombre,  información de contacto como  su dirección de correo electrónica e información demográfica. Así mismo cuando sea necesario podrá ser requerida información específica para procesar algún pedido o realizar una entrega o facturación.</p>

				<h2>Uso de la información recogida</h2>

				<p>Nuestro sitio web emplea la información con el fin de proporcionar el mejor servicio posible, particularmente para mantener un registro de usuarios, de pedidos en caso que aplique, y mejorar nuestros productos y servicios.  Es posible que sean enviados correos electrónicos periódicamente a través de nuestro sitio con ofertas especiales, nuevos productos y otra información publicitaria que consideremos relevante para usted o que pueda brindarle algún beneficio, estos correos electrónicos serán enviados a la dirección que usted proporcione y podrán ser cancelados en cualquier momento.</p>
				<p>DIIA está altamente comprometido para cumplir con el compromiso de mantener su información segura. Usamos los sistemas más avanzados y los actualizamos constantemente para asegurarnos que no exista ningún acceso no autorizado.</p>

				<h2>Control de su información personal</h2>

				<p>En cualquier momento usted puede restringir la recopilación o el uso de la información personal que es proporcionada a nuestro sitio web.  Cada vez que se le solicite rellenar un formulario, como el de alta de usuario, puede marcar o desmarcar la opción de recibir información por correo electrónico.  En caso de que haya marcado la opción de recibir nuestro boletín o publicidad usted puede cancelarla en cualquier momento.</p>
				<p>Esta compañía no venderá, cederá ni distribuirá la información personal que es recopilada sin su consentimiento, salvo que sea requerido por un juez con un orden judicial.</p>
				<p>DIIA Se reserva el derecho de cambiar los términos de la presente Política de Privacidad en cualquier momento.</p>

				<h2>Cookies</h2>
				<p>Una cookie se refiere a un fichero que es enviado con la finalidad de solicitar permiso para almacenarse en su ordenador, al aceptar dicho fichero se crea y la cookie sirve entonces para tener información respecto al tráfico web, y también facilita las futuras visitas a una web recurrente. Otra función que tienen las cookies es que con ellas las web pueden reconocerte individualmente y por tanto brindarte el mejor servicio personalizado de su web.</p>
				<p>Nuestro sitio web emplea las cookies para poder identificar las páginas que son visitadas y su frecuencia. Esta información es empleada únicamente para análisis estadístico y después la información se elimina de forma permanente. Usted puede eliminar las cookies en cualquier momento desde su ordenador. Sin embargo las cookies ayudan a proporcionar un mejor servicio de los sitios web, estás no dan acceso a información de su ordenador ni de usted, a menos de que usted así lo quiera y la proporcione directamente, visitas a una web . Usted puede aceptar o negar el uso de cookies, sin embargo la mayoría de navegadores aceptan cookies automáticamente pues sirve para tener un mejor servicio web. También usted puede cambiar la configuración de su ordenador para declinar las cookies. Si se declinan es posible que no pueda utilizar algunos de nuestros servicios.</p>

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


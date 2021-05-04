/// <reference path="../../helpers.js" />
/// <reference path="../../user-system/main_system_user.js" />
/// <reference path="../../user-system/PiecesPHPSystemUserHelper.js" />

/**
 * Selecciona una imagen de entre varias al azar
 */
function changeImageLogin() {

	let bgElement = $('[bg-js]')
	if (bgElement.is(":visible")) {
		let backgrounds = atob(bgElement.attr('bg-js'))
		backgrounds = JSON.parse(backgrounds)

		let randomImageLoginIndex = randomNumber(backgrounds.length > 0 ? backgrounds.length - 1 : backgrounds.length)

		let urlImage = backgrounds[randomImageLoginIndex]

		let bgHandler = function (e) {

			bgElement.css({
				'background-image': `url(${urlImage})`,
			})

		}

		bgHandler()

		function randomNumber(max = 5) {
			let number = Math.random() * max
			number = Math.round(number)
			if (number > max) {
				number--
			}
			return number
		}
	}
}

/**
 * Configura el formulario de logueo
 */
function configLoginForm() {

	let form = $('[login-form-js]')

	pcsphp.authenticator.verify(() => window.location.reload())

	form.on('submit', function (e) {

		e.preventDefault()

		const LoaderName = 'login'

		showGenericLoader(LoaderName)

		let login = pcsphp.authenticator.authenticateWithUsernamePassword(
			form.find("[name='username']").val(),
			form.find("[name='password']").val()
		)

		let lastURL = form.attr('last-uri')

		login.then(function (res) {

			let auth = res.auth
			let isAuth = res.isAuth

			if (auth === true || isAuth === true) {

				if (typeof lastURL == 'string' && lastURL.trim().length > 0) {

					window.location.href = lastURL

				} else {

					window.location.reload()

				}

			} else {
				setMessageError(res.error, res)
				removeGenericLoader(LoaderName)
			}

		}).catch(function (jqXHR) {
			removeGenericLoader(LoaderName)
			console.error(jqXHR)
			errorMessage(_i18n('loginForm', 'Error'), _i18n('loginForm', 'Ha ocurrido un error inesperado, intente más tarde.'))
		})

		return false
	})

	function setMessageError(error, data) {

		if ('INCORRECT_PASSWORD' == error) {

			errorMessage(_i18n('loginForm', 'CONTRASEÑA_INVÁLIDA'), _i18n('loginForm', 'Por favor, verifique los datos de ingreso y vuelva a intentar.'))

		} else if ('BLOCKED_FOR_ATTEMPTS' == error) {

			errorMessage(_i18n('loginForm', 'USUARIO_BLOQUEADO'), _i18n('loginForm', 'Por favor, ingrese al siguiente enlace para desbloquear su usuario.'))

		} else if ('USER_NO_EXISTS' == error) {

			errorMessage(_i18n('loginForm', 'USUARIO_INEXISTENTE').replace("%r", `'${data.user}'`), _i18n('loginForm', 'Por favor, verifique los datos de ingreso y vuelva a intentar.'))

		} else if ('MISSING_OR_UNEXPECTED_PARAMS' == error) {

			errorMessage('Error', data.message)

		} else if ('GENERIC_ERROR' == error) {

			errorMessage(_i18n('loginForm', 'ERROR_AL_INGRESAR'), _i18n('loginForm', 'Se ha presentado un error al momento de ingresar, por favor intente nuevamente.'))

		} else {

			errorMessage('Error', data.message)

		}

	}
}

window.addEventListener('load', function (e) {
	changeImageLogin()
	configLoginForm()
})

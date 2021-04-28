/// <reference path="../../js/helpers.js" />
/// <reference path="../../own-plugins/CropperAdapterComponent.js" />
window.addEventListener('load', () => {

	setBackgroundCroppers()
	setLogosFaviconCroppers()

})



function setBackgroundCroppers() {
	showGenericLoader('backgrounds')
	$('.special.cards .image').dimmer({
		on: 'hover'
	});

	let backgroundQty = 5
	/**
	 * @type {CropperAdapterComponent[]} croppers
	 */
	let croppers = []
	let forms = []

	for (let i = 1; i <= backgroundQty; i++) {

		croppers[i] = new CropperAdapterComponent({
			containerSelector: `form[bg="${i}"] .cropper-adapter`,
			minWidth: 1920,
			outputWidth: 1920,
			cropperOptions: {
				aspectRatio: 16 / 9,
			},
			outputFormat: 'image/jpeg',
		})

		forms[i] = genericFormHandler(`form[bg="${i}"]`, {
			onSetFormData: function (formData) {
				formData.set(`background-${i}`, croppers[i].getFile(null, 0.2, null, null, true))
				return formData
			},
			onInvalidEvent: function (event) {

				let element = event.target
				let validationMessage = element.validationMessage
				let jElement = $(element)
				let field = jElement.parents('.field')
				let nameOnLabel = field.find('label').html()

				errorMessage(`${nameOnLabel}: ${validationMessage}`)

				event.preventDefault()

			}
		})

	}

	removeGenericLoader('backgrounds')
}


function setLogosFaviconCroppers() {
	showGenericLoader('logos-favicon')
	const onInvalidHandler = function (event) {

		let element = event.target
		let validationMessage = element.validationMessage
		let jElement = $(element)
		let field = jElement.parents('.field')
		let nameOnLabel = field.find('label').html()

		errorMessage(`${nameOnLabel}: ${validationMessage}`)

		event.preventDefault()

	}
	const instantiateCropper = (selector, w = 400, ow = 400, ar = 1 / 1, o = 'image/png') => {
		return new CropperAdapterComponent({
			containerSelector: selector,
			minWidth: w,
			outputWidth: ow,
			allowResizeCrop: true,
			outputFillColor: 'transparent',
			cropperOptions: {
				viewMode: 1,
				aspectRatio: ar,
				cropBoxResizable: true,
			},
			outputFormat: o,
		})
	}
	/**
	 * @param {FormData} formData 
	 * @param {CropperAdapterComponent} cropper 
	 * @param {String} name 
	 */
	const onSetFormData = function (formData, cropper, name) {
		formData.set(name, cropper.getFile(null, null, null, null, true))
		return formData
	}

	let cropperPublicFavicon = instantiateCropper(`form.public-favicon .cropper-adapter`)
	genericFormHandler(`form.public-favicon`, {
		onSetFormData: (formData) => onSetFormData(formData, cropperPublicFavicon, `favicon`),
		onInvalidEvent: onInvalidHandler,
	})

	let cropperBackFavicon = instantiateCropper(`form.back-favicon .cropper-adapter`)
	genericFormHandler(`form.back-favicon`, {
		onSetFormData: (formData) => onSetFormData(formData, cropperBackFavicon, `favicon-back`),
		onInvalidEvent: onInvalidHandler,
	})

	let cropperLogo = instantiateCropper(`form.logo .cropper-adapter`)
	genericFormHandler(`form.logo`, {
		onSetFormData: (formData) => onSetFormData(formData, cropperLogo, `logo`),
		onInvalidEvent: onInvalidHandler,
	})


	let cropperWhiteLogo = instantiateCropper(`form.white-logo .cropper-adapter`)
	genericFormHandler(`form.white-logo`, {
		onSetFormData: (formData) => onSetFormData(formData, cropperWhiteLogo, `white-logo`),
		onInvalidEvent: onInvalidHandler,
	})

	removeGenericLoader('logos-favicon')

}
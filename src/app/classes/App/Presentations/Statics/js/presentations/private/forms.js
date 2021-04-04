/// <reference path="../../../../../../../../statics/core/js/configurations.js" />
/// <reference path="../../../../../../../../statics/core/js/helpers.js" />
/// <reference path="../../../../../../../../statics/core/own-plugins/CropperAdapterComponent.js" />
/// <reference path="../../../../../../../../statics/core/own-plugins/QuillAdapterComponent.js" />
showGenericLoader('_CARGA_INICIAL_')

window.addEventListener('load', function () {

	window.dispatchEvent(new Event('canDeletePresentation'))

	let isEdit = false
	let formSelector = `.ui.form.app-presentations`
	let langGroup = 'appPresentationsLang'

	let currentImages = new Map()
	let imagesToAdd = new Map()
	let imagesToDelete = new Set()

	let form = genericFormHandler(formSelector, {
		onSetFormData: function (formData) {

			let imagesToAddValues = Array.from(imagesToAdd.values())
			let imagesToDeleteValues = Array.from(imagesToDelete.values())

			formData = addObjectToFormData(formData, imagesToDeleteValues, 'images_to_delete')

			for (let imageToAdd of imagesToAddValues) {
				formData.append('images_to_add[]', imageToAdd)
			}

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

	form.find('input, select, textarea').attr('autocomplete', 'off')
	form.find('.ui.dropdown').dropdown()
	$('.tabular.menu .item').tab()

	isEdit = form.find(`[name="id"]`).length > 0

	configDynamicImages(form)
	configLangChange('.ui.dropdown.langs')

	function configDynamicImages(parent) {

		let trigger = parent.find('[images-multiple-trigger-add]')
		let containerItems = parent.find('[images-multiple-container]')
		let editorContainer = parent.find('[images-multiple-editor]')
		let editorTemplate = editorContainer.find('.cropper-adapter').get(0).outerHTML

		let scriptTagTemplate = $('[template-item-images-multiple]')
		let templateItem = scriptTagTemplate.get(0).innerHTML

		scriptTagTemplate.remove()
		editorContainer.find('.cropper-adapter').remove()

		readCurrents()

		trigger.click(function (e) {

			e.preventDefault()

			let uniqueID = generateUniqueID('cropper-')

			let hasEditor = editorContainer.find('.cropper-adapter').length > 0

			if (!hasEditor) {

				let editor = $(editorTemplate)
				editor.attr('data-id', uniqueID)

				editorContainer.append(editor)

				let adapter = new CropperAdapterComponent({
					containerSelector: `[data-id="${uniqueID}"]`,
					minWidth: 1,
					outputWidth: -1,
					allowResizeCrop: true,
					cropperOptions: {
						viewMode: 1,
						aspectRatio: NaN,
						cropBoxResizable: true,
					},
				}, false)

				adapter.on('prepare', function () {
					adapter.forceInitialize()
				})

				adapter.on('save', function (e, data) {
					toAdd(uniqueID, adapter.getFile(), data.b64)
					adapter.destroy()
				})

				adapter.prepare()

			}

		})

		function readCurrents() {

			let currentItems = containerItems.find('> [item]').toArray()

			for (let current of currentItems) {

				current = $(current)

				let id = current.attr('data-id')

				currentImages.set(id, current.find('img').attr('src'))

				let deleteItemButton = current.find('button[delete]')
				deleteItemButton.click(deleteEventHandler)

			}

		}

		function toAdd(id, file, b64) {

			imagesToAdd.set(id, file)

			let item = $(templateItem)

			item.attr('data-id', id)
			item.find('img').attr('src', b64)

			if (countItems() == 0) {
				containerItems.html('')
			}

			containerItems.append(item)

			let deleteItemButton = item.find('button[delete]')

			deleteItemButton.on('click', deleteEventHandler)

		}

		function toDelete(id) {

			let itemToDelete = containerItems.find(`[item][data-id="${id}"]`)

			imagesToAdd.delete(id)

			if (currentImages.has(id)) {
				imagesToDelete.add(currentImages.get(id))
			}

			itemToDelete.remove()

		}

		function deleteEventHandler(e) {

			e.preventDefault()

			let that = $(e.currentTarget)
			let parent = that.closest('[item]')
			let uniqueID = parent.attr('data-id')

			iziToast.question({
				timeout: 20000,
				close: false,
				overlay: true,
				displayMode: 'once',
				id: 'question',
				zindex: 999,
				title: _i18n(langGroup, 'Confirmación'),
				message: _i18n(langGroup, '¿Quiere eliminar la imagen?'),
				position: 'center',
				buttons: [
					['<button><b>' + _i18n(langGroup, 'Sí') + '</b></button>', function (instance, toast) {
						toDelete(uniqueID)
						instance.hide({ transitionOut: 'fadeOut' }, toast, 'button')
					}, true],
					['<button>' + _i18n(langGroup, 'No') + '</button>', function (instance, toast) {
						instance.hide({ transitionOut: 'fadeOut' }, toast, 'button')
					}],
				]
			})

		}

		function countItems() {
			return containerItems.find('>[item]').length
		}

	}

	function configLangChange(dropdownSelector) {

		let dropdown = $(dropdownSelector)

		dropdown.dropdown({
			/**
			 * 
			 * @param {Number|String} value 
			 * @param {String} innerText 
			 * @param {$} element 
			 */
			onChange: function (value, innerText, element) {
				showGenericLoader('redirect')
				window.location.href = value
			},
		})

	}

	removeGenericLoader('_CARGA_INICIAL_')

})



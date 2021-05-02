$(document).ready(function (e) {
	let blackboard = new BlackBoardComponent(1, 3)
	blackboard.configForm('[blackboard-news-create]')
	blackboard.configShow({
		selector: '[normal][blackboard-list]',
		urlAttr: 'blackboard-list',
	})
	blackboard.configShow({
		selector: '[table][blackboard-list]',
		urlAttr: 'blackboard-list',
		type: 'table',
		page: 1,
		perPage: 10,
	})
})


function BlackBoardComponent(page, perPage, attrSelectorForm, attrSelectorList) {

	let instance = this

	const LANG_GROUP = 'news'

	let attrForm = ''
	let selectorForm = ''
	let attrList = ''
	let selectorList = ''

	this.form = null
	this.method = 'POST'
	this.action = ''

	this.page = 1
	this.perPage = 10


	_constructor(page, perPage, attrSelectorForm, attrSelectorList)

	function _constructor(page, perPage, attrSelectorForm, attrSelectorList) {
		attrForm = typeof attrSelectorForm == 'string' && attrSelectorForm.trim().length > 0 ? attrSelectorForm : 'blackboard-news-create'
		selectorForm = `[${attrForm}]`
		attrList = typeof attrSelectorList == 'string' && attrSelectorList.trim().length > 0 ? attrSelectorList : 'blackboard-list'
		selectorList = `[${attrList}]`
		instance.page = page
		instance.perPage = perPage
	}

	this.configForm = function (selector) {

		selector = typeof selector == 'string' && selector.trim().length > 0 ? selector : selectorForm
		this.form = $(selector)

		if (this.form.length > 0) {

			this.action = this.form.attr('action')
			this.method = this.form.attr('method')

			let validAction = typeof this.action == 'string' && this.action.trim().length > 0
			let validMethod = typeof this.method == 'string' && this.method.trim().length > 0

			if (validAction && validMethod) {

				this.action = this.action.trim()
				this.method = this.method.trim().toUpperCase()

				this.form.submit(function (e) {
					e.preventDefault()

					let req = instance.method == 'POST' ? postRequest(instance.action, new FormData(instance.form[0])) : getRequest(instance.action, instance.form)

					req.done((res) => {
						if (res.success) {

							successMessage('Éxito', res.message)
							setTimeout(function () {
								if (typeof res.values.redirect == 'string' && res.values.redirect.trim().length > 0) {
									window.location = res.values.redirect
								} else {
									window.location.reload()
								}
							}, 1500)
							instance.form.find('button[type="submit"]').attr('disabled', true)


						} else {

							errorMessage(_i18n(LANG_GROUP, 'Error'), res.message)

						}
					})

					req.fail((res) => {

						errorMessage(_i18n(LANG_GROUP, 'Error'), _i18n(LANG_GROUP, 'Ha ocurrido un error desconocido.'))
						console.log(res)

					})

					return false

				})

			}

		}
	}

	this.configShow = function (optionsArgs) {

		optionsArgs = typeof optionsArgs == 'object' ? optionsArgs : {}

		let allowedOptions = {
			'selector': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
				default: selectorList,
				required: false,
			},
			'urlAttr': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
				default: attrList,
				required: false,
			},
			'page': {
				validate: (value) => value == null || !isNaN(value),
				default: 1,
				required: false,
			},
			'perPage': {
				validate: (value) => value == null || !isNaN(value),
				default: 5,
				required: false,
			},
			'type': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
				default: 'normal',
				required: false,
			},
		}


		let options = {
			'selector': null,
			'urlAttr': null,
			'page': null,
			'perPage': null,
			'type': null,
		}

		for (let option in allowedOptions) {

			let defaultOption = allowedOptions[option]

			defaultOption.default = typeof defaultOption.default == 'undefined' ? null : defaultOption.default
			defaultOption.required = typeof defaultOption.required == 'undefined' ? true : defaultOption.required
			defaultOption.nullable = typeof defaultOption.nullable == 'undefined' ? false : defaultOption.nullable
			defaultOption.validate = typeof defaultOption.validate == 'undefined' ? () => true : defaultOption.validate

			if (!defaultOption.validate(optionsArgs[option])) {
				if (!defaultOption.required) {
					optionsArgs[option] = defaultOption.default
				} else {
					throw new Error(`La opción ${option} es obligatoria`);
				}
			}

			if (!defaultOption.nullable && optionsArgs[option] === null) {
				throw new Error(`La opción ${option} no puede ser NULL`);
			}

			options[option] = optionsArgs[option]
		}

		this.page = typeof options.page == 'number' ? parseInt(options.page) : this.page
		this.perPage = typeof options.perPage == 'number' ? parseInt(options.perPage) : this.perPage

		let containerList = $(options.selector)

		if (containerList.length > 0) {

			let url = containerList.attr(options.urlAttr)
			let validURL = typeof url == 'string' && url.trim().length > 0

			if (validURL) {

				let newsContainer = containerList.find('[content]')
				let paginationContainer = containerList.find('[paginate]')
				showNews({
					url: url,
					newsContainer: newsContainer,
					paginationContainer: paginationContainer,
					page: this.page,
					perPage: this.perPage,
					type: options.type,
				})

			}
		}
	}

	function showNews(optionsArgs) {

		optionsArgs = typeof optionsArgs == 'object' ? optionsArgs : {}

		let allowedOptions = {
			'url': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
			},
			'newsContainer': {
				validate: (value) => value instanceof $ || value instanceof HTMLElement,
			},
			'paginationContainer': {
				validate: (value) => value instanceof $ || value instanceof HTMLElement,
			},
			'page': {
				validate: (value) => value == null || !isNaN(value),
				default: 1,
				required: false,
			},
			'perPage': {
				validate: (value) => value == null || !isNaN(value),
				default: 5,
				required: false,
			},
			'append': {
				validate: (value) => value == null || !isNaN(value),
				default: false,
				required: false,
			},
			'type': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
				default: 'normal',
				required: false,
			},
		}


		let options = {
			'url': null,
			'newsContainer': null,
			'paginationContainer': null,
			'page': null,
			'perPage': null,
			'append': null,
			'type': null,
		}

		for (let option in allowedOptions) {

			let defaultOption = allowedOptions[option]

			defaultOption.default = typeof defaultOption.default == 'undefined' ? null : defaultOption.default
			defaultOption.required = typeof defaultOption.required == 'undefined' ? true : defaultOption.required
			defaultOption.nullable = typeof defaultOption.nullable == 'undefined' ? false : defaultOption.nullable
			defaultOption.validate = typeof defaultOption.validate == 'undefined' ? () => true : defaultOption.validate

			if (!defaultOption.validate(optionsArgs[option])) {
				if (!defaultOption.required) {
					optionsArgs[option] = defaultOption.default
				} else {
					throw new Error(`La opción ${option} es obligatoria`);
				}
			}

			if (!defaultOption.nullable && optionsArgs[option] === null) {
				throw new Error(`La opción ${option} no puede ser NULL`);
			}

			options[option] = optionsArgs[option]
		}

		if (options.append !== true) {
			options.newsContainer.html('')
		}

		let form = $(document.createElement('form'))
			.append(
				$(document.createElement('input'))
					.attr('name', 'page')
					.attr('value', options.page)
			)
			.append(
				$(document.createElement('input'))
					.attr('name', 'per_page')
					.attr('value', options.perPage)
			)

		if (options.type == 'table') {
			form
				.append(
					$(document.createElement('input'))
						.attr('name', 'is_list')
						.attr('value', true)
				)
		}

		let req = getRequest(options.url, form)

		req.done((res) => {

			if (res.success) {

				let news = res.values.news
				let page = res.values.page
				let pages = res.values.pages
				let hasMoreNews = page < pages
				let loadMoreButtonAttr = 'load-more-button'
				let hasButton = options.paginationContainer.find(`[${loadMoreButtonAttr}]`).length > 0

				itemsToHTML({
					items: news,
					container: options.newsContainer,
					type: options.type,
				})

				if (hasMoreNews) {

					if (!hasButton) {

						let divButton = $(document.createElement('divButton'))

						divButton.css({
							textAlign: 'center'
						})

						let buttonMore = $(document.createElement('button'))
						buttonMore.attr(loadMoreButtonAttr, '')
						buttonMore.addClass('ui mini button green')
						buttonMore.html(_i18n(LANG_GROUP, 'Cargar más.'))

						divButton.append(buttonMore)
						divButton.on('click', function (e) {
							e.preventDefault()
							page++
							showNews({
								url: options.url,
								newsContainer: options.newsContainer,
								paginationContainer: options.paginationContainer,
								page: page,
								perPage: options.perPage,
								type: options.type,
								append: true,
							})
							return false
						})

						options.paginationContainer.append(divButton)
					}

				} else {
					if (hasButton) {
						options.paginationContainer.find(`[${loadMoreButtonAttr}]`).remove()
					}
				}

			} else {
				console.log(res)
			}
		})

		req.fail((res) => {
			console.log(res)
		})
	}

	function itemsToHTML(optionsArgs) {
		optionsArgs = typeof optionsArgs == 'object' ? optionsArgs : {}

		let allowedOptions = {
			'items': {
				validate: (value) => Array.isArray(value),
			},
			'container': {
				validate: (value) => value instanceof $ || value instanceof HTMLElement,
			},
			'type': {
				validate: (value) => typeof value == 'string' && value.trim().length > 0,
				default: 'normal',
				required: false,
			},
		}


		let options = {
			'items': null,
			'type': null,
			'container': null,
		}

		for (let option in allowedOptions) {

			let defaultOption = allowedOptions[option]

			defaultOption.default = typeof defaultOption.default == 'undefined' ? null : defaultOption.default
			defaultOption.required = typeof defaultOption.required == 'undefined' ? true : defaultOption.required
			defaultOption.nullable = typeof defaultOption.nullable == 'undefined' ? false : defaultOption.nullable
			defaultOption.validate = typeof defaultOption.validate == 'undefined' ? () => true : defaultOption.validate

			if (!defaultOption.validate(optionsArgs[option])) {
				if (!defaultOption.required) {
					optionsArgs[option] = defaultOption.default
				} else {
					throw new Error(`La opción ${option} es obligatoria`);
				}
			}

			if (!defaultOption.nullable && optionsArgs[option] === null) {
				throw new Error(`La opción ${option} no puede ser NULL`);
			}

			options[option] = optionsArgs[option]
		}

		let items = options.items

		if (options.type == 'normal') {
			if (items.length == 0) {
				options.container.append('<div class="ui segment">' + _i18n(LANG_GROUP, 'No hay noticias') + '</div>')
			}
			for (let item of items) {

				let div = $(document.createElement('div'))
				div.attr('data-id', item.id)
				div.addClass('ui segment')

				let meta = document.createElement("div");
				let meta2 = document.createElement("div");
				meta.className = "meta"

				meta.innerHTML = `<small>Por ${item.author.firstname} ${item.author.first_lastname}</small>`;
				if (item.start_date != null) {
					meta.innerHTML += `<small>${timeAgo(item.start_date)}`
				}

				div.append(meta);



				div.append(`<h3 class="ui header">${item.title}</h3>`)
				div.append(`<p>${item.text.length > 100 ? item.text.substring(0, 99) + `... <a href="${item.url}">Ver mas.</a>` : item.text}</p>`)
				options.container.append(div)
			}

		}
	}

	return this
}

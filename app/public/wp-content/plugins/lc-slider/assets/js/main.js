'use strict'

class Carousel {
	constructor() {
		this.carousel = document.querySelector('.carousel__container-sliders')
		this.slides = [...this.carousel.children]
		this.nextButton = document.querySelector('.carousel__btn-right')
		this.prevButton = document.querySelector('.carousel__btn-left')
		this.nav = document.querySelector('.carousel__nav')
		this.dots = [...this.nav.children]
		this.slidesPosition()
		this.events()
	}

	events() {
		this.nextButton.addEventListener('click', () => this.nextSlide())
		this.nextButton.addEventListener('keydown', e => this.nextSlidePressedEnter(e))
		this.prevButton.addEventListener('click', () => this.prevSlide())
		this.prevButton.addEventListener('keydown', e => this.prevSlidePressedEnter(e))
		this.nav.addEventListener('click', e => this.clickedDot(e))
		this.nav.addEventListener('keydown', e => this.clickedDotPressedEnter(e))
	}

	slidesPosition() {
		let slideWidth = this.slides[0].getBoundingClientRect().width

		for (let index = 0; index < this.slides.length; index++) {
			this.slides[index].style.left = slideWidth * index + 'px'
		}
	}

	nextSlide() {
		const currentSlide = this.carousel.querySelector('.active')
		const next = currentSlide.nextElementSibling

		this.moveSlider(currentSlide, next)
		this.hideButton(next)
		this.moveToDot(next)
	}

	nextSlidePressedEnter(e) {
		if (13 == e.keyCode) {
			this.nextSlide()
		}
	}

	prevSlide() {
		const currentSlide = this.carousel.querySelector('.active')
		const prev = currentSlide.previousElementSibling
       
		this.moveSlider(currentSlide, prev)
		this.hideButton(prev)
		this.moveToDot(prev)
	}

	prevSlidePressedEnter(e) {
		if (13 == e.keyCode) {
			this.prevSlide()
		}
	}

	moveSlider(currentSlide, targetSlide) {
		const position = targetSlide.style.left

		this.carousel.style.transform = `translateX(-${position})`
		this.toggleActive(currentSlide, targetSlide)
		this.toggleActiveAria(currentSlide, targetSlide)

	}

	toggleActive(current, slide) {
		current.classList.remove('active')
		current.setAttribute('aria-selected', 'false')

		slide.classList.add('active')
		slide.setAttribute('aria-selected', 'true')

	}

	toggleActiveAria(currentSlide, targetSlide) {
		const currentSlideContent = currentSlide.lastElementChild
		const currentSlideContentButton = currentSlide.querySelector('button')
		const currentSlideContentButtonLink = currentSlide.querySelector('button a')
		const targetSlideContent = targetSlide.lastElementChild
		const targetSlideContentButton = targetSlide.querySelector('button')
		const targetSlideContentButtonLink = targetSlide.querySelector('button a')

		currentSlideContent.setAttribute('tabindex', '-1')
		currentSlideContentButton.setAttribute('tabindex', '-1')
		currentSlideContentButtonLink.setAttribute('tabindex', '-1')

		targetSlideContent.setAttribute('tabindex', '0')
		targetSlideContentButton.setAttribute('tabindex', '0')
		targetSlideContentButtonLink.setAttribute('tabindex', '0')
	}

	hideButton(targetSlide) {
		if (targetSlide == this.slides[0]) {
			this.prevButton.classList.add('hide')
			this.nextButton.classList.remove('hide')
		} else if (targetSlide == this.slides[this.slides.length - 1]) {
			this.nextButton.classList.add('hide')
			this.prevButton.classList.remove('hide')
		} else {
			this.nextButton.classList.remove('hide')
			this.prevButton.classList.remove('hide')
		}
	}

	clickedDot(e) {
		if (e.target === this.nav) return

		const targetDot = e.target
		const currentDot = this.nav.querySelector('.active')
		const currentSlide = this.carousel.querySelector('.active')

		let targetDotIndex = this.findIndex(targetDot, this.dots)

		const targetSlide = this.slides[targetDotIndex]

		this.moveSlider(currentSlide, targetSlide)
		this.toggleActive(currentDot, targetDot)
		this.hideButton(targetSlide)
	}

	clickedDotPressedEnter(e) {
		if (13 == e.keyCode) {
			this.clickedDot(e)
		}
	}

	findIndex(item, items) {
		for (let index = 0; index < items.length; index++) {
			if (item === items[index]) {
				return index
			}
		}
	}

	moveToDot(targetSlide) {
		let slideIndex = this.findIndex(targetSlide, this.slides)
		const currentDot = this.nav.querySelector('.active')
		const targetDot = this.dots[slideIndex]
		this.toggleActive(currentDot, targetDot)
	}
}

new Carousel

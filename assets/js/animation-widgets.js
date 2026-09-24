(function () {
	'use strict';

	const initializedWheels = new WeakSet();
	const initializedGalleries = new WeakSet();
	const initializedTestimonials = new WeakSet();
	const initializedPrograms = new WeakSet();
	const stickyContextCounts = new WeakMap();

	function acquireStickyContext(element, className) {
		let counts = stickyContextCounts.get(element);
		if (!counts) {
			counts = {};
			stickyContextCounts.set(element, counts);
		}
		counts[className] = (counts[className] || 0) + 1;
		element.classList.add(className);
	}

	function releaseStickyContext(element, className) {
		const counts = stickyContextCounts.get(element);
		if (!counts || !counts[className]) return;
		counts[className] -= 1;
		if (counts[className] === 0) {
			delete counts[className];
			element.classList.remove(className);
		}
	}

	function numberValue(value, fallback) {
		const parsed = Number(value);
		return Number.isFinite(parsed) ? parsed : fallback;
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	}

	function initWheel(root) {
		if (!root || initializedWheels.has(root)) return;

		const disc = root.querySelector('.aw-wheel__stage > img:not(.aw-wheel__pointer)');
		const button = root.querySelector('.aw-wheel__button');
		const label = root.querySelector('.aw-wheel__button-label');
		const result = root.querySelector('.aw-wheel__result');
		let outcomes;

		try {
			outcomes = JSON.parse(root.dataset.results || '[]').filter(function (item) {
				return item && Number.isFinite(Number(item.angle)) && typeof item.text === 'string' && item.text.trim();
			});
		} catch (error) {
			outcomes = [];
		}

		if (!disc || !button || !label || !result || !outcomes.length) return;
		initializedWheels.add(root);

		let rotation = 0;
		let animation = null;
		const duration = Math.max(200, numberValue(root.dataset.duration, 5200));
		const minimumTurns = Math.max(0, Math.floor(numberValue(root.dataset.minTurns, 5)));
		const maximumTurns = Math.max(minimumTurns, Math.floor(numberValue(root.dataset.maxTurns, 7)));
		const resultDuration = Math.max(0, numberValue(root.dataset.resultDuration, 500));
		const resultAnimation = ['fade', 'slide-up', 'zoom', 'none'].includes(root.dataset.resultAnimation)
			? root.dataset.resultAnimation
			: 'slide-up';

		result.dataset.animation = resultAnimation;
		result.style.setProperty('--aw-result-duration', resultDuration + 'ms');

		button.addEventListener('click', function () {
			if (button.disabled) return;

			const outcome = outcomes[Math.floor(Math.random() * outcomes.length)];
			const normalizedRotation = ((rotation % 360) + 360) % 360;
			const normalizedTarget = ((numberValue(outcome.angle, 0) % 360) + 360) % 360;
			const correction = (normalizedTarget - normalizedRotation + 360) % 360;
			const turns = minimumTurns + Math.floor(Math.random() * (maximumTurns - minimumTurns + 1));
			const target = rotation + (turns * 360) + correction;

			button.disabled = true;
			button.classList.add('is-spinning');
			label.textContent = root.dataset.spinningText || 'La ruleta está girando…';
			result.classList.remove('is-visible');
			result.textContent = '';

			if (animation && typeof animation.cancel === 'function') animation.cancel();

			const finish = function () {
				rotation = target;
				disc.style.transform = 'rotate(' + target + 'deg)';
				button.disabled = false;
				button.classList.remove('is-spinning');
				label.textContent = root.dataset.repeatText || 'Tira de nuevo la ruleta';
				result.textContent = outcome.text;
				void result.offsetWidth;
				result.classList.add('is-visible');
				animation = null;
			};

			if (prefersReducedMotion() || typeof disc.animate !== 'function') {
				finish();
				return;
			}

			animation = disc.animate(
				[
					{ transform: 'rotate(' + rotation + 'deg)' },
					{ transform: 'rotate(' + target + 'deg)' }
				],
				{
					duration: duration,
					easing: 'cubic-bezier(.08,.72,.12,1)',
					fill: 'forwards'
				}
			);
			animation.addEventListener('finish', finish, { once: true });
		});
	}

	function initGallery(root) {
		if (!root || initializedGalleries.has(root)) return;

		const sticky = root.querySelector('.aw-gallery__sticky');
		const viewport = root.querySelector('.aw-gallery__viewport');
		const track = root.querySelector('.aw-gallery__track');
		if (!sticky || !viewport || !track) return;
		initializedGalleries.add(root);

		const breakpoint = Math.max(320, Math.min(1200, numberValue(root.dataset.mobileBreakpoint, 767)));
		const factor = Math.max(0.5, Math.min(3, numberValue(root.dataset.scrollFactor, 1)));
		const reverse = root.dataset.direction === 'left-to-right';
		let frame = 0;
		let distance = 0;
		let resizeTimer = 0;
		let resizeObserver = null;
		let destroyed = false;
		let stickyAncestors = [];

		function releaseStickyAncestors() {
			stickyAncestors.forEach(function (context) {
				releaseStickyContext(context.element, context.className);
			});
			stickyAncestors = [];
		}

		function prepareStickyAncestors() {
			releaseStickyAncestors();
			let ancestor = root.parentElement;
			while (ancestor) {
				const style = window.getComputedStyle(ancestor);
				const overflow = [style.overflow, style.overflowX, style.overflowY].join(' ');
				if (/(auto|scroll|hidden|clip|overlay)/.test(overflow)) {
					const isPageRoot = ancestor === document.body || ancestor === document.documentElement;
					const className = isPageRoot ? 'aw-gallery-page-context' : 'aw-gallery-sticky-context';
					acquireStickyContext(ancestor, className);
					stickyAncestors.push({ element: ancestor, className: className });
				}
				if (ancestor === document.documentElement) break;
				ancestor = ancestor.parentElement;
			}
		}

		function isSwipeMode() {
			return prefersReducedMotion() || window.innerWidth <= breakpoint;
		}

		function paint() {
			frame = 0;
			if (destroyed || !root.isConnected) {
				destroy();
				return;
			}

			if (root.classList.contains('is-swipe')) {
				track.style.transform = '';
				return;
			}

			const travel = Math.max(1, root.offsetHeight - sticky.offsetHeight);
			const rootTop = root.getBoundingClientRect().top + window.scrollY;
			const progress = Math.max(0, Math.min(1, (window.scrollY - rootTop) / travel));
			const translate = reverse ? -distance * (1 - progress) : -distance * progress;
			track.style.transform = 'translate3d(' + translate + 'px,0,0)';
		}

		function measure() {
			if (destroyed || !root.isConnected) {
				destroy();
				return;
			}

			const swipe = isSwipeMode();
			root.classList.toggle('is-swipe', swipe);
			track.style.transform = '';

			if (swipe) {
				releaseStickyAncestors();
				root.style.height = '';
				distance = 0;
				return;
			}

			prepareStickyAncestors();
			const viewportWidth = viewport.clientWidth;
			distance = Math.max(0, track.scrollWidth - viewportWidth);
			root.style.height = (sticky.offsetHeight + (distance * factor)) + 'px';
			paint();
		}

		function requestPaint() {
			if (!frame) frame = window.requestAnimationFrame(paint);
		}

		function requestMeasure() {
			window.clearTimeout(resizeTimer);
			resizeTimer = window.setTimeout(measure, 120);
		}

		function destroy() {
			if (destroyed) return;
			destroyed = true;
			window.removeEventListener('scroll', requestPaint);
			window.removeEventListener('resize', requestMeasure);
			if (resizeObserver) resizeObserver.disconnect();
			releaseStickyAncestors();
			if (frame) window.cancelAnimationFrame(frame);
			window.clearTimeout(resizeTimer);
		}

		window.addEventListener('scroll', requestPaint, { passive: true });
		window.addEventListener('resize', requestMeasure, { passive: true });

		if ('ResizeObserver' in window) {
			resizeObserver = new ResizeObserver(requestMeasure);
			resizeObserver.observe(track);
		}

		Array.prototype.forEach.call(track.querySelectorAll('img'), function (image) {
			if (!image.complete) image.addEventListener('load', requestMeasure, { once: true });
		});

		window.requestAnimationFrame(measure);
	}

	function initTestimonials(root) {
		if (!root || initializedTestimonials.has(root)) return;

		const slides = Array.prototype.slice.call(root.querySelectorAll('[data-aw-testimonial-slide]'));
		if (!slides.length) return;
		initializedTestimonials.add(root);

		const dots = Array.prototype.slice.call(root.querySelectorAll('[data-aw-dot]'));
		const previous = root.querySelector('[data-aw-prev]');
		const next = root.querySelector('[data-aw-next]');
		const reducedMotion = prefersReducedMotion();
		const autoplay = root.dataset.autoplay === 'true' && !reducedMotion && slides.length > 1;
		const pauseHover = root.dataset.pauseHover === 'true';
		const interval = Math.max(1500, numberValue(root.dataset.interval, 4500));
		const duration = reducedMotion ? 0 : Math.max(100, numberValue(root.dataset.duration, 700));
		const resumeDelay = Math.max(500, numberValue(root.dataset.resumeDelay, 3500));
		let index = 0;
		let timer = 0;
		let hovering = false;
		let pointerId = null;
		let pointerStartX = 0;
		let pointerStartY = 0;

		root.style.setProperty('--aw-testimonial-duration', duration + 'ms');

		function stopTimer() {
			window.clearTimeout(timer);
			timer = 0;
		}

		function schedule(delay) {
			stopTimer();
			if (!autoplay || hovering || document.hidden) return;
			timer = window.setTimeout(function () {
				if (!root.isConnected) return;
				show(index + 1, false);
			}, delay);
		}

		function show(target, userInitiated) {
			index = (target % slides.length + slides.length) % slides.length;
			slides.forEach(function (slide, slideIndex) {
				const active = slideIndex === index;
				slide.classList.toggle('is-active', active);
				slide.setAttribute('aria-hidden', active ? 'false' : 'true');
			});
			dots.forEach(function (dot, dotIndex) {
				const active = dotIndex === index;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-current', active ? 'true' : 'false');
			});
			schedule(userInitiated ? resumeDelay : interval);
		}

		if (previous) previous.addEventListener('click', function () { show(index - 1, true); });
		if (next) next.addEventListener('click', function () { show(index + 1, true); });
		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				show(numberValue(dot.dataset.awDot, 0), true);
			});
		});

		root.addEventListener('pointerdown', function (event) {
			if (event.pointerType === 'mouse' && event.button !== 0) return;
			pointerId = event.pointerId;
			pointerStartX = event.clientX;
			pointerStartY = event.clientY;
			stopTimer();
		});
		root.addEventListener('pointerup', function (event) {
			if (pointerId !== event.pointerId) return;
			const deltaX = event.clientX - pointerStartX;
			const deltaY = event.clientY - pointerStartY;
			pointerId = null;
			if (Math.abs(deltaX) >= 42 && Math.abs(deltaX) > Math.abs(deltaY)) {
				show(index + (deltaX < 0 ? 1 : -1), true);
			} else {
				schedule(resumeDelay);
			}
		});
		root.addEventListener('pointercancel', function () {
			pointerId = null;
			schedule(resumeDelay);
		});

		if (pauseHover) {
			root.addEventListener('mouseenter', function () {
				hovering = true;
				stopTimer();
			});
			root.addEventListener('mouseleave', function () {
				hovering = false;
				schedule(resumeDelay);
			});
		}

		document.addEventListener('visibilitychange', function () {
			if (document.hidden) stopTimer();
			else schedule(interval);
		});

		show(0, false);
	}

	function initPrograms(root) {
		if (!root || initializedPrograms.has(root)) return;

		const viewport = root.querySelector('[data-aw-programs-viewport]');
		const track = root.querySelector('[data-aw-programs-track]');
		const group = root.querySelector('[data-aw-programs-group]');
		if (!viewport || !track || !group) return;
		const originalCards = Array.prototype.slice.call(group.querySelectorAll('[data-aw-program-card]'));
		if (!originalCards.length) return;
		initializedPrograms.add(root);

		const dots = Array.prototype.slice.call(root.querySelectorAll('[data-aw-dot]'));
		const previous = root.querySelector('[data-aw-prev]');
		const next = root.querySelector('[data-aw-next]');
		const reducedMotion = prefersReducedMotion();
		const autoplay = root.dataset.autoplay === 'true' && !reducedMotion;
		const speed = Math.max(5, numberValue(root.dataset.speed, 25));
		const direction = root.dataset.direction === 'right' ? -1 : 1;
		const pauseHover = root.dataset.pauseHover === 'true';
		const resumeDelay = Math.max(500, numberValue(root.dataset.resumeDelay, 3000));
		let groupWidth = 1;
		let cardStep = 1;
		let offset = 0;
		let lastTime = 0;
		let frame = 0;
		let hovering = false;
		let visible = true;
		let dragging = false;
		let pointerId = null;
		let pointerStartX = 0;
		let pointerStartY = 0;
		let pointerStartOffset = 0;
		let dragged = false;
		let pendingLink = null;
		let pausedUntil = 0;
		let targetOffset = null;
		let suppressClickUntil = 0;
		let lastPointerType = 'mouse';
		let resizeTimer = 0;
		let activeDot = -1;

		function cloneForLoop(node, filler) {
			const clone = node.cloneNode(true);
			clone.setAttribute('aria-hidden', 'true');
			if (clone.matches('a, button, [tabindex]')) clone.setAttribute('tabindex', '-1');
			if (filler) clone.setAttribute('data-aw-program-filler', '');
			clone.querySelectorAll('a, button, [tabindex]').forEach(function (focusable) {
				focusable.setAttribute('tabindex', '-1');
			});
			return clone;
		}

		function normalize(value) {
			return groupWidth > 0 ? ((value % groupWidth) + groupWidth) % groupWidth : 0;
		}

		function updateDot() {
			if (!dots.length || !originalCards.length) return;
			const normalized = normalize(offset);
			const nextActive = Math.round(normalized / Math.max(1, cardStep)) % originalCards.length;
			if (nextActive === activeDot) return;
			activeDot = nextActive;
			dots.forEach(function (dot, dotIndex) {
				const active = dotIndex === activeDot;
				dot.classList.toggle('is-active', active);
				dot.setAttribute('aria-current', active ? 'true' : 'false');
			});
		}

		function render() {
			track.style.transform = 'translate3d(' + (-normalize(offset)) + 'px,0,0)';
			updateDot();
		}

		function measure() {
			const oldWidth = groupWidth;
			const oldProgress = oldWidth > 1 ? normalize(offset) / oldWidth : 0;
			track.querySelectorAll('[data-aw-program-clone]').forEach(function (clone) { clone.remove(); });
			group.querySelectorAll('[data-aw-program-filler]').forEach(function (clone) { clone.remove(); });

			let guard = 0;
			while (group.scrollWidth < viewport.clientWidth + (originalCards[0].offsetWidth || 1) && guard < 20) {
				originalCards.forEach(function (card) { group.appendChild(cloneForLoop(card, true)); });
				guard += 1;
			}

			groupWidth = Math.max(1, group.getBoundingClientRect().width);
			const duplicate = group.cloneNode(true);
			duplicate.setAttribute('data-aw-program-clone', '');
			duplicate.setAttribute('aria-hidden', 'true');
			duplicate.querySelectorAll('a, button, [tabindex]').forEach(function (focusable) { focusable.setAttribute('tabindex', '-1'); });
			track.appendChild(duplicate);
			const firstCard = originalCards[0];
			const secondCard = originalCards[1];
			cardStep = secondCard ? Math.abs(secondCard.offsetLeft - firstCard.offsetLeft) : firstCard.offsetWidth;
			offset = oldProgress * groupWidth;
			render();
		}

		function requestMeasure() {
			window.clearTimeout(resizeTimer);
			resizeTimer = window.setTimeout(measure, 120);
		}

		function animate(time) {
			if (!root.isConnected) return;
			if (!lastTime) lastTime = time;
			const delta = Math.min(64, time - lastTime);
			lastTime = time;

			if (targetOffset !== null) {
				const difference = targetOffset - offset;
				const portion = Math.min(1, delta / 280);
				offset += difference * portion;
				if (Math.abs(difference) < .5) {
					offset = targetOffset;
					targetOffset = null;
					pausedUntil = time + resumeDelay;
				}
			} else if (autoplay && visible && !hovering && !dragging && time >= pausedUntil) {
				offset += direction * speed * delta / 1000;
			}
			render();
			frame = window.requestAnimationFrame(animate);
		}

		function moveOne(amount) {
			targetOffset = offset + amount * cardStep;
			pausedUntil = performance.now() + resumeDelay;
		}

		if (previous) previous.addEventListener('click', function () { moveOne(-1); });
		if (next) next.addEventListener('click', function () { moveOne(1); });
		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				const selected = Math.max(0, Math.min(originalCards.length - 1, numberValue(dot.dataset.awDot, 0)));
				const desired = selected * cardStep;
				const cycle = Math.round((offset - desired) / groupWidth);
				targetOffset = desired + cycle * groupWidth;
				pausedUntil = performance.now() + resumeDelay;
			});
		});

		viewport.addEventListener('pointerdown', function (event) {
			if (event.pointerType === 'mouse' && event.button !== 0) return;
			lastPointerType = event.pointerType || 'mouse';
			pointerId = event.pointerId;
			pointerStartX = event.clientX;
			pointerStartY = event.clientY;
			pointerStartOffset = offset;
			dragged = false;
			pendingLink = event.target.closest('a.aw-programs__overlay, a.aw-programs__button, a.aw-programs__summary-button');
			targetOffset = null;
			try {
				viewport.setPointerCapture(event.pointerId);
			} catch (error) {
				// La interacción sigue funcionando aunque el navegador no permita capturar.
			}
		});
		viewport.addEventListener('pointermove', function (event) {
			if (pointerId !== event.pointerId) return;
			const deltaX = event.clientX - pointerStartX;
			const deltaY = event.clientY - pointerStartY;
			if (!dragging && Math.abs(deltaX) > 6 && Math.abs(deltaX) > Math.abs(deltaY)) {
				dragging = true;
				dragged = true;
				viewport.classList.add('is-dragging');
			}
			if (dragging) {
				offset = pointerStartOffset - deltaX;
				render();
			}
		});

		function finishPointer(event) {
			if (pointerId !== event.pointerId) return;
			const wasDragged = dragged;
			const linkToActivate = !wasDragged ? pendingLink : null;
			pointerId = null;
			dragging = false;
			pendingLink = null;
			viewport.classList.remove('is-dragging');
			pausedUntil = performance.now() + resumeDelay;
			if (wasDragged) {
				suppressClickUntil = performance.now() + 350;
			} else if (linkToActivate) {
				// Pointer capture retargets the browser click to the viewport. Activate
				// the original link explicitly, then suppress only the duplicate click.
				linkToActivate.click();
				suppressClickUntil = performance.now() + 350;
			}
		}

		viewport.addEventListener('pointerup', finishPointer);
		viewport.addEventListener('pointercancel', finishPointer);
		root.addEventListener('click', function (event) {
			if (performance.now() < suppressClickUntil) {
				event.preventDefault();
				event.stopPropagation();
				return;
			}
			if (event.target.closest('a.aw-programs__overlay, a.aw-programs__button, a.aw-programs__summary-button')) return;
			const card = event.target.closest('[data-aw-program-card]');
			if (!card || (lastPointerType !== 'touch' && !window.matchMedia('(hover: none)').matches)) return;
			const willOpen = !card.classList.contains('is-open');
			root.querySelectorAll('[data-aw-program-card].is-open').forEach(function (openCard) { openCard.classList.remove('is-open'); });
			card.classList.toggle('is-open', willOpen);
			pausedUntil = performance.now() + resumeDelay;
		});

		if (pauseHover) {
			root.addEventListener('mouseenter', function () { hovering = true; });
			root.addEventListener('mouseleave', function () {
				hovering = false;
				pausedUntil = performance.now() + resumeDelay;
			});
		}

		window.addEventListener('resize', requestMeasure, { passive: true });
		if ('ResizeObserver' in window) {
			const observer = new ResizeObserver(requestMeasure);
			observer.observe(viewport);
		}
		if ('IntersectionObserver' in window) {
			const visibilityObserver = new IntersectionObserver(function (entries) {
				visible = Boolean(entries[0] && entries[0].isIntersecting);
			});
			visibilityObserver.observe(root);
		}
		group.querySelectorAll('img').forEach(function (image) {
			if (!image.complete) image.addEventListener('load', requestMeasure, { once: true });
		});

		measure();
		frame = window.requestAnimationFrame(animate);
	}

	function initWithin(scope) {
		const context = scope && scope.querySelectorAll ? scope : document;
		if (context.matches && context.matches('[data-aw-wheel]')) initWheel(context);
		if (context.matches && context.matches('[data-aw-gallery]')) initGallery(context);
		if (context.matches && context.matches('[data-aw-testimonials]')) initTestimonials(context);
		if (context.matches && context.matches('[data-aw-programs]')) initPrograms(context);
		context.querySelectorAll('[data-aw-wheel]').forEach(initWheel);
		context.querySelectorAll('[data-aw-gallery]').forEach(initGallery);
		context.querySelectorAll('[data-aw-testimonials]').forEach(initTestimonials);
		context.querySelectorAll('[data-aw-programs]').forEach(initPrograms);
	}

	function registerElementorHooks() {
		if (!window.elementorFrontend || !window.elementorFrontend.hooks) return;
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/animation-widgets-interactive-wheel.default',
			function ($scope) { initWithin($scope && $scope[0]); }
		);
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/animation-widgets-scroll-gallery.default',
			function ($scope) { initWithin($scope && $scope[0]); }
		);
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/animation-widgets-testimonial-carousel.default',
			function ($scope) { initWithin($scope && $scope[0]); }
		);
		window.elementorFrontend.hooks.addAction(
			'frontend/element_ready/animation-widgets-programs-carousel.default',
			function ($scope) { initWithin($scope && $scope[0]); }
		);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function () { initWithin(document); }, { once: true });
	} else {
		initWithin(document);
	}

	window.addEventListener('elementor/frontend/init', registerElementorHooks, { once: true });
	if (window.elementorFrontend && window.elementorFrontend.hooks) registerElementorHooks();
})();

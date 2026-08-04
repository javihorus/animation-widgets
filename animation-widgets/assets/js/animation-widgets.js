(function () {
	'use strict';

	const initializedWheels = new WeakSet();
	const initializedGalleries = new WeakSet();
	const initializedScrollFills = new WeakSet();
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

	function initScrollFill(root) {
		if (!root || initializedScrollFills.has(root)) return;

		let text = root.querySelector('.aw-scroll-fill__text');
		if (!text && root.dataset.awFillTarget === 'heading') {
			text = root.querySelector('.elementor-heading-title');
		}
		if (!text && root.dataset.awFillTarget === 'text-editor') {
			text = root.querySelector('.elementor-widget-container');
		}
		if (!text) return;
		text.classList.add('aw-scroll-fill__text');
		initializedScrollFills.add(root);

		const excludedTags = ['SCRIPT', 'STYLE', 'NOSCRIPT'];
		const walker = document.createTreeWalker(text, NodeFilter.SHOW_TEXT);
		const textNodes = [];
		let current;

		while ((current = walker.nextNode())) {
			if (current.nodeValue && current.nodeValue.length && !excludedTags.includes(current.parentElement.tagName)) {
				textNodes.push(current);
			}
		}

		const characters = [];
		textNodes.forEach(function (node) {
			const fragment = document.createDocumentFragment();
			Array.from(node.nodeValue).forEach(function (character) {
				if (/\s/.test(character)) {
					fragment.appendChild(document.createTextNode(character));
					return;
				}
				const span = document.createElement('span');
				span.className = 'aw-scroll-fill__char';
				span.setAttribute('aria-hidden', 'true');
				span.textContent = character;
				fragment.appendChild(span);
				characters.push(span);
			});
			node.parentNode.replaceChild(fragment, node);
		});

		if (!characters.length) return;

		text.setAttribute('aria-label', text.textContent.replace(/\s+/g, ' ').trim());
		const start = Math.max(0, Math.min(100, numberValue(root.dataset.start, 80))) / 100;
		const end = Math.max(0, Math.min(100, numberValue(root.dataset.end, 20))) / 100;
		const soften = Math.max(0, Math.min(12, numberValue(root.dataset.soften, 4)));
		let frame = 0;
		let destroyed = false;

		function paint() {
			frame = 0;
			if (destroyed || !root.isConnected) {
				destroy();
				return;
			}

			const rect = text.getBoundingClientRect();
			const viewportHeight = window.innerHeight || document.documentElement.clientHeight;
			const startLine = viewportHeight * start;
			const endLine = viewportHeight * end;
			const distance = Math.max(1, startLine - endLine + rect.height);
			const progress = prefersReducedMotion()
				? (rect.top <= startLine ? 1 : 0)
				: Math.max(0, Math.min(1, (startLine - rect.top) / distance));
			const position = progress * characters.length;

			characters.forEach(function (character, index) {
				const fill = soften > 0
					? Math.max(0, Math.min(1, (position - index + soften) / soften))
					: (index < position ? 1 : 0);
				character.style.setProperty('--aw-char-fill', fill.toFixed(3));
				character.style.setProperty('--aw-char-active', (fill * 100).toFixed(1) + '%');
				character.style.setProperty('--aw-char-muted', ((1 - fill) * 100).toFixed(1) + '%');
			});
		}

		function requestPaint() {
			if (!frame) frame = window.requestAnimationFrame(paint);
		}

		function destroy() {
			if (destroyed) return;
			destroyed = true;
			window.removeEventListener('scroll', requestPaint);
			window.removeEventListener('resize', requestPaint);
			if (frame) window.cancelAnimationFrame(frame);
		}

		window.addEventListener('scroll', requestPaint, { passive: true });
		window.addEventListener('resize', requestPaint, { passive: true });
		window.requestAnimationFrame(paint);
	}

	function initWithin(scope) {
		const context = scope && scope.querySelectorAll ? scope : document;
		if (context.matches && context.matches('[data-aw-wheel]')) initWheel(context);
		if (context.matches && context.matches('[data-aw-gallery]')) initGallery(context);
		if (context.matches && context.matches('[data-aw-scroll-fill]')) initScrollFill(context);
		context.querySelectorAll('[data-aw-wheel]').forEach(initWheel);
		context.querySelectorAll('[data-aw-gallery]').forEach(initGallery);
		context.querySelectorAll('[data-aw-scroll-fill]').forEach(initScrollFill);
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
			'frontend/element_ready/animation-widgets-scroll-fill.default',
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

import Choices from 'choices.js';
import flatpickr from 'flatpickr';
import { flatpickrPortuguese } from "flatpickr/dist/l10n/pt.js"
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import { OverlayScrollbars, ScrollbarsHidingPlugin, SizeObserverPlugin, ClickScrollPlugin } from 'overlayscrollbars';
import Inputmask from "inputmask";
import { computePosition, autoUpdate, flip, offset } from "@floating-ui/dom";
import { Sortable, Plugins } from '@shopify/draggable';
import ToastGyn from './toast-gyn';

window.Choices = Choices;
window.Inputmask = Inputmask;
window.OverlayScrollbars = OverlayScrollbars;
window.ToastGyn = ToastGyn;
window.Swiper = Swiper;
window.Draggable = { Sortable, Plugins };
window.flatpickr = flatpickr;
window.flatpickrPortuguese = flatpickrPortuguese;

OverlayScrollbars.plugin([ScrollbarsHidingPlugin, SizeObserverPlugin, ClickScrollPlugin]);
flatpickr.localize(flatpickrPortuguese);

window.addEventListener('notify', ({ detail: { type, msg, timer = 5000 } }) => {
    const maxZIndex = [...document.querySelectorAll('*')].reduce((max, el) => Math.max(max, +getComputedStyle(el).zIndex || 0), 0);

    ToastGyn.show(type, msg, timer, maxZIndex + 10);
});

window.debounce = function (callback, delay) {
    let timeout;
    return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            callback(...args);
        }, delay);
    };
};

window.currency = function (value, options = {}) {
    const {
        locale = 'en-US',     // ex: pt-BR, en-US, de-DE, fr-FR
        currency = 'USD',     // ex: BRL, EUR, GBP, JPY
        fallback = '',
        minimumFractionDigits,
        maximumFractionDigits
    } = options;

    if (value === null || value === undefined || value === '') return fallback;

    const number = Number(typeof value === 'string' ? value.replace(/[^\d.-]/g, '') : value);

    if (Number.isNaN(number)) return fallback;

    return new Intl.NumberFormat(locale, {
        style: 'currency',
        currency,
        minimumFractionDigits,
        maximumFractionDigits
    }).format(number);
};

window.percent = function (value, options = {}) {
    const {
        locale = 'en-US',
        minimumFractionDigits = 2,
        maximumFractionDigits = 2,
        min = 0,
        max = 100,
        fallback = ''
    } = options;

    if (value === null || value === undefined || value === '') return fallback;

    let number = Number(typeof value === 'string' ? value.replace(/[^\d,.-]/g, '').replace(',', '.') : value);

    if (Number.isNaN(number)) return fallback;

    number = Math.min(Math.max(number, min), max);

    return new Intl.NumberFormat(locale, {
        style: 'percent',
        minimumFractionDigits,
        maximumFractionDigits
    }).format(number / 100);
};

// START IMPLEMENTATION
document.addEventListener('alpine:init', () => {

    Alpine.magic('clipboard', () => {
        return (text) => {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                return navigator.clipboard.writeText(text)
            } else {
                const el = document.createElement('textarea')
                el.value = text
                document.body.appendChild(el)
                el.select()
                try {
                    document.execCommand('copy')
                    document.body.removeChild(el)
                    return Promise.resolve()
                } catch (e) {
                    document.body.removeChild(el)
                    return Promise.reject(e)
                }
            }
        }
    });

    Alpine.data('scrollbar', () => ({
        init() {
            this.$nextTick(() => {
                this.initOverlayScrollbars();
            });
            Livewire.hook('commit', ({ component, succeed }) => {
                succeed(({ snapshot, effect }) => {
                    this.$nextTick(() => {
                        this.initOverlayScrollbars();
                    });
                })
            });
        },
        initOverlayScrollbars() {
            OverlayScrollbars(this.$el, {}).destroy();
            OverlayScrollbars(this.$el, {
                scrollbars: {
                    clickScroll: true,
                    autoHide: 'leave',
                    autoHideDelay: 400
                }
            });
        }
    }));

    Alpine.data('mask', () => ({
        value: '',
        init() {
            this.run();
        },
        run() {
            const inputmask = new Inputmask({
                oncomplete: () => this.updateValue(),
                onincomplete: () => this.updateValue(),
                oncleared: () => this.updateValue()
            });

            inputmask.mask(this.$el);

            this.updateValue();
            this.$el.addEventListener('input', () => this.updateValue());
        },
        updateValue() {
            this.value = this.$el.inputmask.unmaskedvalue();
        }
    }));

    Alpine.data('choices', (value, placeholder = 'N/A', customClass = '', position = 'auto', removeItemButton = true) => ({
        value: value,
        placeholder: placeholder,
        customClass: customClass,
        position: position,
        removeItemButton: removeItemButton,
        choicesInstance: null,
        init() {
            this.run();
        },
        run() {
            this.removeActiveItems();

            const classNames = {
                containerOuter: ['suit-choices']
            };

            if (this.customClass) {
                classNames.containerOuter.push(this.customClass);
            }

            this.choicesInstance = new Choices(this.$el, {
                removeItemButton: this.removeItemButton,
                placeholderValue: this.placeholder,
                shouldSort: false,
                position: this.position,
                itemSelectText: false,
                classNames: classNames,
                loadingText: "Carregando...",
                noResultsText: "Nenhum resultado encontrado",
                noChoicesText: "Nenhuma opção disponível",
                addItemText: (value) => `Pressione Enter para adicionar <b>"${value}"</b>`,
                removeItemLabelText: (value) => `Remover item: ${value}`,
                maxItemText: (max) => `Somente ${max} valores podem ser adicionados`,
            });

            // Define o valor inicial
            if (this.value) this.choicesInstance.setChoiceByValue(this.value.toString());

            // Evento acionado quando houver mudança
            this.$el.addEventListener('change', () => {
                this.value = this.choicesInstance.getValue(true) || "";
            });
        },
        destroy() {
            if (this.choicesInstance) {
                this.choicesInstance.destroy();
                this.choicesInstance = null;
            }
        },
        removeActiveItems() {
            if (this.choicesInstance) {
                this.choicesInstance.removeActiveItems();
                this.value = '';
            }
        },
        disable() {
            if (this.choicesInstance) {
                this.choicesInstance.disable();
            }
        },
        enable() {
            if (this.choicesInstance) {
                this.choicesInstance.enable();
            }
        }
    }));

    Alpine.data('dropdown', (placement = 'left', strategy = 'absolute', distance = 0) => ({
        open: false,
        cleanup: null,
        init() {
            this.$nextTick(() => this.setup());
        },
        setup() {
            const referenceEl = this.$refs.referenceDropdown;
            const floatingEl = this.$refs.floatingDropdown;

            if (!referenceEl || !floatingEl) return;

            // Garante estilos base sem tirar do Alpine
            floatingEl.style.position = strategy;
            floatingEl.style.top = '0px';
            floatingEl.style.left = '0px';

            this.$watch('open', (isOpen) => {

                if (isOpen) {
                    this.showDropdown(floatingEl);
                    this.startAutoUpdate(referenceEl, floatingEl);
                    this.closeOnEscape();
                    this.closeOnScroll();
                } else {
                    this.hideDropdown(floatingEl);
                    this.stopAutoUpdate();
                    this.removeGlobalListeners();
                }

            });

            // Cleanup se Alpine destruir o componente
            this.$el.addEventListener('alpine:destroy', () => {
                this.stopAutoUpdate();
                this.removeGlobalListeners();
            });
        },
        showDropdown(el) {
            el.classList.remove('hidden');
            el.classList.add('flex');

            // z-index leve e sem varrer DOM
            if (!window.__zIndexCounter) window.__zIndexCounter = 2000;
            window.__zIndexCounter += 1;
            el.style.zIndex = window.__zIndexCounter;
        },
        hideDropdown(el) {
            el.classList.add('hidden');
            el.classList.remove('flex');
        },
        startAutoUpdate(referenceEl, floatingEl) {
            this.cleanup = autoUpdate(referenceEl, floatingEl, async () => {
                const { x, y } = await computePosition(referenceEl, floatingEl, {
                    strategy,
                    placement,
                    middleware: [flip(), offset(distance)],
                });

                Object.assign(floatingEl.style, {
                    top: `${y}px`,
                    left: `${x}px`,
                });
            });
        },
        stopAutoUpdate() {
            if (typeof this.cleanup === 'function') {
                this.cleanup();
                this.cleanup = null;
            }
        },
        closeOnEscape() {
            this._escapeHandler = (e) => {
                if (e.key === 'Escape') this.open = false;
            };
            window.addEventListener('keydown', this._escapeHandler);
        },
        closeOnScroll() {
            this._scrollHandler = () => {
                this.open = false;
            };
            window.addEventListener('scroll', this._scrollHandler, true);
            window.addEventListener('resize', this._scrollHandler);
        },
        removeGlobalListeners() {
            if (this._escapeHandler) {
                window.removeEventListener('keydown', this._escapeHandler);
                this._escapeHandler = null;
            }

            if (this._scrollHandler) {
                window.removeEventListener('scroll', this._scrollHandler, true);
                window.removeEventListener('resize', this._scrollHandler);
                this._scrollHandler = null;
            }
        }
    }));

    Alpine.data('sortable', (componentName) => ({
        componentName: componentName,
        init() {
            const sortable = new Draggable.Sortable(this.$root, {
                draggable: '[data-sortable-item]',
                handle: '[data-sortable-handle]',
                mirror: {
                    constrainDimensions: true
                },
                plugins: [Draggable.Plugins.SortAnimation],
                swapAnimation: {
                    duration: 200,
                    easingFunction: 'ease-in-out',
                },
            });

            sortable.on('sortable:start', (event) => {
                event.startContainer.setAttribute('x-ignore', '');
                this.$dispatch('sortable:start', {
                    componentName: this.componentName,
                    event: event
                });
            });

            sortable.on('sortable:stop', (event) => {
                event.newContainer.removeAttribute('x-ignore');
                this.$dispatch('sortable:stop', {
                    componentName: this.componentName,
                    event: event
                });
            });
        }
    }));

    Alpine.data('modal', (ref) => ({
        open: false,
        ref: ref,
        events: {
            ['@open-modal.window']() {
                if (this.$event.detail.ref == this.ref) this.open = true;
            },
            ['@close-modal.window']() {
                if (this.$event.detail.ref == this.ref) this.open = false;
            },
            ['@keydown.escape.window']() {
                if (this.open) this.open = false;
            }
        },
        init() {
            var zIndexCounter = 1000;

            this.$watch('open', (value) => {
                document.body.style.overflow = value ? 'hidden' : 'auto';
                // trigger event on close modal            
                if (value === false) this.$dispatch('closed.' + this.ref);
                // trigger event on open modal
                else if (value === true) this.$dispatch('opened.' + this.ref);
                // calculate z-index automatically
                if (value === true) {
                    this.$root.style.zIndex = this.maxZIndex + 1;
                }
            });
        },
        get maxZIndex() {
            var maxZ = 0;
            document.querySelectorAll('*').forEach(el => {
                const zIndex = parseInt(window.getComputedStyle(el).zIndex, 10);
                if (!isNaN(zIndex) && zIndex > maxZ) {
                    maxZ = zIndex;
                }
            });
            return maxZ;
        }
    }));

    Alpine.data('limitInput', (limit, model, stop = false) => ({
        limit,
        stop,
        length: 0,
        init() {
            const selector = `
                input[x-model*='${model}'], 
                input[wire\\:model*='${model}'], 
                textarea[x-model*='${model}'], 
                textarea[wire\\:model*='${model}']
            `;

            // 👉 pega TODOS os inputs que casam
            const inputs = document.querySelectorAll(selector.trim());

            if (!inputs.length) return;

            inputs.forEach((input) => {

                const updateLength = () => {
                    const value = input.value;

                    if (value?.length > this.limit && this.stop) {
                        input.value = value.substring(0, this.limit);
                        input.dispatchEvent(new Event('input'));
                        this.length = this.limit;
                    } else {
                        this.length = value?.length || 0;
                    }
                };

                updateLength();

                const events = ['input', 'change', 'paste', 'cut', 'keyup', 'blur'];
                events.forEach(event => {
                    input.addEventListener(event, updateLength);
                });

                Livewire.hook('commit', ({ succeed }) => {
                    succeed(() => {
                        queueMicrotask(updateLength);
                    });
                });

                const observer = new MutationObserver(updateLength);
                observer.observe(input, {
                    attributes: true,
                    attributeFilter: ['value']
                });
            });
        }
    }));
});
// END IMPLEMENTATION 
import $ from 'jquery';

export default class Search {
    constructor () {
        this.addSearchHTML();

        this.searchResults = document.querySelector('#search-overlay__result');
        this.openButtons = document.querySelectorAll('.js-search-trigger');
        this.closeButton = document.querySelector('.search-overlay__close');
        this.searchOvelay = document.querySelector('.search-overlay');
        this.searchInput = document.querySelector('#search-term');
        this.overlayOpen = false;
        this.isSpinnerVisible = false;
        this.typingTimer = null;
        this.previusValue = '';

        this.events();
    }

    events () {
        this.openButtons.forEach((button) => this.searchButtonListener(button));
        this.closeButton.addEventListener('click', () => this.closeOverlay());
        this.searchInput.addEventListener('keyup', (e) => this.typingLogic(e));
        document.addEventListener('keydown', (e) => this.keyPressDispatcher(e));
    }

    searchButtonListener (button)  {
        button.addEventListener('click', () => this.openOverlay())
    }

    typingLogic (e) {
        if (this.previusValue !== this.searchInput.value) {
            clearTimeout(this.typingTimer);

            if (this.searchInput.value) {
                if (!this.isSpinnerVisible) {
                    this.searchResults.innerHTML = '<div class="spinner-loader"></div>';
                    this.isSpinnerVisible = true;
                }
                this.typingTimer = setTimeout(() => this.getResults(e), 750);
            } else {
                this.searchResults.innerHTML = '';
                this.isSpinnerVisible = false;
            }
        }

        this.previusValue = this.searchInput.value;
    }

    getResults () {
        $.when(
            $.getJSON(`/wp-json/wp/v2/pages?search=${this.searchInput.value}`),
            $.getJSON(`/wp-json/wp/v2/pages?search=${this.searchInput.value}`)
        ).then((posts, pages) => {
            const result = posts[0].concat(pages[0]);
            const items = result.map(({link = '#', title: {rendered : title}}) => `<li><a href="${link}">${title}</a></li>`).join('');
            const noResults = `<p>No General Information</p>`;
            const list = items.length ? `<ul class="link-list min-list">${items}</ul>` : noResults;
            this.searchResults.innerHTML = `<h2 class="search-overlay__section-title">General Information</h2>${list}`;
            this.isSpinnerVisible = false;
        }, () => {
            this.searchResults.innerHTML = '<p>Unexpected error, please try again!</p>'
        });
    }

    openOverlay () {
        this.searchOvelay.classList.add('search-overlay--active');
        document.body.classList.add('body-no-scroll');
        this.searchInput.value = '';
        this.searchResults.innerHTML = '';
        setTimeout(() => this.searchInput.focus(), 301);
        this.overlayOpen = true;
    }

    closeOverlay () {
        this.searchOvelay.classList.remove('search-overlay--active');
        document.body.classList.remove('body-no-scroll');
        this.overlayOpen = false;
    }

    keyPressDispatcher (e) {
        let inputIsFocused = isFocused('input', 'textarea');

        if (e.keyCode === 83 && !this.overlayOpen && !inputIsFocused) {
            this.openOverlay();
        }

        if (e.keyCode === 27 && this.overlayOpen) {
            this.closeOverlay();
        }
    }

    addSearchHTML () {
        const searchOverlay = document.createElement('div');
        searchOverlay.classList.add('search-overlay');

        const searchOverlayTop = document.createElement('div');
        searchOverlayTop.classList.add('search-overlay__top');

        const container = document.createElement('div');
        container.classList.add('container');

        const containerTop = container.cloneNode(false);
        const containerBottom = container.cloneNode(false);

        const searchIcon = document.createElement('i');
        searchIcon.classList.add('fa', 'fa-search', 'search-overlay__icon');
        searchIcon.setAttribute('aria-hidden', 'true');

        const input = document.createElement('input');
        input.classList.add('search-term');
        input.setAttribute('type', 'text');
        input.setAttribute('id', 'search-term');
        input.setAttribute('placeholder', 'What are you looking for?');

        const closeIcon = document.createElement('i');
        closeIcon.classList.add('fa', 'fa-window-close', 'search-overlay__close');
        closeIcon.setAttribute('aria-hidden', 'true');

        const result = document.createElement('div');
        result.setAttribute('id', 'search-overlay__result');

        containerTop.appendChild(searchIcon);
        containerTop.appendChild(input);
        containerTop.appendChild(closeIcon);

        containerBottom.appendChild(result);

        searchOverlayTop.appendChild(containerTop);

        searchOverlay.appendChild(searchOverlayTop);
        searchOverlay.appendChild(containerBottom);

        document.body.appendChild(searchOverlay);
    }
}

function isFocused (...node) {
    let state = false;
    const items = document.querySelectorAll(node);

    for (let i = 0; i < items.length; i++) {
        if (items[i] === document.activeElement) {
            state = true;
            break;
        }
    }

    return state;
}

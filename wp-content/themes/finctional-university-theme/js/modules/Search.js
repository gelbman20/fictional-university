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
        function renderListItem ({title, permalink}) {
            return `<li><a href="${permalink}">${title}</a></li>`;
        }

        function renderList (list) {
            if (!list.length) {
                return `<p>Result not found</p>`;
            }

            return `<ul class="link-list min-list">${list.map(renderListItem).join('')}</ul>`;
        }

        function renderProfessor ({title, image, permalink}) {
            return `
                    <li class="professor-card__list-item">
                        <a class="professor-card" href="${permalink}">
                            <img src="${image}" alt="" class="professor-card__image">
                            <span class="professor-card__name">${title}</span>
                        </a>
                    </li>`;
        }

        function renderProfessors (list) {
            if (!list.length) {
                return `<p>Result not found</p>`
            }

            return `<ul class="professor-cards">${list.map(renderProfessor).join('')}</ul>`
        }

        function renderEvents (events) {
            if (!events.length) {
                return `<p>Result not found</p>`
            }

            return events.map(({title, permalink, month = '', day = '', content}) => {
                return `
                    <div class="event-summary">
                        <a class="event-summary__date t-center" href="${permalink}">
                            <span class="event-summary__month">${month}</span>
                            <span class="event-summary__day">${day}</span>
                        </a>
                        <div class="event-summary__content">
                            <h5 class="event-summary__title headline headline--tiny"><a href="${permalink}">${title}</a></h5>
                            <p>${content} <a href="${permalink}" class="nu gray">Learn more</a></p>
                        </div>
                    </div>`
            }).join('')
        }

        $.getJSON(`/wp-json/university/v1/search?term=${this.searchInput.value}`, ({generalInfo, professors, programs, events, campuses}) => {
            const emptyResult = `<p>Result not found</p>`;

            this.searchResults.innerHTML = `
                <div class="row">
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">General Information</h2>
                        ${renderList(generalInfo)}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Programs</h2>
                        ${renderList(programs)}
                        
                        <h2 class="search-overlay__section-title">Professors</h2>
                        ${renderProfessors(professors)}
                    </div>
                    <div class="one-third">
                        <h2 class="search-overlay__section-title">Campuses</h2>
                        ${renderList(campuses)}
                        
                        <h2 class="search-overlay__section-title">Events</h2>
                        ${renderEvents(events)}
                    </div>
                </div>
            `;
            this.isSpinnerVisible = false;
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

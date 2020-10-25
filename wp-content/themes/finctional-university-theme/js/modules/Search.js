export default class Search {
    constructor () {
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
                this.typingTimer = setTimeout(() => this.getResults(e), 2000);
            } else {
                this.searchResults.innerHTML = '';
                this.isSpinnerVisible = false;
            }
        }

        this.previusValue = this.searchInput.value;
    }

    getResults () {
        this.searchResults.innerHTML = 'Hello 123';
        this.isSpinnerVisible = false;
    }

    openOverlay () {
        this.searchOvelay.classList.add('search-overlay--active');
        document.body.classList.add('body-no-scroll');
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

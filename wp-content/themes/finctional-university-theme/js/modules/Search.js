export default class Search {
    constructor () {
        this.openButtons = document.querySelectorAll('.js-search-trigger');
        this.closeButton = document.querySelector('.search-overlay__close');
        this.searchOvelay = document.querySelector('.search-overlay');

        this.events();
    }

    events () {
        this.openButtons.forEach((button) => this.searchButtonListener(button));
        this.closeButton.addEventListener('click', () => this.closeOverlay());
    }

    searchButtonListener (button)  {
        button.addEventListener('click', () => this.openOverlay())
    }

    openOverlay () {
        this.searchOvelay.classList.add('search-overlay--active');
    }

    closeOverlay () {
        this.searchOvelay.classList.remove('search-overlay--active');
    }
}

export default function () {
  const headerSearch = document.querySelector('.header__search');
  const searchInput = document.querySelector('.search__input');
  const searchSubmit = document.querySelector('.search__submit');
  const searchReset = document.querySelector('.search__reset');
  const searchTrigger = document.querySelector('.header__search-trigger');

  const removeShowClass = () => {
    headerSearch.classList.contains('show') && headerSearch.classList.remove('show');
    searchTrigger.classList.contains('active') && searchTrigger.classList.remove('active');
  }

  searchInput && searchInput.addEventListener('input', e => {
    if (e.target.value.length !== 0) {
      searchSubmit.classList.add('hidden');
      searchReset.classList.remove('hidden');
    } else {
      searchSubmit.classList.remove('hidden');
      searchReset.classList.add('hidden');
    }
  });

  searchReset && searchReset.addEventListener('click', () => {
    searchInput.focus();
    searchInput.value = '';
    searchInput.classList.remove('filled');
    searchReset.classList.add('hidden');
    searchSubmit.classList.remove('hidden');
  });

  searchTrigger && searchTrigger.addEventListener('click', e => {
    e.preventDefault();
    const search = e.currentTarget.previousElementSibling;
    searchTrigger.classList.toggle('active');
    search.classList.toggle('show');
    setTimeout(() => searchInput.focus(), 300);
  });

  const headerSearchInput = document.querySelector('.header__search [type="search"]');
  const overlay = document.querySelector('.header__search-overlay');
  const results = document.querySelector('.header__search .result_wrapper');
  headerSearchInput && headerSearchInput.addEventListener('focus', () => {
    overlay.classList.add('header__search-overlay--visible');
  });
  overlay && overlay.addEventListener('click', () => {
    overlay.classList.remove('header__search-overlay--visible');
    searchInput.value = '';
    searchInput.classList.remove('filled');
    searchReset.classList.add('hidden');
    searchSubmit.classList.remove('hidden');
    results.removeAttribute('style');
    results.innerHTML = null;
  });

  document.addEventListener('click', e => {
    if (headerSearch && !headerSearch.contains(e.target) && !searchTrigger.contains(e.target)) {
      removeShowClass();
    }
  });

  // headerSearch && window.addEventListener('resize', removeShowClass);
}
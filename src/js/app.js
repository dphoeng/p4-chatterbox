const searchInput = document.querySelector('#search-input');
const navSearch = document.querySelector('.nav-search');
const closeSearch = document.querySelector('#closeSearch');

searchInput.addEventListener('input', (e) => {
  if (searchInput.value.length > 0) {
    navSearch.classList.add('active');
  } else {
    navSearch.classList.remove('active')
  }
});

closeSearch.addEventListener('click', (e) => {
  navSearch.classList.remove('active')
  searchInput.value = ''
})
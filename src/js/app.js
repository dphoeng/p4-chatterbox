let lastOpend;

document.querySelectorAll('[data-open-dropdown]').forEach(button => {
  button.addEventListener('click', event => {
    const target = button.getAttribute('data-open-dropdown');
    if (!target) return;
    if (target === 'more-options') {
      const elemet = document.querySelector('#more-options');
      elemet.classList.toggle('active');
      if (lastOpend) {
        lastOpend.classList.remove('active');
      }
      lastOpend = elemet;
      return;
    }
    if (target === 'notifications') {
      const elemet = document.querySelector('#notifications');
      elemet.classList.toggle('active');
      if (lastOpend) {
        lastOpend.classList.remove('active');
      }
      lastOpend = elemet;
      return;
    }
    l
  });
});

document.querySelectorAll('[data-open-popup]').forEach(button => {
  button.addEventListener('click', event => {
    const target = button.getAttribute('data-open-popup');
    if (!target) return;
    const elemet = document.querySelector(`[data-popup="${target}"]`);
    console.log(elemet);
    console.log(target);
    elemet.showModal();
  });
});
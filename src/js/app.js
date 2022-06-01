document.querySelectorAll('[data-open-dropdown]').forEach(button => {
  button.addEventListener('click', event => {
    const target = button.getAttribute('data-open-dropdown');
    if (!target) return;
    if (target === 'more-options') {
      const element = document.querySelector('#more-options');
      element.classList.toggle('active');
      document.querySelector('#notifications').classList.remove('active');
      return;
    }
    if (target === 'notifications') {
      const element = document.querySelector('#notifications');
      element.classList.toggle('active');
      document.querySelector('#more-options').classList.remove('active');
      return;
    }
  });
});

document.querySelectorAll('[data-open-popup]').forEach(button => {
  button.addEventListener('click', event => {
    const target = button.getAttribute('data-open-popup');
    if (!target) return;
    const element = document.querySelector(`[data-popup="${target}"]`);
    console.log(element);
    console.log(target);
    element.showModal();
  });
});

function showPreview(event) {
  if (event.target.files.length > 0) {
    var src = URL.createObjectURL(event.target.files[0]);
    var preview = document.getElementById("file-ip-1-preview");
    preview.src = src;
    preview.style.display = "block";
  }
}
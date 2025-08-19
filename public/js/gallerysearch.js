document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('searchInput');
  const galleryItems = document.querySelectorAll('.container-box');
  const notFoundMessage = document.getElementById('tidakDitemukan');

  searchInput.addEventListener('keyup', () => {
    const filter = searchInput.value.toLowerCase();
    let found = false;

    galleryItems.forEach(item => {
      const title = item.querySelector('.nama-gallery').textContent.toLowerCase();
      if (title.includes(filter)) {
        item.style.display = 'block';
        found = true;
      } else {
        item.style.display = 'none';
      }
    });

    notFoundMessage.style.display = found ? 'none' : 'block';
  });
});
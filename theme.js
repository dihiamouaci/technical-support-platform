document.querySelector('.theme-toggle').addEventListener('click', () => {
  const current = document.documentElement.getAttribute('data-theme');
  const next = current === 'dark' ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', next);
  document.querySelector('.theme-toggle').textContent = next === 'dark' ? '☀️' : '🌙';
});

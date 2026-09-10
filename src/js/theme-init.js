(function () {
  'use strict';

  var theme = localStorage.getItem('theme');

  if (theme === 'light' || theme === 'dark') {
    document.documentElement.dataset.theme = theme;
  }
})();

(function (global) {
  'use strict';

  var STORAGE_KEY = 'theme';
  var THEME_COLORS = {
    light: '#4d698e',
    dark: '#1d232f'
  };

  function getStoredTheme() {
    var theme = localStorage.getItem(STORAGE_KEY);

    return theme === 'light' || theme === 'dark' ? theme : null;
  }

  function getEffectiveTheme() {
    var stored = getStoredTheme();

    if (stored) {
      return stored;
    }

    return global.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
  }

  function updateThemeColor() {
    var meta = document.querySelector('meta[name="theme-color"]');
    var theme = getEffectiveTheme();

    if (meta) {
      meta.setAttribute('content', THEME_COLORS[theme]);
    }
  }

  function applyTheme(theme) {
    var root = document.documentElement;

    if (theme === 'light' || theme === 'dark') {
      root.dataset.theme = theme;
      localStorage.setItem(STORAGE_KEY, theme);
    } else {
      delete root.dataset.theme;
      localStorage.removeItem(STORAGE_KEY);
    }

    updateThemeColor();
  }

  function initThemeControls() {
    var lightButton = document.getElementById('light-mode-button');
    var darkButton = document.getElementById('dark-mode-button');
    var systemButton = document.getElementById('system-mode-button');

    if (lightButton) {
      lightButton.addEventListener('click', function () {
        applyTheme('light');
      });
    }

    if (darkButton) {
      darkButton.addEventListener('click', function () {
        applyTheme('dark');
      });
    }

    if (systemButton) {
      systemButton.addEventListener('click', function () {
        applyTheme(null);
      });
    }

    global.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function () {
      if (!getStoredTheme()) {
        updateThemeColor();
      }
    });
  }

  updateThemeColor();
  initThemeControls();
})(window);

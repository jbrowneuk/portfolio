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

  function updateButtonStates(buttons, theme) {
    if (theme === null) theme = 'auto';
    buttons.forEach(button => button.setAttribute('aria-pressed', button.dataset.theme === theme));
  }

  function applyTheme(theme, buttons) {
    var root = document.documentElement;

    if (theme === 'light' || theme === 'dark') {
      root.dataset.theme = theme;
      localStorage.setItem(STORAGE_KEY, theme);
    } else {
      delete root.dataset.theme;
      localStorage.removeItem(STORAGE_KEY);
    }

    updateButtonStates(buttons, theme);
    updateThemeColor();
  }

  function initThemeControls() {
    const buttons = [document.getElementById('light-mode-button'), document.getElementById('dark-mode-button'), document.getElementById('system-mode-button')];
    updateButtonStates(buttons, getStoredTheme());

    buttons.forEach(button =>  button.addEventListener('click', () => applyTheme(button.dataset.theme, buttons)));

    global.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
      if (!getStoredTheme()) {
        updateThemeColor();
      }
    });
  }

  updateThemeColor();
  initThemeControls();
})(window);

const { defineConfig } = require("cypress");

module.exports = defineConfig({
<<<<<<< HEAD
  e2e: {
    setupNodeEvents(on, config) {
      // implement node event listeners here
    },
    baseUrl: "http://127.0.0.1:8000",
  },
  viewportHeight: 1080,
  viewportWidth:1920,
=======
    e2e: {
        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
        baseUrl: "http://localhost:8000",
    },
    viewportHeight: 1080,
    viewportWidth: 1920,
>>>>>>> e898e6d6defce4691a6f337b5b6d1e613d1ea7b4
});

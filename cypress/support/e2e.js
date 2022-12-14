// ***********************************************************
// This example support/e2e.js is processed and
// loaded automatically before your test files.
//
// This is a great place to put global configuration and
// behavior that modifies Cypress.
//
// You can change the location of this file or turn off
// automatically serving support files with the
// 'supportFile' configuration option.
//
// You can read more here:
// https://on.cypress.io/configuration
// ***********************************************************

// Import commands.js using ES2015 syntax:
<<<<<<< HEAD
import './commands'
Cypress.on("uncaught:exception", (err, runnable) => {
    return false;
});

Cypress.config('defaultCommandTimeout', 10000);

// Alternatively you can use CommonJS syntax:
// require('./commands')
=======
import "./commands";

// Alternatively you can use CommonJS syntax:
// require('./commands')

Cypress.on("uncaught:exception", (err, runnable) => {
    // returning false here prevents Cypress from
    // failing the test
    return false;
});
>>>>>>> e898e6d6defce4691a6f337b5b6d1e613d1ea7b4

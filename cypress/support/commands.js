// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
<<<<<<< HEAD
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
=======
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

/**
 * Login helper function.
 */
Cypress.Commands.add("login", () => cy.request("/_testing/login"));

/**
 * Create model factory:
 * ex: baseUrl/_testing/create?model=User
 */
Cypress.Commands.add("create", (model) => {
    return cy.request("/_testing/create?model=" + model).its("body");
});
>>>>>>> e898e6d6defce4691a6f337b5b6d1e613d1ea7b4

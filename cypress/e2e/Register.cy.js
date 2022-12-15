describe('Register', () => {
  it('Register Success', () => {
    cy.visit('http://127.0.0.1:8000/')
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > [href="http://127.0.0.1:8000/register"]').click()
    cy.get('#name').click().type('Faiz')
    cy.get('#email').click().type('faiz@gmail.com')
    cy.get('#password').click().type('password')
    cy.get('#password-confirm').click().type('password')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
  })
})
describe('empty spec', () => {
  it('passes', () => {
    cy.visit('http://127.0.0.1:8000/login')
    cy.get('#email').type('hilmy@gmail.com')
    cy.get('#password').type('hilmy123')
    cy.get('#sign-in > .ps-form__content > .submtit > .ps-btn').click()
    cy.get('.header__content-right > .header__actions > .ps-block--user-header > .ps-block__right > a').click()
    cy.get('.ps-widget__content > ul > :nth-child(5) > a').click()
  })
})
